<?php

namespace App\Services;

use App\Models\HouseSurvey;
use App\Models\Citizen;
use App\Models\QRCode;
use App\Models\Garbage;
use App\Models\FileUpload;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QRCodeGenerator;
use Illuminate\Support\Str;
use Throwable;

class GarbageService
{
    public function generateQRCode(int $houseNo)
    {
        try {
            // Validate input
            $validator = Validator::make(['houseNo' => $houseNo], [
                'houseNo' => 'required|integer|exists:mp_house,house_number'
            ]);
    
            if ($validator->fails()) {
                return [
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->all()
                ];
            }
    
            // Find the house
            $house = HouseSurvey::where('house_number', $houseNo)->first();
    
            // Generate a unique QR code content
            $qrContent = "app-specific-content:" . $house->house_id;
    
            $qrImage = QRCodeGenerator::format('svg')->size(200)->generate($qrContent);
    
            // Save QR code image to S3
            $fileName = 'qrcodes/' . $house->house_id . '.svg';
            $s3Link = Storage::disk('s3')->put($fileName, $qrImage);
    
            // Save QR code details in the database
            QRCode::create([
                'qr_id' => uniqid(),
                'house_id' => $house->house_id,
                'status' => 1, // Active
                'created_by' => auth()->id(),
                'created_at' => now(),
            ]);
    
            return [
                'status' => true,
                'message' => 'QR code generated successfully',
                'data' => [
                    'qr_code_url' => Storage::disk('s3')->url($fileName)
                ]
            ];
    
        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getScannedHouseDetailService($request, $id)
    {        
        try {
            // Query to fetch house details along with the name of the head of family (hof)
            $house = HouseSurvey::where('house_id', $id) // Assuming $id corresponds to house_id
                ->select(
                    'house_number as houseNo',
                    'address as houseAddress',
                    'head_of_family as hof'
                )
                ->first();
    
            if (!$house) {
                return ['status' => false, 'code' => 404, 'message' => 'House not found'];
            }
    
            // Now, get the name of the head of family (hof) from the mp_citizen table using hof (citizen_id)
            $hofName = Citizen::where('citizen_id', $house->hof)
                ->select('name')
                ->first();
    
            // Add hof name to the house data
            if ($hofName) {
                $house->hofName = $hofName->name;
            } else {
                $house->hofName = null; // If hof is not found
            }
    
            return [
                'status' => true,
                'code' => 200,
                'data' => $house
            ];
    
        } catch (QueryException $t) {
            // Log::error('DB Error in getScannedHouseDetailService method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            // Log::error('Error in getScannedHouseDetailService method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function uploadGarbageByHouseId($request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'houseId' => 'required|exists:mp_house,house_id',
                'status' => 'required|in:collected,not collected',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'userId' => 'required|exists:mp_users,user_id', // Validate userId
                'garbageImage.*' => 'required|file|mimes:jpg,jpeg,png|max:2048', // 2MB max per file
            ]);
    
            if ($validator->fails()) {
                return [
                    'status' => false,
                    'code' => 422,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors(),
                ];
            }

             // Map status to integer
            $statusMapping = [
                'collected' => 1,
                'not collected' => 0,
            ];
    
            // Create the garbage record
            $garbage = new Garbage();
            $garbage->house_id = $request->houseId;
            $garbage->status = $statusMapping[$request->status];
            $garbage->latitude = $request->latitude;
            $garbage->longitude = $request->longitude;
            $garbage->user_id = $request->userId; // Assign userId
            $garbage->created_at = now();
            $garbage->save();
    
            // Process and save images
            $garbageId = $garbage->garbage_id; // Get the inserted garbage_id
            $uploadedFiles = $request->file('garbageImage');
            $fileUrls = [];
    
            foreach ($uploadedFiles as $file) {
                // Generate unique file name
                $fileName = uniqid('garbage_') . '.' . $file->getClientOriginalExtension();
    
                // Generate S3 file path
                $fileUrl = 'garbage/' . $fileName;
    
                // Upload to S3
                Storage::disk('s3')->put($fileUrl, file_get_contents($file));
    
                // Save file details to mp_file_upload
                $fileUpload = new FileUpload();
                $fileUpload->table_id = $garbageId;
                $fileUpload->table_name = 'mp_garbage';
                $fileUpload->document_type = 'garbage';
                $fileUpload->file_name = $fileName;
                $fileUpload->file_url = $fileUrl;
                $fileUpload->created_at = now();
                $fileUpload->save();
    
                // Add file URL to response
                $fileUrls[] = Storage::disk('s3')->url($fileUrl);
            }
    
            return [
                'status' => true,
                'code' => 201,
                'message' => 'Garbage record and files uploaded successfully',
                'data' => [
                    'garbage_id' => $garbageId,
                    'file_urls' => $fileUrls,
                ],
            ];
        } catch (QueryException $e) {
            // Log::error('DB Error in uploadGarbageByHouseId: ' . $e->getMessage());
            return ['status' => false, 'code' => $e->getCode(), 'message' => $e->getMessage()];
        } catch (Throwable $t) {
            // Log::error('Error in uploadGarbageByHouseId: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'message' => $t->getMessage()];
        }
    }

    public function getGarbageCollectionData(string $date)
    {
        try {
            // Validate the input date format
            $validator = Validator::make(['date' => $date], [
                'date' => 'required|date_format:Y-m-d'
            ]);

            if ($validator->fails()) {
                return [
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->all()
                ];
            }

            // Query the mp_garbage table for records matching the date
            $garbageData = Garbage::whereDate('created_at', $date)->get();

            // Transform the data into the desired format
            $responseData = $garbageData->map(function ($item) {
                return [
                    'house_id' => $item->house_id,
                    'lat' => $item->latitude,
                    'lng' => $item->longitude,
                    'collected' => $item->status == 1
                ];
            });

            return [
                'status' => true,
                'message' => 'Garbage collection data retrieved successfully',
                'data' => $responseData
            ];

        } catch (\Exception $e) {
            return [
                'status' => false,
                'message' => 'An error occurred while retrieving data: ' . $e->getMessage()
            ];
        }
    }
}