<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('getRegister/meta', 'GamesController@getRegisterMeta');

//reset password
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::middleware('auth:api')->post('/user', function (Request $request) {

    return $request->user();
});

//Customer Routes


Route::post('customers/create/{user_id}', 'Admin\CustomerController@create');
Route::get('customers/read/{id}', 'Admin\CustomerController@getCustomer');
Route::post('customers/update/{id}', 'Admin\CustomerController@updateCustomer');
Route::delete('customers/delete/{id}', 'Admin\CustomerController@deleteCustomer');
Route::get('customers/read', 'Admin\CustomerController@getallCustomers');
Route::get('customers/customersmeta', 'Admin\CustomerController@getcustomersmeta');
Route::post('customers/search/filter/{current_page_no}', 'Admin\CustomerController@getcustomersearchfiltermeta');
Route::post('customers/reference/search/filter', 'Admin\CustomerController@getcustomerreferencesearchfiltermeta');
Route::get('customers/softdeleted/all', 'Admin\CustomerController@getSoftDeletedCustomers');
Route::get('customers/softdeleted/restore/{customer_no}', 'Admin\CustomerController@restoreSoftDeletedCustomer');
Route::get('customers/searchReference/read', 'Admin\CustomerController@getSearchReferenceCustomers');
Route::get('customers/interactions/read/{id}', 'Admin\CustomerController@getCustomerInteractions');
Route::post('customers/interactions/create/userNote', 'Admin\CustomerController@createUserNote');
Route::post('customers/interactions/filter', 'Admin\CustomerController@getFilteredCustomerInteractions');


//Project Routes
Route::get('project/meta/{user_id}', 'Admin\ProjectController@getprojectmeta');
Route::get('project/meta/{customer_id}/{user_id}', 'Admin\ProjectController@getCustomerprojectmeta');
Route::post('project/create/{user_id}', 'Admin\ProjectController@create');
Route::post('project/update/{id}/{user_id}', 'Admin\ProjectController@update');
Route::get('project/read/{id}', 'Admin\ProjectController@getprojectdetailmeta');
Route::get('project/single/read/{id}', 'Admin\ProjectController@getSingleProject');
Route::delete('project/delete/{id}', 'Admin\ProjectController@deleteProject');
Route::get('projects/read', 'Admin\ProjectController@getallprojects');
Route::post('project/search/filter/{user_id}/{current_page_no}', 'Admin\ProjectController@getprojectsearchfiltermeta');
Route::post('project/searchbykeyword/{user_id}', 'Admin\ProjectController@getProjectSearchByKeyWords');

// Project Images


Route::post('project/files', 'Admin\ProjectController@uploadProjectAttachments');
Route::post('project/files/delete', 'Admin\ProjectController@deleteProjectAttachment');


//Project details Routes
Route::post('project/savesubproject/{user_id}', 'Admin\ProjectController@savesubprojects');
Route::post('project/sub_project/update/{id}/{user_id}', 'Admin\ProjectController@updatesubprojects');
Route::get('project/sub_project/read/{id}', 'Admin\ProjectController@getsubprojectsmeta');
Route::post('project/sub_project/items/create', 'Admin\ProjectController@savesubprojectitems');
Route::post('project/sub_project/items/create', 'Admin\ProjectController@savesubprojectitems');
Route::post('project/sub_project/read/{id}', 'Admin\ProjectController@getprojectdetailsid');
Route::get('project/sub_project/get/{id}', 'Admin\ProjectController@getsubprojectitem');
Route::get('project/pdf/{id}', 'Admin\ProjectController@getPdfData');


Route::post('project/savepayment/{user_id}', 'Admin\ProjectController@savepayment');
Route::get('customer_payments/read/{id}', 'Admin\ProjectController@getcustomerpayment');
Route::get('customer_payment/read/{id}', 'Admin\ProjectController@getonlyonecustomerpayment');
Route::get('project/expenseitems/meta/{id}', 'Admin\ProjectController@getallprojectexpenseitemsmeta');
Route::delete('project/delete/subproject/{id}', 'Admin\ProjectController@deletesubproject');
Route::post('customer_payment/update/{id}/{user_id}', 'Admin\ProjectController@updatecustomerpayment');

