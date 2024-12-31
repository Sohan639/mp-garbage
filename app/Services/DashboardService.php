<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\Asset;
use App\Models\Employment;
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

class DashboardService
{
    public function getMaleVFemaleResidentListService($request)
    {
        
        try {
            $maleVFemale = Citizen::where('status',  1)
            ->groupBy('mp_citizen.gender')
            ->select(                    
                'gender',
                DB::raw('COUNT(*) AS count')
            )
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $maleVFemale];
        } catch (QueryException $t) {
            Log::error('DB Error in getMaleVFemaleResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getMaleVFemaleResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getAgeGroupResidentListService($request)
    {
        
        try {
            $ageGroup = Citizen::where('status', 1)
            ->select(
                DB::raw("CASE
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 18 THEN '0-18'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 19 AND 35 THEN '19-35'
                    WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 60 THEN '36-60'
                    ELSE '60+'  
                END AS ageGroup"),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw("CASE
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 18 THEN '0-18'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 19 AND 35 THEN '19-35'
                WHEN TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 36 AND 60 THEN '36-60'
                ELSE '60+'  
            END"))
            ->orderBy('ageGroup', 'asc') // Optional: Orders the results by the age group
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $ageGroup];
        } catch (QueryException $t) {
            Log::error('DB Error in getAgeGroupResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getAgeGroupResidentList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getQualificationResidentCountService($request){
        try {
            $qualificationCount = Qualifications::join('mp_citizen as mpc', 'mpc.citizen_id', '=', 'mp_qualifications.citizen_id')
            ->where('mp_qualifications.status', 1)
            ->where('mpc.status',1)
            ->select(
                'mp_qualifications.qualification_name as qualification',
                DB::raw('SUM(CASE WHEN mpc.gender = "Male" THEN 1 ELSE 0 END) as maleCount'),
                DB::raw('SUM(CASE WHEN mpc.gender = "Female" THEN 1 ELSE 0 END) as femaleCount')
            )
            ->groupBy('mp_qualifications.qualification_name')
            ->get();

            return ['status' => true, 'code' => 200, 'data' => $qualificationCount];
        } catch (QueryException $t) {
            Log::error('DB Error in getQualificationResidentCount method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getQualificationResidentCount method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }
}