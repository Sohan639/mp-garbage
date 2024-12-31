<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Services\SchemesService;


class SchemesController extends BaseController
{
    protected $schemesServiceVar;

    public function __construct(SchemesService $schemesService)
    {
        $this->schemesServiceVar = $schemesService;
    }

    public function getSchemesOptionListApi(Request $request)
    {
        $dataResponse = $this->schemesServiceVar->getSchemesOptionListService($request);
        return response()->json($dataResponse);
    }

}