//Route::get('project/expense/meta/{id}', 'Admin\ProjectController@getExpenseMeta');
Route::post('project/saveexpense/{user_id}', 'Admin\ProjectController@saveexpense');
Route::post('project/save/expenseitems', 'Admin\ProjectController@saveexpenseitems');
Route::post('project/update1/expenseitems/{id}', 'Admin\ProjectController@updateexpenseitems');
Route::delete('project/delete/expenseitems/{id}', 'Admin\ProjectController@deleteexpenseitems');


Route::post('project/expense/update/{id}/{user_id}', 'Admin\ProjectController@updateexpense');
Route::get('project/expense/read/{id}', 'Admin\ProjectController@getexpense');
Route::get('project/expenses/read/{id}', 'Admin\ProjectController@getallprojectexpensesmeta');


Route::post('project/saveestimate/{user_id}', 'Admin\ProjectController@saveestimate');
Route::get('project/estimate/read/{pid}', 'Admin\ProjectController@getestimate');
Route::get('project/estimates/read', 'Admin\ProjectController@getallestimates');
Route::post('project/estimate/adddesciption', 'Admin\ProjectController@addDescription');
Route::post('project/estimate/updatedesciption', 'Admin\ProjectController@updateDescription');
Route::post('project/estimate/update/{id}/{user_id}', 'Admin\ProjectController@updateestimate');
//Route::post('user/create', 'Admin\CustomerController@createUser');
Route::delete('project/estimate/deletedesciption/{id}', 'Admin\ProjectController@deleteDescription');
Route::post('project/estimate/addinteriorpaint', 'Admin\ProjectController@addInteriorPaint');
Route::post('project/estimate/updateinteriorpaint', 'Admin\ProjectController@updateInteriorPaint');
Route::delete('project/estimate/deleteinteriorpaint/{id}', 'Admin\ProjectController@deleteInteriorPaint');
Route::post('project/estimate/addinteriordescription', 'Admin\ProjectController@addInteriordescription');
Route::post('project/estimate/updateinteriordescription', 'Admin\ProjectController@updateInteriorDescription');
Route::delete('project/estimate/deleteinteriordescription/{id}', 'Admin\ProjectController@deleteInteriorDescription');


//Work Order Route
Route::get('project/workorder/read/{id}', 'Admin\ProjectController@getworkorder');

//project/workorder/read/

//User Routes
Route::get('user/meta', 'Admin\UserController@get_user_meta');
Route::post('user/create', 'Admin\UserController@create');
Route::post('user/update/{id}', 'Admin\UserController@update');
Route::post('users/update', 'Admin\UserController@updateUserPasswords');


//Route::post('users/manage_access/create', 'Admin\UserController@createusermanageaccess');
Route::post('users/manage_access/authorize_city', 'Admin\UserController@saveAuthorizeCity');
Route::post('users/manage_access/authorize_zip_code', 'Admin\UserController@saveauthorizezipcode');
Route::get('users/manage_access/authorization_meta/{id}', 'Admin\UserController@getauthorizationmeta');
Route::delete('users/manage_access/authorized_city/delete/{id}', 'Admin\UserController@deleteAuthorizedcity');
Route::delete('users/manage_access/authorized_zip_code/delete/{id}', 'Admin\UserController@deleteAuthorizedzipcode');
Route::post('users/user/is_active/{id}', 'Admin\UserController@updateUserIsActiveStatus');
Route::delete('user/delete/{id}', 'Admin\UserController@delete');
Route::get('user/{id}', 'Admin\UserController@get');

Route::post('users/all/{active_user_current_page_no}/{inactive_user_current_page_no}/', 'Admin\UserController@getusers');


//    users/user/is_active
Route::get('users/manage_access/meta', 'Admin\UserController@getusersmanageaccessmeta');


//User Details Route
Route::get('userdetail/{id}', 'Admin\UserController@getalluserlogindetails');


//Vendor Routes
Route::post('vendors/create', 'Admin\VendorController@create');
Route::post('vendors/update/{id}', 'Admin\VendorController@update');
Route::delete('vendors/delete/{id}', 'Admin\VendorController@delete');
Route::get('vendors/read/{id}', 'Admin\VendorController@get');
Route::get('vendors/all', 'Admin\VendorController@getallvendors');
Route::get('vendors/data', 'Admin\VendorController@getStatesAndCities');

