<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/16/19
 * Time: 12:11 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerInteractions;
use App\Models\Call;
use App\Models\Customer;
use App\Models\PhoneCallsDetail;
use App\Models\Project;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use PDO;
use DB;
use App\Http\Requests;
use Twilio\Twiml;
use App\User;


class CallController extends Controller
{

    //Schedule Bulk Phone Calls
    public function create(Request $request)
    {
        $bulk_call_array = $request->input('phone_call_array');
        foreach ($bulk_call_array as $call_array) {

            $call = new Call();
//            $call->customer_arr = $call_array;
//            $customer = DB::table('projects')->where()->first();

            $projectObj = Project::find($call_array);
            $customer_id = $projectObj->customer_id;
            $call->project_id = $call_array;
            $call->customer_detail_obj = $customer_id;
            $call->title = $request->input('title');
            $call->reason = $request->input('reason');
            $call->call_date = $request->input('call_date');
            $call->scheduled_by = $request->input('scheduled_by');
            $call->assigned_to = $request->input('assigned_to');
            $call->created_by = $request->input('created_by');
            $call->modified_by = $request->input('modified_by');
            $call->save();
        }

        return response()->json(['success' => 'Bulk calls assigned successfully', 'bulk_call_array' => $bulk_call_array], 201);
    }

//  Schedule Phone Calls
    public function createphonecall(Request $request, $user_id)
    {
        $call = new Call();
        $call->customer_detail_obj = $request->input('customer_detail_obj');
        $call->project_id = $request->input('project_id');
        $call->title = $request->input('title');
        $call->reason = $request->input('reason');
        $call->call_date = $request->input('call_date');
        $call->scheduled_by = $request->input('scheduled_by');
        $call->assigned_to = $request->input('assigned_to');
        $call->created_by = $request->input('created_by');
        $call->modified_by = $request->input('modified_by');
        $call->save();

        $project = Project::find($call->project_id);
        $customer_id = $project->customer_id;

//        $customer = Customer::find($customer_id);


        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->project_id =  $call->project_id;
        $customer_interactions->interaction_type = 'Phone Call';
        $customer_interactions->interaction_notes = 'Phone Call to Customer ' . $customer_id . ' scheduled';
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_calls = true;
        $customer_interactions->save();


//        $phonecallsdetail = new PhoneCallsDetail();
//        $phonecallsdetail->phone_call_id = $call->id;
//        $phonecallsdetail->status = 70;
//        $phonecallsdetail->save();

        return response()->json(['success' => 'Phone call scheduled successfully'], 201);
    }

    public function deletescheduledphonecall($id)
    {
        $call = Call::find($id);
        $call->delete();
        return response()->json(['success' => 'Scheduled calls deleted successfully'], 201);
    }

    //get all scheduled calls
    public function getallscheduledcalls()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_customers_projects_meta');
        $stmt->execute();
        $calls = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $estimator_name = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();
        return response()->json(['calls' => $calls, 'projects' => $projects, 'customers' => $customers, 'estimator_name' => $estimator_name], 201);
    }

    //get all projects and customers
    public function get_all_customers_project_meta()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_customers_projects_meta');
        $stmt->execute();

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['projects' => $projects, 'customers' => $customers, 'users' => $users], 201);
    }

    public function get_project_estimator_meta()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_project_estimator_meta');
        $stmt->execute();

        $estimator_name = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['estimator_name' => $estimator_name], 201);
    }

    public function get_phone_calls_meta()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_phone_calls_meta');
        $stmt->execute();

        $phone_call_details = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $sid = env('TWILIO_ACCOUNT_SID');
