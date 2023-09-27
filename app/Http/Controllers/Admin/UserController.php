<?php
/**
 * Created by PhpStorm.
 * User: wasee
 * Date: 31-Jul-19
 * Time: 12:12 PM
 */


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthorizeCity;
use App\Models\AuthorizeZipCode;
use App\Models\UserLoginDetails;
use App\Models\UserManageAccess;
use App\User;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use PDO;

//use Illuminate\Support\Facades\DB;


class UserController extends Controller
{

    //Create Customer
    public function create(Request $request)
    {
        $user = new User();


        $validator = Validator::make($request->all(), [
            'username' => 'required|max:25|unique:users',
            'email' => 'required|max:100|unique:users',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'password' => 'required|confirmed|min:5'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->is_active = $request->input('is_active');
        $user->password = bcrypt($request->input('password'));


        $user->save();

        $data = $request->input('roles');

        $roles = array();

        if ($data != null) {
            foreach ($data as $d) {
                $roles [] = $d['id'];
            }
        }

        $user->roles()->attach($roles);


        return response()->json(['success' => 'User created successfully'], 201);

    }

    //Update User
    public function update(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);

        $validator = Validator::make($request->all(), [
            'username' => 'required|max:25,username,' . $user->id . ',id',
            /*'email' => 'required|max:100',*/
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'password' => 'sometimes|required|confirmed|min:4'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->phone_number = $request->input('phone_number');
        $user->is_active = $request->input('is_active');
        if ($request->input('password') != null) {
            $user->password = bcrypt($request->input('password'));
        }


        $user->save();

        $user->roles()->detach();

        $data = $request->input('roles');

        $roles = array();

        if ($data != null) {
            foreach ($data as $d) {
                $roles [] = $d['id'];
            }
        }

        $user->roles()->attach($roles);

        return response()->json(['success' => 'user updated successfully', 'user' => $user], 201);
    }


    public function updateUserPasswords(Request $request)
    {
//        $id = $request->input('ids');
//        $password = $request->input('passwords');
//        $ids = array();
//        $passwords = array();
//
//        if ($id != null && $password != null) {
//
//            foreach ($id as $key => $value) {
//                $ids [] = $value;
//                $userObj = User::find($value);
//
//                foreach ($password as $pkey => $pvalue) {
//                    $passwords[] = $pvalue;
////                    $userObj->new_password = $pvalue;
//                    if ($pvalue != null) {
//                        $userObj->new_password = bcrypt($pvalue);
//                    }
//                }
//                $userObj->save();
//            }
//        }
//        $call_array = array();
//        foreach ($calls as $record) {
//            $record1 = new \stdClass();
//            $record1->sid = $record->sid;
//            $record1->status = $record->status;
//            $record1->startTime = $record->startTime;
//            $record1->from = $record->from;
//            $record1->to = $record->to;
//            $call_array [] = $record1;
//        }
//        $user->first_name = $request->input('first_name');


        $users = User::all();
        foreach ($users as $user) {
            $user->update(['password' => bcrypt($user->password)]);
        }

        return response()->json(['success' => 'users  password updated successfully', 'users' => $users], 201);

    }


    //Update User Is Active Status
    public function updateUserIsActiveStatus(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);
        $user->is_active = $request->input('is_active');
        $user->save();
        return response()->json(['success' => 'is active updated successfully', 'user' => $user, 'user_id' => $user->id], 201);

    }


    //get user
    public function get($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_single_user(?)');
        $stmt->execute(array($id));

        $user = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $user = $user[0];

        $user_roles = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

//        $user = User::find($id);
//        $user_roles = $user->roles()->get();


        return response()->json(['user' => $user, 'user_roles' => $user_roles], 201);
    }

    //get all userlogindetails


    public function getalluserlogindetails($id)
    {
        $userlogindetails = new UserLoginDetails();
        $userlogindetails = UserLoginDetails::where('users_id', $id)->get();
        return response()->json(['userlogindetails' => $userlogindetails], 201);
    }

    //get all users
    public function getusers(Request $request,$active_user_current_page_no,$inactive_user_current_page_no)
    {
        $query = $request->input('query');
        if($query==null){ $query='';}

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_all_users(?,?,?)');
        $stmt->execute(array($active_user_current_page_no,$inactive_user_current_page_no,$query));

        $activeUsers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $InactiveUsers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $activeAcount = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();
        if($activeAcount!=null){
            $activeAcount = $activeAcount[0]->active_count;
        }
        $InactiveAcount = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();
        if($InactiveAcount!=null){
            $InactiveAcount = $InactiveAcount[0]->in_active_count;
        }


        return response()->json(['activeUsers' => $activeUsers, 'InactiveUsers' => $InactiveUsers,'activeUserAcount' => $activeAcount,'inActiveAcount' => $InactiveAcount,], 201);

    }
//delete user
    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['success' => 'User deleted successfully'], 201);
    }

