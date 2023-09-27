<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Calendars;
use App\Models\City;
use App\Models\Customer;
use App\Models\CustomerInteractions;
use App\Models\CustomerPayment;
use App\Models\ExpenseItems;
use App\Models\InteriorDescription;
use App\Models\InteriorPaints;
use App\Models\OtherDescription;
use App\Models\Project;
use App\Models\ProjectAttachment;
use App\Models\ProjectDetails;
use App\Models\ProjectExpenses;
use App\Models\SubProject;
use App\Models\SubProjectItems;
use App\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use PDO;


class ProjectController extends Controller
{

    //Create Project
    public function create(Request $request, $user_id)
    {
       $isUserCustomerAddress = $request->input('is_customer_address');

        if($isUserCustomerAddress == 0){

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
//            'project_number' => 'required|unique:projects',
            'address_1' => 'required',
            'state_id' => 'required',
            'zip_code' => 'required'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }
        }


        $project = new Project();
      //   $project->project_number = $request->input('project_number');
        $project->customer_id = $request->input('customer_id');
        $project->is_customer_address = $request->input('is_customer_address');
        $project->address_1 = $request->input('address_1');
        $project->address_2 = $request->input('address_2');
        $project->city_id = $request->input('city_id');
        $project->state_id = $request->input('state_id');
        $project->zip_code = $request->input('zip_code');
        $project->sub_division_name = $request->input('sub_division_name');
        $project->major_intersection = $request->input('major_intersection');
        $project->project_type_id = $request->input('project_type_id');
        $project->project_description = $request->input('project_description');
        $project->internal_notes = $request->input('internal_notes');
        $project->nick_names = $request->input('nick_names');
        $project->status_id = $request->input('status_id');
        $project->potential_type_id = $request->input('potential_type_id');
        $project->supervisor_id = $request->input('supervisor_id');
        $project->estimator_id = $request->input('estimator_id');
        $project->crew_id = $request->input('crew_id');
        $project->start_date = $request->input('start_date');
        $project->end_date = $request->input('end_date');
        $project->location_map_url = $request->input('location_map_url');
        $project->total_cost = $request->input('total_cost');
        $project->created_by = $request->input('created_by');
//       $project->created_at = $request->input('created_at');
        $project->updated_at = $request->input('updated_at');
        $project->is_deleted = $request->input('is_deleted');
        $project->save();

        $project_details = new ProjectDetails();
        $project_details->project_id = $project->id;
        // $project_details->final_price = 0.0;

        $project_details->save();

        if ($request->input('attachments') != null) {
            $project->attachments()->createMany($request->input('attachments'));
        }

        $project_type = DB::Table('project_types')->where('id', $project->project_type_id)->first();
        $project_type_name = $project_type->name;

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $project->customer_id;
        $customer_interactions->project_id = $project->id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = $project_type_name . ' Project ' . $project->id . ' created';
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_projects = true;
        $customer_interactions->save();

        return response()->json(['success' => 'Project created successfully', 'id' => $project->id], 201);
    }


    public function update(Request $request, $id, $user_id)
    {
        $project = Project::find($id);

//        $project->project_number = $request->input('project_number');
//        $old_description = $project->project_description;
//        $old_internal_notes = $project->internal_notes;
//        $old_nick_names = $project->nick_names;

        $old_project_type_id = $project->project_type_id;
        $old_status_id = $project->status_id;
        $old_potential_type_id = $project->potential_type_id;
        $old_supervisor_id = $project->supervisor_id;
        $old_estimator_id = $project->estimator_id;
        $old_crew_id = $project->crew_id;

        $project_type = DB::Table('project_types')->where('id', $old_project_type_id)->first();
        $old_project_type_name = $project_type->name;

        $status_type = DB::Table('statuses')->where('StatusID', $old_status_id)->first();
        $old_status_name = $status_type->StatusName;

        $potential_type = DB::Table('potential_types')->where('id', $old_potential_type_id)->first();
        if($potential_type != null)
        {
            $old_potential_type_name = $potential_type->name;
        }

//        $supervisor_id = DB::Table('users')->where('id', $old_supervisor_id)->first();
//        $supervisor_role_id = $supervisor_id != null ? $supervisor_id->role_id : null;
//        $object_Type = DB::Table('object_type')->where('ObjectTypeID', $supervisor_role_id)->first();
//        $old_supervisor_name = $object_Type->ObjectName;
//        $estimator_id = DB::Table('users')->where('id', $old_estimator_id)->first();
//        $estimator_role_id = $estimator_id != null ? $estimator_id->role_id : null;
//        $crew_id = DB::Table('users')->where('id', $old_crew_id)->first();
//        $crew_role_id = $crew_id != null ? $crew_id->role_id : null;

        $project->customer_id = $request->input('customer_id');
        $project->is_customer_address = $request->input('is_customer_address');
        $project->address_1 = $request->input('address_1');
        $project->address_2 = $request->input('address_2');
        $project->city_id = $request->input('city_id');
        $project->state_id = $request->input('state_id');
        $project->zip_code = $request->input('zip_code');
        $project->sub_division_name = $request->input('sub_division_name');
        $project->major_intersection = $request->input('major_intersection');
        $project->project_type_id = $request->input('project_type_id');
        $project->project_description = $request->input('project_description');
        $project->internal_notes = $request->input('internal_notes');
        $project->nick_names = $request->input('nick_names');
        $project->status_id = $request->input('status_id');
        $project->potential_type_id = $request->input('potential_type_id');
        $project->supervisor_id = $request->input('supervisor_id');
        $project->estimator_id = $request->input('estimator_id');
        $project->crew_id = $request->input('crew_id');
        $project->start_date = $request->input('start_date');
        $project->end_date = $request->input('end_date');
        $project->location_map_url = $request->input('location_map_url');
        $project->total_cost = $request->input('total_cost');
        $project->created_by = $request->input('created_by');
        $project->created_at = $request->input('created_at');
        $project->updated_at = $request->input('updated_at');
        $project->is_deleted = $request->input('is_deleted');
        $project->save();


        $project_type = DB::Table('project_types')->where('id', $project->project_type_id)->first();
//        $new_project_type_name = $project_type->name;

        $project_status = DB::Table('statuses')->where('StatusID', $project->status_id)->first();
//        $new_project_status_name = $project_status->StatusName;

        $potential_type = DB::Table('potential_types')->where('id', $project->potential_type_id)->first();
//        $new_potential_type_name = $potential_type->name;

        $supervisor_id = DB::Table('users')->where('id', $project->supervisor_id)->first();
        $new_supervisor_name = $supervisor_id != null ? $supervisor_id->first_name . ' ' . $supervisor_id->last_name : null;
//        $object_type = DB::Table('object_type')->where('ObjectTypeID', $supervisor_role_id)->first();
//        $new_supervisor_name = $object_type->ObjectName;


        $estimator_id = DB::Table('users')->where('id', $project->estimator_id)->first();
//        $new_estimator_name = $estimator_id->first_name . ' ' . $estimator_id->last_name;

        $crew_id = DB::Table('users')->where('id', $project->crew_id)->first();
//        $new_crew_name = $crew_id->first_name . ' ' . $crew_id->last_name;

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $project->customer_id;
        $customer_interactions->project_id = $project->id;
        $customer_interactions->interaction_type = 'User Action';

        $new_description = $request->input('project_description');
        $new_internal_notes = $request->input('internal_notes');
        $new_nick_names = $request->input('nick_names');
        $new_project_type_id = $request->input('project_type_id');
        $new_status_id = $request->input('status_id');
        $new_potential_type_id = $request->input('potential_type_id');
        $new_supervisor_id = $request->input('supervisor_id');
        $new_estimator_id = $request->input('estimator_id');
        $new_crew_id = $request->input('crew_id');

        $changes = $project->getChanges();

        $changes_array = array();

        $changes_notes = '';

        foreach ($changes as $key => $value) {
            $keysObject = new \stdClass();
            $keysObject->key = $key;
            $keysObject->value = $value;
            $changes_array [] = $keysObject;

            if ($keysObject->key == 'potential_type_id') {

                $potential_types = DB::Table('potential_types')->where('id', $project->potential_type_id)->first();
                $new_potential_types_name = $potential_types->name;

                $changes_notes = $changes_notes . ' ' .
//                    $old_project_type_name . ' Project ' .
//                    $project->id . ' updated . ' .
                    ' potential changed to ' . $new_potential_types_name . '. ';

            }
            if ($keysObject->key == 'project_type_id') {
                $project_types = DB::Table('project_types')->where('id', $project->project_type_id)->first();
                $new_project_types_name = $project_types->name;

                $changes_notes = $changes_notes . ' ' .

//                    $old_project_type_name . ' Project ' .
//                    $project->id . ' updated . ' .
                    'project type changed to ' . $new_project_types_name . '. ';

            }
            if ($keysObject->key == 'status_id') {

                $status = DB::Table('statuses')->where('StatusID', $project->status_id)->first();
                $new_status_name = $status->StatusName;

                $changes_notes = $changes_notes . ' ' .
//                    $old_project_type_name . ' Project ' .
//                    $project->id . ' updated . ' .
                    'project status changed to ' . $new_status_name . '. ';


            }
            if ($keysObject->key == 'supervisor_id') {

                $supervisor_id = DB::Table('users')->where('id', $project->supervisor_id)->first();
                $new_supervisor_name = $supervisor_id->first_name . ' ' . $supervisor_id->last_name;

                $changes_notes = $changes_notes . ' ' .
//                    $old_project_type_name . ' Project ' .
//                    $project->id . ' updated . ' .
                    'project supervisor changed to ' . $new_supervisor_name . '. ';

            }
            if ($keysObject->key == 'estimator_id') {

                $estimator_id = DB::Table('users')->where('id', $project->estimator_id)->first();
                $new_estimator_name = $estimator_id->first_name . ' ' . $estimator_id->last_name;

                $changes_notes = $changes_notes . ' ' .

//                    $old_project_type_name . ' Project ' .
//                    $project->id . ' updated . ' .
                    'project estimator changed to ' . $new_estimator_name . '. ';

            }
            if ($keysObject->key == 'crew_id') {

                $crew_id = DB::Table('users')->where('id', $project->crew_id)->first();
                $new_crew_name = $crew_id->first_name . ' ' . $crew_id->last_name;

                $changes_notes = $changes_notes . ' ' .

//                    $old_project_type_name . ' Project ' .
//                    $project->id . ' updated . ' .
                    'project crew changed to ' . $new_crew_name . '. ';

            }
            if ($keysObject->key == 'address_1') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Address 1 changed ';
            }
            if ($keysObject->key == 'address_2') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Address 2 changed ';
            }
            if ($keysObject->key == 'city_id') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' City changed ';
            }
            if ($keysObject->key == 'state_id') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' State changed ';
            }
            if ($keysObject->key == 'zip_code') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Zip Code changed ';
            }
            if ($keysObject->key == 'sub_division_name') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Sub Division changed ';
            }
            if ($keysObject->key == 'major_intersection') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Major Intersection changed ';
            }
            if ($keysObject->key == 'project_description') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Description changed ';
            }

            if ($keysObject->key == 'nick_names') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Nick Names changed ';
            }

            if ($keysObject->key == 'internal_notes') {
                $changes_notes = $changes_notes . ' ' . $old_project_type_name . ' Project ' .
                    $project->id . ' updated . ' . ' Notes changed ';
            }
        }


        if ($changes_notes != null && $changes_notes != '') {
            $customer_interactions->interaction_notes = $changes_notes;
            $customer_interactions->is_show_projects = true;
        } else {
            $project_type = DB::Table('project_types')->where('id', $project->project_type_id)->first();
            $project_type_name = $project_type->name;
            $customer_interactions->interaction_notes = $project_type_name . ' Project ' . $project->id . ' updated';
        }

        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->save();

        return response()->json(['success' => 'Project updated successfully', 'project' => $project,
            'project description' => $project->project_description,
            'array is' => $changes_array,
            'changes are ' => $changes], 201);

    }


    //get single project by id
    public
    function getprojectdetailmeta($id)
    {
        $projects = DB::table('projects')->where('id', $id)->first();
        $customer_id = $projects->customer_id;

        $project_detail_id = null;

        $prj_detail = ProjectDetails::where('project_id', $id)->first();
        if($prj_detail != null)
        {
            $project_detail_id = $prj_detail->id;
        }

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_project_by_id(?)');
        $stmt->execute(array($id));

        $project = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $project_attachments = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $payment_methods = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $payment_collected_by = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

        $expense_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $pay_to = $stmt->fetchAll(PDO ::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $expense_statuses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $siding_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $assignment = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $finish = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $trim_type = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $shutter_type = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $decks_paint = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $decks_finish = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $crew = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

        $discount_type = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $sub_project_statuses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $customer_interactions = new CustomerInteractions();
//        $customer_interactions->customer_id = $customer_id;
//        $customer_interactions->interaction_type = 'User Action';
//        if ($project != null || sizeof($project) > 0) {
//            $customer_interactions->interaction_notes = 'Estimate Scheduled For Project ' . $project->id;
//        }
//        $customer_interactions->performed_by_id = 'System Automated';
//        $customer_interactions->save();

        return response()->json([

            'project' => $project,
            'project_attachments' => $project_attachments,
            'payment_methods' => $payment_methods,
           // 'payment_collected_by' => $payment_collected_by,
            'expense_types' => $expense_types,
            'pay_to' => $pay_to,
            'expense_statuses' => $expense_statuses,
            'siding_types' => $siding_types,
            'assignment' => $assignment,
            'finish' => $finish,
            'trim_type' => $trim_type,
            'shutter_type' => $shutter_type,
            'decks_paint' => $decks_paint,
            'decks_finish' => $decks_finish,
          //'crew' => $crew,
            'discount_type' => $discount_type,
            'sub_project_statuses' => $sub_project_statuses,
            'project_detail_id' => $project_detail_id,

        ], 201);

    }

//Delete Project
    public
    function deleteProject($id)
    {
        $project = Project::find($id);

        $project_detail = ProjectDetails::where('project_id', $id)->first();
        $project_attachment = DB::table('project_attachments')->where('project_id', $id);
        $sub_projects = DB::table('sub_projects')->where('project_id', $id);
        $project_expenses = DB::table('project_expenses')->where('project_id', $id);
        $customer_payments = DB::table('customer_payments')->where('project_id', $id);

        if ($project != null) {
            $project->delete();
        }

        if ($project_detail != null) {
            $project_detail->delete();
        }
        if ($project_attachment != null) {
            $project_attachment->delete();
        }
        if ($sub_projects != null) {
            $sub_projects->delete();
        }
        if ($project_expenses != null) {
            $project_expenses->delete();
        }
        if ($customer_payments != null) {
            $customer_payments->delete();
        }

        return response()->json(['success' => 'Project Deleted successfully'], 201);

    }

    //get all projects
    function getallprojects()
    {
        //$projects = Project::all();;
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_projects');
        $stmt->execute();

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['projects' => $projects, 'users' => $users], 201);
    }

    public
    function uploadProjectAttachments(Request $request)
    {
        $id = $request->input('id');

        $files = $request->input('files');

        $project_attachments = array();

        foreach ($files as $f) {

            $base64_image = $f['encrypted_name']; // your base64 encoded
            preg_match("/data:image\/(.*?);/", $base64_image, $image_extension);
            @list($type, $file_data) = explode(';', $base64_image);
            @list(, $file_data) = explode(',', $file_data);


            $imageName = 'file_' . time() . '_' . rand(pow(10, 3 - 1), pow(10, 3) - 1) . '.' . $image_extension[1];
//            $date = new \DateTime();
//            $imageName = $date->getTimestamp(). '.' . $image_extension[1];

            Storage::disk('local')->put('public/Uploads/' . $imageName, base64_decode($file_data));

            $attachment = new \stdClass();
            $attachment->encrypted_name = $imageName;
            $attachment->original_name = $f['original_name'];

            $project_attachment = new ProjectAttachment();
            if ($id != null) {
                $project_attachment->encrypted_name = $attachment->encrypted_name;
                $project_attachment->original_name = $attachment->original_name;
                $project_attachment->project_id = $id;
                $project_attachment->save();

                $attachment->id = $project_attachment->id;
            }

            $project_attachments [] = $attachment;

        }

        return response()->json(['project_attachments' => $project_attachments], 201);

    }

    function deleteProjectAttachment(Request $request)
    {
        if ($request->input('id') != null) {
            $project_attachment = ProjectAttachment::find($request->input('id'));
            Storage::delete($project_attachment->encrypted_name);
            $project_attachment->delete();

            Storage::delete('/public/Uploads/' . $request->input('encrypted_name'));

//            unlink(storage_path('app/public/Uploads/'.$request->input('encrypted_name')));
        }
        return response()->json(['success' => 'Deleted successfully'], 202);
    }


    //get estimate
    public
    function getestimate($pid)
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_estimate_details(?)');
        $stmt->execute(array($pid));

        $project_information = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass')[0];
        $stmt->nextRowset();

        $estimate = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $estimate = $estimate[0];

        $other_descriptions = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $interior_paints = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $interior_description = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $estimate = new ProjectDetails();
