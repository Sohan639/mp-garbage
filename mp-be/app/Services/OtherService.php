<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\Asset;
use App\Models\Employment;
use App\Models\HouseSurvey;
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

class OtherService
{
    public function getAadharListService()
    {        
        try {
            $aadharList =Citizen::whereNotNull('aadhar_number')->where('status',1)->pluck('aadhar_number')->toArray();
            return ['status' => true, 'code' => 200, 'data' => $aadharList];
        } catch (QueryException $t) {
            Log::error('DB Error in getRelationshipOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getRelationshipOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }
}