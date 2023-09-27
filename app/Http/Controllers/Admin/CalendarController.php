<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/15/19
 * Time: 12:27 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Calendars;
use App\Models\CustomerInteractions;
use App\Models\Project;
use App\Models\SubProject;
use App\User;
use DB;
use function foo\func;
use http\Env\Response;
use Illuminate\Http\Request;
use PDO;
use phpDocumentor\Reflection\Types\Object_;


class CalendarController extends Controller
{


    public function createEvent(Request $request, $user_id)
    {
        $calendar = new Calendars();

        $calendar->role_id = $request->input('role_id');
        $calendar->appointment_type = $request->input('appointment_type');
        $calendar->appointment_for = $request->input('appointment_for');
        $calendar->appointment_with = $request->input('appointment_with');
        $calendar->manage_calendar_id = $request->input('manage_calendar_id');

        $calendar->project_for_appointment = $request->input('project_for_appointment');
        $calendar->start_date = $request->input('start_date');
        $calendar->start_time = $request->input('start_time');
        $calendar->end_date = $request->input('end_date');
        $calendar->duration = $request->input('duration');
        $calendar->end_time = $request->input('end_time');
        $calendar->is_sub_project = $request->input('is_sub_project');
        $calendar->display_text = $request->input('display_text');
        $calendar->use_default = $request->input('use_default');
        $calendar->appointment_details = $request->input('appointment_details');

        $calendar->save();

        if ($calendar->appointment_type != 82 && $calendar->appointment_type != 83 && $calendar->appointment_type != 84 && $calendar->appointment_type != 85) {

            $project = Project::find($calendar->project_for_appointment);

            if ($calendar->appointment_type == 78 || $calendar->appointment_type == 80 || $calendar->appointment_type == 81) {
                $project->estimate_work_start_date = $calendar->start_date;
                $project->estimate_work_end_date = $calendar->end_date;
                $project->status_id = 2;
                $project->estimator_id = $calendar->appointment_for;

                if (isset($project)) {
                    $user = User::find($project->estimator_id);
                    $user_name = $user->first_name . ' ' . $user->last_name;

                    $customer_interactions = new CustomerInteractions();
                    $customer_interactions->customer_id = $calendar->appointment_with;
                    $customer_interactions->project_id = $calendar->project_for_appointment;
                    $customer_interactions->interaction_type = 'System Auto Action';
                    $customer_interactions->interaction_notes = 'Estimate Appointment for Project ' . $calendar->project_for_appointment . ' changed Estimator to: ' . $user_name;
                    $customer_interactions->performed_by_id = $user_id;
                    $customer_interactions->is_show_appointments = true;
                    $customer_interactions->save();

                }

            }


//            return response()->json(['project' => $project], 201);

            if ($calendar->appointment_type == 79) {

                if (isset($project)) {
                    $project->start_date = $calendar->start_date;
                    $project->end_date = $calendar->end_date;
                    $project->status_id = 6;
                    $project->crew_id = $calendar->appointment_for;
                }

                if (isset($project)) {
                    $user = User::find($project->crew_id);
                    $user_name = $user->first_name . ' ' . $user->last_name;

                    $customer_interactions = new CustomerInteractions();
                    $customer_interactions->customer_id = $calendar->appointment_with;
                    $customer_interactions->project_id = $calendar->project_for_appointment;
                    $customer_interactions->interaction_type = 'System Auto Action';
                    $customer_interactions->interaction_notes = 'Crew Work Appointment for Project ' . $calendar->project_for_appointment . ' changed Crew Leader to: ' . $user_name;
                    $customer_interactions->performed_by_id = $user_id;
                    $customer_interactions->is_show_appointments = true;
                    $customer_interactions->save();

                }
            }

            if ($calendar->appointment_type == 82) {
                if (isset($project)) {
                    $project->estimate_work_start_date = $calendar->start_date;
                    $project->estimate_work_end_date = $calendar->end_date;
                    $project->status_id = 2;
                }
            }

            if ($calendar->appointment_type == 84) {
                if (isset($project)) {
                    $project->start_date = $calendar->start_date;
                    $project->end_date = $calendar->end_date;
                    $project->status_id = 6;
                }
            }


            if ($calendar->is_sub_project == true) {
                $sub_project = SubProject::find($request->input('sub_project_id'));
                if (isset($sub_project)) {
                    $sub_project->work_start_date = $calendar->start_date;
                    $sub_project->work_end_date = $calendar->end_date;
                    $sub_project->save();
                }
            }

            if (isset($project)) {
                $project->save();
            }


        }

        //added_today

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $calendar->appointment_with;
        $customer_interactions->project_id = $calendar->project_for_appointment;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Estimate Scheduled For Project ' . $calendar->project_for_appointment;
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_appointments = true;
        $customer_interactions->save();

//        $projectsObj = DB::table('projects')->where('id', $calendar->appointment_with)->first();
//        $customer_interactions = new CustomerInteractions();
//        $customer_interactions->customer_id = $calendar->appointment_with;
//        $customer_interactions->interaction_type = 'User Action';
//        if ($calendar->project_for_appointment != null || $calendar->project_for_appointment != '') {
//            $customer_interactions->interaction_notes = 'Estimate Scheduled For Project ' . $calendar->project_for_appointment;
//        }
//        $customer_interactions->performed_by_id = $user_id;
//        $customer_interactions->save();
        return response()->json(['success' => 'Event created successfully', 'id' => $calendar->id], 201);
    }

