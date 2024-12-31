<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Services\UserService;


class UserController extends BaseController
{
    protected $userServiceVar;

    public function __construct(UserService $userService)
    {
        $this->userServiceVar = $userService;
    }

    public function getUserListApi(Request $request)
    {
        $dataResponse = $this->userServiceVar->getUserListService($request);
        return response()->json($dataResponse);
    }

    public function getUserDetailsByIdApi(Request $request, $id)
    {
        $dataResponse = $this->userServiceVar->getUserDetailService($request, $id);
        return response()->json($dataResponse);
    }

    public function addUserApi(Request $request)
    {
        $dataResponse = $this->userServiceVar->addUserService($request);
        return $dataResponse;
    }
    public function updateUserApi(Request $request, $id)
    {
        $dataResponse = $this->userServiceVar->updateUserService($request, $id);
        return $dataResponse;
    }
    public function deleteUserApi(Request $request, $id)
    {
        $dataResponse = $this->userServiceVar->deleteUserService($request, $id);
        return $dataResponse;
    }

    public function getUserDetailsByContactApi(Request $request)
    {
        $dataResponse = $this->userServiceVar->getUserDetailByContactService($request);
        return response()->json($dataResponse);
    }
}
