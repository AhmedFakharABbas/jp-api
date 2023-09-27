<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use App\Models\Customer;
use App\Models\CustomerInteractions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use League\OAuth2\Server\Entities;
use App\User;
use App\Http\Resources\CustomerCollection;
use PDO;
use DB;
use phpDocumentor\Reflection\Types\Array_;
use Carbon\Carbon;

class CustomerController extends Controller
{

    //Create Customer
    public function create(Request $request, $user_id)
    {
        $customer = new Customer();
        $customer->first_name = $request->input('first_name');
        $customer->last_name = $request->input('last_name');
        $customer->city_id = $request->input('city_id');
        $customer->zip_code = $request->input('zip_code');
        $customer->state_id = $request->input('state_id');
        $customer->home_phone = $request->input('home_phone');
        $customer->work_phone = $request->input('work_phone');
        $customer->sub_division_name = $request->input('sub_division_name');
        $customer->address_1 = $request->input('address_1');
        $customer->address_2 = $request->input('address_2');
        $customer->company = $request->input('company');
        $customer->major_intersection = $request->input('major_intersection');
        $customer->work_phone = $request->input('work_phone');
        $customer->extention = $request->input('extention');
        $customer->fax = $request->input('fax');
        $customer->email = $request->input('email');
        $customer->referral_source_id = $request->input('referral_source_id');
        $customer->referral_source_note = $request->input('referral_source_note');
        $customer->reference_status_type_id = $request->input('reference_status_type_id');
        $customer->potential_type_id = $request->input('potential_type_id');
        $customer->notes = $request->input('notes');
        $customer->company = $request->input('company');
        $customer->mobile_phone = $request->input('mobile_phone');

        $customer->save();

        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $customer->id;
        $customer_interactions->interaction_type = 'User Action';
        $customer_interactions->interaction_notes = 'Customer created';
        $customer_interactions->performed_by_id = $user_id;
        $customer_interactions->is_show_projects = true;
        $customer_interactions->is_show_notes = true;
        $customer_interactions->is_show_appointments = true;
        $customer_interactions->is_show_calls = true;
        $customer_interactions->is_show_expenses = true;
        $customer_interactions->is_show_payments = true;
        $customer_interactions->save();

        return response()->json(['success' => 'created successfully', 'id' => $customer->id], 201);
    }


    public function createUserNote(Request $request)
    {
        $customer_interactions = new CustomerInteractions();
        $customer_interactions->customer_id = $request->input('customer_id');
        $customer_interactions->interaction_type = $request->input('interaction_type');
        $customer_interactions->interaction_notes = $request->input('interaction_notes');
        $customer_interactions->performed_by_id = $request->input('performed_by_id');
        $customer_interactions->is_show_notes = true;
        $customer_interactions->save();

        return response()->json(['success' => 'created successfully', 'id' => $customer_interactions->id], 201);

    }


    //get single customer by id
    public function getCustomer($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_customer(?)');
        $stmt->execute(array($id));

        $customer = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        $stmt->nextRowset();
        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        return response()->json(['customer' => $customer, 'projects' => $projects], 201);
    }


    //Update Customer
    public function updateCustomer(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (isset($customer)) {

            $customer->first_name = $request->input('first_name');
            $customer->last_name = $request->input('last_name');
            $customer->city_id = $request->input('city_id');
            $customer->zip_code = $request->input('zip_code');
            $customer->state_id = $request->input('state_id');
            $customer->home_phone = $request->input('home_phone');
            $customer->work_phone = $request->input('work_phone');
            $customer->sub_division_name = $request->input('sub_division_name');
            $customer->address_1 = $request->input('address_1');
            $customer->address_2 = $request->input('address_2');
            $customer->company = $request->input('company');
            $customer->major_intersection = $request->input('major_intersection');
            $customer->work_phone = $request->input('work_phone');
            $customer->extention = $request->input('extention');
            $customer->reference_status_type_id = $request->input('reference_status_type_id');
            $customer->fax = $request->input('fax');
            $customer->email = $request->input('email');
            $customer->referral_source_id = $request->input('referral_source_id');
            $customer->referral_source_note = $request->input('referral_source_note');
            $customer->potential_type_id = $request->input('potential_type_id');
            $customer->notes = $request->input('notes');
            $customer->created_on = $request->input('created_on');
            $customer->company = $request->input('company');
            $customer->mobile_phone = $request->input('mobile_phone');
            $customer->save();
            return response()->json(['success' => 'Updated successfully', 'customer' => $customer], 201);
        }
    }

