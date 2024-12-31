<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\BaseController as BaseController;
use App\Services\HouseSurveyService;


class HouseSurveyController extends BaseController
{
    protected $houseSurveyServiceVar;

    public function __construct(HouseSurveyService $houseSurveyService)
    {
        $this->houseSurveyServiceVar = $houseSurveyService;
    }

    public function getHouseSurveyListApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->getHouseSurveyListService($request);
        return response()->json($dataResponse);
    }

    public function getHouseNumberListApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->getHouseNumberListService($request);
        return response()->json($dataResponse);
    }

    public function getResidentDetailsByHNoApi(Request $request, $id){
        $dataResponse = $this->houseSurveyServiceVar->getResidentDetailsByHouseNumber($id);
        return $dataResponse;
    }

    public function viewResidentDetailsByHNoApi(Request $request, $id){
        $dataResponse = $this->houseSurveyServiceVar->viewResidentDetailsByHouseNumber($id);
        return $dataResponse;
    }


    public function viewHouseListByCitizenIdApi(Request $request, $id){
        $dataResponse = $this->houseSurveyServiceVar->viewHouseListByCitizenIdService($id);
        return $dataResponse;
    }


    public function addHouseDetailsApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addHouseSurveyService($request);
        return $dataResponse;
    }

    public function addNewCitizenApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewCitizenService($request);
        return $dataResponse;
    }

    public function addNewQualificationApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewQualificationDetailService($request);
        return $dataResponse;
    }

    public function addNewMedicalApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewMedicalDetailService($request);
        return $dataResponse;
    }

    public function addNewEmploymentApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewEmploymentDetailService($request);
        return $dataResponse;
    }

    public function addNewSchemeApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewSchemesDetailService($request);
        return $dataResponse;
    }

    public function addNewTaxApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewTaxDetailService($request);
        return $dataResponse;
    }

    public function addNewAssetApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewAssetDetailService($request);
        return $dataResponse;
    }
    public function addNewDocumentsApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->addNewDocumentsService($request);
        return $dataResponse;
    }
// update
    public function updateHouseDetailApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateHouseSurveyService($request,$id);
        return $dataResponse;
    }

    public function updateCitizenApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateCitizenService($request, $id);
        return $dataResponse;
    }

    public function updateQualificationApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateQualificationDetailService($request, $id);
        return $dataResponse;
    }

    public function updateMedicalApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateMedicalDetailService($request, $id);
        return $dataResponse;
    }

    public function updateEmploymentApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateEmploymentDetailService($request, $id);
        return $dataResponse;
    }

    public function updateSchemeApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateSchemesDetailService($request, $id);
        return $dataResponse;
    }

    public function updateTaxApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateTaxDetailService($request, $id);
        return $dataResponse;
    }

    public function updateAssetApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->updateAssetDetailService($request, $id);
        return $dataResponse;
    }

    public function deleteHouseSurveyApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteHouseSurveyService($request, $id);
        return $dataResponse;
    }    

    public function deleteCitizenApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteCitizenService($request, $id);
        return $dataResponse;
    }  
    public function deleteQualificationApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteQualificationService($request, $id);
        return $dataResponse;
    }  
    public function deleteMedicalApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteHealthDataService($request, $id);
        return $dataResponse;
    }  
    public function deleteEmploymentApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteEmploymentService($request, $id);
        return $dataResponse;
    }  
    public function deleteSchemeApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteSchemeService($request, $id);
        return $dataResponse;
    }  
    public function deleteTaxApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteTaxService($request, $id);
        return $dataResponse;
    }  
    public function deleteAssetApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteAssetService($request, $id);
        return $dataResponse;
    }  

    public function deleteHouseImageApi(Request $request)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteHouseImageService($request);
        return $dataResponse;
    }  
    public function deleteCitizenDocumentApi(Request $request, $id)
    {
        $dataResponse = $this->houseSurveyServiceVar->deleteCitizenDocumentService($request, $id);
        return $dataResponse;
    }  
}