    //get user meta
    public function get_user_meta()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_user_meta()');
        $stmt->execute();

        $user_types = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        return response()->json(['user_types' => $user_types], 201);
    }

    //get user meta
    public function getusersmanageaccessmeta()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_user_manage_access_meta()');
        $stmt->execute();

        $estimators = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $cities = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['estimators' => $estimators, 'cities' => $cities], 201);
    }


    //Create User Manage Access
//    public function createusermanageaccess(Request $request)
//    {
//        $validator = Validator::make($request->all(), [
//            'estimator_id' => 'required',
//        ]);
//
//        if ($validator->fails()) {
//            $error = $validator->errors()->first();
//            return response()->json(['error' => $error], 403);
//        }
//
//        $usermanageaccess = new UserManageAccess();
//        $usermanageaccess->estimator_id = $request->input('estimator_id');
//        $authorizeCityArray = $request->input('authorizeCityArray');
//        $authorizeZipCodeArray = $request->input('authorizeZipCodeArray');
//
//        if ($authorizeCityArray != null && sizeof($authorizeCityArray) > 0) {
//            $usermanageaccess->authorizecity()->createMany($authorizeCityArray);
//        }
//
//        if ($authorizeZipCodeArray != null && sizeof($authorizeZipCodeArray) > 0) {
//            $usermanageaccess->authorizezipcode()->saveMany($authorizeZipCodeArray);
//        }
//
//        $usermanageaccess->created_at = $request->input('created_at');
//        $usermanageaccess->updated_at = $request->input('updated_at');
//        $usermanageaccess->save();
//
//        return response()->json(['success' => 'User Access Managed successfully',
//            'usermanageaccess' => $usermanageaccess], 201);
//    }

    public function saveauthorizecity(Request $request)
    {

//        $validator = Validator::make($request->all(), [
//            'city_id' => ['required', Rule::unique('authorize_cities')->ignore($authorizecity->estimator_id),
//                'estimator_id' => 'required'],
//        ]);

        $authorizecity = new AuthorizeCity();

        $authorizecity->estimator_id = $request->input('estimator_id');
        $estimator_id = $authorizecity->estimator_id;

        $validator = Validator::make($request->all(), [
            'city_id' => [
                 'required',
                'estimator_id' => 'required',
                Rule::unique('authorize_cities')->
                ignore($authorizecity->city_id, 'city_id')
                    ->where('estimator_id', $estimator_id),
            ],
        ]);


        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => 'This City is already assigned to this estimator'], 403);
        }

        $authorizecity->city_id = $request->input('city_id');
        $authorizecity->created_at = $request->input('created_at');
        $authorizecity->updated_at = $request->input('updated_at');
        $authorizecity->save();

        return response()->json(['success' => 'City authorized successfully',
            'authorizecity' => $authorizecity, 'city_id' => $authorizecity->id], 201);
    }


    public function saveauthorizezipcode(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'zip_code' => 'required|unique:authorize_zip_codes',
            'estimator_id' => 'required',

        ]);

        $authorizezipcode = new AuthorizeZipCode();
        $authorizezipcode->estimator_id = $request->input('estimator_id');
        $estimator_id = $authorizezipcode->estimator_id;

        $validator = Validator::make($request->all(), [
            'zip_code' => [
                'required',
                'estimator_id' => 'required',
                Rule::unique('authorize_zip_codes')->
                ignore($authorizezipcode->zip_code, 'zip_code')
                    ->where('estimator_id', $estimator_id),
            ],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => 'This Zip Code is already assigned to this estimator'], 403);
        }

        $authorizezipcode->estimator_id = $request->input('estimator_id');
        $authorizezipcode->zip_code = $request->input('zip_code');
        $authorizezipcode->created_at = $request->input('created_at');
        $authorizezipcode->updated_at = $request->input('updated_at');
        $authorizezipcode->save();

        return response()->json(['success' => 'Zip Code authorized successfully',
            'authorizezipcode' => $authorizezipcode, 'zip_code_id' => $authorizezipcode->id], 201);
    }

    public function getauthorizationmeta($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_authorized_estimator_details_meta(?)');
        $stmt->execute(array($id));

        $authorized_city_details = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $authorized_zip_code_details = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['authorized_city_details' => $authorized_city_details, 'authorized_zip_code_details' => $authorized_zip_code_details], 201);
    }

    public function deleteAuthorizedcity($id)
    {
        $authorized_city = AuthorizeCity::find($id);
        $authorized_city->delete();
        return response()->json(['success' => 'City deleted successfully'], 201);

    }

    public function deleteAuthorizedzipcode($id)
    {
        $authorized_zip_code = AuthorizeZipCode::find($id);
        if (isset($authorized_zip_code)) {
            $authorized_zip_code->delete();
            return response()->json(['success' => 'Zip Code deleted successfully'], 201);
        }

    }

}
