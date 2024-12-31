<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Services\DashboardService;


class DashboardController extends BaseController
{
    protected $dashboardServiceVar;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardServiceVar = $dashboardService;
    }

    public function getMaleVFemaleListApi(Request $request)
    {
        $dataResponse = $this->dashboardServiceVar->getMaleVFemaleResidentListService($request);
        return response()->json($dataResponse);
    }

    public function getAgeGroupResidentApi(Request $request)
    {
        $dataResponse = $this->dashboardServiceVar->getAgeGroupResidentListService($request);
        return response()->json($dataResponse);
    }

    public function getQualificationResidentCountApi(Request $request)
    {
        $dataResponse = $this->dashboardServiceVar->getQualificationResidentCountService($request);
        return response()->json($dataResponse);
    }

}