//        $estimate = ProjectDetails::find($id);
//        $other_descriptions = OtherDescription::where('project_details_id', $estimate->id)->get();
//        $interior_paints = InteriorPaints::where('project_details_id', $estimate->id)->get();
//        $interior_description = InteriorDescription::where('project_details_id', $estimate->id)->get();
//        $estimateArray = ProjectDetails::where('dynamicProjectsArray', $id)->get();
//        $estimateArr = ProjectDetails::find($estimate);
//        $estimateArray = $estimate->;

        return response()->json(['project_information' => $project_information, 'estimate' => $estimate, 'other_descriptions' => $other_descriptions,
            'interiorpaints' => $interior_paints, 'interior_description' => $interior_description], 200);
    }


    public
    function getworkorder($id)
    {
        $estimate = ProjectDetails::find($id);
        $other_descriptions = OtherDescription::where('project_details_id', $estimate->id)->get();
        $interior_paints = InteriorPaints::where('project_details_id', $estimate->id)->get();
        $interior_description = InteriorDescription::where('project_details_id', $estimate->id)->get();
        return response()->json(['estimate' => $estimate, 'other_descriptions' => $other_descriptions,
            'interiorpaints' => $interior_paints, 'interior_description' => $interior_description], 200);
    }


    //get all estimates
    public function getallestimates()
    {
        $estimatedetails = ProjectDetails::all();
        return response()->json(['estimatedetails' => $estimatedetails], 201);
    }