//        $token = env('TWILIO_AUTH_TOKEN');
//        $username = env('TWILIO_ACCOUNT_SID');
//        $password = env('PASSWORD');
//        $twilio = new Client($sid, $token);
//
//        $calls = $twilio->calls->read(array("status" => "answered"));
//
//        $call_array = array();
//
//        foreach ($calls as $record) {
//            $record1 = new \stdClass();
//            $record1->sid = $record->sid;
//            $record1->status = $record->status;
//            $record1->startTime = $record->startTime;
//            $record1->from = $record->from;
//            $record1->to = $record->to;
//            $call_array [] = $record1;
//        }

        return response()->json([
            'phone_call_details' => $phone_call_details,
            'success' => 'List retrieved successfully',
//            'callLogs' => $call_array
        ], 200);

    }


    public function get_user_full_name_meta()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_user_full_name_meta');
        $stmt->execute();

        $full_name = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['full_name' => $full_name], 201);
    }


    public function getmyphonecallsmeta($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_my_phone_calls_meta(?)');
        $stmt->execute(array($id));

        $my_phone_calls = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['my_phone_calls' => $my_phone_calls], 201);
    }

    public function deletemyphonecall($id)
    {
        $call = Call::find($id);
        $call->delete();
        return response()->json(['success' => 'My Phone call deleted successfully'], 201);
    }


    public function gettodayphonecallsmeta($id)
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_today_phone_calls_meta(?)');
        $stmt->execute(array($id));

        $today_phone_calls = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['today_phone_calls' => $today_phone_calls], 201);
    }

    public function deletetodayphonecall($id)
    {
        $call = Call::find($id);
        $call->delete();
        return response()->json(['success' => 'Today Phone call deleted successfully'], 201);
    }

    public function getphonecallsearchfiltermeta(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL search_phone_call_filter_meta(:pdue_date_from,
                                                                  :pdue_date_to,
                                                                  :pphone_call_title,
                                                                  :preason_for_call ,
                                                                  :pscheduled_by,
                                                                  :passigned_to_user ,
                                                                  :passigned_to_role,
                                                                  :pcall_result ,
                                                                  :pcall_status ,
                                                                  :pproject_number ,
                                                                  :pcustomer_first_name ,
                                                                  :pcustomer_last_name ,
                                                                  :pestimator ,
                                                                  :pcrew

                                                                  )');
        $stmt->execute(array(
            ':pdue_date_from' => $request->input('due_date_from'),
            ':pdue_date_to' => $request->input('due_date_to'),
            ':pphone_call_title' => $request->input('phone_call_title'),
            ':preason_for_call' => $request->input('reason_for_call'),
            ':pscheduled_by' => $request->input('scheduled_by'),
            ':passigned_to_user' => $request->input('assigned_to_user'),
            ':passigned_to_role' => $request->input('assigned_to_role'),
            ':pcall_result' => $request->input('call_result'),
            ':pcall_status' => $request->input('call_status'),
            ':pproject_number' => $request->input('project_number'),
            ':pcustomer_first_name' => $request->input('customer_first_name'),
            ':pcustomer_last_name' => $request->input('customer_last_name'),
            ':pestimator' => $request->input('estimator'),
            ':pcrew' => $request->input('crew'),
        ));

        $searchResult = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['searchResult' => $searchResult], 201);
    }

    public function getUnScheduledPhoneCallsCustomers(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL search_unscheduled_phone_calls_customers(:unscheduled_date)');
        $stmt->execute(array(':unscheduled_date' => $request->input('unscheduled_date')));

        $unScheduledCustomers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();


        return response()->json(['unScheduledCustomers' => $unScheduledCustomers], 201);

    }

    public function getUnScheduledPhoneCallsProjects(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL search_unscheduled_phone_calls_projects(:punscheduled_date,
                                                                   :pproject_type_id,
                                                                   :pstate_id ,
                                                                   :ppotential_type_id,
                                                                   :pestimator_id ,
                                                                   :pcrew_id
                                                                  )');
        $stmt->execute(array(
            ':punscheduled_date' => $request->input('unscheduled_date'),
            ':pproject_type_id' => $request->input('project_type_id'),
            ':pstate_id' => $request->input('project_status_id'),
            ':ppotential_type_id' => $request->input('project_potential_id'),
            ':pestimator_id' => $request->input('estimator_id'),
            ':pcrew_id' => $request->input('crew_id'),
        ));

        $unScheduledProjects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['unScheduledProjects' => $unScheduledProjects], 201);

    }


    public function newCall(Request $request)
    {
        $sid = "ACedc3e5841340801b51c49be4458012e6";
        $token = "your_auth_token";
        $twilio = new Client($sid, $token);

        $call = $twilio->calls
            ->create("+923134780737", // to
                "+12054635435", // from
                array("url" => "http://demo.twilio.com/docs/voice.xml")
            );
        return response()->json(['success' => 'call is making', 'call_SID' => $call->sid], 200);
    }

    public function callLogs(Request $request)
    {
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $username = env('USERNAME');
        $password = env('PASSWORD');
        $twilio = new Client($sid, $token, $username);

        $calls = $twilio->calls->read(array());

        $call_array = array();

        foreach ($calls as $record) {
            $record1 = new \stdClass();
            $record1->sid = $record->sid;
            $record1->status = $record->status;
            $record1->startTime = $record->startTime;
            $record1->from = $record->from;
            $record1->to = $record->to;
            $call_array [] = $record1;
        }

        return response()->json(['success' => 'List retrieved successfully',
            'callLogs' => $call_array
        ], 200);
    }

    public function getphonecallsfiltertypes()
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_call_filter_types()');
        $stmt->execute();

        $phone_calls_select_filter = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $phone_calls_status_filter = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['phone_calls_select_filter' => $phone_calls_select_filter, 'phone_calls_status_filter' => $phone_calls_status_filter], 201);
    }

    public function phonecallfilter($id, $filter_id,$current_page_no)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL phone_calls_filter(?,?,?)');

