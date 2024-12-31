<?php

namespace App\Http\Controllers\api;

use App\Services\OtherService;
use App\Services\RelationshipService;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;


class OtherController extends BaseController
{
    protected $otherServiceVar;

    public function __construct(OtherService $otherService)
    {
        $this->otherServiceVar = $otherService;
    }

    public function getAadharListApi(Request $request)
    {
        $dataResponse = $this->otherServiceVar->getAadharListService();
        return response()->json($dataResponse);
    }

}
