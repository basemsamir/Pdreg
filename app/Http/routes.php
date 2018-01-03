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

Route::get('/','FrontController@index')->middleware(['auth']);
Route::get('/JavascriptNotFound','FrontController@no_javascript')->middleware('auth');

////////////////////////////
// Patient Module routes /////
////////////////////////////
Route::group(['middleware' => ['auth']], function () {
	Route::get('patients','PatientController@index')->middleware('entrypoint_role');
	Route::post('patients','PatientController@store')->middleware('entrypoint_role');
	Route::get('patients/reserve/{pid}','PatientController@indexTicket')->middleware('receiption_role');
	Route::post('patients/reserve/{pid}','PatientController@storeTicket')->middleware('receiption_role');

	Route::get('patients/desk/{pid}','PatientController@indexDesk')->middleware('desk_role');
	Route::post('patients/desk/{pid}','PatientController@storeDesk')->middleware('desk_role');

	Route::get('patients/show','PatientController@show')->middleware('entry_desk_receiption_role');
	Route::post('patients/show','PatientController@search')->middleware('entry_desk_receiption_role');
	Route::get('printpatientdata/{id}&{vid}','PatientController@printdata')->middleware('entrypoint_receiption_role');
	Route::get('printpatientcard/{id}','PatientController@printcarddata')->middleware('entrypoint_receiption_role');
	Route::get('patients/ticket/{id}','PatientController@print_ticket')->middleware('receiption_role');
	Route::get('patients/visits/{id}&{visit_id}','PatientController@addvisit')->middleware('entry_desk_receiption_role');
	Route::post('patients/visits/{id}&{visit_id}','PatientController@storeVisit')->middleware('entry_desk_receiption_role');
	Route::get('patients/visit_exit/{id}&{visit_id}','PatientController@exitvisit')->middleware('entrypoint_role');
	Route::post('patients/visit_exit/{id}&{visit_id}','PatientController@storeexitvisit')->middleware('entrypoint_role');
	Route::get('patients/exit_visit_report/{visit_id}','VisitController@reportvisit')->middleware('entrypoint_role');
	Route::post('patients/showSID','PatientController@checkexistSID');
	Route::post('patients/showPID','PatientController@checkexistPID');
	Route::get('patients/showvisits/{eid}','PatientController@showvisits')->middleware('desk_receiption_role');
	Route::get('patients/cancelvisit/{vid}','VisitController@deleteVisit')->middleware('desk_receiption_role');
	Route::get('patients/convertvisit/{eid}&{vid}','VisitController@convertVisit')->middleware('desk_receiption_role');

	Route::get('patients/showinpatient','PatientController@showinpatientvisits')->middleware('entry_desk_receiption_role');
	Route::post('patients/getPatient','PatientController@ajax_get_patient_data');

	Route::post('patients/getDepartmentDoctors','PatientController@ajax_get_department_doctors');

});
////////////////////////////
// Visit Module routes /////
////////////////////////////
// Put ajax post before store function
Route::post('visits/getDiagnoses','AjaxVisitController@checkVisitDiagnoses');
Route::post('visits/getComplaints','AjaxVisitController@checkVisitComplaints');
Route::post('visits/getProcedures','AjaxVisitController@ajaxProcedures');
Route::post('visits/getRadiology','AjaxVisitController@ajaxGetVisitRadiology');
Route::post('visits/getMedicine','AjaxVisitController@ajaxGetVisitMedicine');
Route::post('visits/getDrRecommendation','AjaxVisitController@ajaxGetDrRecommendation');
Route::post('visits/getVisits','AjaxVisitController@ajaxGetPatientHistory');
Route::post('visits/getNewVisits','AjaxVisitController@ajaxGetNotSeenVisits');
Route::get('visits/getAllDiagnoses','AjaxVisitController@ajaxGetAllDiagnoses');
Route::get('visits/getAllComplaints','AjaxVisitController@ajaxGetAllComplaints');
Route::get('visits/getAllMedicines','AjaxVisitController@ajaxGetAllMedicines');

