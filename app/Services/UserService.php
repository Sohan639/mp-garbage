<?php declare(strict_types=1); 

namespace App\Services;

use App\Models\Asset;
use App\Models\Citizen;
use App\Models\Employment;
use App\Models\FileUpload;
use App\Models\HouseSurvey;
use App\Models\User;
use App\Models\Medical;
use App\Models\Qualifications;
use App\Models\Scheme;
use App\Models\SchemeMaster;
use App\Models\Tax;
use DB;
use File;
use Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class UserService
{
    public function getUserListService($request)
    {        
        try {
            $userList = User::where('mp_users.user_status',  1)
            ->select(    
                'mp_users.user_id as userId',       
                'mp_users.name',
                'mp_users.email',
                'mp_users.role',
                'mp_users.created_at as createdAt'
            )
            ->get();
            return ['status' => true, 'code' => 200, 'data' => $userList];
        } catch (QueryException $t) {
            Log::error('DB Error in getUserList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getUserList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function getUserDetailService($request, $id)
    {        
        try {
            $userList = User::where('mp_users.user_status',  1)
            ->where('user_id',$id)
            ->select(    
                'mp_users.user_id as userId',       
                'mp_users.name',
                'mp_users.email',
                'mp_users.role',
                'mp_users.user_status as status',
                'mp_users.created_at as createdAt'
            )
            ->first();
            $userImage = FileUpload::where('table_name','mp_users')->where('table_id',$userList->userId)
            ->select('file_url as url')->first();
            if($userImage){
                $userList->profilePhoto =  getFileS3Bucket(getPathS3Bucket().'/'.$userImage->url);
            }
            return ['status' => true, 'code' => 200, 'data' => $userList];
        } catch (QueryException $t) {
            Log::error('DB Error in getUserList method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getUserList method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }

    public function addUserService($request)
    {        
        try {    
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email'=>'required',
                'password'=>'required',
                'confirmPassword'=>'required',
                'role'=>'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
            if($request->password !== $request->confirmPassword)
            {
                return response()->json(['status' => false, 'code' => 400, 'error' => 'Password does not match'], 400);
            }
            else{
                $userDetail = new User();
                $userDetail->name = $request->name;
                $userDetail->email = $request->email;
                $userDetail->role = $request->role;
                $userDetail->password = Hash::make($request->password);
                $userDetail->user_status = 1;
                $userDetail->created_by = $authId;
                $userDetail->created_at = now();
                $userDetail->save();      

                if($request->profile)
                {
                    $path = '/users';
                    $s3Link = Storage::disk('s3')->put($path, $request->profile);
                    $fileUpload = new FileUpload();
                    $fileUpload->table_id = $userDetail->user_id;
                    $fileUpload->table_name = 'mp_users';
                    $fileUpload->document_type = 'userImage';
                    $fileUpload->file_name = substr($s3Link, strrpos($s3Link, '/') + 1);
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }
            }
            return response()->json(['status' => true, 'code' => 201, 'message' => 'User Details Added!','data' => $userDetail->user_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function updateUserService($request, $id)
    {        
        try {    
            $authId = Auth::user()->user_id;
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email'=>'required',
                'role'=>'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'code' => 400, 'errors' => $validator->errors()->all()], 400);
            }
                $userDetail = User::where('user_status',1)->find($id);
                $userDetail->name = $request->name;
                $userDetail->email = $request->email;
                $userDetail->role = $request->role;
                $userDetail->updated_by = $authId;
                $userDetail->updated_at = now();
                $userDetail->save();      

                if($request->profile)
                {
                    $path = '/users';
                    $s3Link = Storage::disk('s3')->put($path, $request->profile);
                    $fileUpload = FileUpload::where('table_name','mp_users')->where('table_id',$id)->first();
                    if(!$fileUpload)
                    {
                        $fileUpload =  new FileUpload();
                    }
                    $fileUpload->table_id = $userDetail->user_id;
                    $fileUpload->table_name = 'mp_users';
                    $fileUpload->document_type = 'userImage';
                    $fileUpload->file_name = substr($s3Link, strrpos($s3Link, '/') + 1);
                    $fileUpload->file_url = $s3Link;
                    $fileUpload->created_at = now();
                    $fileUpload->save();
                }
            return response()->json(['status' => true, 'code' => 201, 'message' => 'User Details Updated!','data' => $userDetail->user_id], 201);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function deleteUserService($request, $id)
    {        
        try {           
            $authId = Auth::user()->user_id;
            $userDetail = User::find($id);
            $userDetail->delete();

            return response()->json(['status' => true, 'code' => 200, 'message' => 'User Record Deleted!'], 200);
        } catch (\Throwable $e) {
            return response()->json(['status' => false, 'code' => 501, 'message' => $e->getMessage()], 501);
        }
    }

    public function getUserDetailByContactService($request)
    {        
        try {
            $userList = Citizen::where('mp_citizen.status',  1)
            ->where('mobile_number',$request->contact)
            ->select(    
                'mp_citizen.citizen_id as userId',       
                'mp_citizen.name as citizenName',
                'mp_citizen.profile_photo as profileImage'
            )
            ->first();

            if($userList->profileImage){
                $userList->profileImage =  getFileS3Bucket( getPathS3Bucket() . '/citizen/' .  $userList->profileImage);
            }
            return ['status' => true, 'code' => 200, 'data' => $userList];
        } catch (QueryException $t) {
            Log::error('DB Error in getUserDetailByContact method: ' . $t->getMessage());
            return ['status' => false, 'code' => $t->getCode(), 'errors' => $t->getMessage()];
        } catch (Throwable $t) {
            Log::error('Error in getUserDetailByContact method: ' . $t->getMessage());
            return ['status' => false, 'code' => 500, 'errors' => $t->getMessage()];
        }
    }
}