//update the estimate
    public
    function updateestimate(Request $request, $id, $user_id)
    {
//        $validator = Validator::make($request->all(), [
//            'project_id' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            $error = $validator->errors()->first();
//            return response()->json(['error' => $error], 403);
//        }

        $project = Project::find($request->input('project_id'));
//        $project_details = DB::table('project_details')->where('project_id', $request->input('project_id'))->first();

//            if ($project_details != null) {
        $prj_detail = ProjectDetails::find($request->input('id'));
//        }
//        else {
//            $prj_detail = new ProjectDetails();
//            $prj_detail->project_id = $request->input('project_id');
//            $prj_detail->save();
//        }

        //interior project type
        if ($project->project_type_id == 2) {

//            $interior_paints = $request->input('dynamicPaintAreaArray');
//            if ($interior_paints != null && sizeof($interior_paints) > 0) {
//                $prj_detail->interiorpaints()->delete();
//                $prj_detail->interiorpaints()->createMany($interior_paints);
//            }
//
//            $interior_description = $request->input('dynamicNotesAreaArray');
//            if ($interior_description != null && sizeof($interior_description) > 0) {
//                $prj_detail = ProjectDetails::find($id);
//                $prj_detail->interiordescription()->createMany($interior_description);
//            }

            $prj_detail->is_fix_sheetrock = $request->input('is_fix_sheetrock');
            $prj_detail->is_skim_sheetrock = $request->input('is_skim_sheetrock');
            $prj_detail->is_strip_wallpaper = $request->input('is_strip_wallpaper');
            $prj_detail->carpentery = $request->input('carpentery');
            $prj_detail->carpentery_amount = $request->input('carpentery_amount');
            $prj_detail->is_porter = $request->input('is_porter');
            $prj_detail->is_duron = $request->input('is_duron');
            $prj_detail->is_glidden = $request->input('is_glidden');
            $prj_detail->is_benjamin_moore = $request->input('is_benjamin_moore');
            $prj_detail->is_sherwin_williams = $request->input('is_sherwin_williams');
            $prj_detail->is_other_paint = $request->input('is_other_paint');
            $prj_detail->ceilings = $request->input('ceilings');
            $prj_detail->walls = $request->input('walls');
            $prj_detail->trims = $request->input('trims');
            $prj_detail->others = $request->input('others');


            $prj_detail->is_interior_paint_material_included = $request->input('is_interior_paint_material_included');
            $prj_detail->interior_deposit_amount = $request->input('interior_deposit_amount');
            $prj_detail->interior_discount_amount = $request->input('interior_discount_amount');
            $prj_detail->interior_discount_type = $request->input('interior_discount_type');
            $prj_detail->interior_payment_method = $request->input('interior_payment_method');
            $prj_detail->interior_special_notes = $request->input('interior_special_notes');
            $prj_detail->interior_final_price = $request->input('interior_final_price');
            $prj_detail->interior_subtotal = $request->input('interior_subtotal');
            $prj_detail->interior_discount_value = $request->input('interior_discount_value');
            $prj_detail->interior_net_amount = $request->input('interior_net_amount');
            $prj_detail->final_price = $request->input('interior_final_price');

            //added_today
            //in interior condition
            $projectObj = Project::find($request->input('project_id'));
            $customer_id = $projectObj->customer_id;

            $customer_interactions = new CustomerInteractions();
            $customer_interactions->customer_id = $customer_id;
            $customer_interactions->project_id = $projectObj->id;
            $customer_interactions->interaction_type = 'User Action';
            $customer_interactions->interaction_notes = 'Estimate for Interior Project ' . $projectObj->id . ' updated';
            $customer_interactions->is_show_projects = true;
            if ($request->input('final_price') != null) {
                $customer_interactions->interaction_notes = 'Estimate for Interior Project ' . $projectObj->id . ' updated ' . ' Final Price changed to $' . $request->input('final_price');
                $customer_interactions->is_show_projects = true;
            }
            $customer_interactions->performed_by_id = $user_id;
            $customer_interactions->save();

            $projectObj->status_id = 3;
            $projectObj->save();

        }
        //exterior project type
        else if ($project->project_type_id == 3) {

            $prj_detail = ProjectDetails::find($id);
            $prj_detail->is_house = $request->input('is_house');
            $prj_detail->is_gutters = $request->input('is_gutters');
            $prj_detail->is_decks = $request->input('is_decks');
            $prj_detail->is_driveway = $request->input('is_driveway');
            $prj_detail->is_patio = $request->input('is_patio');
            $prj_detail->is_fence = $request->input('is_fence');
            $prj_detail->pressure_wash_notes = $request->input('pressure_wash_notes');
            $prj_detail->pressure_wash_price = $request->input('pressure_wash_price');
            $prj_detail->is_scrape_prime = $request->input('is_scrape_prime');
            $prj_detail->is_prime_window = $request->input('is_prime_window');
            $prj_detail->is_putty = $request->input('is_putty');
            $prj_detail->is_scrape_price = $request->input('is_scrape_price');

            $prj_detail->is_windows = $request->input('is_windows');
            $prj_detail->is_sliding = $request->input('is_sliding');
            $prj_detail->is_ground_doors = $request->input('is_ground_doors');
            $prj_detail->is_cover_plant = $request->input('is_cover_plant');
            $prj_detail->is_doors = $request->input('is_doors');
            $prj_detail->is_cornice = $request->input('is_cornice');
            $prj_detail->is_brick = $request->input('is_brick');
            $prj_detail->is_metal = $request->input('is_metal');
            $prj_detail->is_reglaze_windows = $request->input('is_reglaze_windows');
            $prj_detail->is_silicone_caulk_notes = $request->input('is_silicone_caulk_notes');
            $prj_detail->recaulk = $request->input('recaulk');
            $prj_detail->is_windows_price = $request->input('is_windows_price');

            $prj_detail->is_stucco = $request->input('is_stucco');
            $prj_detail->is_stucco_brick = $request->input('is_stucco_brick');
            $prj_detail->is_stucco_metal = $request->input('is_stucco_metal');
            $prj_detail->is_concrete = $request->input('is_concrete');
            $prj_detail->is_bay_tops_notes = $request->input('is_bay_tops_notes');
            $prj_detail->caulk = $request->input('caulk');
            $prj_detail->stucco_price = $request->input('stucco_price');

            $prj_detail->is_prime_siding = $request->input('is_prime_siding');
            $prj_detail->is_prime_trim = $request->input('is_prime_trim');
            $prj_detail->is_prime_windows = $request->input('is_prime_windows');
            $prj_detail->is_prime_new_wood = $request->input('is_prime_new_wood');
            $prj_detail->is_prime_brick = $request->input('is_prime_brick');
            $prj_detail->is_prime_metal = $request->input('is_prime_metal');
            $prj_detail->prime_coats = $request->input('prime_coats');
            $prj_detail->prime_gallons = $request->input('prime_gallons');
            $prj_detail->prime_notes = $request->input('prime_notes');
            $prj_detail->paint = $request->input('paint');
            $prj_detail->prime_price = $request->input('prime_price');
            $prj_detail->prime_assignment = $request->input('prime_assignment');

            $prj_detail->siding_type = $request->input('siding_type');
            $prj_detail->siding_assigned_to = $request->input('siding_assigned_to');
            $prj_detail->siding_color = $request->input('siding_color');
            $prj_detail->siding_paint = $request->input('siding_paint');
            $prj_detail->siding_coats = $request->input('siding_coats');
            $prj_detail->siding_gallons = $request->input('siding_gallons');
            $prj_detail->siding_finish = $request->input('siding_finish');
            $prj_detail->siding_price = $request->input('siding_price');

            $prj_detail->trim_type = $request->input('trim_type');
            $prj_detail->trim_assigned_to = $request->input('trim_assigned_to');
            $prj_detail->trim_color = $request->input('trim_color');
            $prj_detail->trim_paint = $request->input('trim_paint');
            $prj_detail->trim_coat = $request->input('trim_coat');
            $prj_detail->trim_gallon = $request->input('trim_gallon');
            $prj_detail->trim_finish = $request->input('trim_finish');
            $prj_detail->trim_price = $request->input('trim_price');

            $prj_detail->shutter_type = $request->input('shutter_type');
            $prj_detail->shutter_assigned_to = $request->input('shutter_assigned_to');
            $prj_detail->shutter_color = $request->input('shutter_color');
            $prj_detail->shutter_paint = $request->input('shutter_paint');
            $prj_detail->shutter_coats = $request->input('shutter_coats');
            $prj_detail->shutter_gallons = $request->input('shutter_gallons');
            $prj_detail->shutter_finish = $request->input('shutter_finish');
            $prj_detail->shutter_price = $request->input('shutter_price');

            $prj_detail->is_front_door_prime = $request->input('is_front_door_prime');
            $prj_detail->is_front_door_paint = $request->input('is_front_door_paint');
            $prj_detail->front_door_coats = $request->input('front_door_coats');
            $prj_detail->front_door_gallons = $request->input('front_door_gallons');
            $prj_detail->front_door_notes = $request->input('front_door_notes');
            $prj_detail->front_door_price = $request->input('front_door_price');

            $prj_detail->is_bay_tops_paint = $request->input('is_bay_tops_paint');
            $prj_detail->is_bay_tops_copper = $request->input('is_bay_tops_copper');
            $prj_detail->iron_railing_strip = $request->input('iron_railing_strip');
            $prj_detail->iron_railing_prime = $request->input('iron_railing_prime');
            $prj_detail->iron_railing_paint = $request->input('iron_railing_paint');
            $prj_detail->iron_railing_notes = $request->input('iron_railing_notes');
            $prj_detail->bay_tops_price = $request->input('bay_tops_price');

            $prj_detail->is_porch_outside = $request->input('is_porch_outside');
            $prj_detail->is_porch_inside = $request->input('is_porch_inside');
            $prj_detail->is_porch_ceiling = $request->input('is_porch_ceiling');
            $prj_detail->is_porch_cover = $request->input('is_porch_cover');
            $prj_detail->is_porch_floor = $request->input('is_porch_floor');
            $prj_detail->is_porch_seal = $request->input('is_porch_seal');
            $prj_detail->is_porch_stain = $request->input('is_porch_stain');
            $prj_detail->is_porch_paint = $request->input('is_porch_paint');
            $prj_detail->porch_price = $request->input('porch_price');

            $prj_detail->is_decks_clean = $request->input('is_decks_clean');
            $prj_detail->is_decks_seal = $request->input('is_decks_seal');
            $prj_detail->is_decks_prime = $request->input('is_decks_prime');
            $prj_detail->is_decks_paint = $request->input('is_decks_paint');
            $prj_detail->is_decks_stain = $request->input('is_decks_stain');
            $prj_detail->decks_color = $request->input('decks_color');
            $prj_detail->decks_coats = $request->input('decks_coats');
            $prj_detail->decks_assigned_to = $request->input('decks_assigned_to');
            $prj_detail->decks_paint = $request->input('decks_paint');
            $prj_detail->decks_finish = $request->input('decks_finish');
            $prj_detail->decks_gallons = $request->input('decks_gallons');
            $prj_detail->decks_price = $request->input('decks_price');

            $prj_detail->is_seal_cracks = $request->input('is_seal_cracks');
            $prj_detail->is_seal_around_trim = $request->input('is_seal_around_trim');
            $prj_detail->is_seal_dow_corning = $request->input('is_seal_dow_corning');
            $prj_detail->seal_color = $request->input('seal_color');
            $prj_detail->seal_coats = $request->input('seal_coats');
            $prj_detail->seal_price = $request->input('seal_price');

            $prj_detail->is_elastromeric_paint = $request->input('is_elastromeric_paint');
            $prj_detail->is_spray_black_roll = $request->input('is_spray_black_roll');
            $prj_detail->is_paint_stucco_trim = $request->input('is_paint_stucco_trim');
            $prj_detail->paint_coats = $request->input('paint_coats');
            $prj_detail->paint_gallons = $request->input('paint_gallons');
            $prj_detail->paint_assigned_to = $request->input('paint_assigned_to');
            $prj_detail->paint_color = $request->input('paint_color');
            $prj_detail->paint_price = $request->input('paint_price');
            $prj_detail->paint_finish = $request->input('paint_finish');
            $prj_detail->paint_notes = $request->input('paint_notes');
            $prj_detail->carpentry = $request->input('carpentry');
            $prj_detail->carpentry_price = $request->input('carpentry_price');
            $prj_detail->others_notes = $request->input('others_notes');
            $prj_detail->other_price = $request->input('other_price');

            $prj_detail->is_price_include_paint_material = $request->input('is_price_include_paint_material');
            $prj_detail->price_subtotal = $request->input('price_subtotal');
            $prj_detail->estimate_subtotal=$request->input('exterior_net_amount');
            $prj_detail->price_subtotal = $request->input('price_subtotal');
            $prj_detail->exterior_discount_type = $request->input('exterior_discount_type');
            $prj_detail->exterior_discount_value = $request->input('exterior_discount_value');
            $prj_detail->exterior_net_amount = $request->input('exterior_net_amount');
            $prj_detail->exterior_deposit_amount = $request->input('exterior_deposit_amount');
            $prj_detail->exterior_discount_amount = $request->input('exterior_discount_amount');
            $prj_detail->exterior_payment_method = $request->input('exterior_payment_method');
            $prj_detail->exterior_payment_amount = $request->input('exterior_payment_amount');
            $prj_detail->exterior_special_notes = $request->input('exterior_special_notes');

            $prj_detail->final_price = $request->input('exterior_price');

            //added_today
            // in exterior condition
            $projectObj = Project::find($request->input('project_id'));
            $customer_id = $projectObj->customer_id;

            $customer_interactions = new CustomerInteractions();
            $customer_interactions->customer_id = $customer_id;
            $customer_interactions->project_id = $projectObj->id;
            $customer_interactions->interaction_type = 'User Action';
            $customer_interactions->interaction_notes = 'Estimate for Exterior Project ' . $projectObj->id . ' updated';
            $customer_interactions->is_show_projects = true;
            if ($request->input('final_price') != null) {
                $customer_interactions->interaction_notes = 'Estimate for Exterior Project ' . $projectObj->id . ' updated' . 'Final Price changed to: ' . $request->input('final_price');
                $customer_interactions->is_show_projects = true;
            }
            $customer_interactions->performed_by_id = $user_id;
            $customer_interactions->save();

            $projectObj->status_id = 3;
            $projectObj->save();


        }
        //other project type
        else if ($project->project_type_id == 4 || $project->project_type_id == 5 || $project->project_type_id == 6) {


            $prj_detail->estimate_description = $request->input('estimate_description');
            $prj_detail->is_paint_material_included = $request->input('is_paint_material_included');
            $prj_detail->estimate_description_amount = $request->input('estimate_description_amount');
            $prj_detail->estimate_subtotal = $request->input('estimate_subtotal');
            $prj_detail->estimate_discount_value = $request->input('estimate_discount_value');
            $prj_detail->discount_type = $request->input('discount_type');
            $prj_detail->discount_payment_method = $request->input('discount_payment_method');
            $prj_detail->deposit_amount = $request->input('deposit_amount');
            $prj_detail->discount_amount = $request->input('discount_amount');
            $prj_detail->estimate_net_amount = $request->input('estimate_net_amount');
            $prj_detail->special_notes = $request->input('special_notes');
            $prj_detail->final_price = $request->input('final_price');


            //added_today
            //in other condition
            $projectObj = Project::find($request->input('project_id'));
            $customer_id = $projectObj->customer_id;

            $customer_interactions = new CustomerInteractions();
            $customer_interactions->customer_id = $customer_id;
            $customer_interactions->project_id = $projectObj->id;
            $customer_interactions->interaction_type = 'User Action';
            $customer_interactions->interaction_notes = 'Estimate for Other Project ' . $projectObj->id . ' updated';
            $customer_interactions->is_show_projects = true;
            if ($request->input('final_price') != null) {
                $customer_interactions->interaction_notes = 'Estimate for Other Project ' . $projectObj->id . ' updated' . 'Final Price changed to: ' . $request->input('final_price');
                $customer_interactions->is_show_projects = true;
            }
            $customer_interactions->performed_by_id = $user_id;
            $customer_interactions->save();

            $projectObj->status_id = 3;
            $projectObj->save();

        }

        $prj_detail->save();

        $project->total_cost = $request->input('final_price');
        $project->status_id = 3;
        $project->save();

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $project->customer_id;
        $customer_interactions->project_id = $projectObj->id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Project ' . $project->id . ' estimate ' . 'updated ' . ' final price changed to $' . $project->total_cost;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->save();

        return response()->json(['success' => 'Estimate updated successfully', 'projectdetail' => $prj_detail], 201);

    }