    //Delete Customer
    public function deleteCustomer(Request $request, $id)
    {
        $customer = Customer::find($id);
//        $projects = DB::table('projects')->where('customer_id', $id)->first();
//        if ($projects == null) {

        if (isset($customer)) {
            $customer->is_deleted = true;
            $customer->delete();
        }

        return response()->json(['success' => 'Deleted successfully'], 201);
//        }
//        else {
//            return response()->json(['error' => 'Customer has associated projects'], 403);
//        }
    }


    //get all customers
    public function getallCustomers()
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_all_customers');
        $stmt->execute();

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['customers' => $customers], 201);

    }


    public function getSearchReferenceCustomers()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_search_reference_customers');
        $stmt->execute();

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['customers' => $customers], 201);

    }


    //get meta data for customers
    public function getcustomersmeta()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_customers_meta');
        $stmt->execute();

        $cities = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $states = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $sources = $stmt->fetchAll(PDO ::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $statuses = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $potential = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');

        return response()->json(['cities' => $cities, 'states' => $states, 'sources' => $sources, 'statuses' => $statuses, 'potential' => $potential], 201);
    }


    //new
    public function getcustomersearchfiltermeta(Request $request,$page_no)
    {

//        return response()->json(['test' =>  $page_no],500);
// :pfax,'pfax' =>  $request->input('fax'),
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL search_customer_filter_meta(
        :pfirst_name,
        :plast_name,
        :pcompany,
        :pemail,
        :paddress_1,
        :paddress_2,
        :pmobile_phone,
        :ppotential_type_id,
        :pstate_id,
        :pzip_code,
        :pcity_id,
        :psub_division_name,
        :pmajor_intersection,
        :pcreated_on,
        :pfax,
        :page_no
        )');

        $stmt->execute(array(
         'pfirst_name' =>   $request->input('first_name'),
         'plast_name' =>  $request->input('last_name'),
         'pcompany' =>  $request->input('company'),
         'pemail' =>  $request->input('email'),
         'paddress_1' =>  $request->input('address_1'),
         'paddress_2' =>  $request->input('address_2'),
         'pmobile_phone' =>  $request->input('mobile_phone'),
         'ppotential_type_id' =>  $request->input('potential_type_id'),
         'pstate_id' => $request->input('state_id'),
         'pzip_code' => $request->input('zip_code'),
         'pcity_id' =>  $request->input('city_id'),
         'psub_division_name' => $request->input('sub_division_name'),
         'pmajor_intersection' =>  $request->input('major_intersection'),
         'pcreated_on' =>  $request->input('created_on'), 'pfax' =>  $request->input('fax'),
         'page_no' => $page_no,
        ));

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');


        $stmt->nextRowset();

        $customer_count = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $customer_count = $customer_count[0]->customer_count;


        return response()->json(['customers' => $customers,
            'customer_count' => $customer_count], 200);
    }


    //old