    public function editEvent(Request $request, $id, $user_id)
    {
        $calendar = Calendars::find($id);
        $calendar->role_id = $request->input('role_id');
        $calendar->appointment_type = $request->input('appointment_type');
        $calendar->appointment_for = $request->input('appointment_for');
        $calendar->appointment_with = $request->input('appointment_with');
        $calendar->manage_calendar_id = $request->input('manage_calendar_id');

        $calendar->project_for_appointment = $request->input('project_for_appointment');

        $calendar->start_date = $request->input('start_date');
        $calendar->start_time = $request->input('start_time');
        $calendar->end_date = $request->input('end_date');
        $calendar->duration = $request->input('duration');
        $calendar->end_time = $request->input('end_time');
        $calendar->is_sub_project = $request->input('is_sub_project');
        $calendar->display_text = $request->input('display_text');
        $calendar->use_default = $request->input('use_default');
        $calendar->appointment_details = $request->input('appointment_details');
        $calendar->save();

        if ($calendar->appointment_type != 82 && $calendar->appointment_type != 83 && $calendar->appointment_type != 84 && $calendar->appointment_type != 85) {
            $project = Project::find($calendar->project_for_appointment);

            if (isset($project)) {
                $project->status_id = 3;
            }


            if ($calendar->appointment_type == 78 || $calendar->appointment_type == 80 || $calendar->appointment_type == 81) {
                if (isset($project)) {
                    $project->estimate_work_start_date = $calendar->start_date;
                    $project->estimate_work_end_date = $calendar->end_date;
                    $project->status_id = 2;
                    $project->estimator_id = $calendar->appointment_for;
                }

                if (isset($project)) {
                    $user = User::find($project->estimator_id);
                    $user_name = $user->first_name . ' ' . $user->last_name;

                    $customer_interactions = new CustomerInteractions();
                    $customer_interactions->customer_id = $calendar->appointment_with;
                    $customer_interactions->project_id = $calendar->project_for_appointment;
                    $customer_interactions->interaction_type = 'System Auto Action';
                    $customer_interactions->interaction_notes = 'Estimate Appointment for Project ' . $calendar->project_for_appointment . ' changed Estimator to: ' . $user_name;
                    $customer_interactions->performed_by_id = $user_id;
                    $customer_interactions->is_show_appointments = true;
                    $customer_interactions->save();

                }


            }

            if ($calendar->appointment_type == 79) {

                if (isset($project)) {
                    $project->start_date = $calendar->start_date;
                    $project->end_date = $calendar->end_date;
                    $project->status_id = 6;
                    $project->crew_id = $calendar->appointment_for;
                }

                if (isset($project)) {
                    $user = User::find($project->crew_id);
                    $user_name = $user->first_name . ' ' . $user->last_name;

                    $customer_interactions = new CustomerInteractions();
                    $customer_interactions->customer_id = $calendar->appointment_with;
                    $customer_interactions->project_id = $calendar->project_for_appointment;
                    $customer_interactions->interaction_type = 'System Auto Action';
                    $customer_interactions->interaction_notes = 'Crew Work Appointment for Project ' . $calendar->project_for_appointment . ' changed Crew Leader to: ' . $user_name;
                    $customer_interactions->performed_by_id = $user_id;
                    $customer_interactions->is_show_appointments = true;
                    $customer_interactions->save();

                }


            }

            if ($calendar->appointment_type == 82) {
                if (isset($project)) {
                    $project->estimate_work_start_date = $calendar->start_date;
                    $project->estimate_work_end_date = $calendar->end_date;
                    $project->status_id = 2;
                }
            }

            if ($calendar->appointment_type == 84) {
                if (isset($project)) {
                    $project->start_date = $calendar->start_date;
                    $project->end_date = $calendar->end_date;
                    $project->status_id = 6;
                }
            }

            if ($calendar->is_sub_project == true) {
                $sub_project = SubProject::find($request->input('sub_project_id'));
                if (isset($sub_project)) {
                    $sub_project->work_start_date = $calendar->start_date;
                    $sub_project->work_end_date = $calendar->end_date;
                    $sub_project->save();
                }
            }

            if (isset($project)) {
                $project->save();
            }
        }


//        $old_appointment_id = $calendar->appointment_type;
//        $old_appointment_for_id = $calendar->appointment_for;
//        $old_appointment_with_id = $calendar->appointment_with;
//
//        $old_appointment_type = DB::Table('object_type')->where('object_type', $old_appointment_id)->first();
//        $old_appointment_type_name = $old_appointment_type->ObjectName;
//
//        $old_appointment_for = DB::Table('users')->where('id', $old_appointment_for_id)->first();
//        $old_appointment_for_name = $old_appointment_for->first_name . ' ' . $old_appointment_for->last_name;
//
//        $old_appointment_with = DB::Table('customers')->where('id', $old_appointment_with_id)->first();
//        $old_appointment_with_name = $old_appointment_with->first_name . ' ' . $old_appointment_with->last_name;

        $changes = $calendar->getChanges();
        $changes_array = array();
        $changes_notes = '';

        foreach ($changes as $key => $value) {
            $keysObject = new \stdClass();
            $keysObject->key = $key;
            $keysObject->value = $value;
            $changes_array [] = $keysObject;

            if ($keysObject->key == 'appointment_type') {

                if ($keysObject->value == 78) {

                    $appointment_type_estimate_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->value)->first();
                    $new_estimate_appointment_type_name = $appointment_type_estimate_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_estimate_appointment_type_name . '. ';
                }

                if ($keysObject->value == 79) {
                    $appointment_type_crew_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->value)->first();
                    $new_crew_appointment_type_name = $appointment_type_crew_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_crew_appointment_type_name . '. ';
                }

                if ($keysObject->value == 80) {
                    $appointment_type_klean_ups_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->value)->first();
                    $new_kleap_ups_appointment_type_name = $appointment_type_klean_ups_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_kleap_ups_appointment_type_name . '. ';
                }