//get meta data for project
    public function getprojectmeta($user_id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_project_meta(?)');

        $stmt->execute(array($user_id));
        $project_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $statuses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $potential_types = $stmt->fetchAll(PDO ::FETCH_CLASS, 'stdClass');

//        $stmt->nextRowset();
//        $users = $stmt->fetchAll(PDO ::FETCH_CLASS, 'stdClass');

//        $stmt->nextRowset();
//        $estimators = $stmt->fetchAll(PDO ::FETCH_CLASS, 'stdClass');

//      $stmt->nextRowset();
//       $crews = $stmt->fetchAll(PDO ::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $cities = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $states = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

//        $stmt->nextRowset();
//        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        return response()->json([
            'project_types' => $project_types,
            'statuses' => $statuses,
            'potential_types' => $potential_types,
//          'users' => $users,
//            'estimators' => $estimators,
//            'crews' => $crews,
            'cities' => $cities,
            'states' => $states,
  //          'customers' => $customers
        ],
            200);
    }

    public function getCustomerprojectmeta($customer_id,$user_id)
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_customer_project_meta(?,?)');

        $stmt->execute(array($user_id,$customer_id));
        $project_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $statuses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $potential_types = $stmt->fetchAll(PDO ::FETCH_CLASS, 'stdClass');




        $stmt->nextRowset();
        $states = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $cities = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $customer = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');


        return response()->json([
            'project_types' => $project_types,
            'statuses' => $statuses,
            'potential_types' => $potential_types,
            'cities' => $cities,
            'states' => $states,
            'customer'=>$customer
        ],
            200);
    }

//save sub project
    public
    function savesubprojects(Request $request, $user_id)
    {
        $subprojectObj = new SubProject();

        $subprojectObj->project_id = $request->input('project_id');
        $subprojectObj->number = $request->input('number');
        $subprojectObj->name = $request->input('name');
        $subprojectObj->status = $request->input('status');
        $subprojectObj->crew_id = $request->input('crew_id');
        $subprojectObj->work_start_date = $request->input('work_start_date');
        $subprojectObj->work_end_date = $request->input('work_end_date');
        $subprojectObj->description = $request->input('description');
        $subprojectObj->notes = $request->input('notes');
        $subprojectObj->save();


        $project_details = DB::table('project_details')->where('project_id', $request->input('project_id'))->first();
        if ($project_details != null) {
            $prj_detail = ProjectDetails::find($project_details->id);
        } else {
            $prj_detail = new ProjectDetails();
            $prj_detail->project_id = $request->input('project_id');
        }

        $prj_detail->sub_projects = $prj_detail->sub_projects + 1;
        $prj_detail->save();

        $customer_interactions = new CustomerInteractions();
//        $ion =

        $projectObj = DB::Table('projects')->where('id', $subprojectObj->project_id)->first();
        $customer_id = $projectObj->customer_id;
        $customer_interactions->project_id = $projectObj->id;
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Sub Project ' . $subprojectObj->id . ' created of project ' . $projectObj->id;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->save();

        return response()->json(['success' => 'Sub - project saved successfully', 'subprojectObj' => $subprojectObj, 'project_details_id' => $prj_detail->id], 201);

    }

//update sub project
    public
    function updatesubprojects(Request $request, $id, $user_id)
    {

        $subprojectObj = SubProject::find($id);
        $subprojectObj->project_id = $request->input('project_id');
        $subprojectObj->number = $request->input('number');
        $subprojectObj->name = $request->input('name');
        $subprojectObj->status = $request->input('status');
        $old_crew_id = $subprojectObj->crew_id;
        $subprojectObj->crew_id = $request->input('crew_id');
        $subprojectObj->work_start_date = $request->input('work_start_date');
        $subprojectObj->work_end_date = $request->input('work_end_date');
        $subprojectObj->description = $request->input('description');
        $subprojectObj->notes = $request->input('notes');
        $subprojectObj->save();

        if ($old_crew_id != $subprojectObj->crew_id) {
            $apt = Calendars::where('is_sub_project', 1)->where('project_for_appointment', $subprojectObj->id)->where('appointment_for', $old_crew_id)->orderBy('id', 'desc')->first();
            if ($apt != null) {
                $apt->delete();
            }
            $subprojectObj->work_start_date = null;
            $subprojectObj->work_end_date = null;
            $subprojectObj->save();
        }


        $project_details = DB::table('project_details')->where('project_id', $request->input('project_id'))->first();
        if ($project_details != null) {
            $prj_detail = ProjectDetails::find($project_details->id);
        } else {
            $prj_detail = new ProjectDetails();
            $prj_detail->project_id = $request->input('project_id');
        }

        $prj_detail->sub_projects = $prj_detail->sub_projects + 1;
        $prj_detail->save();

        $customer_interactions = new CustomerInteractions();
        $projectObj = DB::Table('projects')->where('id', $subprojectObj->project_id)->first();
        $customer_id = $projectObj->customer_id;

        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->project_id = $projectObj->id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Sub Project ' . $subprojectObj->id . ' updated of project ' . $projectObj->id;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->save();

        return response()->json(['success' => 'Sub - project updated successfully', 'subprojectObj' => $subprojectObj, 'project_details_id' => $prj_detail->id], 201);

    }


//get meta data for sub-project(s)
    public
    function getsubprojectsmeta($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_Sub_projects_meta(?)');
        $stmt->execute(array($id));

        $sub_projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['sub_projects' => $sub_projects], 201);
    }


    public
    function deletesubproject($id)
    {
        $sub_project = SubProject::find($id);

        $apts = Calendars::where('project_for_appointment', $id)->get();
        if (sizeof($apts) > 0) {
            foreach ($apts as $a) {
                $a->delete();
            }
        }

        $sub_project->delete();

        return response()->json(['success' => 'Sub Project Deleted Successfully', 201]);
    }


//save payment

    public
    function getsubprojectitem($id)
    {
        $project_details = ProjectDetails::find($id);
        $other_descriptions = $project_details->otherdescription()->get();

        $sub_project_items_array = array();
        if (sizeof($other_descriptions) > 0) {
            foreach ($other_descriptions as $od) {
                $sub_project_item_obj = new \stdClass();
                $sub_project_item_obj->item_name = $od->other_project_descriptions;
                $sub_project_item = SubProjectItems::where('item_id', $od->id)->first();
                if ($sub_project_item != null) {
                    $sub_project_item_obj->id = $sub_project_item->id;
                    $sub_project_item_obj->item_id = $sub_project_item->item_id;
                    $sub_project_item_obj->sub_project_id = $sub_project_item->sub_project_id;
                } else {
                    $sub_project_item_obj->id = null;
                    $sub_project_item_obj->item_id = $od->id;
                    $sub_project_item_obj->sub_project_id = null;
                }
                $sub_project_items_array [] = $sub_project_item_obj;
            }
        }
        return response()->json(['sub_project_items_array' => $sub_project_items_array], 200);
    }

    public
    function savesubprojectitems(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'sub_projects_id' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            $error = $validator->errors()->first();
//            return response()->json(['error' => $error], 403);
//        }

        $sub_projects_items_array = $request->input('SubProjectItemsArray');

        $sub_projects_items = array();
        if (sizeof($sub_projects_items_array) > 0) {
            foreach ($sub_projects_items_array as $item) {
                if ($item['id'] != null) {
                    $sub_project_items = SubProjectItems::find($item['id']);

                } else {
                    $sub_project_items = new SubProjectItems();
                }
                $sub_project_items->sub_project_id = $item['sub_project_id'];
                $sub_project_items->item_id = $item['item_id'];
                $sub_project_items->save();

                $sub_projects_item_obj = new \stdClass();
                $sub_projects_item_obj->id = $sub_project_items->id;
                $sub_projects_item_obj->item_id = $sub_project_items->item_id;
                $sub_projects_item_obj->sub_project_id = $sub_project_items->sub_project_id;
                $sub_projects_item_obj->item_name = $item['item_name'];

                $sub_projects_items [] = $sub_projects_item_obj;
            }

            return response()->json(['success' => 'Saved successfully',
                'sub_projects_items_array' => $sub_projects_items], 201);
        }


    }

