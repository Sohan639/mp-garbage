<?php

namespace App\Http\Controllers\api;

use App\Services\HealthStatusService;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;


class HealthStatusController extends BaseController
{    protected $healthStatusServiceVar;

    public function __construct(HealthStatusService $healthstatusService)
    {
        $this->healthStatusServiceVar = $healthstatusService;
    }

    public function getHealthStatusOptionListApi(Request $request)
    {
        $dataResponse = $this->healthStatusServiceVar->getHealthStatusOptionListService($request);
        return response()->json($dataResponse);
    }

}
