<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'web'], function () {
    Route::auth();
    
    Route::get( '/', 'HomeController@index' );

	Route::get( '/home', 'HomeController@index' );
});


Route::group(['middleware' => 'web'], function () {
	Route::get( 'company/setting', 'CompanyController@general_setting' );
	Route::post( 'company/save_setting', 'CompanyController@save_setting' );
	Route::resource( 'company', 'CompanyController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'jobtitle/latest_id', 'JobTitleController@latest_id' );
	
	Route::resource( 'jobtitle', 'JobTitleController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'poli/latest_id', 'PoliController@latest_id' );

	Route::resource( 'poli', 'PoliController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'factory/latest_id', 'FactoryController@latest_id' );

	Route::resource( 'factory', 'FactoryController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'diagnosis/latest_id', 'DiagnosisController@latest_id' );
	Route::post( 'diagnosis/search', 'DiagnosisController@search' );

	Route::resource( 'diagnosis', 'DiagnosisController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'medicine-group/latest_id', 'MedicineGroupController@latest_id' );

	Route::resource( 'medicine-group', 'MedicineGroupController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'department/latest_id', 'DepartmentController@latest_id' );

	Route::resource( 'department', 'DepartmentController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::resource( 'client', 'ClientController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'user/latest_id', 'UserController@latest_id' );
	Route::get( 'user/user-registration', 'UserController@user_registration' );
	Route::get( 'user/profile', 'UserController@profile' );
	Route::post( 'user/update_profile', 'UserController@update_profile' );
	Route::get( 'logout', 'UserController@logout' );

	Route::resource( 'user', 'UserController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'participant/latest_id', 'ParticipantController@latest_id' );
	Route::get( 'participant/latest_medrec', 'ParticipantController@latest_medrec' );
	Route::get( 'participant/import', 'ParticipantController@import' );
	Route::get( 'participant/anc', 'ParticipantController@anc' );
	Route::post( 'participant/action-import', 'ParticipantController@action_import' );
	Route::post( 'participant/do-import', 'ParticipantController@do_import' );

	Route::resource( 'participant', 'ParticipantController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'medicine/latest_id', 'MedicineController@latest_id' );
	Route::post( 'medicine/search_med_by_name', 'MedicineController@search_med_by_name' );
	Route::post( 'medicine/search_med_by_code', 'MedicineController@search_med_by_code' );
	Route::post( 'medicine/search_med_by_code_or_name', 'MedicineController@search_med_by_code_or_name' );

	Route::resource( 'medicine', 'MedicineController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'poliregistration/latest_id', 'PoliRegistrationController@latest_id' );
	Route::get( 'poliregistration/ordering_no', 'PoliRegistrationController@ordering_no' );
	Route::get( 'poliregistration/emergency', 'PoliRegistrationController@emergency' );
	Route::post( 'poliregistration/search_id_card', 'PoliRegistrationController@search_id_card' );
	Route::post( 'poliregistration/search_medrec', 'PoliRegistrationController@search_medrec' );

	Route::resource( 'poliregistration', 'PoliRegistrationController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'medical-record/latest_id', 'MedicalRecordController@latest_id' );
	Route::resource( 'medical-record', 'MedicalRecordController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::resource( 'observation', 'ObservationController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::resource( 'doctor-check', 'DoctorCheckController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::resource( 'medicine-allergic', 'MedicineAllergicController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'action-observation/latest_id', 'ObservationActionController@latest_id' );
	Route::resource( 'action-observation', 'ObservationActionController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::resource( 'sick-letter', 'SickLetterController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::resource( 'reference-letter', 'ReferenceLetterController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::resource( 'day-off-letter', 'DayOffLetterController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'ambulance/latest_id', 'AmbulanceController@latest_id' );

	Route::resource( 'ambulance', 'AmbulanceController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::post( 'doctor-recipe/search_doctor_recipe', 'DoctorRecipeController@search_doctor_recipe' );

	Route::resource( 'doctor-recipe', 'DoctorRecipeController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'medicine-in/latest_id', 'MedicineInController@latest_id' );
		
	Route::resource( 'medicine-in', 'MedicineInController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'medicine-out/latest_id', 'MedicineOutController@latest_id' );
		
	Route::resource( 'medicine-out', 'MedicineOutController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'staff/latest_id', 'StaffController@latest_id' );
	Route::post( 'staff/search_staff', 'StaffController@search_staff' );
		
	Route::resource( 'staff', 'StaffController' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'report/staff', 'ReportController@staff' );
	Route::get( 'report/participant', 'ReportController@participant' );
	Route::get( 'report/organization', 'ReportController@organization' );
	Route::get( 'report/visit', 'ReportController@visit' );
	Route::get( 'report/recap', 'ReportController@recap' );
	Route::get( 'report/medrec', 'ReportController@medrec' );
	Route::get( 'report/medrec2', 'Medrec2Controller@index' );
	Route::get( 'report/accident', 'ReportController@accident' );
	Route::get( 'report/anc', 'ReportController@anc' );
	Route::get( 'report/observation', 'ReportController@observation' );
	Route::get( 'report/letter', 'ReportController@letter' );
	Route::get( 'report/registration', 'ReportController@registration' );
	Route::get( 'report/doctorcheck', 'ReportController@doctorcheck' );
	Route::get( 'report/ambulance', 'ReportController@ambulancereport' );
	Route::get( 'report/doctorrecipe', 'ReportController@doctorrecipe' );
	Route::get( 'report/medicinein', 'ReportController@medicinein' );
	Route::get( 'report/medicineout', 'ReportController@medicineout' );
	Route::get( 'report/medicinestock', 'ReportController@medicinestock' );

	Route::get( 'report/top10disease', 'ReportController@top10disease' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'export/medicinein', 'ExportController@medicinein' );
	Route::get( 'export/medicineout', 'ExportController@medicineout' );
	Route::get( 'export/medicinestock', 'ExportController@medicinestock' );
	Route::get( 'export/doctorrecipe', 'ExportController@doctorrecipe' );
	Route::get( 'export/ambulance', 'ExportController@ambulance' );
	Route::get( 'export/doctorcheck', 'ExportController@doctorcheck' );
	Route::get( 'export/poliregistration', 'ExportController@poliregistration' );
	Route::get( 'export/letter', 'ExportController@letter' );
	Route::get( 'export/medrec', 'ExportController@medrec' );
	Route::get( 'export/accident', 'ExportController@accident' );
	Route::get( 'export/anc', 'ExportController@anc' );
	Route::get( 'export/observation', 'ExportController@observation' );
	Route::get( 'export/visit', 'ExportController@visit' );

	Route::get( 'export/participant/sex', 'ExportController@participant_sex' );
	Route::get( 'export/participant/pregnant', 'ExportController@participant_pregnant' );
	Route::get( 'export/participant/tb', 'ExportController@participant_tb' );
	Route::get( 'export/participant/factory', 'ExportController@participant_factory' );
	Route::get( 'export/participant/department', 'ExportController@participant_department' );
	Route::get( 'export/participant/client', 'ExportController@participant_client' );
	Route::get( 'export/participant/status', 'ExportController@participant_status' );
	Route::get( 'export/participant/data', 'ExportController@participant_data' );

	Route::get( 'export/staff/status', 'ExportController@staff_status' );
	Route::get( 'export/staff/jobtitle', 'ExportController@staff_jobtitle' );
	Route::get( 'export/staff/sex', 'ExportController@staff_sex' );
	Route::get( 'export/staff/user', 'ExportController@staff_user' );
});

Route::group(['middleware' => 'web'], function () {
	Route::get( 'print/medicinein', 'PrintController@medicinein' );
	Route::get( 'print/medicineout', 'PrintController@medicineout' );
	Route::get( 'print/medicinestock', 'PrintController@medicinestock' );
	Route::get( 'print/doctorrecipe', 'PrintController@doctorrecipe' );
	Route::get( 'print/ambulance', 'PrintController@ambulance' );
	Route::get( 'print/doctorcheck', 'PrintController@doctorcheck' );
	Route::get( 'print/poliregistration', 'PrintController@poliregistration' );
	Route::get( 'print/referenceletter', 'PrintController@referenceletter' );
	Route::get( 'print/sickletter', 'PrintController@sickletter' );
	Route::get( 'print/dayoffletter', 'PrintController@dayoffletter' );
	Route::get( 'print/letter', 'PrintController@letter' );
	Route::get( 'print/medrec', 'PrintController@medrec' );
	Route::get( 'print/accident', 'PrintController@accident' );
	Route::get( 'print/anc', 'PrintController@anc' );
	Route::get( 'print/observation', 'PrintController@observation' );
	Route::get( 'print/medrec_detail', 'PrintController@medrec_detail' );
	Route::get( 'print/visit', 'PrintController@visit' );
	Route::get( 'print/recap', 'PrintController@recap' );
	Route::get( 'print/top10disease', 'PrintController@top10disease' );

	Route::get( 'print/participant/sex', 'PrintController@participant_sex' );
	Route::get( 'print/participant/pregnant', 'PrintController@participant_pregnant' );
	Route::get( 'print/participant/tb', 'PrintController@participant_tb' );
	Route::get( 'print/participant/factory', 'PrintController@participant_factory' );
	Route::get( 'print/participant/department', 'PrintController@participant_department' );
	Route::get( 'print/participant/client', 'PrintController@participant_client' );
	Route::get( 'print/participant/status', 'PrintController@participant_status' );
	Route::get( 'print/participant/data', 'PrintController@participant_data' );

	Route::get( 'print/staff/status', 'PrintController@staff_status' );
	Route::get( 'print/staff/jobtitle', 'PrintController@staff_jobtitle' );
	Route::get( 'print/staff/sex', 'PrintController@staff_sex' );
	Route::get( 'print/staff/user', 'PrintController@staff_user' );

	Route::get( 'print/organization/client', 'PrintController@organization_client' );
	Route::get( 'print/organization/factory', 'PrintController@organization_factory' );
	Route::get( 'print/organization/department', 'PrintController@organization_department' );
});

Route::group(['middleware' => 'web'], function () {
	//Route::get( 'medicine-out/latest_id', 'MedicineOutController@latest_id' );
		
	Route::resource( 'legality', 'LegalityController' );
	Route::resource( 'legality-detail', 'LegalityDetailController' );
});
