<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\HealthStatus;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class HealthStatusService
{
    public function getHealthStatusOptionListService($request)
    {        
        try {
            $healthStatusOption = HealthStatus::select(    
                'health_status_id as id',       
                'health_status as status'
            )
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $healthStatusOption];
        } catch (QueryException $t) {
            Log::error('DB Error in getHealthStatusOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getHealthStatusOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }
}