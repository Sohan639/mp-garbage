<?php

namespace App\Http\Controllers\api;

use App\Services\ReportsService;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;


class ReportsController extends BaseController
{
    protected $reportsServiceVar;

    public function __construct(ReportsService $reportsService)
    {
        $this->reportsServiceVar = $reportsService;
    }

    public function getRentTaxVerificationReportApi(Request $request)
    {
        $dataResponse = $this->reportsServiceVar->getRentTaxVerificationReportService($request);
        return response()->json($dataResponse);
    }

    public function getDailyCollectionStatusReportApi(Request $request)
    {
        $dataResponse = $this->reportsServiceVar->getDailyCollectionStatusReportService($request);
        return response()->json($dataResponse);
    }

}
