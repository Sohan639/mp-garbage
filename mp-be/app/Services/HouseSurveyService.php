<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\Asset;
use App\Models\Employment;
use App\Models\FileUpload;
use App\Models\HealthStatus;
use App\Models\HouseCitizenConnect;
use App\Models\HouseSurvey;
use App\Models\Citizen;
use App\Models\Medical;
use App\Models\Qualifications;
use App\Models\Scheme;
use App\Models\Tax;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class HouseSurveyService
{
    public function getHouseSurveyListService($request)
    {
        $sort = $request->has('sort') ? $request->get('sort') : 'mp_house.house_id';
        $order = $request->has('order') ? $request->get('order') : 'DESC';
        $search = $request->has('search') ? $request->get('search') : '';

        $sortColumn = $sort;
        if ($sort == 'houseNumber') {
            $sortColumn = 'house_number';
        } elseif ($sort == 'hofName') {
            $sortColumn = 'head_of_family';
        } elseif ($sort == 'mobileNumber') {
            $sortColumn = 'mobile_number';
        } elseif ($sort == 'noOfMembers') {
            $sortColumn = 'members_count';
        }

        try {

            $houseSurvey = HouseSurvey::join("mp_users as u","u.user_id","=", "mp_house.created_by")
            ->where('mp_house.house_status',  1)
                   ->where(function ($query) use ($search) {
                    if ($search != '') {                        
                        $query->where('mp_house.house_number', 'LIKE', '%' . $search . '%');
                        $query->orWhere('mp_house.head_of_family', 'LIKE', '%' . $search . '%');
                        $query->orWhere('c.mobile_number', 'LIKE', '%' . $search . '%');
                        $query->orWhere('mp_house.members_count', 'LIKE', '%' . $search . '%');
                    }
                })
                ->orderBy($sortColumn, $order)
            ->select(                    
                'mp_house.house_id as houseId',
                'mp_house.house_number as houseNo',
                'mp_house.members_count as noOfMembers',
                'mp_house.income_level as incomeLevel',
                'mp_house.address as houseAddress',
                'mp_house.created_at as createdOn',
                'u.name as createdBy',
                'mp_house.house_status as status'
            )
            ->get();
            foreach ($houseSurvey as $key => $hs) {
                $citizenDetails = Citizen::join('mp_house_citizen as hc','hc.citizen_id','mp_citizen.citizen_id')->where('status','=',1)->where('hc.house_id','=',$hs->houseId)
                ->where('is_head_of_family','=',1)
                ->select('mp_citizen.citizen_id as citizenId','name','address','mobile_number as contact', 'is_head_of_family as isHof')->first();  
               if ($citizenDetails)   {
                 $hs->hofName = $citizenDetails->name;
                $hs->hofContact = $citizenDetails->contact;
                $hs->address = $citizenDetails->address;  
               }   
               else{
                $hs->hofName ='-';
                $hs->hofContact = '-';
                $hs->address = '-';
               }           
            }
            return ['status' => true, 'code' => 200, 'data' => $houseSurvey];
        } catch (QueryException $t) {
            Log::error('DB Error in getHouseSurveyList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getHouseSurveyList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getHouseNumberListService($request)
    {
        try {

            $houseNumberList = HouseSurvey::where('mp_house.house_status',  1)
            ->pluck(                    
                'mp_house.house_number',
            )
            ->toArray();
            return ['status' => true, 'code' => 200, 'data' => $houseNumberList];
        } catch (QueryException $t) {
            Log::error('DB Error in getHouseSurveyList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getHouseSurveyList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getResidentDetailsByHouseNumber($id){
        try {           
            $authId = Auth::user()->user_id;
            $houseSurveyDetails  = $residentDetail = $citizenDetails =[];
            $residentDetail = HouseSurvey::where('house_status', '=', 1)
                ->select(
                    'house_id as houseId',
                    'house_number as houseNo',
                    'head_of_family as hof',
                    'address',
                    DB::raw('COALESCE(members_count, "") as membersCount'),
                    DB::raw('COALESCE(income_level, "") as incomeLevel'),
                    DB::raw('COALESCE(house_status, "") as status'),
                    DB::raw('COALESCE(house_type, "") as houseType'),
                    DB::raw('COALESCE(rent_type, "") as renttype'),
                    DB::raw('COALESCE(business_type, "") as businessType'),
                    DB::raw('COALESCE(house_length, "") as houseLength'),
                    DB::raw('COALESCE(house_breadth, "") as houseBreadth'),
                    DB::raw('COALESCE(house_height, "") as houseHeight'),
                    DB::raw('COALESCE(no_of_floors, "") as noOfFloors'),
                )
                ->find($id);
            $houseSurveyDetails= $residentDetail;
            $houseImages = FileUpload::where('table_name', '=', 'mp_house')->where('table_id','=',$residentDetail->houseId)->select('file_name as file','document_type as docType')->get();
            $house=[];
            foreach ($houseImages as $key => $value) {
                if($value->docType === 'houseImage')
                {
                    $house[$key] = getFileS3Bucket(getPathS3Bucket() . '/house/' .  $value->file);
                } 
                if($value->docType === 'rentReceipt')  {
                    $houseSurveyDetails->rentReceipt = getFileS3Bucket(getPathS3Bucket() . '/rent/' .  $value->file);
                }           
                if($value->docType === 'rentAgreement')  {
                    $houseSurveyDetails->rentAgreement = getFileS3Bucket(getPathS3Bucket() . '/rent/' .  $value->file);
                }  
                if($value->docType === 'policeVerification')  {
                    $houseSurveyDetails->policeVerification = getFileS3Bucket(getPathS3Bucket() . '/rent/' .  $value->file);
                }  
            }
            $houseSurveyDetails->houseImage = $house;
            $houseSurveyDetails->tax = Tax::where('house_id',$residentDetail->houseId)
            ->select('tax_id as taxId','type as taxType','amount as taxAmount','payment_date as paymentDate',
            DB::raw('COALESCE(receipt_number, "") as receiptNumber'),'tax_year as taxYear')->get();

            $citizenDetails = Citizen::join('mp_house_citizen as hc', 'hc.citizen_id', 'mp_citizen.citizen_id')
            ->where('mp_citizen.status', '=', 1)
            ->where('hc.house_id', '=', $residentDetail->houseId)
            ->select(
                'mp_citizen.citizen_id as citizenId',
                DB::raw('COALESCE(name, "") as name'),
                DB::raw('COALESCE(gender, "") as gender'),
                DB::raw('COALESCE(date_of_birth, "") as dob'),
                DB::raw('COALESCE(aadhar_number, "") as aadharNo'),
                DB::raw('COALESCE(pan_card_number, "") as panCard'),
                DB::raw('COALESCE(driving_license, "") as drivingLicense'),
                DB::raw('COALESCE(address, "") as address'),
                DB::raw('COALESCE(mobile_number, "") as contact'),
                DB::raw('COALESCE(alternate_phone, "") as altContact'),
                DB::raw('COALESCE(email_id, "") as email'),
                DB::raw('COALESCE(citizen_password, "") as password'),
                DB::raw('COALESCE(is_head_of_family, 0) as isHof'),
                DB::raw('COALESCE(relation_with_hof, "") as relationWithHof'),
                DB::raw('COALESCE(annual_income, 0) as annualIncome'),
                DB::raw('COALESCE(profile_photo, "") as image'),
                DB::raw('COALESCE(electricity_bill_no, "") as electricityBill'),
                DB::raw('COALESCE(water_bill_no, "") as waterBill'),
                DB::raw('COALESCE(passport_no, "") as passportNo'),
                DB::raw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age')
            )
            ->get();
            if($citizenDetails)
            {
                $houseSurveyDetails->citizenDetails = $citizenDetails;                    
            foreach ($houseSurveyDetails->citizenDetails  as $key => $ctz) {
                if($ctz->image){
                    $ctz->citizenImage = getFileS3Bucket( getPathS3Bucket() . '/' .  $ctz->image);
                }               
                $ctz->qualificationDetails = Qualifications::where('mp_qualifications.status', '=', 1)->where('mp_qualifications.citizen_id','=',$ctz->citizenId)
                ->select('qualification_id as qualificationId',
                DB::raw('COALESCE(qualification_name, "") as qualificationName'),
                DB::raw('COALESCE(institution_name, "") as institutionName'),
                DB::raw('COALESCE(year_of_passing, "") as yop'),
                DB::raw('COALESCE(grade, "") as grade'),
                DB::raw('COALESCE(degree_type, "") as degreeType'),
                DB::raw('COALESCE(status, "") as status'),
                )->get();
                $ctz->medicalDetails = Medical::where('mp_health_data.status', '=', 1)->where('mp_health_data.citizen_id','=',$ctz->citizenId)
                ->select('health_id as medicalId',
                DB::raw('COALESCE(blood_group, "") as bloodGroup'),
                DB::raw('COALESCE(weight, "") as weight'),
                DB::raw('COALESCE(height, "") as height'),
                DB::raw('COALESCE(health_status, "") as healthStatus'),
                DB::raw('COALESCE(other_illness, "") as otherHealthIssues'),
                DB::raw('COALESCE(status, "") as status')
                )->get();
                foreach ($ctz->medicalDetails as $key => $value) {
                   $value->healthStatus = json_decode($value->healthStatus);
                }
                $ctz->employmentDetails = Employment::where('mp_employment.status', '=', 1)->where('mp_employment.citizen_id','=',$ctz->citizenId)
                ->select('employment_id as employmentId',
                DB::raw('COALESCE(service_type, "") as serviceType'),
                DB::raw('COALESCE(employer_name, "") as employerName'),
                DB::raw('COALESCE(position, "") as position'),
                DB::raw('COALESCE(salary, "") as salary'),
                DB::raw('COALESCE(joining_date, "") as joiningDate'),
                DB::raw('COALESCE(status, "") as status')
                )->get();
                $ctz->schemeDetails = Scheme::join('mp_scheme_master as mps','mps.scheme_id','mp_citizen_scheme.scheme_id')->where('mp_citizen_scheme.status', '=', 'Active')->where('mp_citizen_scheme.citizen_id','=',$ctz->citizenId)
                ->select('mp_citizen_scheme.citizen_scheme_id as ctzSchemeId',
                'mps.scheme_id as schemeId',
                DB::raw('COALESCE(mps.name, "") as schemeName'),
                DB::raw('COALESCE(mp_citizen_scheme.enrollment_date, "") as enrollmentDate'),
                DB::raw('COALESCE(mp_citizen_scheme.status, "") as status')
                )->get();
                $ctz->taxDetails = Tax::where('mp_taxes.status', '=', 1)->where('mp_taxes.citizen_id','=',$ctz->citizenId)
                ->select('tax_id as taxId',
                DB::raw('COALESCE(type, "") as type'),
                DB::raw('COALESCE(amount, "") as amount'),
                DB::raw('COALESCE(payment_date, "") as paymentDate'),
                DB::raw('COALESCE(receipt_number, "") as receiptNumber'),
                DB::raw('COALESCE(tax_year, "") as taxYear'),
                DB::raw('COALESCE(mp_taxes.status, "") as status'),

                )->get();
                $ctz->assetDetails = Asset::join('mp_assets as mpa','mpa.asset_id','mp_citizen_assets.assets_id')->where('mp_citizen_assets.status', '=', 1)->where('mp_citizen_assets.citizen_id','=',$ctz->citizenId)
                ->select('mp_citizen_assets.citizen_assets_id as ctzAssetId',
                'mp_citizen_assets.assets_id as assetId',
                DB::raw('COALESCE(mpa.name, "") as assetName'),
                DB::raw('COALESCE(mp_citizen_assets.status, "") as status'),
                )->get();

                $ctz->document = FileUpload::where('table_name', '=', 'mp_citizen')->where('table_id','=',$ctz->citizenId)
                ->select('file_upload_id as documentId',
                'document_type as documentType',
                'file_name as documentName',
                'file_url as document'
                )->get();
                foreach ($ctz->document  as $key => $doc) {
                    if($doc->document){
                        $doc->document = getFileS3Bucket( getPathS3Bucket() . '/document/' .  $doc->document);
                    }   
                }  
            }
        } 
    
            return response()->json(['status' => true, 'code' => 200, 'data' => $houseSurveyDetails], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function viewResidentDetailsByHouseNumber($id){
        try {           
            $authId = Auth::user()->user_id;
            $houseSurveyDetails  = $residentDetail = $citizenDetails =[];
            $residentDetail = HouseSurvey::where('house_status', '=', 1)
                ->select(
                    'house_id as houseId',
                    'house_number as houseNo',
                    'head_of_family as hof',
                    'address',
                    DB::raw('COALESCE(members_count, "-") as membersCount'),
                    DB::raw('COALESCE(income_level, "-") as incomeLevel'),
                    DB::raw('COALESCE(house_status, "-") as status'),
                    DB::raw('COALESCE(house_type, "-") as houseType'),
                    DB::raw('COALESCE(rent_type, "") as renttype'),
                    DB::raw('COALESCE(business_type, "") as businessType'),
                    DB::raw('COALESCE(house_length, "-") as houseLength'),
                    DB::raw('COALESCE(house_breadth, "-") as houseBreadth'),
                    DB::raw('COALESCE(house_height, "-") as houseHeight'),
                    DB::raw('COALESCE(no_of_floors, "-") as noOfFloors'),
                )
                ->find($id);
            $houseSurveyDetails= $residentDetail;
            $houseImages = FileUpload::where('table_name', '=', 'mp_house')->where('table_id','=',$residentDetail->houseId)->select('file_name as file','document_type as docType')->get();
            $house=[];
            foreach ($houseImages as $key => $value) {
                if($value->docType === 'houseImage')
                {
                    $house[$key] = getFileS3Bucket(getPathS3Bucket() . '/house/' .  $value->file);
                } 
                if($value->docType === 'rentReceipt')  {
                    $houseSurveyDetails->rentReceipt = getFileS3Bucket(getPathS3Bucket() . '/rent/' .  $value->file);
                    $houseSurveyDetails->rentReceiptName = $value->file;
                }           
                if($value->docType === 'rentAgreement')  {
                    $houseSurveyDetails->rentAgreement = getFileS3Bucket(getPathS3Bucket() . '/rent/' .  $value->file);
                    $houseSurveyDetails->rentAgreementName =  $value->file;
                }  
                if($value->docType === 'policeVerification')  {
                    $houseSurveyDetails->policeVerification = getFileS3Bucket(getPathS3Bucket() . '/rent/' .  $value->file);
                    $houseSurveyDetails->policeVerificationName =  $value->file;
                }  
            }
            $houseSurveyDetails->houseImage = $house;
            $houseSurveyDetails->tax = Tax::where('house_id',$residentDetail->houseId)
            ->select('tax_id as taxId','type as taxType','amount as taxAmount','payment_date as paymentDate',
            DB::raw('COALESCE(receipt_number, "") as receiptNumber'),'tax_year as taxYear')->get();

            $citizenDetails = Citizen::join('mp_house_citizen as hc', 'hc.citizen_id', 'mp_citizen.citizen_id')
            ->where('mp_citizen.status', '=', 1)
            ->where('hc.house_id', '=', $residentDetail->houseId)
            ->select(
                'mp_citizen.citizen_id as citizenId',
                DB::raw('COALESCE(name, "-") as name'),
                DB::raw('COALESCE(gender, "-") as gender'),
                DB::raw('COALESCE(date_of_birth, "-") as dob'),
                DB::raw('COALESCE(aadhar_number, "-") as aadharNo'),
                DB::raw('COALESCE(pan_card_number, "-") as panCard'),
                DB::raw('COALESCE(driving_license, "-") as drivingLicense'),
                DB::raw('COALESCE(address, "-") as address'),
                DB::raw('COALESCE(mobile_number, "-") as contact'),
                DB::raw('COALESCE(alternate_phone, "-") as altContact'),
                DB::raw('COALESCE(email_id, "-") as email'),
                DB::raw('COALESCE(citizen_password, "-") as password'),
                DB::raw('COALESCE(is_head_of_family, 0) as isHof'),
                DB::raw('COALESCE(relation_with_hof, "-") as relationWithHof'),
                DB::raw('COALESCE(annual_income, 0) as annualIncome'),
                DB::raw('COALESCE(profile_photo, "-") as image'),
                DB::raw('COALESCE(electricity_bill_no, "-") as electricityBill'),
                DB::raw('COALESCE(water_bill_no, "-") as waterBill'),
                DB::raw('COALESCE(passport_no, "-") as passportNo'),
                DB::raw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age')
            )
            ->get();
            if($citizenDetails)
            {
                foreach ($citizenDetails as $key => $ct) {
                    $ct->aadharNo = maskData($ct->aadharNo, 1, 1);
                    $ct->contact = maskData($ct->contact, 1, 1);
                    $ct->panCard = maskData($ct->panCard, 1, 1);
                    if (filter_var($ct->email, FILTER_VALIDATE_EMAIL)) {
                        $emailParts = explode('@', $ct->email);
                        $ct->email = maskData($ct->email, 1, strlen($emailParts[1]) + 1);
                    } else {
                        $ct->email = maskData($ct->email, 1, 1); // Fallback for invalid or empty email
                    }
                }    
                $houseSurveyDetails->citizenDetails = $citizenDetails;                
            foreach ($citizenDetails  as $key => $ctz) {
                if($ctz->image){
                    $ctz->citizenImage = getFileS3Bucket( getPathS3Bucket() . '/citizen/' .  $ctz->image);
                }               
                $ctz->qualificationDetails = Qualifications::where('mp_qualifications.status', '=', 1)->where('mp_qualifications.citizen_id','=',$ctz->citizenId)
                ->select('qualification_id as qualificationId',
                DB::raw('COALESCE(qualification_name, "-") as qualificationName'),
                DB::raw('COALESCE(institution_name, "-") as institutionName'),
                DB::raw('COALESCE(year_of_passing, "-") as yop'),
                DB::raw('COALESCE(grade, "-") as grade'),
                DB::raw('COALESCE(degree_type, "-") as degreeType'),
                DB::raw('COALESCE(status, "-") as status'),
                )->get();
               if($ctz->qualificationDetails) 
               {
                    foreach ($ctz->qualificationDetails  as $key => $qf) {
                        $qf->institutionName = maskData($qf->institutionName, 1, 1);
                        $qf->grade = maskData($qf->grade, 0, 0);
                    }  
                }
                $ctz->medicalDetails = Medical::where('mp_health_data.status', '=', 1)->where('mp_health_data.citizen_id','=',$ctz->citizenId)
                ->select('health_id as medicalId',
                DB::raw('COALESCE(blood_group, "-") as bloodGroup'),
                DB::raw('COALESCE(weight, "-") as weight'),
                DB::raw('COALESCE(height, "-") as height'),
                DB::raw('COALESCE(health_status, "-") as healthStatus'),
                DB::raw('COALESCE(other_illness, "-") as otherHealthIssues'),
                DB::raw('COALESCE(status, "-") as status')
                )->get();
                if($ctz->medicalDetails){ 
                        foreach ($ctz->medicalDetails as $key => $value) {
                            $healthArr =[];
                            foreach ( json_decode($value->healthStatus)  as $key => $hs) {
                                    $data = HealthStatus::find($hs);
                                    array_push($healthArr,maskData($data->health_status, 1, 1));
                            }
                           $value->healthStatus = $healthArr;
                        }
                    }
                $ctz->employmentDetails = Employment::where('mp_employment.status', '=', 1)->where('mp_employment.citizen_id','=',$ctz->citizenId)
                ->select('employment_id as employmentId',
                DB::raw('COALESCE(service_type, "-") as serviceType'),
                DB::raw('COALESCE(employer_name, "-") as employerName'),
                DB::raw('COALESCE(position, "-") as position'),
                DB::raw('COALESCE(salary, "-") as salary'),
                DB::raw('COALESCE(joining_date, "-") as joiningDate'),
                DB::raw('COALESCE(status, "-") as status')
                )->get();
                if( $ctz->employmentDetails )
                {
                    foreach ($ctz->employmentDetails  as $key => $em) {
                        $em->employerName = maskData($em->employerName, 1, 1);
                        $em->salary = maskData($em->salary, 0, 1);
                        $em->joiningDate = maskData($em->joiningDate, 0, 1);
                    }
                }                 
                $ctz->schemeDetails = Scheme::join('mp_scheme_master as mps','mps.scheme_id','mp_citizen_scheme.scheme_id')->where('mp_citizen_scheme.status', '=', 'Active')->where('mp_citizen_scheme.citizen_id','=',$ctz->citizenId)
                ->select('mp_citizen_scheme.citizen_scheme_id as ctzSchemeId',
                'mps.scheme_id as schemeId',
                DB::raw('COALESCE(mps.name, "-") as schemeName'),
                DB::raw('COALESCE(mp_citizen_scheme.enrollment_date, "-") as enrollmentDate'),
                DB::raw('COALESCE(mp_citizen_scheme.status, "-") as status')
                )->get();
                $ctz->taxDetails = Tax::where('mp_taxes.status', '=', 1)->where('mp_taxes.citizen_id','=',$ctz->citizenId)
                ->select('tax_id as taxId',
                DB::raw('COALESCE(type, "-") as type'),
                DB::raw('COALESCE(amount, "-") as amount'),
                DB::raw('COALESCE(payment_date, "-") as paymentDate'),
                DB::raw('COALESCE(receipt_number, "-") as receiptNumber'),
                DB::raw('COALESCE(tax_year, "-") as taxYear'),
                DB::raw('COALESCE(mp_taxes.status, "-") as status'),

                )->get();
                $ctz->assetDetails = Asset::join('mp_assets as mpa','mpa.asset_id','mp_citizen_assets.assets_id')->where('mp_citizen_assets.status', '=', 1)->where('mp_citizen_assets.citizen_id','=',$ctz->citizenId)
                ->select('mp_citizen_assets.citizen_assets_id as ctzAssetId',
                'mp_citizen_assets.assets_id as assetId',
                DB::raw('COALESCE(mpa.name, "-") as assetName'),
                DB::raw('COALESCE(mp_citizen_assets.status, "-") as status'),
                )->get();

                $ctz->document = FileUpload::where('table_name', '=', 'mp_citizen')->where('table_id','=',$ctz->citizenId)
                ->select('file_upload_id as documentId',
                'document_type as documentType',
                'file_name as documentName',
                'file_url as document'
                )->get();
                foreach ($ctz->document  as $key => $doc) {
                    if($doc->document){
                        $doc->document = getFileS3Bucket( getPathS3Bucket() . '/document/' .  $doc->document);
                    }   
                }  
            }
        } 
    
            return response()->json(['status' => true, 'code' => 200, 'data' => $houseSurveyDetails], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function viewHouseListByCitizenIdService($id){
        try {           
            $citizenBasedHouseList = Citizen::join('mp_house_citizen as hc','hc.citizen_id','mp_citizen.citizen_id')
            ->join('mp_house as mph','mph.house_id','hc.house_id')
            ->where('mp_citizen.status','=',1)
            ->where('mp_citizen.citizen_id','=',$id)
            ->select('mph.house_id as houseId','mph.house_number as houseNo')->get();             
    
            return response()->json(['status' => true, 'code' => 200, 'data' => $citizenBasedHouseList], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addHouseSurveyService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'houseNo' => 'required',
                'address' => 'required',
                'incomeLevel' => 'required',
                'houseType' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }

            $houseDetail = new HouseSurvey();
            $houseDetail->house_number = $request->houseNo;
            $houseDetail->address = $request->address;
            $houseDetail->members_count = 0;
            $houseDetail->head_of_family = 0;
            $houseDetail->income_level = $request->incomeLevel;
            $houseDetail->house_type = $request->houseType;
            $houseDetail->rent_type = $request->rentType;
            $houseDetail->business_type = $request->businessType;
            $houseDetail->house_length = $request->houseLength;
            $houseDetail->house_breadth = $request->houseBreadth;
            $houseDetail->house_height = $request->houseHeight;
            $houseDetail->no_of_floors = $request->noOfFloors;
            $houseDetail->house_status = 1;
            $houseDetail->created_by = $authId;
            $houseDetail->created_at = now();
           $houseDetail->save();
            if(!empty($request->houseImage))
            {
                foreach ($request->houseImage as $key => $image) {
                    if($image)
                    {
                        $path = '/house';
                        $s3Link = Storage::disk('s3')->put($path, $image);
                    }                  
                    $fileUpload = new FileUpload();
                    $fileUpload->table_id = $houseDetail->house_id;
                    $fileUpload->table_name = 'mp_house';
                    $fileUpload->document_type = 'houseImage';
                    $fileUpload->file_name = $s3Link?substr($s3Link, strrpos($s3Link, '/') + 1):'';
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }
            }
            if($request->rentReceipt)
                {
                    $path = '/rent';
                    $s3Link = Storage::disk('s3')->put($path, $request->rentReceipt);
                    $fileUpload = new FileUpload();
                    $fileUpload->table_id = $houseDetail->house_id;
                    $fileUpload->table_name = 'mp_house';
                    $fileUpload->document_type = 'rentReceipt';
                    $fileUpload->file_name = $request->rentReceipt->getClientOriginalName();;
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }       
             if($request->rentAgreement)
                {
                    $path =  '/rent';
                    $s3Link = Storage::disk('s3')->put($path, $request->rentAgreement);
                    $fileUpload = new FileUpload();
                    $fileUpload->table_id = $houseDetail->house_id;
                    $fileUpload->table_name = 'mp_house';
                    $fileUpload->document_type = 'rentAgreement';
                    $fileUpload->file_name = $request->rentAgreement->getClientOriginalName();;
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }
             if($request->policeVerification)
                {
                        $path = '/rent';
                        $s3Link = Storage::disk('s3')->put($path, $request->rentAgreement);
                        $fileUpload = new FileUpload();
                        $fileUpload->table_id = $houseDetail->house_id;
                        $fileUpload->table_name = 'mp_house';
                        $fileUpload->document_type = 'policeVerification';
                        $fileUpload->file_name = $request->rentAgreement->getClientOriginalName();;
                        $fileUpload->file_url = $s3Link;
                        $fileUpload->created_at = now();
                        $fileUpload->save();
                }
                if($request->tax)
                {
                    foreach ($request->tax as $key => $value) {
                        $taxDetail = new Tax();
                        $taxDetail->house_id = $houseDetail->house_id;
                        $taxDetail->type = $value['taxType'];
                        $taxDetail->amount = $value['taxAmount'];
                        $taxDetail->payment_date = $value['paymentDate'];
                        $taxDetail->receipt_number = $value['receiptNumber'];
                        $taxDetail->tax_year = $value['taxYear'];
                        $taxDetail->status = 1;
                        $taxDetail->created_by = $authId;
                        $taxDetail->created_at = now();
                        $taxDetail->save();
                    }
                }

            return response()->json(['status' => true, 'code' => 201, 'message' => 'House Details Added!','data' => $houseDetail->house_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewCitizenService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            if( $request->citizenImage)
            {
            $path = '/citizen';
            $s3Link = Storage::disk('s3')->put($path, $request->citizenImage);
            }
            else{
                $s3Link='';
            }
            $validator = Validator::make($request->all(), [
                'houseId' => 'required',
                'name' => 'required',
                'gender' => 'required',
                'dob' => 'required',
                'address' => 'required',
                'contact' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $citizenDetail = new Citizen();
            $citizenDetail->name = $request->name;
            $citizenDetail->gender = $request->gender;
            $citizenDetail->date_of_birth = $request->dob;
            $citizenDetail->aadhar_number = $request->aadharNo;
            $citizenDetail->pan_card_number = $request->panCard;
            $citizenDetail->driving_license = $request->drivingLicense;
            $citizenDetail->address = $request->address;
            $citizenDetail->mobile_number = $request->contact;
            $citizenDetail->alternate_phone = $request->altContact;
            $citizenDetail->email_id = $request->email;
            $citizenDetail->citizen_password = $request->password;
            $citizenDetail->is_head_of_family = $request->isHof;
            $citizenDetail->relation_with_hof = $request->relationWithHof;
            $citizenDetail->annual_income = $request->annualIncome?$request->annualIncome:0;
            $citizenDetail->electricity_bill_no = $request->electricityBillNo?$request->electricityBillNo:0;
            $citizenDetail->water_bill_no = $request->waterBillNo? $request->waterBillNo:0;
            $citizenDetail->passport_no = $request->passportNo;
            $citizenDetail->profile_photo = $s3Link?substr($s3Link, strrpos($s3Link, '/') + 1):'';
            $citizenDetail->status = 1;
            $citizenDetail->created_by = $authId;
            $citizenDetail->created_at = now();
            $citizenDetail->save();

            $houseDetails = HouseSurvey::where('house_status', '=', 1)->find($request->houseId);
            $houseDetails->members_count = $houseDetails->members_count+1;
            if($request->isHof === 1)
            {
                $houseDetails->head_of_family = $citizenDetail->citizen_id;
            }
            $houseDetails->save();

            $houseCitizenConnect = new HouseCitizenConnect();
            $houseCitizenConnect->house_id = $request->houseId;
            $houseCitizenConnect->citizen_id = $citizenDetail->citizen_id;
            $houseCitizenConnect->created_at = now();
            $houseCitizenConnect->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Citizen Details Added!','data' => $citizenDetail->citizen_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewQualificationDetailService($request)
    {
        try {    
            $qualificationDetails=[];       
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'qualificationName' => 'required',
                'institutionName'=>'required',
                 'yop'=>'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $qualificationDetail = new Qualifications();
            $qualificationDetail->citizen_id = $request->citizenId;
            $qualificationDetail->qualification_name = $request->qualificationName;
            $qualificationDetail->institution_name = $request->institutionName;
            $qualificationDetail->year_of_passing = $request->yop;
            $qualificationDetail->grade = $request->grade;
            $qualificationDetail->degree_type = $request->degreeType;
            $qualificationDetail->status = 1;
            $qualificationDetail->created_by = $authId;
            $qualificationDetail->created_at = now();
            $qualificationDetail->save();      

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Qualification Details Added!','data' => $qualificationDetail->qualification_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewMedicalDetailService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'bloodGroup' => 'required',
                'weight' => 'required',
                'height' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $medicalDetails = new Medical();
            $medicalDetails->citizen_id = $request->citizenId;
            $medicalDetails->blood_group = $request->bloodGroup;
            $medicalDetails->weight = $request->weight;
            $medicalDetails->height = $request->height;
            $medicalDetails->health_status = json_encode($request->healthStatus);
            $medicalDetails->other_illness = $request->otherHealthIssues;
            $medicalDetails->status = 1;
            $medicalDetails->created_by = $authId;
            $medicalDetails->created_at = now();
            $medicalDetails->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Medical Details Added!','data' => $medicalDetails->health_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewEmploymentDetailService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'employerName' => 'required',
                'serviceType' => 'required',
                'position' => 'required',
                'salary' => 'required',
                'joiningDate' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $employmentDetail = new Employment();
            $employmentDetail->citizen_id = $request->citizenId;
            $employmentDetail->service_type = $request->serviceType;
            $employmentDetail->employer_name = $request->employerName;
            $employmentDetail->position = $request->position;
            $employmentDetail->salary = $request->salary;
            $employmentDetail->joining_date = $request->joiningDate;
            $employmentDetail->status = 1;
            $employmentDetail->created_by = $authId;
            $employmentDetail->created_at = now();
            $employmentDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Employment Details Added!','data' => $employmentDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewSchemesDetailService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'schemeId' => 'required',
                'enrollmentDate' => 'required',
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $schemeDetail = new Scheme();
            $schemeDetail->citizen_id = $request->citizenId;
            $schemeDetail->scheme_id = $request->schemeId;
            $schemeDetail->enrollment_date = $request->enrollmentDate;
            $schemeDetail->status = 1;
            $schemeDetail->created_by = $authId;
            $schemeDetail->created_at = now();
            $schemeDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Scheme Details Added!','data' => $schemeDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewTaxDetailService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'type' => 'required',
                'paymentDate' => 'required',
                'taxYear' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $taxDetail = new Tax();
            $taxDetail->citizen_id = $request->citizenId;
            $taxDetail->type = $request->type;
            $taxDetail->amount = $request->amount;
            $taxDetail->payment_date = $request->paymentDate;
            $taxDetail->receipt_number = $request->receiptNumber;
            $taxDetail->tax_year = $request->taxYear;
            $taxDetail->status = 1;
            $taxDetail->created_by = $authId;
            $taxDetail->created_at = now();
            $taxDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Tax Details Added!','data' => $taxDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewAssetDetailService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'assetId' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $assetDetail = new Asset();
            $assetDetail->citizen_id = $request->citizenId;
            $assetDetail->assets_id = $request->assetId;
            $assetDetail->location = $request->location;
            $assetDetail->status = 1;
            $assetDetail->created_by = $authId;
            $assetDetail->created_at = now();
            $assetDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Asset Details Added!','data' => $assetDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function addNewDocumentsService($request)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
               'citizenId' => 'required|integer',
                'documents' => 'required|array',
                'documents.*.documentType' => 'required|string',
                'documents.*.documentFile' => 'required|file|mimes:jpeg,jpg,png|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            foreach ($request->documents as $key => $value) {
                if($value['documentFile'])
                {
                    $path = '/document';
                    $s3Link = Storage::disk('s3')->put($path, $value['documentFile']);
                }                  
                $fileUpload = new FileUpload();
                $fileUpload->table_id = $request->citizenId;
                $fileUpload->table_name = 'mp_citizen'; 
                $fileUpload->document_type = $value['documentType'];
                $fileUpload->file_name = $s3Link?substr($s3Link, strrpos($s3Link, '/') + 1):'';
                $fileUpload->file_url =$s3Link?substr($s3Link, strrpos($s3Link, '/') + 1):'';
                $fileUpload->created_at = now();
                $fileUpload->save();
            }
           
            return response()->json(['status' => true, 'code' => 201, 'message' => 'Documents Details Added!','data' => $request->citizenId], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function updateHouseSurveyService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            
            $validator = Validator::make($request->all(), [
                'houseNo' => 'required',
                'address' => 'required',
                'incomeLevel' => 'required',
                'houseType' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }

            $houseDetail = HouseSurvey::where('house_status', '=', '1')->find($id);
            $houseDetail->house_number = $request->houseNo;
            $houseDetail->address = $request->address;
            $houseDetail->house_type= $request->houseType;
            $houseDetail->rent_type = $request->rentType;
            $houseDetail->business_type = $request->businessType;
            $houseDetail->house_status =  $request->houseStatus;
            $houseDetail->house_length = $request->houseLength;
            $houseDetail->house_breadth = $request->houseBreadth;
            $houseDetail->house_height = $request->houseHeight;
            $houseDetail->no_of_floors = $request->noOfFloors;
            $houseDetail->created_by = $authId;
            $houseDetail->created_at = now();
            $houseDetail->save();

            if(!empty($request->houseImage))
            {
                foreach ($request->houseImage as $key => $image) {
                    if($image)
                    {
                        $path = '/house';
                        $s3Link = Storage::disk('s3')->put($path, $image);
                    }                  
                    $fileUpload = new FileUpload();
                    $fileUpload->table_id = $houseDetail->house_id;
                    $fileUpload->table_name = 'mp_house';
                    $fileUpload->document_type = 'houseImage';
                    $fileUpload->file_name = $s3Link?substr($s3Link, strrpos($s3Link, '/') + 1):'';
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }
            }
            if($request->rentReceipt)
                {
                    $path =  '/rent';
                    $s3Link = Storage::disk('s3')->put($path, $request->rentReceipt);
                    $fileUpload = new FileUpload();
                    $fileUpload->table_id = $houseDetail->house_id;
                    $fileUpload->table_name = 'mp_house';
                    $fileUpload->document_type = 'rentReceipt';
                    $fileUpload->file_name = $request->rentReceipt->getClientOriginalName();;
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }       
             if($request->rentAgreement)
                {
                    $path =  '/rent';
                    $s3Link = Storage::disk('s3')->put($path, $request->rentAgreement);
                    $fileUpload = new FileUpload();
                    $fileUpload->table_id = $houseDetail->house_id;
                    $fileUpload->table_name = 'mp_house';
                    $fileUpload->document_type = 'rentAgreement';
                    $fileUpload->file_name = $request->rentAgreement->getClientOriginalName();;
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }
             if($request->policeVerification)
                {
                        $path = '/rent';
                        $s3Link = Storage::disk('s3')->put($path, $request->rentAgreement);
                        $fileUpload = new FileUpload();
                        $fileUpload->table_id = $houseDetail->house_id;
                        $fileUpload->table_name = 'mp_house';
                        $fileUpload->document_type = 'policeVerification';
                        $fileUpload->file_name = $request->rentAgreement->getClientOriginalName();;
                        $fileUpload->file_url = $s3Link;
                        $fileUpload->created_at = now();
                        $fileUpload->save();
                }
           if(!empty($request->tax))
           {
                if(count($request->tax))
                {
                    foreach ($request->tax as $key => $value) {
                        $id = (int)$value['taxId'];
                        if($id)
                        {
                            $taxDetail = Tax::find($id);
                        }
                        else{
                            $taxDetail = new Tax();
                        }     
                        $taxDetail->house_id = $houseDetail->house_id;
                        $taxDetail->type = $value['taxType'];
                        $taxDetail->amount = $value['taxAmount'];
                        $taxDetail->payment_date = $value['paymentDate'];
                        $taxDetail->receipt_number = $value['receiptNumber'];
                        $taxDetail->tax_year = $value['taxYear'];
                        $taxDetail->status = 1;
                        $taxDetail->created_by = $authId;
                        $taxDetail->created_at = now();
                        $taxDetail->save();
                    }
                }
            }

            return response()->json(['status' => true, 'code' => 201, 'message' => 'House Details Updated!','data' => $houseDetail->house_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function updateCitizenService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            if($request->citizenImage)
            {
                $path = '/citizen';
                $s3Link = Storage::disk('s3')->put($path, $request->citizenImage);
            }
            else{
                $s3Link = $request->citizenImageUrl;
            }

            $validator = Validator::make($request->all(), [
                'houseId' => 'required',
                'name' => 'required',
                'gender' => 'required',
                'dob' => 'required',
                'address' => 'required',
                'contact' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }

            $citizenDetail = Citizen::where('status', '=', 1)->find($id);
            $citizenDetail->name = $request->name;
            $citizenDetail->gender = $request->gender;
            $citizenDetail->date_of_birth = $request->dob;
            $citizenDetail->aadhar_number = $request->aadharNo;
            $citizenDetail->pan_card_number = $request->panCard;
            $citizenDetail->driving_license = $request->drivingLicense;
            $citizenDetail->address = $request->address;
            $citizenDetail->mobile_number = $request->contact;
            $citizenDetail->alternate_phone = $request->altContact;
            $citizenDetail->email_id = $request->email;
            $citizenDetail->citizen_password = $request->password;
            $citizenDetail->is_head_of_family = $request->isHof;
            $citizenDetail->relation_with_hof = $request->relationWithHof;
            $citizenDetail->annual_income = $request->annualIncome;
            $citizenDetail->electricity_bill_no = $request->electricityBillNo?$request->electricityBillNo:0;
            $citizenDetail->water_bill_no = $request->waterBillNo? $request->waterBillNo:0;
            $citizenDetail->passport_no = $request->passportNo;
            $citizenDetail->profile_photo =  $s3Link?substr($s3Link, strrpos($s3Link, '/') + 1):'';;
            $citizenDetail->created_by = $authId;
            $citizenDetail->created_at = now();
            $citizenDetail->save();

            $houseDetails = HouseSurvey::where('house_status', '=', 1)->find($request->houseId);
            $houseDetails->members_count = $houseDetails->members_count+1;
            if($request->isHof === 1)
            {
                $houseDetails->head_of_family = $citizenDetail->citizen_id;
            }
            $houseDetails->save();

            $houseCitizenConnect = HouseCitizenConnect::where('citizen_id',$id)->where('house_id',$request->houseId)->first();
            if(!$houseCitizenConnect)
            {
                $houseCitizenConnect = new HouseCitizenConnect(); 
            }
                $houseCitizenConnect->house_id = $request->houseId;
                $houseCitizenConnect->citizen_id = $citizenDetail->citizen_id;
                $houseCitizenConnect->created_at = now();
                $houseCitizenConnect->save();
            

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Citizen Details Updated!','data' => $citizenDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function updateQualificationDetailService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
               'citizenId' => 'required',
                'qualificationName' => 'required',
                'institutionName'=>'required',
                'yop'=>'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $qualificationDetail = Qualifications::where('status', '=', 1)->find($id);
            $qualificationDetail->citizen_id = $request->citizenId;
            $qualificationDetail->qualification_name = $request->qualificationName;
            $qualificationDetail->institution_name = $request->institutionName;
            $qualificationDetail->year_of_passing = $request->yop;
            $qualificationDetail->grade = $request->grade;
            $qualificationDetail->degree_type = $request->degreeType;
            $qualificationDetail->created_by = $authId;
            $qualificationDetail->created_at = now();
            $qualificationDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Qualification Details Updated!','data' => $qualificationDetail->qualification_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function updateMedicalDetailService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [                
                'citizenId' => 'required',
                'bloodGroup' => 'required',
                'weight' => 'required',
                'height' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $medicalDetails = Medical::where('status', '=', 1)->find($id);
            $medicalDetails->citizen_id = $request->citizenId;
            $medicalDetails->blood_group = $request->bloodGroup;
            $medicalDetails->weight = $request->weight;
            $medicalDetails->height = $request->height;
            $medicalDetails->health_status = json_encode($request->healthStatus);
            $medicalDetails->other_illness = $request->otherHealthIssues;
            $medicalDetails->created_by = $authId;
            $medicalDetails->created_at = now();
            $medicalDetails->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Medical Details Updated!','data' => $medicalDetails->health_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function updateEmploymentDetailService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'employerName' => 'required',
                'serviceType' => 'required',
                'position' => 'required',
                'salary' => 'required',
                'joiningDate' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $employmentDetail = Employment::where('status', '=', 1)->find($id);
            $employmentDetail->citizen_id = $request->citizenId;
            $employmentDetail->service_type = $request->serviceType;
            $employmentDetail->employer_name = $request->employerName;
            $employmentDetail->position = $request->position;
            $employmentDetail->salary = $request->salary;
            $employmentDetail->joining_date = $request->joiningDate;
            $employmentDetail->created_by = $authId;
            $employmentDetail->created_at = now();
            $employmentDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Employment Details Updated!','data' => $employmentDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function updateSchemesDetailService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
               'citizenId' => 'required',
                'schemeId' => 'required',
                'enrollmentDate' => 'required',
                'status' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $schemeDetail = Scheme::where('status', '=', "Active")->find($id);
            $schemeDetail->citizen_id = $request->citizenId;
            $schemeDetail->scheme_id = $request->schemeId;
            $schemeDetail->enrollment_date = $request->enrollmentDate;
            $schemeDetail->status = $request->status;
            $schemeDetail->created_by = $authId;
            $schemeDetail->created_at = now();
            $schemeDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Scheme Details Updated!','data' => $schemeDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function updateTaxDetailService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'type' => 'required',
                'paymentDate' => 'required',
                'taxYear' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $taxDetail = Tax::where('status', '=', 1)->find($id);
            $taxDetail->citizen_id = $request->citizenId;
            $taxDetail->type = $request->type;
            $taxDetail->amount = $request->amount;
            $taxDetail->payment_date = $request->paymentDate;
            $taxDetail->receipt_number = $request->receiptNumber;
            $taxDetail->tax_year = $request->taxYear;
            $taxDetail->created_by = $authId;
            $taxDetail->created_at = now();
            $taxDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Tax Details Updated!','data' => $taxDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function updateAssetDetailService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'citizenId' => 'required',
                'assetId' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            $assetDetail = Asset::where('status', '=', 1)->find($id);
            $assetDetail->citizen_id = $request->citizenId;
            $assetDetail->assets_id = $request->assetId;
            $assetDetail->location = $request->location;
            $assetDetail->created_by = $authId;
            $assetDetail->created_at = now();
            $assetDetail->save();

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Asset Details Updated!','data' => $assetDetail], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function deleteHouseSurveyService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $houseDetail = HouseSurvey::find($id);
             Tax::where('mp_taxes.house_id','=',$id)->delete();
             $houseCitizenConnect = HouseCitizenConnect::where('house_id',$id)->select('citizen_id as cid')->get(); 
             foreach ($houseCitizenConnect as $key => $value) {
                $houseCitizenConnectCheck = HouseCitizenConnect::where('citizen_id',$value->cid)->select('house_id as hid')->get(); 
                if(count($houseCitizenConnectCheck) === 1)
                {
                    $keyToDelete = $value->cid;
                    $value->qualificationDetails = Qualifications::where('mp_qualifications.citizen_id','=',$keyToDelete)->delete();
                    $value->medicalDetails = Medical::where('mp_health_data.citizen_id','=',$keyToDelete)->delete();
                    $value->employmentDetails = Employment::where('mp_employment.citizen_id','=',$keyToDelete)->delete();
                    $value->schemeDetails = Scheme::where('mp_citizen_scheme.citizen_id','=',$keyToDelete)->delete();
                    $value->taxDetails = Tax::where('mp_taxes.citizen_id','=',$keyToDelete)->delete();
                    $value->assetDetails = Asset::where('mp_citizen_assets.citizen_id','=',$keyToDelete)->delete();
                    Citizen::where('citizen_id','=',$keyToDelete)->delete();
                }
                HouseCitizenConnect::where('citizen_id',$value->cid)->where('house_id',$id)->delete();
             }
             $houseDetail->delete();
            return response()->json(['status' => true, 'code' => 200, 'message' => 'House Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function deleteCitizenService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            $houseCitizenConnect = HouseCitizenConnect::where('citizen_id',$id)->select('house_id as hid')->get(); 
               if(count($houseCitizenConnect) === 1)
               {
                   $keyToDelete = $id;
                   Qualifications::where('mp_qualifications.citizen_id','=',$keyToDelete)->delete();
                    Medical::where('mp_health_data.citizen_id','=',$keyToDelete)->delete();
                   Employment::where('mp_employment.citizen_id','=',$keyToDelete)->delete();
                   Scheme::where('mp_citizen_scheme.citizen_id','=',$keyToDelete)->delete();
                   Tax::where('mp_taxes.citizen_id','=',$keyToDelete)->delete();
                   Asset::where('mp_citizen_assets.citizen_id','=',$keyToDelete)->delete();
                   Citizen::where('citizen_id','=',$keyToDelete)->delete();
               }
               foreach ($houseCitizenConnect as $key => $value) {
                HouseCitizenConnect::where('citizen_id',$id)->where('house_id',$value->hid)->delete();
               }
               
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Citizen Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function deleteQualificationService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;           
            Qualifications::find($id)->delete();                    
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Qualification Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function deleteHealthDataService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            Medical::find($id)->delete();
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Health Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function deleteEmploymentService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            Employment::find($id)->delete();
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Employment Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function deleteSchemeService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            
                   Scheme::find($id)->delete();
                   
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Scheme Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function deleteTaxService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;
            
                   Tax::find($id)->delete();
                   
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Tax Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function deleteAssetService($request, $id)
    {
        try {           
            $authId = Auth::user()->user_id;            
            Asset::find($id)->delete();                  
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Asset Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function deleteHouseImageService($request)
    {
        try {           
            $authId = Auth::user()->user_id;     
            $url = basename($request->imageUrl);       
            FileUpload::where('table_name','mp_house')->where('table_id',$request->houseId)->where('file_name',$url)->delete();                  
            return response()->json(['status' => true, 'code' => 200, 'message' => 'House Image Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
    public function deleteCitizenDocumentService($request,$id)
    {
        try {           
            $authId = Auth::user()->user_id;     
            FileUpload::find($id)->delete();                  
            return response()->json(['status' => true, 'code' => 200, 'message' => 'Document Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }
}
