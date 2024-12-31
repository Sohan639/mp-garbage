<?php

namespace App\Http\Controllers\api;

use App\Services\RelationshipService;
use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;


class RelationshipController extends BaseController
{
    protected $relationshipServiceVar;

    public function __construct(RelationshipService $relationshipService)
    {
        $this->relationshipServiceVar = $relationshipService;
    }

    public function getRelationshipOptionListApi(Request $request)
    {
        $dataResponse = $this->relationshipServiceVar->getRelationshipOptionListService($request);
        return response()->json($dataResponse);
    }

}