Route::get('visits/{mid}','VisitController@index')->middleware(['auth','doctor_role']);
Route::post('visits/{mid}','VisitController@store')->middleware(['auth','doctor_role']);
Route::get('visits/patient/show','PatientController@show')->middleware(['auth','doctor_role']);
Route::post('visits/patient/show','PatientController@search')->middleware(['auth','doctor_role']);
Route::get('visits/printhistory/{id}','PatientController@printhistorydata')->middleware(['auth','doctor_role']);
Route::get('visits/showinpatient_file/{vid}','VisitController@show_inpatient_file_data')->middleware(['auth','entrypoint_receiption_role']);
Route::get('visits/showinpatient_diagnoses/{vid}','VisitController@show_inpatient_diagnoses_data')->middleware(['auth','entrypoint_receiption_role']);


/////////////////////////
// Admin Module routes //
/////////////////////////
Route::group(['middleware' => ['auth','admin']], function () {
	Route::get('admin','AdminController@index');
	Route::post('admin','AdminController@searchTicketNumber');
	Route::get('admin/datalog','AdminController@showLogData');
	Route::post('admin/datalog','AdminController@searchLogData');
	Route::get('admin/users','AdminController@indexUser');
	Route::post('admin/users','AdminController@storeUser');
	Route::delete('admin/users/{uid}','AdminController@destroyUser');
	Route::get('admin/medicalunits','AdminController@indexMedicalUnit');
	Route::post('admin/medicalunits','AdminController@storeMedicalUnit');
	Route::get('admin/assignclinic','AdminController@indexClinicToDepartment');
	Route::post('admin/assignclinic','AdminController@storeClinicToDepartment');
	Route::get('admin/assigndoctor','AdminController@indexDoctorToDepartment');
	Route::post('admin/assigndoctor','AdminController@storeDoctorToDepartment');
	Route::get('admin/delete_dep_user/{user_id}&{dep_id}','AdminController@detachDoctorToDepartment');
	Route::get('admin/entrypoints','AdminController@indexEntrypoint');
	Route::post('admin/entrypoints','AdminController@storeEntrypoint');
	Route::get('admin/assignentrypoint','AdminController@indexEmployeeToEntrypoint');
	Route::post('admin/assignentrypoint','AdminController@storeEmployeeToEntrypoint');
	Route::get('admin/delete_entry_user/{user_id}&{entry_id}','AdminController@detachEmployeeToEntrypoint');
	Route::get('admin/{pid}&{vid}/edit','AdminController@editPatient');
	Route::patch('admin/update/{pid}&{vid}','AdminController@updatePatient');
	Route::get('admin/show/{vid}','AdminController@showPatientVisit');
	Route::get('admin/show','PatientController@show');
	Route::post('admin/show','PatientController@search');
	Route::get('admin/{pid}&{vid}&{cid}/converttoentry','AdminController@convertPatientToEntry');




	/////////////////////////
	// Reports routes /////
	////////////////////////

	Route::get('admin/visitsperiod','AdminController@print_visits_period');
	Route::post('admin/visitsperiod','AdminController@show_visits_period');
	Route::get('admin/print','AdminController@report_visits_period');

	Route::get('admin/desk_visits_period','AdminController@print_desk_visits_period');
	Route::post('admin/desk_visits_period','AdminController@show_desk_visits_period');
	Route::get('admin/print_desk','AdminController@report_desk_visits_period');

	Route::get('admin/rec_desk_visits_period','AdminController@print_rec_desk_visits_period');
	Route::post('admin/rec_desk_visits_period','AdminController@show_rec_desk_visits_period');
	Route::get('admin/print_rec_to_desk','AdminController@report_rec_desk_visits_period');

	Route::get('admin/total_patients_period','AdminController@show_total_patient_view');
	Route::post('admin/total_patients_period','AdminController@show_total_patient_results');
	Route::get('admin/print_total_patients','AdminController@report_total_patients_period');
	Route::get('admin/print_total_patients_today','AdminController@report_total_patients_today');

	Route::get('admin/printinpatient','AdminController@report_inpatient_period');
	Route::get('admin/inpatientsvisitsperiod','AdminController@print_inpatients_visits_period');
	Route::post('admin/inpatientsvisitsperiod','AdminController@show_inpatients_visits_period');

	Route::get('admin/printworkingusers','AdminController@report_working_users_period');
	Route::get('admin/workingusers','AdminController@workingusers_view');
	Route::post('admin/workingusers','AdminController@show_workingusers');

	Route::get('admin/print_total_inpatients','AdminController@report_total_inpatients_period_view');
	Route::post('admin/print_total_inpatients','AdminController@report_total_inpatients_period_results');
	Route::get('admin/print_all_inpatients','AdminController@print_inpatients_dep_period');

	Route::get('admin/print_medicines','AdminController@report_print_medicines');
	Route::post('admin/print_medicines','AdminController@report_print_medicines_results');
	Route::get('admin/print_medicines_paper','AdminController@print_medicines_results');

	/* Medical Reports */
	Route::get('admin/medicalreports/clinics','AdminController@medical_report_clinics_view');
	Route::post('admin/medicalreports/clinics','AdminController@medical_report_clinics_results');
	Route::get('admin/medicalreports/clinics/{vid}/print','AdminController@medical_report_clinics_print');

	Route::get('admin/medicalreports/entry_clinics','AdminController@medical_report_entry_clinics_view');
	Route::post('admin/medicalreports/entry_clinics','AdminController@medical_report_entry_clinics_results');
	Route::get('admin/medicalreports/entry_clinics/{vid}/print','AdminController@medical_entry_report_clinics_print');

	Route::get('admin/medicalreports/gdesk','AdminController@medical_report_deskclinics_view')->name('gdesk');
	Route::post('admin/medicalreports/gdesk','AdminController@medical_report_deskclinics_results');
	Route::get('admin/medicalreports/gdesk/{vid}/print','AdminController@medical_report_deskclinics_print');

	Route::get('admin/medicalreports/tdesk','AdminController@medical_report_deskclinics_view')->name('tdesk');
	Route::post('admin/medicalreports/tdesk','AdminController@medical_report_deskclinics_results');
	Route::get('admin/medicalreports/tdesk/{vid}/print','AdminController@medical_report_deskclinics_print');

	Route::get('admin/medicalreports/entry_gdesk','AdminController@medical_report_entry_deskclinics_view')->name('entry_gdesk');
	Route::post('admin/medicalreports/entry_gdesk','AdminController@medical_report_entry_deskclinics_results');
	Route::get('admin/medicalreports/entry_gdesk/{vid}/print','AdminController@medical_entry_report_deskclinics_print');

	Route::get('admin/medicalreports/entry_tdesk','AdminController@medical_report_entry_deskclinics_view')->name('entry_tdesk');
	Route::post('admin/medicalreports/entry_tdesk','AdminController@medical_report_entry_deskclinics_results');
	Route::get('admin/medicalreports/entry_tdesk/{vid}/print','AdminController@medical_entry_report_deskclinics_print');

	
	Route::get('admin/backup','AdminController@make_backup');
	Route::post('admin/restore','AdminController@restore_file');

});
Route::get('admin/visitstoday/{eid}','AdminController@print_visits_today')->middleware('auth');
Route::get('admin/inpatienttoday','AdminController@print_inpatient_today')->middleware('auth');


/////////////////////////
// Logger plugin route /////
////////////////////////
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

// Logging in and out
Route::get('/auth/login', 'Auth\AuthController@getLogin');
Route::post('/auth/login', 'Auth\AuthController@postLogin');
Route::get('/auth/logout', 'Auth\AuthController@getLogout');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');


?>