//save payment
    public
    function savepayment(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }

        $customer_payment = new CustomerPayment();

        $customer_payment->project_id = $request->input('project_id');
        $customer_payment->payment_date = $request->input('payment_date');
        $customer_payment->payment_method = $request->input('payment_method');
        $customer_payment->payment_amount = $request->input('payment_amount');
        $customer_payment->cheque_number = $request->input('cheque_number');
        $customer_payment->payment_collected_by = $request->input('payment_collected_by');
        $customer_payment->payment_notes = $request->input('payment_notes');
        $customer_payment->save();

        $object_type = DB::table('object_type')->where('ObjectTypeID', $customer_payment->payment_method)->first();
        $payment_method = $object_type->ObjectName;

        $user = User::find($customer_payment->payment_collected_by);
        $payment_collected_by = $user->first_name . ' ' . $user->last_name;

        $project_obj = ProjectDetails::find($customer_payment->project_id);
        $customer_payments = CustomerPayment::select('payment_amount')->where('project_id', $request->input('project_id'))->get();
        $amount = 0.0;
        if (sizeof($customer_payments) > 0) {
            foreach ($customer_payments as $cp) {
                $amount = $amount + $cp->payment_amount;
            }
        }

        $project = Project::find($customer_payment->project_id);

        $final_price = 0.0;
        if ($project->project_type_id == 3) {

            $final_price = $project_obj->exterior_price;

        } else if ($project->project_type_id == 2) {
            $final_price = $project_obj->interior_final_price;
        } else {
            $final_price = $project_obj->final_price;
        }

        if ($amount >= floatval($final_price)) {
            $project->status_id = 9;
            $project->save();
        }


        $customer_interactions = new CustomerInteractions();
        $projectObj = DB::Table('projects')->where('id', $customer_payment->project_id)->first();
        $customer_id = $projectObj->customer_id;
        $customer_interactions->project_id = $customer_payment->project_id;
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Customer paid $' . $customer_payment->payment_amount . ' for project ' . $customer_payment->project_id;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->save();

        //added_today
        $projectObj = Project::find($request->input('project_id'));
        $customer_id = $projectObj->customer_id;

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->project_id = $customer_payment->project_id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Payment made for Project ' . $customer_payment->project_id . ' in the amount of $' . $customer_payment->payment_amount;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_payments = true;
        $customer_interactions->save();

        return response()->json(['success' => 'Payment saved successfully', 'customer_payment' => $customer_payment,
            'customer_payment_id' => $customer_payment->id,
            'customer_payments' => $customer_payments,
            'project_obj' => $project_obj, 'paid_amount' => $amount, 'final_price' => floatval($final_price),
            'payment_method' => $payment_method, 'payment_collected_by' => $payment_collected_by
        ], 201);
    }

    //get customer payment
    public
    function getcustomerpayment($id)
    {
        $payment = CustomerPayment::where('project_id', $id)->get();
//      $project_detail_id = $prj_detail->id;

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_all_customer_payments(?)');
        $stmt->execute(array($id));

        $customer_payments = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();


        return response()->json(['customer_payment' => $payment, 'customer_payments' => $customer_payments], 201);


    }

//get  single payment of the customer
    public
    function getonlyonecustomerpayment($id)
    {
        $payment = CustomerPayment::where('id', $id)->first();
        return response()->json(['customer_payment' => $payment], 201);
    }

    public
    function updatecustomerpayment(Request $request, $id, $user_id)
    {
        $customer_payment = CustomerPayment::find($id);
        $customer_payment->project_id = $request->input('project_id');
        $customer_payment->payment_date = $request->input('payment_date');
        $customer_payment->payment_method = $request->input('payment_method');
        $customer_payment->payment_amount = $request->input('payment_amount');
        $customer_payment->cheque_number = $request->input('cheque_number');
        $customer_payment->payment_collected_by = $request->input('payment_collected_by');
        $customer_payment->payment_notes = $request->input('payment_notes');
        $customer_payment->save();

        $project_obj = ProjectDetails::find($customer_payment->project_id);
        $customer_payments = CustomerPayment::select('payment_amount')->where('project_id', $request->input('project_id'))->get();

        $amount = 0.0;
        if (sizeof($customer_payments) > 0) {
            foreach ($customer_payments as $cp) {
                $amount = $amount + $cp->payment_amount;
            }
        }

        $project = Project::find($customer_payment->project_id);
        $final_price = 0.0;
        if ($project->project_type_id == 3) {
            $final_price = $project_obj->exterior_price;

        } else if ($project->project_type_id == 2) {
            $final_price = $project_obj->interior_final_price;
        } else {
            $final_price = $project_obj->final_price;
        }

        if (floatval($final_price) >= $amount) {

            $project->status_id = 9;
            $project->save();
        }


        $customer_interactions = new CustomerInteractions();
        $projectObj = DB::Table('projects')->where('id', $customer_payment->project_id)->first();
        $customer_id = $projectObj->customer_id;
        $customer_interactions->project_id = $customer_payment->project_id;
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Customer payment changed to $' . $customer_payment->payment_amount . ' for project ' . $customer_payment->project_id;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->save();


        return response()->json(['success' => 'Payment updated successfully'], 201);
    }

//save expense
    public
    function saveexpense(Request $request, $user_id)
    {
//        $project_details = DB::table('project_details')->where('project_id', $request->input('project_id'))->first();
//
//        if ($project_details != null) {
//            $prj_detail = ProjectDetails::find($project_details->id);
//        } else {
//            $prj_detail = new ProjectDetails();
//            $prj_detail->project_id = $request->input('project_id');
//        }
//        $prj_detail->expense_type = $request->input('expense_type');
//        $prj_detail->pay_to = $request->input('pay_to');
//        $prj_detail->description = $request->input('description');
//        $prj_detail->expense_notes = $request->input('expense_notes');
//        $prj_detail->expense_date = $request->input('expense_date');
//        $prj_detail->paid_date = $request->input('paid_date');
//        $prj_detail->ordered_by = $request->input('ordered_by');
//        $prj_detail->status = $request->input('status');
//        $prj_detail->save();
//
//        return response()->json(['success' => 'Expense saved successfully', 'projectdetail' => $prj_detail], 201);
//        $project_expenses = DB::table('project_expenses')->where('project_id', $request->input('project_id'))->first();
//
//        if ($project_expenses != null) {
//            $prj_expenses = ProjectExpenses::find($project_expenses->id);
//        } else {
//            $prj_expenses = new ProjectExpenses();
//            $prj_expenses->project_id = $request->input('project_id');
//        }

        $prj_expenses = new ProjectExpenses();
        $prj_expenses->project_id = $request->input('project_id');
        $prj_expenses->expense_type = $request->input('expense_type');
        $prj_expenses->pay_to = $request->input('pay_to');
        $prj_expenses->collected_by = $request->input('collected_by');
        $prj_expenses->description = $request->input('description');
        $prj_expenses->expense_notes = $request->input('expense_notes');
        $prj_expenses->expense_date = $request->input('expense_date');
        $prj_expenses->paid_date = $request->input('paid_date');
        $prj_expenses->ordered_by = $request->input('ordered_by');
        $prj_expenses->status = $request->input('status');

        $prj_expenses->save();

        $customer_interactions = new CustomerInteractions();
        $projectObj = DB::Table('projects')->where('id', $prj_expenses->project_id)->first();
        $customer_id = $projectObj->customer_id;
        $customer_interactions->project_id = $prj_expenses->project_id;
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Expense ' . $prj_expenses->id . ' created against ' . $prj_expenses->project_id;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->save();


        return response()->json(['success' => 'Expense saved successfully', 'projectexpensedetail' => $prj_expenses], 201);

    }

// update expense and expense items
    public
    function updateexpense(Request $request, $id, $user_id)
    {

        $prj_expenses = ProjectExpenses::find($id);
        $prj_expenses->project_id = $request->input('project_id');
        $prj_expenses->expense_type = $request->input('expense_type');
        $prj_expenses->pay_to = $request->input('pay_to');
        $prj_expenses->collected_by = $request->input('collected_by');
        $prj_expenses->description = $request->input('description');
        $prj_expenses->expense_notes = $request->input('expense_notes');
        $prj_expenses->expense_date = $request->input('expense_date');
        $prj_expenses->paid_date = $request->input('paid_date');
        $prj_expenses->ordered_by = $request->input('ordered_by');
        $prj_expenses->status = $request->input('status');
        $prj_expenses->save();

        //added today
        $projectObj = Project::find($request->input('project_id'));
        $customer_id = $projectObj->customer_id;

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->project_id = $projectObj->project_id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Expense Created ' . ' for project ' . $prj_expenses->project_id;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_expenses = true;
        $customer_interactions->save();

        return response()->json(['success' => 'Expense updated successfully'], 201);
    }


    /*public function getExpenseMeta($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_expense_meta(?)');
        $stmt->execute(array($id));

        $user_meta = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $vendor_meta = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['user_meta' => $user_meta, 'vendor_meta' => $vendor_meta], 201);

    }

*/
    //save expense items
    public
    function saveexpenseitems(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'expense_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }

//        $expense_items = $request->input('expenseItemsArray');
//        $prj_expense = ProjectExpenses::find($project_expenses->id);
//        if ($expense_items != null && sizeof($expense_items) > 0) {
//            $prj_expense->expenseitems()->createMany($expense_items);
//        }

        $expense_item = new ExpenseItems();
        $expense_item->expense_id = $request->input('expense_id');
        $expense_item->product_name = $request->input('product_name');
        $expense_item->color = $request->input('color');
        $expense_item->description = $request->input('description');
        $expense_item->formula = $request->input('formula');
        $expense_item->size = $request->input('size');
        $expense_item->price = $request->input('price');
        $expense_item->quantity = $request->input('quantity');
        $expense_item->total_price = $request->input('total_price');
        $expense_item->save();

        $expense = ProjectExpenses::find($expense_item->expense_id);
        $expense->amount = $expense->amount + $expense_item->total_price;
        $expense->save();

        return response()->json(['success' => 'Expense item saved successfully', 'id' => $expense_item->id, 'amount' => $expense->amount], 201);
    }