//Call Routes
Route::post('bulk_calls/create', 'Admin\CallController@create');
Route::post('phone_call/create/{user_id}', 'Admin\CallController@createphonecall');
Route::delete('delete_scheduled_calls/{id}', 'Admin\CallController@deletescheduledphonecall');
Route::get('scheduled_calls/all', 'Admin\CallController@getallscheduledcalls');
Route::get('customers_projects_meta/all', 'Admin\CallController@get_all_customers_project_meta');
Route::get('calls/my_phone_calls/all/{id}', 'Admin\CallController@getmyphonecallsmeta');
Route::delete('calls/my_phone_calls/delete/{id}', 'Admin\CallController@deletemyphonecall');
Route::get('calls/today_phone_calls/all/{id}', 'Admin\CallController@gettodayphonecallsmeta');
Route::delete('calls/today_phone_calls/delete/{id}', 'Admin\CallController@deletetodayphonecall');
Route::get('customers_estimator_meta/all', 'Admin\CallController@get_project_estimator_meta');
Route::get('scheduled_phone_calls/all', 'Admin\CallController@get_phone_calls_meta');
Route::get('get_user_full_name', 'Admin\CallController@get_user_full_name_meta');
Route::post('phone_call/search/filter', 'Admin\CallController@getphonecallsearchfiltermeta');
Route::post('phone_call/make', 'Admin\CallController@makeCall');
Route::get('phone_call/logs', 'Admin\CallController@newCall');
Route::get('phone_calls/filter_types', 'Admin\CallController@getphonecallsfiltertypes');
Route::get('phone_calls/filter/{id}/{filter_id}/{current_page_no}', 'Admin\CallController@phonecallfilter');
Route::get('phone_calls/projects/{id}', 'Admin\CallController@getprojectsofcustomers');
Route::post('phone_call/complete/{user_id}', 'Admin\CallController@phonecallsdetail');
Route::get('phone_call/search/required/data', 'Admin\CallController@getPhoneCallsSearchDropDownData');
Route::get('customer/calls/all/{id}', 'Admin\CallController@getAllCustomerCalls');
Route::get('phone_calls/unscheduled_phone_calls/projects/meta', 'Admin\CallController@getProjectsMetaForUnscheduledCalls');
Route::post('unscheduled_phone_calls_customers/search', 'Admin\CallController@getUnScheduledPhoneCallsCustomers');
Route::post('unscheduled_phone_calls_projects/search', 'Admin\CallController@getUnScheduledPhoneCallsProjects');


//Capability Token Route

Route::get('phone_call/capability_token', 'Admin\CapabilityTokenController@getCapabilityToken');


//Calendar routes
Route::get('calendars/appointments/customer/projects/{customer_id}', 'Admin\CalendarController@getprojectsofcustomers');
Route::get('calendars/appointment/data', 'Admin\CalendarController@getappointmentdata');
Route::post('calendars/event/create/{user_id}', 'Admin\CalendarController@createEvent');
Route::get('calendars/event1/data/{id}', 'Admin\CalendarController@getEvent');
Route::get('calendars/event/data/{user_id}', 'Admin\CalendarController@getEvents');

Route::get('calendars/appointments/paginate/{start_date}/{view}/{userID}', 'Admin\CalendarController@getPaginatedEvents');

Route::post('calendars/event/edit/{id}/{user_id}', 'Admin\CalendarController@editEvent');
Route::delete('calendars/appointment/delete/{id}', 'Admin\CalendarController@deleteEvent');
Route::get('calendars/event/view/{id}', 'Admin\CalendarController@viewEvent');
Route::get('calendars/user/roles', 'Admin\CalendarController@getUserRoles');
Route::get('calendars/project/data/{id}', 'Admin\CalendarController@getdatafromproject');
Route::get('calendars/event/appointment/event/{id}', 'Admin\CalendarController@getDataToScheduleEstimate');
Route::get('calendars/selected/users/{id}/{user_id}', 'Admin\CalendarController@getUsersOfSelectedCalendar');

//task routes

Route::post('task/create', 'Admin\TodoController@createTask');
Route::get('task/get/{user_id}/{company_todo_page}/{personal_todo_page}', 'Admin\TodoController@getTasks');
Route::delete('task/delete/{id}', 'Admin\TodoController@deleteTodoTask');
Route::post('complete/task/{id}', 'Admin\TodoController@completeTask');

// Admin Options