                if ($keysObject->value == 81) {
                    $appointment_type_emp_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->value)->first();
                    $new_emp_appointment_type_name = $appointment_type_emp_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_emp_appointment_type_name . '. ';
                }

                if ($keysObject->value == 82) {
                    $appointment_type_other_est_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->valuee)->first();
                    $new_other_est_appointment_type_name = $appointment_type_other_est_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_other_est_appointment_type_name . '. ';
                }

                if ($keysObject->value == 83) {
                    $appointment_type_sub_contractor_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->value)->first();
                    $new_sub_contractor_appointment_type_name = $appointment_type_sub_contractor_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_sub_contractor_appointment_type_name . '. ';
                }

                if ($keysObject->value == 84) {
                    $appointment_type_other_crew_leader_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->value)->first();
                    $new_other_crew_leader_appointment_type_name = $appointment_type_other_crew_leader_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_other_crew_leader_appointment_type_name . '. ';
                }

                if ($keysObject->value == 85) {
                    $appointment_type_free_libre_old = DB::Table('object_type')->where('ObjectTypeID', $keysObject->value)->first();
                    $new_free_libre_appointment_type_name = $appointment_type_free_libre_old->ObjectName;

                    $changes_notes = $changes_notes . ' ' .
                        'Appointment type for project ' . $calendar->project_for_appointment . ' changed to ' . $new_free_libre_appointment_type_name . '. ';
                }
            }

            if ($keysObject->key == 'appointment_for') {
                $appointment_for = DB::Table('users')->where('id', $calendar->appointment_for)->first();
                $new_appointment_for_name = $appointment_for->first_name . ' ' . $appointment_for->last_name;

                $changes_notes = $changes_notes . ' ' .
                    'project appointment changed to ' . $new_appointment_for_name . '. ';

            }

            if ($keysObject->key == 'appointment_with') {
                $new_appointment_with = DB::Table('customers')->where('id', $calendar->appointment_with)->first();
                $new_appointment_with_name = $new_appointment_with->first_name . ' ' . $new_appointment_with->last_name;

                $changes_notes = $changes_notes . ' ' .
                    'appointment with changed to ' . $new_appointment_with_name . '. ';

            }
            if ($keysObject->key == 'project_for_appointment') {

                $project_for_appointment = DB::Table('projects')->where('id', $calendar->project_for_appointment)->first();
                $new_project_for_appointment = $project_for_appointment->id;

                $changes_notes = $changes_notes . ' ' .
                    'project for appointment changed to ' . $new_project_for_appointment . '. ';

            }

            if ($keysObject->key == 'start_date') {
                $changes_notes = $changes_notes . ' ' .
                    'appointment start date changed to ' . $calendar->start_date . '. ';
            }

            if ($keysObject->key == 'start_time') {

                $changes_notes = $changes_notes . ' ' .
                    'Estimate Appointment for Project ' . $calendar->project_for_appointment . '. New start time set: ' . $calendar->start_time . '. ';

                $changes_notes = $changes_notes . ' ' .
                    'Crew Work Appointment for Project ' . $calendar->project_for_appointment . '. New start time set: ' . $calendar->start_time . '. ';

            }

            if ($keysObject->key == 'end_date') {
                $changes_notes = $changes_notes . ' ' .
                    'appointment end date changed to ' . $calendar->end_date . '. ';
            }

            if ($keysObject->key == 'end_time') {

                $changes_notes = $changes_notes . ' ' .
                    'Estimate Appointment for Project ' . $calendar->project_for_appointment . '. New end time set: ' . $calendar->end_time . '. ';

                $changes_notes = $changes_notes . ' ' .
                    'Crew Work Appointment for Project ' . $calendar->project_for_appointment . '. New end time set: ' . $calendar->end_time . '. ';

            }

            if ($keysObject->key == 'duration') {
                $changes_notes = $changes_notes . ' ' .
                    'appointment duration changed to ' . $calendar->duration . '. ';
            }

            if ($keysObject->key == 'display_text') {
                $changes_notes = $changes_notes . ' ' .
                    'appointment display text changed to ' . $calendar->display_text . '. ';
            }

            if ($keysObject->key == 'appointment_details') {
                $changes_notes = $changes_notes . ' ' .
                    'appointment details changed to ' . $calendar->appointment_details . '. ';
            }

        }

        if ($changes_notes != null && $changes_notes != '') {
            $customer_interactions->interaction_notes = $changes_notes;
            $customer_interactions->is_show_appointments = true;
        }

        $customer_interactions->performed_by_id = intval($user_id);
        $customer_interactions->save();

        return response()->json(['success' => 'Event updated successfully', 'values' => $changes, 'user_id' => $user_id], 201);
    }

    public function getEvent($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_event(?)');
        $stmt->execute(array($id));

        $event = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['event' => $event, 'projects' => $projects], 201);
    }


    //viewEvent


    public function viewEvent($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_view_event(?)');
        $stmt->execute(array($id));

        $event_detail = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['event_detail' => $event_detail], 201);
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


    public function getPaginatedEvents($query_start_date, $view , $puser_id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_calendar_events_through_pagination(?,?,?)');
        $stmt->execute(array($query_start_date, $view, $puser_id));

        $events = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');


        return response()->json(['events' => $events,
            'start_date' => $query_start_date,
            'view' => $view,
            'projects' => $projects
        ], 201);

    }


    public
    function getEvents($puser_id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_events_new(?)');
        $stmt->execute(array($puser_id));

//        $events = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

        $calendars = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $crews = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

        $type_of_estimate = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $customers = [];

//        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();


        $projects = [];

//        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

//        $user_roles = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

        $is_exists = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $is_exists = $is_exists[0];

//        foreach ($users as $u) {
//            $u->objectTypes = array();
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Estimate"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return ($item1->role_id == 6 || $item1->role_id == 7) && $u->id == $item1->user_id;
//                        }))) > 0);
//            }));
//
//
//
//            if (sizeof($desired_object) > 0) {
//
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
//            }
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Crew Work"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return ($item1->role_id == 4 || $item1->role_id == 7) && $u->id == $item1->user_id;
//                        }))) > 0);
//            }));
//
//            if (sizeof($desired_object) > 0) {
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
////                return response()->json(['u' => $u,'type' => $desired_object],200);
//            }
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Klean Ups"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return ($item1->role_id == 6 || $item1->role_id == 7) && $u->id == $item1->user_id;
//                        }))) > 0);
//            }));
//
//            if (sizeof($desired_object) > 0) {
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
//            }
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Empowered Woman"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return ($item1->role_id == 6 || $item1->role_id == 7) && $u->id == $item1->user_id;
//                        }))) > 0);
//            }));
//
//            if (sizeof($desired_object) > 0) {
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
//            }
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Other - Estimator"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return ($item1->role_id == 6 || $item1->role_id == 7) && $u->id == $item1->user_id;
//                        }))) > 0);
//            }));
//
//            if (sizeof($desired_object) > 0) {
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
//            }
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Other - SubContractor"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return $item1->role_id == 7 && $u->id == $item1->user_id;
//                        }))) > 0);
//            }));
//
//            if (sizeof($desired_object) > 0) {
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
//            }
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Other - Crew Leader"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return ($item1->role_id == 4 || $item1->role_id == 7) && $u->id == $item1->user_id;
//                        }))) > 0);
//            }));
//
//            if (sizeof($desired_object) > 0) {
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
//            }
//
//
//            $desired_object = null;
//            $desired_object = array_filter($type_of_estimate, (function ($item) use ($user_roles, $u) {
//                return $item->ObjectName == "Free / Libre"
//                    && (sizeof(array_filter($user_roles, (function ($item1) use ($u) {
//                            return ($item1->role_id == 4 || $item1->role_id == 6 || $item1->role_id == 7) && $u->id == $item1->user_id;
//                    }))) > 0);
//            }));
//
//            if (sizeof($desired_object) > 0) {
//                foreach ($desired_object as $da) {
//                    $object_type = new \stdClass();
//                    $object_type->ObjectTypeID = $da->ObjectTypeID;
//                    $u->objectTypes [] = $object_type;
//                }
//            }
//
//        }

//        $duration = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

//        $estimators = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();

        $userRoles = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $users_array = array();


        foreach ($users as $user) {


            $main = new \stdClass();
            $main->first_name = $user->first_name;
            $main->id = $user->id;
            $main->last_name = $user->last_name;
            $main->manage_calendars_id = $user->manage_calendars_id;


            $filter_array = array_filter($userRoles, function ($key) use ($user) {
                return $key->user_id == $user->id;
            });
            $main->roles = array();
            if (sizeof($filter_array) > 0) {
                foreach ($filter_array as $fa) {
                    $main->roles [] = $fa->role_id;
                }

            }
            $users_array [] = (array)$main;
        }


        return response()->json([
//            'events' => $events,
            'calendars' => $calendars,
            'users' => $users_array,
//            'crews' => $crews,
            'type_of_estimate' => $type_of_estimate,
            'customers' => $customers,
            'projects' => $projects,
            'is_exists' => $is_exists->is_exists
//            'duration' => $duration,
//            'estimators' => $estimators
        ], 201);
    }


    public
    function getappointmentdata()
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_appointment_data()');
        $stmt->execute();

        $crews = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $type_of_estimate = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $all_users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['crews' => $crews, 'type_of_estimate' => $type_of_estimate,
            'all_users' => $all_users, 'customers' => $customers], 201);
    }

    public
    function deleteEvent($id)
    {
        $event = Calendars::find($id);
        $event->delete();
        return response()->json(['success' => 'Event deleted successfully'], 200);
    }


    public
    function getUserRoles()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_user_roles()');
        $stmt->execute();

        $roles = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['roles' => $roles, 'users' => $users], 201);

    }


    public
    function getDataToScheduleEstimate($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_data_to_schedule_estimator_appointment(?)');
        $stmt->execute(array($id));

        $appointment_data = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['appointment_data' => $appointment_data], 201);

    }

    public
    function getdatafromproject($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_data_from_project(?)');
        $stmt->execute(array($id));

        $project_detail = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['project_detail' => $project_detail], 201);
    }

    public
    function getUsersOfSelectedCalendar($id, $user_id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_users_of_selected_calendar(?,?)');

        $stmt->execute(array($id, $user_id));

        $selected_calendar_users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['selected_calendar_users' => $selected_calendar_users], 201);
    }


}