//update expense items
    public
    function updateexpenseitems(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'expense_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }

        $expense_items = ExpenseItems::find($id);
        $expense_items->expense_id = $request->input('expense_id');
        $expense_items->product_name = $request->input('product_name');
        $expense_items->color = $request->input('color');
        $expense_items->description = $request->input('description');
        $expense_items->formula = $request->input('formula');
        $expense_items->size = $request->input('size');
        $expense_items->price = $request->input('price');
        $expense_items->quantity = $request->input('quantity');
        $expense_items->total_price = $request->input('total_price');
        $expense_items->save();

        $total_amount = 0;
        $expense = ProjectExpenses::find($expense_items->expense_id);
        $expense_items_array = ExpenseItems::where('expense_id', $expense_items->expense_id)->get();
        foreach ($expense_items_array as $e) {
            $total_amount = $total_amount + $e->total_price;
        }
        $expense->amount = $total_amount;
        $expense->save();

        return response()->json(['success' => 'Expense item updated successfully', 'expense_items' => $expense_items, 'amount' => $expense->amount], 201);

    }

//Delete Project Expense Items
    public
    function deleteexpenseitems($id)
    {
        $projectexpenseitems = ExpenseItems::find($id);

        $expense = ProjectExpenses::find($projectexpenseitems->expense_id);
        $expense->amount = $expense->amount - $projectexpenseitems->amount;

        $projectexpenseitems->delete();

        return response()->json(['success' => 'Expense Items deleted successfully'], 201);

    }

    //get all project expense items meta
    public
    function getallprojectexpenseitemsmeta($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_expense_items_paint_order_meta(?)');
        $stmt->execute(array($id));

        $expense_details = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $expense_details = $expense_details[0];

        $expense_items = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['expense_details' => $expense_details, 'expense_items' => $expense_items], 201);
    }


//get all project expenses meta
    public
    function getallprojectexpensesmeta($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_all_project_expenses(?)');
        $stmt->execute(array($id));

        $expenses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['project_expenses' => $expenses], 201);
    }

//get estimate
    public
    function getexpense($id)
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_expense_drop_down_meta(?)');
        $stmt->execute(array($id));

        $expense = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $expense = $expense[0];

        $expense_items = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

        $vendors = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();


//        $expense = ProjectExpenses::find($id);
//        $expense_items = ExpenseItems::where('expense_id', $id)->get();
//        $users = User::all();
//        $vendors = Vendor::all();
//'users' => $users,
        return response()->json(['expense' => $expense, 'expense_items' => $expense_items,  'vendors' => $vendors], 200);
    }

