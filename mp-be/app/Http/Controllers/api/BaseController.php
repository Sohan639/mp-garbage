<?php


namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;


class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($message='', $result='', $code=200,$id='')
    {
        $response = [
            'code'    => $code,
            'message' => $message,
        ];
        if ($id) {
            $response['id']=$id;
        }

        if(!empty($result)){
            $response = [
                'code'    => $code,
                'data' => $result,
            ];
            if ($message) {
                $response['message']=$message;
            }
        }

        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error='',$code = 404)
    {
    	$response = [
            'code'    => $code,
            'error' => $error,
        ];
        return response()->json($response, $code);
    }
}