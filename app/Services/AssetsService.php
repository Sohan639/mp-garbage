<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\Asset;
use App\Models\AssetMaster;
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

class AssetsService
{
    public function getAssetsOptionListService($request)
    {        
        try {
            $assetsOption = AssetMaster::where('status',  1)
            ->select( 
                'asset_id as assetId',                   
                'name as assetName',
                'type as assetType'
            )
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $assetsOption];
        } catch (QueryException $t) {
            Log::error('DB Error in getAssetsOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getAssetsOptionList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }
}