//save expense
    public
    function saveestimate(Request $request, $user_id)
    {
        $project_details = new ProjectDetails();

        $validator = Validator::make($request->all(), [
            'project_id' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }

        $project = Project::find($request->input('project_id'));
        $project_details = DB::table('project_details')->where('project_id', $request->input('project_id'))->first();

        if ($project_details != null) {
            $prj_detail = ProjectDetails::find($project_details->id);
        } else {
            $prj_detail = new ProjectDetails();
            $prj_detail->project_id = $request->input('project_id');
            $prj_detail->save();
        }

        //interior project type
        if ($project->project_type_id == 2) {

            $interior_paints = $request->input('dynamicPaintAreaArray');
            $interior_description = $request->input('dynamicNotesAreaArray');

            if ($interior_paints != null && sizeof($interior_paints) > 0) {
                $prj_detail->interiorpaints()->createMany($interior_paints);
            }

            if (sizeof($interior_description) > 0) {
                $prj_detail->interiordescription()->createMany($interior_description);
            }

            $prj_detail->is_fix_sheetrock = $request->input('is_fix_sheetrock');
            $prj_detail->is_skim_sheetrock = $request->input('is_skim_sheetrock');
            $prj_detail->is_strip_wallpaper = $request->input('is_strip_wallpaper');
            $prj_detail->carpentery = $request->input('carpentery');
            $prj_detail->carpentery_amount = $request->input('carpentery_amount');
            $prj_detail->is_porter = $request->input('is_porter');
            $prj_detail->is_duron = $request->input('is_duron');
            $prj_detail->is_glidden = $request->input('is_glidden');
            $prj_detail->is_benjamin_moore = $request->input('is_benjamin_moore');
            $prj_detail->is_sherwin_williams = $request->input('is_sherwin_williams');
            $prj_detail->is_other_paint = $request->input('is_other_paint');
            $prj_detail->ceilings = $request->input('ceilings');
            $prj_detail->walls = $request->input('walls');
            $prj_detail->trims = $request->input('trims');
            $prj_detail->others = $request->input('others');

            $prj_detail->is_interior_paint_material_included = $request->input('is_interior_paint_material_included');
            $prj_detail->interior_discount_amount = $request->input('interior_discount_amount');
            $prj_detail->interior_discount_type = $request->input('interior_discount_type');
            $prj_detail->interior_payment_method = $request->input('interior_payment_method');
            $prj_detail->interior_special_notes = $request->input('interior_special_notes');
            $prj_detail->interior_final_price = $request->input('interior_final_price');
            $prj_detail->interior_subtotal = $request->input('interior_subtotal');
            $prj_detail->interior_discount_value = $request->input('interior_discount_value');
            $prj_detail->interior_net_amount = $request->input('interior_net_amount');


            //added_today
            //in Interior condition
            $projectObj = Project::find($request->input('project_id'));
            $customer_id = $projectObj->customer_id;

            $customer_interactions = new CustomerInteractions();
            $customer_interactions->customer_id = $customer_id;
            $customer_interactions->project_id = $projectObj->id;
            $customer_interactions->interaction_type = 'User Action';
            $customer_interactions->interaction_notes = 'Interior Estimate created For Project ' . $projectObj->id;
            $customer_interactions->performed_by_id = $user_id;
            $customer_interactions->is_show_projects = true;
            $customer_interactions->save();


        } //exterior project type
        else if ($project->project_type_id == 3) {


            $prj_detail->is_house = $request->input('is_house');
            $prj_detail->is_gutters = $request->input('is_gutters');
            $prj_detail->is_decks = $request->input('is_decks');
            $prj_detail->is_driveway = $request->input('is_driveway');
            $prj_detail->is_patio = $request->input('is_patio');
            $prj_detail->is_fence = $request->input('is_fence');
            $prj_detail->pressure_wash_notes = $request->input('pressure_wash_notes');
            $prj_detail->pressure_wash_price = $request->input('pressure_wash_price');
            $prj_detail->is_scrape_prime = $request->input('is_scrape_prime');
            $prj_detail->is_prime_window = $request->input('is_prime_window');
            $prj_detail->is_putty = $request->input('is_putty');
            $prj_detail->is_scrape_price = $request->input('is_scrape_price');

            $prj_detail->is_windows = $request->input('is_windows');
            $prj_detail->is_sliding = $request->input('is_sliding');
            $prj_detail->is_ground_doors = $request->input('is_ground_doors');
            $prj_detail->is_cover_plant = $request->input('is_cover_plant');
            $prj_detail->is_doors = $request->input('is_doors');
            $prj_detail->is_cornice = $request->input('is_cornice');
            $prj_detail->is_brick = $request->input('is_brick');
            $prj_detail->is_metal = $request->input('is_metal');
            $prj_detail->is_reglaze_windows = $request->input('is_reglaze_windows');
            $prj_detail->is_silicone_caulk_notes = $request->input('is_silicone_caulk_notes');
            $prj_detail->recaulk = $request->input('recaulk');
            $prj_detail->is_windows_price = $request->input('is_windows_price');

            $prj_detail->is_stucco = $request->input('is_stucco');
            $prj_detail->is_stucco_brick = $request->input('is_stucco_brick');
            $prj_detail->is_stucco_metal = $request->input('is_stucco_metal');
            $prj_detail->is_concrete = $request->input('is_concrete');
            $prj_detail->is_bay_tops_notes = $request->input('is_bay_tops_notes');
            $prj_detail->caulk = $request->input('caulk');
            $prj_detail->stucco_price = $request->input('stucco_price');

            $prj_detail->is_prime_siding = $request->input('is_prime_siding');
            $prj_detail->is_prime_trim = $request->input('is_prime_trim');
            $prj_detail->is_prime_windows = $request->input('is_prime_windows');
            $prj_detail->is_prime_new_wood = $request->input('is_prime_new_wood');
            $prj_detail->is_prime_brick = $request->input('is_prime_brick');
            $prj_detail->is_prime_metal = $request->input('is_prime_metal');
            $prj_detail->prime_coats = $request->input('prime_coats');
            $prj_detail->prime_gallons = $request->input('prime_gallons');
            $prj_detail->prime_notes = $request->input('prime_notes');
            $prj_detail->paint = $request->input('paint');
            $prj_detail->prime_price = $request->input('prime_price');
            $prj_detail->prime_assignment = $request->input('prime_assignment');

            $prj_detail->siding_type = $request->input('siding_type');
            $prj_detail->siding_assigned_to = $request->input('siding_assigned_to');
            $prj_detail->siding_color = $request->input('siding_color');
            $prj_detail->siding_paint = $request->input('siding_paint');
            $prj_detail->siding_coats = $request->input('siding_coats');
            $prj_detail->siding_gallons = $request->input('siding_gallons');
            $prj_detail->siding_finish = $request->input('siding_finish');
            $prj_detail->siding_price = $request->input('siding_price');

            $prj_detail->trim_type = $request->input('trim_type');
            $prj_detail->trim_assigned_to = $request->input('trim_assigned_to');
            $prj_detail->trim_color = $request->input('trim_color');
            $prj_detail->trim_paint = $request->input('trim_paint');
            $prj_detail->trim_coat = $request->input('trim_coat');
            $prj_detail->trim_gallon = $request->input('trim_gallon');
            $prj_detail->trim_finish = $request->input('trim_finish');
            $prj_detail->trim_price = $request->input('trim_price');

            $prj_detail->shutter_type = $request->input('shutter_type');
            $prj_detail->shutter_assigned_to = $request->input('shutter_assigned_to');
            $prj_detail->shutter_color = $request->input('shutter_color');
            $prj_detail->shutter_paint = $request->input('shutter_paint');
            $prj_detail->shutter_coats = $request->input('shutter_coats');
            $prj_detail->shutter_gallons = $request->input('shutter_gallons');
            $prj_detail->shutter_finish = $request->input('shutter_finish');
            $prj_detail->shutter_price = $request->input('shutter_price');

            $prj_detail->is_front_door_prime = $request->input('is_front_door_prime');
            $prj_detail->is_front_door_paint = $request->input('is_front_door_paint');
            $prj_detail->front_door_coats = $request->input('front_door_coats');
            $prj_detail->front_door_gallons = $request->input('front_door_gallons');
            $prj_detail->front_door_notes = $request->input('front_door_notes');
            $prj_detail->front_door_price = $request->input('front_door_price');

            $prj_detail->is_bay_tops_paint = $request->input('is_bay_tops_paint');
            $prj_detail->is_bay_tops_copper = $request->input('is_bay_tops_copper');
            $prj_detail->iron_railing_strip = $request->input('iron_railing_strip');
            $prj_detail->iron_railing_prime = $request->input('iron_railing_prime');
            $prj_detail->iron_railing_paint = $request->input('iron_railing_paint');
            $prj_detail->iron_railing_notes = $request->input('iron_railing_notes');
            $prj_detail->bay_tops_price = $request->input('bay_tops_price');

            $prj_detail->is_porch_outside = $request->input('is_porch_outside');
            $prj_detail->is_porch_inside = $request->input('is_porch_inside');
            $prj_detail->is_porch_ceiling = $request->input('is_porch_ceiling');
            $prj_detail->is_porch_cover = $request->input('is_porch_cover');
            $prj_detail->is_porch_floor = $request->input('is_porch_floor');
            $prj_detail->is_porch_seal = $request->input('is_porch_seal');
            $prj_detail->is_porch_stain = $request->input('is_porch_stain');
            $prj_detail->is_porch_paint = $request->input('is_porch_paint');
            $prj_detail->porch_price = $request->input('porch_price');

            $prj_detail->is_decks_clean = $request->input('is_decks_clean');
            $prj_detail->is_decks_seal = $request->input('is_decks_seal');
            $prj_detail->is_decks_prime = $request->input('is_decks_prime');
            $prj_detail->is_decks_paint = $request->input('is_decks_paint');
            $prj_detail->is_decks_stain = $request->input('is_decks_stain');
            $prj_detail->decks_color = $request->input('decks_color');
            $prj_detail->decks_coats = $request->input('decks_coats');
            $prj_detail->decks_assigned_to = $request->input('decks_assigned_to');
            $prj_detail->decks_paint = $request->input('decks_paint');
            $prj_detail->decks_finish = $request->input('decks_finish');
            $prj_detail->decks_gallons = $request->input('decks_gallons');
            $prj_detail->decks_price = $request->input('decks_price');

            $prj_detail->is_seal_cracks = $request->input('is_seal_cracks');
            $prj_detail->is_seal_around_trim = $request->input('is_seal_around_trim');
            $prj_detail->is_seal_dow_corning = $request->input('is_seal_dow_corning');
            $prj_detail->seal_color = $request->input('seal_color');
            $prj_detail->seal_coats = $request->input('seal_coats');
            $prj_detail->seal_price = $request->input('seal_price');

            $prj_detail->is_elastromeric_paint = $request->input('is_elastromeric_paint');
            $prj_detail->is_spray_black_roll = $request->input('is_spray_black_roll');
            $prj_detail->is_paint_stucco_trim = $request->input('is_paint_stucco_trim');
            $prj_detail->paint_coats = $request->input('paint_coats');
            $prj_detail->paint_gallons = $request->input('paint_gallons');
            $prj_detail->paint_assigned_to = $request->input('paint_assigned_to');
            $prj_detail->paint_color = $request->input('paint_color');
            $prj_detail->paint_price = $request->input('paint_price');
            $prj_detail->paint_finish = $request->input('paint_finish');
            $prj_detail->paint_notes = $request->input('paint_notes');
            $prj_detail->carpentry = $request->input('carpentry');
            $prj_detail->carpentry_price = $request->input('carpentry_price');
            $prj_detail->others_notes = $request->input('others_notes');
            $prj_detail->other_price = $request->input('other_price');

            $prj_detail->is_price_include_paint_material = $request->input('is_price_include_paint_material');
            $prj_detail->price_subtotal = $request->input('price_subtotal');
            $prj_detail->exterior_discount_type = $request->input('exterior_discount_type');
            $prj_detail->exterior_discount_value = $request->input('exterior_discount_value');
            $prj_detail->exterior_net_amount = $request->input('exterior_net_amount');
            $prj_detail->exterior_discount_amount = $request->input('exterior_discount_amount');
            $prj_detail->exterior_payment_method = $request->input('exterior_payment_method');
            $prj_detail->exterior_payment_amount = $request->input('exterior_payment_amount');
            $prj_detail->exterior_special_notes = $request->input('exterior_special_notes');
            $prj_detail->exterior_price = $request->input('exterior_price');
            $prj_detail->final_price = $prj_detail->exterior_price;


            //added_today
            //in Exterior condtion
            $projectObj = Project::find($request->input('project_id'));
            $customer_id = $projectObj->customer_id;

            $customer_interactions = new CustomerInteractions();
            $customer_interactions->customer_id = $customer_id;
            $customer_interactions->project_id = $projectObj->id;
            $customer_interactions->interaction_type = 'User Action';
            $customer_interactions->interaction_notes = 'Interior Estimate created For Project ' . $projectObj->id;
            $customer_interactions->performed_by_id = $user_id;
            $customer_interactions->is_show_projects = true;
            $customer_interactions->save();


        } //other project type

        else if ($project->project_type_id == 4 || $project->project_type_id == 5 || $project->project_type_id == 6) {

            $other_description = $request->input('dynamicProjectsArray');

            if ($other_description != null && sizeof($other_description) > 0) {
                $prj_detail->otherdescription()->createMany($other_description);
            }

            $prj_detail->estimate_description = $request->input('estimate_description');
            $prj_detail->is_paint_material_included = $request->input('is_paint_material_included');
            $prj_detail->estimate_description_amount = $request->input('estimate_description_amount');
            $prj_detail->estimate_subtotal = $request->input('estimate_subtotal');
            $prj_detail->estimate_discount_value = $request->input('estimate_discount_value');
            $prj_detail->discount_type = $request->input('discount_type');
            $prj_detail->discount_payment_method = $request->input('discount_payment_method');
            $prj_detail->discount_amount = $request->input('discount_amount');
            $prj_detail->estimate_net_amount = $request->input('estimate_net_amount');
            $prj_detail->special_notes = $request->input('special_notes');
            $prj_detail->final_price = $request->input('final_price');
        }

        //added today
        $projectObj = Project::find($request->input('project_id'));
        $customer_id = $projectObj->customer_id;

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $customer_id;
        $customer_interactions->project_id = $projectObj->id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Expense Created ' . ' against project ' . $request->input('project_id');
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_projects = true;
        $customer_interactions->save();

        $prj_detail->save();

        return response()->json(['success' => 'Estimate saved successfully', 'projectdetail' => $prj_detail], 201);

    }


    public
    function getprojectsearchfiltermeta(Request $request, $user_id, $current_page_no)
    {

//        return response()->json(['user_id' => $user_id,'page_no' => $current_page_no],500);
        $pdo = DB::connection()->getpdo();
////        $stmt = $pdo->prepare('CALL search_project_filter(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
//        $stmt = $pdo->prepare('CALL search_project_filter(:pfirst_name,:plast_name, :paddress_1 , :paddress_2 , :pcity_id ,
//        :pstate_id, :pzip_code,  :pproject_number , :pproject_type_id , :pcreate_date_from , :pcreate_date_to ,
//        :pnick_names , :pstatus_id ,:pend_date_from , :pend_date_to , :ppotential_type_id , :pstart_date_from , :pstart_date_to, :pcrew_id,
//        :pestimator_id,
//        :psub_division_name, :pmajor_intersection, :pfinal_price_from, :pfinal_price_to ,
//        :puser_id , :current_page_no)');



//        $paddress_1 = $request->input('address_1')
//        ':paddress_2' => $request->input('address_2'),
//        ':pcity_id' => $request->input('city_id'),
//        ':pstate_id' => $request->input('state_id'),
//        ':pzip_code' => $request->input('zip_code'),
//        ':pcrew_id' => $request->input('crew_id'),
//        ':pestimator_id' => $request->input('estimator_id'),
//        ':psub_division_name' => $request->input('sub_division_name'),
//        ':pmajor_intersection' => $request->input('major_intersection'),
//        ':pproject_type_id' => $request->input('project_type_id'),
//        ':pproject_number' => $request->input('project_number'),
//        ':pnick_names' => $request->input('nick_names'),
//        ':pstatus_id' => $request->input('status_id'),
//        ':ppotential_type_id' => $request->input('potential_type_id'),
//        ':pcreate_date_from' => $request->input('create_date_from'),
//        ':pcreate_date_to' => $request->input('create_date_to'),
//        ':pstart_date_from' => $request->input('start_date_from'),
//        ':pstart_date_to' => $request->input('start_date_to'),
//        ':pend_date_from' => $request->input('end_date_from'),
//        ':pend_date_to' => $request->input('end_date_to'),
//        ':pfirst_name' => $request->input('first_name'),
//        ':plast_name' => $request->input('last_name'),
//        ':pfinal_price_from' => $request->input('pfinal_price_from'),
//        ':pfinal_price_to' => $request->input('pfinal_price_to')



        $stmt = $pdo->prepare('CALL search_projects(
        :current_page_no,
        :puser_id,
        :paddress_1,
        :paddress_2,
        :pcity_id,
        :pstate_id,
        :pzip_code,
        :pcrew_id,
        :pestimator_id,
        :psub_division_name,
        :pmajor_intersection,
        :pproject_type_id,
        :pproject_number,
        :pnick_names,
        :pstatus_id,
        :ppotential_type_id,
        :pcreate_date_from,
        :pcreate_date_to,
        :pstart_date_from,
        :pstart_date_to,
        :pend_date_from,
        :pend_date_to,
        :pfirst_name,
        :plast_name,
        :pfinal_price_from,
        :pfinal_price_to)'
        );


        $stmt->execute(array(
        'current_page_no' => $current_page_no,
        'puser_id' => $user_id,
        ':paddress_1' => $request->input('address_1'),
        ':paddress_2' => $request->input('address_2'),
        ':pcity_id' => $request->input('city_id'),
        ':pstate_id' => $request->input('state_id'),
        ':pzip_code' => $request->input('zip_code'),
        ':pcrew_id' => $request->input('crew_id'),
        ':pestimator_id' => $request->input('estimator_id'),
        ':psub_division_name' => $request->input('sub_division_name'),
        ':pmajor_intersection' => $request->input('major_intersection'),
        ':pproject_type_id' => $request->input('project_type_id'),
        ':pproject_number' => $request->input('project_number'),
        ':pnick_names' => $request->input('nick_names'),
        ':pstatus_id' => $request->input('status_id'),
        ':ppotential_type_id' => $request->input('potential_type_id'),
        ':pcreate_date_from' => $request->input('create_date_from'),
        ':pcreate_date_to' => $request->input('create_date_to'),
        ':pstart_date_from' => $request->input('start_date_from'),
        ':pstart_date_to' => $request->input('start_date_to'),
        ':pend_date_from' => $request->input('end_date_from'),
        ':pend_date_to' => $request->input('end_date_to'),
        ':pfirst_name' => $request->input('first_name'),
        ':plast_name' => $request->input('last_name'),
        ':pfinal_price_from' => $request->input('pfinal_price_from'),
        ':pfinal_price_to' => $request->input('pfinal_price_to')
            )
        );

//        $stmt->execute(array(
//            ':pfirst_name' => $request->input('first_name'),
//            ':plast_name' => $request->input('last_name'),
//            ':paddress_1' => $request->input('address_1'),
//            ':paddress_2' => $request->input('address_2'),
//            ':pcity_id' => $request->input('city_id'),
//            ':pstate_id' => $request->input('state_id'),
//            ':pzip_code' => $request->input('zip_code'),
//            ':pproject_type_id' => $request->input('project_type_id'),
//            ':pproject_number' => $request->input('project_number'),
//            ':pcreate_date_from' => $request->input('create_date_from'),
//            ':pcreate_date_to' => $request->input('create_date_to'),
//            ':pnick_names' => $request->input('nick_names'),
//            ':pstatus_id' => $request->input('status_id'),
//            ':pend_date_from' => $request->input('end_date_from'),
//            ':pend_date_to' => $request->input('end_date_to'),
//            ':ppotential_type_id' => $request->input('potential_type_id'),
//            ':pstart_date_from' => $request->input('start_date_from'),
//            ':pstart_date_to' => $request->input('start_date_to'),
//            ':pcrew_id' => $request->input('crew_id'),
//            ':pestimator_id' => $request->input('estimator_id'),
//            ':psub_division_name' => $request->input('sub_division_name'),
//            ':pmajor_intersection' => $request->input('major_intersection'),
//            ':pfinal_price_from' => $request->input('final_price_from'),
//            ':pfinal_price_to' => $request->input('final_price_to'),
//            ':puser_id' => $user_id,
//            ':current_page_no' => $current_page_no
//
//        ));

        //$project = new Project();
//        $searchResult = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();
//        $projects_count = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();
//
        $searchResult = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();
        $projects_count = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $projects_count = $projects_count[0]->projects_count;

//        'projects_count' => $projects_count,
//        'pages'=> $projects_count

//        $searchResult = [];

        return response()->json([
            'searchResult' => $searchResult,
            'projects_count' => $projects_count], 201);
    }

    public
    function getSingleProject($id)
    {

        $project = Project::find($id);
        $customers = Customer::select('id',DB::raw("CONCAT(first_name,' ',last_name) AS customer_name"),'address_1','address_2','city_id','state_id','zip_code','sub_division_name','major_intersection')->where('id',$project->customer_id)->get();
        $crew = User::select('id',DB::raw("CONCAT(first_name,' ',last_name) AS crew_name"))->where('id',$project->crew_id)->get();
        $estimator = User::select('id',DB::raw("CONCAT(first_name,' ',last_name) AS estimator_name"))->where('id',$project->estimator_id)->get();
        $supervisor = User::select('id',DB::raw("CONCAT(first_name,' ',last_name) AS supervisor_name"))->where('id',$project->supervisor_id)->get();
        $project_attachments = ProjectAttachment::where('project_id', $id)->get();
        $City = City::find( $project->city_id);
        if($City!=null){
        $project->city_name=$City->name;
        }
        if($customers[0]!=null){
            $cityId=$customers[0]->city_id;

            $CustomerCity = City::find($cityId);
            if($CustomerCity!=null){

                $customers[0]->city_name=$CustomerCity->name;

            }
        }




        return response()->json([
            'project' => $project,
            'project_attachments' => $project_attachments,
            'customers' => $customers,
            'crew'=>$crew,
            'estimator'=>$estimator,
            'supervisor'=>$supervisor,
        ], 200);
    }

    public
    function addDescription(Request $request)
    {
        $other_description = new OtherDescription();
        $other_description->other_price = $request->input('other_price');
        $other_description->other_project_descriptions = $request->input('other_project_descriptions');
        $other_description->project_details_id = $request->input('project_details_id');

        $other_description->save();

        return response()->json(['id' => $other_description->id], 201);
    }

    public
    function updateDescription(Request $request)
    {
        $other_description = OtherDescription::find($request->input('id'));
        $other_description->other_price = $request->input('other_price');
        $other_description->other_project_descriptions = $request->input('other_project_descriptions');

        $other_description->save();

        return response()->json(['success' => 'updated'], 201);
    }

    public
    function deleteDescription($id)
    {
        $other_description = OtherDescription::find($id);
        $other_description->delete();

        return response()->json(['success' => 'delete'], 202);
    }

    public
    function addInteriorPaint(Request $request)
    {
        $interior_paint = new InteriorPaints();
        $interior_paint->project_details_id = $request->input('project_details_id');
        $interior_paint->paint_area = $request->input('paint_area');
        $interior_paint->coat_1 = $request->input('coat_1');
        $interior_paint->coat1_gallons = $request->input('coat1_gallons');
        $interior_paint->coat_2 = $request->input('coat_2');
        $interior_paint->coat2_gallons = $request->input('coat2_gallons');
        $interior_paint->trim = $request->input('trim');
        $interior_paint->trim_coats = $request->input('trim_coats');
        $interior_paint->trim_gallons = $request->input('trim_gallons');
        $interior_paint->ceiling = $request->input('ceiling');
        $interior_paint->ceiling_coats = $request->input('ceiling_coats');
        $interior_paint->ceiling_gallons = $request->input('ceiling_gallons');
        $interior_paint->closet = $request->input('closet');
        $interior_paint->price = $request->input('price');

        $interior_paint->save();

        return response()->json(['id' => $interior_paint->id], 201);
    }

    public
    function updateInteriorPaint(Request $request)
    {
        $interior_paint = InteriorPaints::find($request->input('id'));
        $interior_paint->project_details_id = $request->input('project_details_id');
        $interior_paint->paint_area = $request->input('paint_area');
        $interior_paint->coat_1 = $request->input('coat_1');
        $interior_paint->coat1_gallons = $request->input('coat1_gallons');
        $interior_paint->coat_2 = $request->input('coat_2');
        $interior_paint->coat2_gallons = $request->input('coat2_gallons');
        $interior_paint->trim = $request->input('trim');
        $interior_paint->trim_coats = $request->input('trim_coats');
        $interior_paint->trim_gallons = $request->input('trim_gallons');
        $interior_paint->ceiling = $request->input('ceiling');
        $interior_paint->ceiling_coats = $request->input('ceiling_coats');
        $interior_paint->ceiling_gallons = $request->input('ceiling_gallons');
        $interior_paint->closet = $request->input('closet');
        $interior_paint->price = $request->input('price');

        $interior_paint->save();

        return response()->json(['success' => 'update'], 201);
    }

    public
    function deleteInteriorPaint($id)
    {
        $other_description = InteriorPaints::find($id);
        $other_description->delete();

        return response()->json(['success' => 'deleted'], 202);
    }

    public
    function addInteriordescription(Request $request)
    {
        $interior_description = new InteriorDescription();
        $interior_description->project_details_id = $request->input('project_details_id');
        $interior_description->interior_description_area = $request->input('interior_description_area');
        $interior_description->interior_special_notes = $request->input('interior_special_notes');
        $interior_description->interior_final_price = $request->input('interior_final_price');

        $interior_description->save();
        return response()->json(['id' => $interior_description->id], 201);

    }

    public
    function updateInteriorDescription(Request $request)
    {
        $interior_description = InteriorDescription::find($request->input('id'));
        $interior_description->project_details_id = $request->input('project_details_id');
        $interior_description->interior_description_area = $request->input('interior_description_area');
        $interior_description->interior_special_notes = $request->input('interior_special_notes');
        $interior_description->interior_final_price = $request->input('interior_final_price');

        $interior_description->save();
        return response()->json(['success' => 'updated'], 201);
    }

    function deleteInteriorDescription($id)
    {
        $interior_description = InteriorDescription::find($id);
        $interior_description->delete();

        return response()->json(['success' => 'deleted'], 202);
    }

    public function getPdfData($id)
    {
        $project = Project::find($id);
        $project_descriptions = null;
        $project_information = null;

        if ($project->project_type_id == 4 || $project->project_type_id == 5 || $project->project_type_id == 6) {
            $pdo = DB::connection()->getpdo();
            $stmt = $pdo->prepare('CALL get_other_project_pdf_data(?)');
            $stmt->execute(array($id));

            $project_information = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

            $project_information = sizeof($project_information) > 0 ? $project_information[0] : null;
            $stmt->nextRowset();

            $project_descriptions = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
            $stmt->nextRowset();

            return response()->json(['project_information' => $project_information,
                'project_descriptions' => $project_descriptions
            ]);

        } else if ($project->project_type_id == 2) {

            $pdo = DB::connection()->getpdo();
            $stmt = $pdo->prepare('CALL get_interior_project_pdf_data(?)');
            $stmt->execute(array($id));

            $project_information = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

            $project_information = sizeof($project_information) > 0 ? $project_information[0] : null;
            $stmt->nextRowset();

            $interior_paints = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
            $stmt->nextRowset();

            $interior_descriptions = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
            $stmt->nextRowset();

            $project_descriptions = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
            $stmt->nextRowset();

            return response()->json(['project_information' => $project_information, 'interior_paints' => $interior_paints,
                'interior_descriptions' => $interior_descriptions,
                'project_descriptions' => $project_descriptions
            ]);

        } else if ($project->project_type_id == 3) {

            $pdo = DB::connection()->getpdo();
            $stmt = $pdo->prepare('CALL get_exterior_project_pdf_data(?)');
            $stmt->execute(array($id));

            $project_information = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

            $project_information = sizeof($project_information) > 0 ? $project_information[0] : null;
            $stmt->nextRowset();

            $project_descriptions = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
            $stmt->nextRowset();

            return response()->json(['project_information' => $project_information,
                'project_descriptions' => $project_descriptions
            ]);

        }
    }


        public function getProjectSearchByKeyWords(Request $request,$user_id)
        {
//            return response()->json(['que'])

            $query = $request->input('query');
            $type = $request->input('type');

            $pdo = DB::connection()->getpdo();
            $stmt = $pdo->prepare('CALL get_project_search_by_keyword(:query,:type,:puserid)');
            $stmt->execute(array
                (
                'query' => $query,
                'type' => $type,
                'puserid' => $user_id

            ));

            $search_result = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
            $stmt->nextRowset();

            return response()->json(['search_result' => $search_result],200);

        }
}
