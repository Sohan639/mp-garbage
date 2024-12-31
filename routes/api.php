<?php
use App\Http\Controllers\api\AssetsController;
use App\Http\Controllers\api\DashboardController;
use App\Http\Controllers\api\HealthStatusController;
use App\Http\Controllers\api\OtherController;
use App\Http\Controllers\api\RelationshipController;
use App\Http\Controllers\api\ReportsController;
use App\Http\Controllers\api\SchemesController;
use App\Http\Controllers\api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\HouseSurveyController;
use App\Http\Controllers\api\ResidentController;
use App\Http\Controllers\api\GarbageController;


Route::post('login', [AuthController::class, 'loginApi']);   

Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logoutApi']);      
    Route::put('resetUserPassword', [AuthController::class, 'resetUserPasswordApi']);      
    Route::get('getLoginLogsList', [AuthController::class, 'getLoginLogsListApi']);      

    // Dashboard API
    Route::get('getMaleVFemaleList', [DashboardController::class, 'getMaleVFemaleListApi']);    
    Route::get('getAgeGroupResident', [DashboardController::class, 'getAgeGroupResidentApi']);    
    Route::get('getQualificationResidentCount', [DashboardController::class, 'getQualificationResidentCountApi']);    
    
    // House Survey API
    Route::get('getHouseSurveyList', [HouseSurveyController::class, 'getHouseSurveyListApi']);  
    Route::get('getHouseNumberList', [HouseSurveyController::class, 'getHouseNumberListApi']);  
    Route::post('addHouseDetails', [HouseSurveyController::class, 'addHouseDetailsApi']);   
    Route::post('addNewCitizen', [HouseSurveyController::class, 'addNewCitizenApi']);   
    Route::post('addNewQualification', [HouseSurveyController::class, 'addNewQualificationApi']);   
    Route::post('addNewMedical', [HouseSurveyController::class, 'addNewMedicalApi']);   
    Route::post('addNewEmployment', [HouseSurveyController::class, 'addNewEmploymentApi']);   
    Route::post('addNewCitizenScheme', [HouseSurveyController::class, 'addNewSchemeApi']);   
    Route::post('addNewCitizenTax', [HouseSurveyController::class, 'addNewTaxApi']);   
    Route::post('addNewCitizenAsset', [HouseSurveyController::class, 'addNewAssetApi']);   
    Route::post('addNewDocuments', [HouseSurveyController::class, 'addNewDocumentsApi']);   
    Route::post('updateHouseDetail/{id}', [HouseSurveyController::class, 'updateHouseDetailApi']);   
    Route::post('updateCitizen/{id}', [HouseSurveyController::class, 'updateCitizenApi']);   
    Route::put('updateQualification/{id}', [HouseSurveyController::class, 'updateQualificationApi']);   
    Route::put('updateMedical/{id}', [HouseSurveyController::class, 'updateMedicalApi']);   
    Route::put('updateEmployment/{id}', [HouseSurveyController::class, 'updateEmploymentApi']);   
    Route::put('updateCitizenScheme/{id}', [HouseSurveyController::class, 'updateSchemeApi']);   
    Route::put('updateCitizenTax/{id}', [HouseSurveyController::class, 'updateTaxApi']);   
    Route::put('updateCitizenAsset/{id}', [HouseSurveyController::class, 'updateAssetApi']);   
    Route::get('getResidentDetailsByHNo/{id}', [HouseSurveyController::class, 'getResidentDetailsByHNoApi']);     
    Route::get('viewResidentDetailsByHNo/{id}', [HouseSurveyController::class, 'viewResidentDetailsByHNoApi']);     
    Route::get('viewHouseListByCitizenId/{id}', [HouseSurveyController::class, 'viewHouseListByCitizenIdApi']);   
    Route::delete('deleteHouseSurvey/{id}', [HouseSurveyController::class, 'deleteHouseSurveyApi']);      
    Route::delete('deleteCitizen/{id}', [HouseSurveyController::class, 'deleteCitizenApi']);      
    Route::delete('deleteQualification/{id}', [HouseSurveyController::class, 'deleteQualificationApi']);      
    Route::delete('deleteMedical/{id}', [HouseSurveyController::class, 'deleteMedicalApi']);      
    Route::delete('deleteEmployment/{id}', [HouseSurveyController::class, 'deleteEmploymentApi']);      
    Route::delete('deleteScheme/{id}', [HouseSurveyController::class, 'deleteSchemeApi']);      
    Route::delete('deleteTax/{id}', [HouseSurveyController::class, 'deleteTaxApi']);      
    Route::delete('deleteAsset/{id}', [HouseSurveyController::class, 'deleteAssetApi']);      
    Route::delete('deleteHouseImage', [HouseSurveyController::class, 'deleteHouseImageApi']);      
    Route::delete('deleteCitizenDocument/{id}', [HouseSurveyController::class, 'deleteCitizenDocumentApi']);      
    
    // Assets 
    Route::get('getAssetsOptionList', [AssetsController::class, 'getAssetsOptionListApi']);      
    
    // Schemes
    Route::get('getSchemesOptionList', [SchemesController::class, 'getSchemesOptionListApi']);      

    // Relationship
    Route::get('getRelationshipOptionList', [RelationshipController::class, 'getRelationshipOptionListApi']);      

    // Health Status
    Route::get('getHealthStatusOptionList', [HealthStatusController::class, 'getHealthStatusOptionListApi']);      

    // Resident 
    Route::get('getResidentDetailsForAdmin', [ResidentController::class, 'getResidentDetailsForAdminApi']);   
    Route::get('getResidentOptionList', [ResidentController::class, 'getResidentOptionListApi']);   
    Route::get('getResidentDetailList', [ResidentController::class, 'getResidentDetailListApi']);   

    // Users
    Route::get('getUserList', [UserController::class, 'getUserListApi']);  
    Route::post('addUser', [UserController::class, 'addUserApi']);  
    Route::post('updateUser/{id}', [UserController::class, 'updateUserApi']);  
    Route::delete('deleteUser/{id}', [UserController::class, 'deleteUserApi']);  
    Route::get('getUserDetailsById/{id}', [UserController::class, 'getUserDetailsByIdApi']);  
    Route::post('getUserDetailsByContact', [UserController::class, 'getUserDetailsByContactApi']); 
    
    // Garbage
    Route::post('getQRCodebyHouseNo', [GarbageController::class, 'getQRCodeByHouseNoApi']);
    Route::get('getScannedHouseDetailsById/{id}', [GarbageController::class, 'getScannedHouseDetailsByIdApi']);  
    Route::post('uploadGarbageByHouseId', [GarbageController::class, 'uploadGarbageByHouseIdApi']);
    Route::post('getGarbageCollectionData', [GarbageController::class, 'getGarbageCollectionDataApi']);

    // Others
    Route::get('getAadharList', [OtherController::class, 'getAadharListApi']);  

    // Reports
    Route::post('getRentTaxVerificationReport', [ReportsController::class, 'getRentTaxVerificationReportApi']);
    Route::post('getDailyCollectionStatusReport', [ReportsController::class, 'getDailyCollectionStatusReportApi']);  
});