Route::post('referralSource/create', 'Admin\AdminOptionsController@createReferralSource');
Route::get('referralSource/all', 'Admin\AdminOptionsController@getReferralSources');
Route::get('referralSource/read/{id}', 'Admin\AdminOptionsController@getReferralSource');
Route::post('referralSource/update/{id}', 'Admin\AdminOptionsController@updateReferralSource');


Route::post('paintBrand/create', 'Admin\AdminOptionsController@createPaintBrand');
Route::get('paintBrand/all', 'Admin\AdminOptionsController@getPaintBrands');
Route::get('paintBrand/read/{id}', 'Admin\AdminOptionsController@getPaintBrand');
Route::post('paintBrand/update/{id}', 'Admin\AdminOptionsController@updatePaintBrand');


Route::get('commission/user/all', 'Admin\AdminOptionsController@getUsers');
Route::post('commission/create', 'Admin\AdminOptionsController@createCommissions');
Route::get('commission/all', 'Admin\AdminOptionsController@getCommissions');
Route::get('commission/read/{id}', 'Admin\AdminOptionsController@getCommission');
Route::post('commission/update/{id}', 'Admin\AdminOptionsController@updateCommission');
Route::post('all-user-login/filter', 'Admin\AdminOptionsController@allUserLoginFilter');


// Marketing Expenditures

Route::post('adminOptions/marketingExpenditure/create', 'Admin\AdminOptionsController@createMarketingExpenditure');
Route::get('adminOptions/marketingExpenditure/get/{id}', 'Admin\AdminOptionsController@getMarketingExpenditure');
Route::delete('adminOptions/marketingExpenditure/delete/{id}', 'Admin\AdminOptionsController@deleteMarketingExpenditure');
Route::get('adminOptions/marketingExpenditure/get', 'Admin\AdminOptionsController@getMarketingExpenditures');

// Manage Calendars

Route::post('adminOptions/manageCalendars/create', 'Admin\AdminOptionsController@createManageCalendars');
Route::get('adminOptions/manageCalendars/get', 'Admin\AdminOptionsController@getCalendarNames');
Route::get('adminOptions/manageCalendars/meta/{id}', 'Admin\AdminOptionsController@getCalendarMeta');
Route::get('adminOptions/manageCalendars/users/get/{id}/{user_id}', 'Admin\AdminOptionsController@getCalendarUsers');
Route::post('adminOptions/manageCalendars/update/{id}', 'Admin\AdminOptionsController@updateManageCalendars');
Route::delete('adminOptions/manageCalendars/delete/{id}', 'Admin\AdminOptionsController@deleteManageCalendars');
Route::post('adminOptions/allUserLogins/get/{page_no}', 'Admin\AdminOptionsController@getAllUserLogins');
Route::post('customers/softdeleted/search/filter', 'Admin\AdminOptionsController@getDeletedCustomerSearchFilterMeta');
Route::delete('adminOptions/manageCalendars/currentUser/delete/{id}', 'Admin\AdminOptionsController@deleteManageCalendarsCurrentUser');


// Statistics

Route::post('statistics/filter/customer-referral-source-marketing-costs', 'Admin\StatisticsController@onFilterCustomerReferralSourceMarketingCosts');
Route::post('statistics/filter/overall-estimate-statistics', 'Admin\StatisticsController@onFilterOverallEstimateStatistics');
Route::post('statistics/filter/overall-work-statistics', 'Admin\StatisticsController@onFilterOverallWorkStatistics');
Route::post('statistics/filter/estimates-by-customer-zipcode', 'Admin\StatisticsController@onFilterEstimatesByCustomerZipCode');
Route::post('statistics/filter/work-by-customer-zipcode', 'Admin\StatisticsController@onFilterWorkByCustomerZipCode');
Route::post('statistics/filter/csr-customer-to-work-stat', 'Admin\StatisticsController@onFilterCSRCustomerToWorkStat');
Route::post('statistics/filter/estimator-breakdowns', 'Admin\StatisticsController@onFilterEstimatorBreakdowns');
Route::post('statistics/filter/estimate-to-work-stats', 'Admin\StatisticsController@onFilterEstimateToWorkStat');
Route::post('statistics/filter/missing-estimates', 'Admin\StatisticsController@onFilterMissingEstimates');
Route::post('statistics/filter/crew-break-downs', 'Admin\StatisticsController@onFilterCrewBreakdowns');




