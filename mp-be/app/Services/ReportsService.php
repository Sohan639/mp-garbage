<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\Asset;
use App\Models\Employment;
use App\Models\HouseSurvey;
use App\Models\Garbage;
use App\Models\User;
use App\Models\Citizen;
use App\Models\Medical;
use App\Models\Qualifications;
use App\Models\RelationMaster;
use App\Models\Scheme;
use App\Models\SchemeMaster;
use App\Models\Tax;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ReportsService
{
    public function getRentTaxVerificationReportService($request)
    {        
        try {
            $reportsData = HouseSurvey::join('mp_citizen as mpc','mpc.citizen_id','mp_house.head_of_family')
            ->whereIn('mp_house.house_number',$request->houseNumber)
            ->select(    
                'house_number as houseNo',       
                'mpc.name as hofName',
                'mp_house.address as address',
                'mpc.mobile_number as contact',
                'mp_house.house_id as houseId',
                'mpc.citizen_id as citizenid'
            )
            ->get();
            foreach ($reportsData as $key => $data) {
                $houseRelatedtax = Tax::where('house_id',$data->houseId)
                ->where('mp_taxes.tax_year', $request->taxYear)
                ->first();
                if($houseRelatedtax)
                {
                    $data->assessmentYear = $request->taxYear;
                    $data->rent='PAID';
                }
                else{
                    $data->assessmentYear = $request->taxYear;
                    $data->rent=' NOT PAID';
                }
            }
            return ['status' => true, 'code' => 200, 'data' => $reportsData];
        } catch (QueryException $t) {
            Log::error('DB Error in getRelationshipOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getRelationshipOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getDailyCollectionStatusReportService($request)
    {
        try {
            // Fetch records from mp_garbage where created_at is within the provided date range
            $garbageQuery = Garbage::whereBetween('created_at', [$request->fromDate, $request->toDate]);

             // Apply staffId filter if it is not empty
            if (!empty($request->staffId)) {
                $garbageQuery->whereIn('user_id', $request->staffId);
            }

            // Fetch records after applying filters
            $garbageRecords = $garbageQuery->get();

            $data = [];

            foreach ($garbageRecords as $record) {
                // Fetch house number from mp_house table
                $house = HouseSurvey::where('house_id', $record->house_id)->first();
                $houseNo = $house ? $house->house_number : null;

                // Fetch staff name from mp_users table
                $user = User::where('user_id', $record->user_id)->first();
                $staffName = $user ? $user->name : null;

                // Map status to collectionStatus
                $collectionStatus = $record->status === 1 ? 'Collected' : 'Not collected';

                // Add to the response data array
                $data[] = [
                    'houseNo' => $houseNo,
                    'collectionStatus' => $collectionStatus,
                    'staffName' => $staffName,
                ];
            }

            return [
                'status' => true,
                'code' => 200,
                'data' => $data,
            ];
        } catch (QueryException $t) {
            Log::error('DB Error in getDailyCollectionStatusReportService: ' . $t->getMessage());
            return [
                'status' => false,
                'code' => $t->getCode(),
                'errors' => $t->getMessage(),
            ];
        } catch (Throwable $t) {
            Log::error('Error in getDailyCollectionStatusReportService: ' . $t->getMessage());
            return [
                'status' => false,
                'code' => 500,
                'errors' => $t->getMessage(),
            ];
        }
    }
}