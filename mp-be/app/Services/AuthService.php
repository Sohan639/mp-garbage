<?php

namespace App\Services;
https://infipre-01.atlassian.net/jira/software/c/projects/LMS/boards/2
use App\Models\Citizen;
use App\Models\FileUpload;
use App\Models\LoginLogs;
use Log;
use Validator;
use App\Models\User;
use Hash;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPasswordNotification;

class AuthService
{
    public function login(Request $request)
    {
        try {
            // $agent = new Agent();

            // // Correctly parse browser and platform
            // $browser = $request->header('User-Agent');  //$agent->browser();
            // $platform = $agent->platform();
            // $device = $agent->device();
            
            // // Check if values are empty and provide fallbacks
            // if (empty($platform)) {
            //     $platform = 'Unknown Platform';
            // }
            
            // if (empty($device)) {
            //     $device = $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop');
            // } 

            //    // Get IP Address
            //    $ip = $request->ip();

            // $location = ($ip === "127.0.0.1") ? 'Localhost' : getLocationFromIP($ip);

            $user = User::where('email', $request->email)->first();

            if ($user) {
                //officer or admin login
                if (!Hash::check($request->password, $user->password)) {               
                    return response()->json(['message' => 'Invalid credentials'], 401);
                }
                else
                {
                    $profile = FileUpload::where('table_name','mp_users')
                    ->where('table_id',$user->user_id)
                    ->select('file_url as url')
                    ->first();
                    $profilePath = $profile?getFileS3Bucket(getPathS3Bucket() . '/'. $profile->url):"";
                    $token = $user->createToken('MP-BE')->plainTextToken;
                    $loginResponseData = [
                        "token" => $token,
                        "userId" => $user->user_id,
                        "role" => $user['role'],
                        "name" => $user['name'],
                        "email" => $user['email'],
                        "profile" => $profilePath,
                        "status" =>1
                    ];
                }
            } 
        else {
            //citizen login
                $citizen = Citizen::where('mobile_number', $request->email)
                  //  ->where('is_head_of_family', 1)
                    ->first();
                    //echo json_encode($citizen);
                if (!$citizen || $request->password != $citizen->citizen_password) {
                     return response()->json(['message' => 'Invalid credentials'], 401);
                } 
                else 
                {
                    $token = $citizen->createToken('MP-BE-CITIZEN')->plainTextToken;
                    $profile = FileUpload::where('table_name', 'mp_citizen')
                        ->where('table_id', $citizen->citizen_id)
                        ->select('file_url as url')
                        ->first();
                    if ($profile) {
                        $profilePath = getFileS3Bucket(getPathS3Bucket() . '/' . $profile->url);
                    } else {
                        $profilePath = "";
                    }
                    $loginResponseData = [
                        "token" => $token,
                        "userId" => $citizen->citizen_id,
                        "role" => 'citizen',
                        "name" => $citizen['name'],
                        "email" => $citizen['email_id'],
                        "profile" => $profilePath,
                        "status" => 1
                    ];
                }
            //citizen login ends
            }          
           

        // if($loginResponseData)
        // {
        //     $loginLogs = new LoginLogs();
        //     $loginLogs->user_id = $loginResponseData['userId'];
        //     $loginLogs->ip_address = $ip;
        //     $loginLogs->browser = $browser;
        //     $loginLogs->device = $device; 
        //     $loginLogs->platform = $platform;
        //     $loginLogs->location = $location;
        //     $loginLogs->when_logged_in = now();
        //     $loginLogs->created_at = now();
        //     $loginLogs->updated_at = now();
        //     $loginLogs->save();
        // }
            return array("status" => 1, "data" => $loginResponseData, "code" => 200);
        } catch (\Throwable $e) {
            return array('status' => false, 'code' => 501, 'message' =>'User Doesnt Exist', 'data' => $e->getMessage());
        }
    }
  
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['message' => 'Logged out successfully.']);

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function resetCitizenPasswordService($request)
    {        
        try {    
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'userId' => 'required',
                'newPassword'=>'required',
                'confirmPassword'=>'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
                $userDetail = Citizen::where('status',1)->find($request->userId);
                $userDetail->citizen_password = $request->newPassword;
                $userDetail->updated_by = $authId;
                $userDetail->updated_at = now();
                $userDetail->save();      

            return response()->json(['status' => true, 'code' => 201, 'message' => 'Password Reset Successfully!','data' => $userDetail->citizen_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function getLoginLogsListService($request)
    {        
        try {
            $loginLogsList = loginLogs::join('mp_citizen as mpc','mpc.citizen_id','mp_login_logs.user_id')
            ->select(    
                'mpc.citizen_id as userId',       
                'mpc.name as username',
                'mpc.mobile_number as phone',
                'mp_login_logs.ip_address as ip',
                'mp_login_logs.browser as browser',
                'mp_login_logs.device as device',
                'mp_login_logs.platform as platform',
                'mp_login_logs.location as location',
                'mp_login_logs.when_logged_in as lastLogin'                
            )
            ->orderBy('created_at', 'desc')
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $loginLogsList];
        } catch (\Throwable $t) {
            Log::error('Error in getUserDetailByContact method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

}