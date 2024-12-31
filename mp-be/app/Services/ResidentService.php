<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\Asset;
use App\Models\Employment;
use App\Models\HouseSurvey;
use App\Models\Citizen;
use App\Models\Medical;
use App\Models\Qualifications;
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

class ResidentService
{
    public function getResidentListService($request)
    {        
        try {
            $residentList = Citizen::join('mp_house_citizen as hc','hc.citizen_id', 'mp_citizen.citizen_id')
            ->join('mp_house as mph','mph.house_id', 'hc.house_id')
            ->join('mp_users as mpu', 'mpu.user_id','mp_citizen.created_by')
            ->where('mp_citizen.status',  1)
            ->where('mph.house_status', 1)
            ->select(    
                'mp_citizen.citizen_id as citizenId',       
                'mp_citizen.name as citizenName',
                'mph.house_number as houseNo',
                'mph.address as address',
                'mp_citizen.mobile_number as contact',
                'mp_citizen.aadhar_number as aadharNo',
                'mp_citizen.gender as gender',
                'mp_citizen.created_at as createdAt',
                'mpu.name as createdBy'
            )
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $residentList];
        } catch (QueryException $t) {
            Log::error('DB Error in getResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getResidentOptionListService($request)
    {        
        try {
            $residentList = Citizen::where('mp_citizen.status',  1)
            ->select(    
                'citizen_id as citizenId',       
                'name',
                'gender',
                'date_of_birth as dob',
                'mobile_number as contact',
                'aadhar_number as aadharNo',
                'pan_card_number as panCard',
                'driving_license as drivingLicense',
                'address as address',
                'alternate_phone as altContact',
                'email_id as email',
                'annual_income as annualIncome',
                'electricity_bill_no as electricityBill',
                'water_bill_no as waterBill',
                'passport_no as passportNo'
            )
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $residentList];
        } catch (QueryException $t) {
            Log::error('DB Error in getResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getResidentDetailListService($request)
    {        
        try {
            $residentList = Citizen::join('mp_house_citizen as hc', 'hc.citizen_id', '=', 'mp_citizen.citizen_id')
            ->join('mp_house as mph', 'mph.house_id', '=', 'hc.house_id')
            ->join('mp_users as mpu', 'mpu.user_id','mp_citizen.created_by')
            ->where('mp_citizen.status', 1)
            ->select(
                'mp_citizen.citizen_id as citizenId',
                DB::raw("GROUP_CONCAT(mph.house_number SEPARATOR ', ') as houseNo"),
                'mp_citizen.name',
                'gender',
                'date_of_birth as dob',
                'mobile_number as contact',
                'aadhar_number as aadharNo',
                'pan_card_number as panCard',
                'driving_license as drivingLicense',
                'mp_citizen.address as address',
                'alternate_phone as altContact',
                'email_id as email',
                'annual_income as annualIncome',
                'electricity_bill_no as electricityBill',
                'water_bill_no as waterBill',
                'passport_no as passportNo',
                'mp_citizen.created_at as createdAt',
                'mpu.name as createdBy'
            )
            ->groupBy(
                'mp_citizen.citizen_id',
                'mp_citizen.name',
                'gender',
                'date_of_birth',
                'mobile_number',
                'aadhar_number',
                'pan_card_number',
                'driving_license',
                'mp_citizen.address',
                'alternate_phone',
                'email_id',
                'annual_income',
                'electricity_bill_no',
                'water_bill_no',
                'passport_no',
                'mp_citizen.created_at',
                'mpu.name'
            )
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $residentList];
        } catch (QueryException $t) {
            Log::error('DB Error in getResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }
}