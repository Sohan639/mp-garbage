<?php

namespace App\Http\Controllers\api;
use App\Services\AuthService;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\api\BaseController;

class AuthController extends BaseController
{  
    protected $AuthServiceLogin;

    public function __construct(AuthService $AuthService )
    {
        $this->AuthServiceLogin = $AuthService;
    }
    
    public function loginApi(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email' => 'required|string',
        'password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',
            'regex:/[A-Z]/',
            'regex:/[0-9]/',
            'regex:/[@$!%*#?&]/',
            ]
        ]);

        if ($validator->fails()) {
            $errorMessage = implode(', ', $validator->errors()->all());
            return response()->json(['code' => 400, 'error' => $errorMessage], 400);
        }
        
        $loginResult = $this->AuthServiceLogin->login($request);

        if ($loginResult['status'] == 1) {
            return $this->sendResponse('Login Successful.', $loginResult['data'], $loginResult['code']);
        }else{
            return $this->sendError('Please enter valid Email id or Password.', $loginResult['code']);
        }
    }

    public function logoutApi(Request $request)
    {
        $logoutResult = $this->AuthServiceLogin->logout($request);
        
        return $this->sendResponse('Logout Successful.','',200);
    }

    public function resetUserPasswordApi(Request $request)
    {
        $dataResponse = $this->AuthServiceLogin->resetCitizenPasswordService($request);
        return $dataResponse;
    }

    public function getLoginLogsListApi(Request $request)
    {
        $dataResponse = $this->AuthServiceLogin->getLoginLogsListService($request);
        return $dataResponse;
    }
 
}