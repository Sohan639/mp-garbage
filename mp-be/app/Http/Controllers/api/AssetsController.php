<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Services\AssetsService;


class AssetsController extends BaseController
{
    protected $assetsServiceVar;

    public function __construct(AssetsService $assetsService)
    {
        $this->assetsServiceVar = $assetsService;
    }

    public function getAssetsOptionListApi(Request $request)
    {
        $dataResponse = $this->assetsServiceVar->getAssetsOptionListService($request);
        return response()->json($dataResponse);
    }
}