//        $current_page_no=10 * ($current_page_no-1);

        $stmt->execute(array($id, $filter_id,$current_page_no));



        $phone_calls = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $count = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        if($count!=null){
        $count = $count[0]->recoad_count;
        }



        return response()->json(['phone_calls' => $phone_calls,'count'=>$count], 201);
    }


    public function getProjectsMetaForUnscheduledCalls()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_projects_meta_for_unscheduled_calls()');
        $stmt->execute();

        $project_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $project_statuses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $project_potentials = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $estimators = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $crews = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json([

            'project_types' => $project_types,
            'project_statuses' => $project_statuses,
            'project_potentials' => $project_potentials,
            'estimators' => $estimators,
            'crews' => $crews

        ], 201);
    }


    public function getAllCustomerCalls($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_all_customer_calls(?)');
        $stmt->execute(array($id));

        $allCustomerPhoneCalls = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['allCustomerPhoneCalls' => $allCustomerPhoneCalls], 201);
    }


    public function phonecallsdetail(Request $request, $user_id)
    {
        //phonecallsdetail = PhoneCallsDetail::find($id);

        $phonecallsdetail = new PhoneCallsDetail();
        $phonecallsdetail->phone_call_id = $request->input('phone_call_id');
        $phonecallsdetail->status = $request->input('status');
        $phonecallsdetail->result = $request->input('result');
        $phonecallsdetail->save();

        $phone_call = Call::find($phonecallsdetail->phone_call_id);
        $project_id = $phone_call->project_id;

        $project = Project::find($project_id);
        $customer_id = $project->customer_id;

        $object_type = DB::table('object_type')->where('ObjectTypeID',$phonecallsdetail->status)->first();
        $object_type->ObjectName;

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->project_id = $project_id;
        $customer_interactions->interaction_type = 'Phone Call';
        $customer_interactions->interaction_notes = 'Phone Call to Customer completed with status: ' . $object_type->ObjectName;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_calls = true;
        $customer_interactions->save();


        return response()->json(['success' => 'Call detail saved successfully'], 201);
    }

    public function getprojectsofcustomers($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_projects_of_customer(?)');
        $stmt->execute(array($id));

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();


        return response()->json(['projects' => $projects], 201);
    }


    public function getPhoneCallsSearchDropDownData()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_phone_call_search_required_data()');
        $stmt->execute();


        $user_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $call_statuses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();




        return response()->json([ 'user_types' => $user_types,
            'call_statuses' => $call_statuses], 201);
    }


}