//    public function getcustomersearchfiltermeta(Request $request)
//    {
//        $pdo = DB::connection()->getpdo();
//        $stmt = $pdo->prepare('CALL search_customer_filter_meta(?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
//        $stmt->execute(array($request->input('first_name'), $request->input('last_name'),
//            $request->input('company'), $request->input('email'),
//            $request->input('address_1'), $request->input('address_2'),
//            $request->input('mobile_phone'), $request->input('potential_type_id'), $request->input('state_id'),
//            $request->input('zip_code'), $request->input('city_id'),
//            $request->input('sub_division_name'),
//            $request->input('major_intersection'), $request->input('created_on'),
//        ));
//
//        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();
//
//        return response()->json(['customers' => $customers], 201);
//    }


    public function getcustomerreferencesearchfiltermeta(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL search_customer_reference_filter_meta(?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array($request->input('first_name'), $request->input('last_name'),
            $request->input('address_1'), $request->input('address_2'), $request->input('sub_division_name'),
            $request->input('city_id'), $request->input('state_id'), $request->input('zip_code'),
            $request->input('major_intersection'), $request->input('reference_status_type_id'),
            $request->input('project_type_id'),
        ));

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['customers' => $customers, 'projects' => $projects], 201);
    }

    public function getSoftDeletedCustomers()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_soft_deleted_customers');
        $stmt->execute();

        $customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['customers' => $customers], 201);
    }


    public function restoreSoftDeletedCustomer($id)
    {
//        $restore_customer = Customer::find($id);

        $restore_customer = Customer::onlyTrashed()->where('id', $id)->first();

        if (isset($restore_customer)) {
            $restore_customer->restore();
        }

        return response()->json(['success' => 'Customer Restored Successfully', 'customer_id' => $restore_customer->id], 201);
    }

    public function getCustomerInteractions($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_customer_interactions(?)');

        $stmt->execute(array($id));

        $customer_interactions = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $customer_projects = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['customer_interactions' => $customer_interactions, 'customer_projects' => $customer_projects], 201);
    }


    public function getFilteredCustomerInteractions(Request $request)
    {

        $customer_projects = $request->input('customer_projects');

        if (isset($customer_projects)) {

            $array_param = implode(',', $request->input('customer_projects'));

        } else if (!isset($customer_projects)) {
            $array_param = $request->input('customer_projects');
        }


        $mytime = Carbon::now();
        $mytime->toDateTimeString();


//        return response()->json([$request->input('is_show_appointments'), $request->input('is_show_calls'), $request->input('is_show_expenses'),
//            $request->input('is_show_notes'), $request->input('is_show_payments'), $request->input('is_show_projects'), $request->input('customer_id'),
//            $array_param, $request->input('pcurrent_date')], 201);

        $pdo = DB::connection()->getpdo();

//        $stmt = $pdo->prepare('CALL get_filtered_customer_interactions(
//               :customer_projects,
//                  :pcurrent_date,
//                        :customer_id,
//                        :is_show_notes,
//                        :is_show_projects,
//                        :is_show_appointments,
//                        :is_show_calls,
//                        :is_show_expenses,s
//                        :is_show_payments)');

//        $stmt = $pdo->prepare('CALL get_filtered_customer_interactions(?,?,?,?,?,?,?,?,?)');


//        $stmt->execute(array(
//            'customer_projects' => $array_param,
//            'pcurrent_date' => $request->input('current_date'),
//            'customer_id' => $request->input('customer_id'),
//            'is_show_notes' => $request->input('is_show_notes') == true ? 1 : null,
//            'is_show_projects' => $request->input('is_show_projects') == true ? 1 : null,
//            'is_show_appointments' => $request->input('is_show_appointments') == true ? 1 : null,
//            'is_show_calls' => $request->input('is_show_calls') == true ? 1 : null,
//            'is_show_expenses' => $request->input('is_show_expenses') == true ? 1 : null,
//            'is_show_payments' => $request->input('is_show_payments') == true ? 1 : null,
//        ));


//        $stmt->execute(array(
//
//            $request->input('is_show_appointments'),
//            $request->input('is_show_calls'),
//            $request->input('is_show_expenses'),
//            $request->input('is_show_notes'),
//            $request->input('is_show_payments'),
//            $request->input('is_show_projects'),
//            $request->input('customer_id'),
//            $array_param,
//            $request->input('pcurrent_date')
//
//        ));

        $stmt = $pdo->prepare('CALL get_filtered_customer_interactions(
      :pis_show_appointments,
      :pis_show_calls,
      :pis_show_expenses,
      :pis_show_notes,
      :pis_show_payments,
      :pis_show_projects,
      :pcustomer_id,
      :pcustomer_projects,
      :pcurrent_date

      )');

        $stmt->execute(array(
            ':pis_show_appointments' => $request->input('is_show_appointments') == true ? 1 : 0,
            ':pis_show_calls' => $request->input('is_show_calls') == true ? 1 : 0,
            ':pis_show_expenses' => $request->input('is_show_expenses') == true ? 1 : 0,
            ':pis_show_notes' => $request->input('is_show_notes') == true ? 1 : 0,
            ':pis_show_payments' => $request->input('is_show_payments') == true ? 1 : 0,
            ':pis_show_projects' => $request->input('is_show_projects') == true ? 1 : 0,
            ':pcustomer_id' => $request->input('customer_id'),
            ':pcustomer_projects' => $array_param,
            ':pcurrent_date' => $request->input('pcurrent_date')
        ));


        $customer_interactions = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['customer_interactions' => $customer_interactions], 201);

    }

}

