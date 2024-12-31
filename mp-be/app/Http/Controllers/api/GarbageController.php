<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Services\GarbageService;


class GarbageController extends BaseController
{
    protected $garbageServiceVar;

    public function __construct(GarbageService $garbageService)
    {
        $this->garbageServiceVar = $garbageService;
    }


    public function getQRCodeByHouseNoApi(Request $request)
    {
        // echo "v"; exit;
        $houseNo = $request->input('houseNo');
        $dataResponse = $this->garbageServiceVar->generateQRCode($houseNo);
        return response()->json($dataResponse);
    }

    public function getScannedHouseDetailsByIdApi(Request $request, $id)
    {
        $dataResponse = $this->garbageServiceVar->getScannedHouseDetailService($request, $id);
        return response()->json($dataResponse);
    }

    public function uploadGarbageByHouseIdApi(Request $request)
    {
        // echo "v"; exit;
        // $houseNo = $request->input('houseNo');
        $dataResponse = $this->garbageServiceVar->uploadGarbageByHouseId($request);
        return response()->json($dataResponse);
    }

    public function getGarbageCollectionDataApi(Request $request)
    {
        // echo "v"; exit;
        $date = $request->input('date');
        $dataResponse = $this->garbageServiceVar->getGarbageCollectionData($date);
        return response()->json($dataResponse);
    }


}
