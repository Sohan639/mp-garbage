<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Services\ResidentService;


class ResidentController extends BaseController
{
    protected $residentServiceVar;

    public function __construct(ResidentService $residentService)
    {
        $this->residentServiceVar = $residentService;
    }

    public function getResidentDetailsForAdminApi(Request $request)
    {
        $dataResponse = $this->residentServiceVar->getResidentListService($request);
        return response()->json($dataResponse);
    }

    public function getResidentOptionListApi(Request $request)
    {
        $dataResponse = $this->residentServiceVar->getResidentOptionListService($request);
        return response()->json($dataResponse);
    }

    public function getResidentDetailListApi(Request $request)
    {
        $dataResponse = $this->residentServiceVar->getResidentDetailListService($request);
        return response()->json($dataResponse);
    }

}
