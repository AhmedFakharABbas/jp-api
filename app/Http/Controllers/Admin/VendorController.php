<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/03/19
 * Time: 1:16 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Illuminate\Support\Facades\Validator;
use PDO;
use DB;


class VendorController extends Controller
{
    //Create Vendor
    public function create(Request $request)
    {

//        $validator = Validator::make($request->all(), [
//            'company' => 'required|unique:vendors'
//        ]);
//
//        if ($validator->fails()) {
//            $error = $validator->errors()->first();
//            return response()->json(['error' => $error], 403);
//        }

        $vendor = new Vendor();
        $vendor->first_name = $request->input('first_name');
        $vendor->last_name = $request->input('last_name');
        $vendor->email = $request->input('email');
        $vendor->city = $request->input('city');
        $vendor->zip_code = $request->input('zip_code');
        $vendor->state = $request->input('state');
        $vendor->work_phone = $request->input('work_phone');
        $vendor->address_1 = $request->input('address_1');
        $vendor->address_2 = $request->input('address_2');
        $vendor->company = $request->input('company');
        $vendor->mobile_phone = $request->input('mobile_phone');
        $vendor->extension = $request->input('extension');
        $vendor->fax = $request->input('fax');
        $vendor->save();
        return response()->json(['success' => 'Vendor created successfully'], 201);
    }

    //get single vendor by id
    public function get($id)
    {
        $vendor = Vendor::find($id);

        $citId=$vendor->city;

        if($citId!=null){
        $City = City::find($citId);
            $vendor->city_name=$City->name;

        }
        return response()->json(['vendor' => $vendor], 201);
    }


    //Update Vendor
    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);
        $vendor->first_name = $request->input('first_name');
        $vendor->last_name = $request->input('last_name');
        $vendor->email = $request->input('email');
        $vendor->city = $request->input('city');
        $vendor->zip_code = $request->input('zip_code');
        $vendor->state = $request->input('state');
        $vendor->work_phone = $request->input('work_phone');
        $vendor->address_1 = $request->input('address_1');
        $vendor->address_2 = $request->input('address_2');
        $vendor->company = $request->input('company');
        $vendor->mobile_phone = $request->input('mobile_phone');
        $vendor->extension = $request->input('extension');
        $vendor->fax = $request->input('fax');
        $vendor->save();
        return response()->json(['success' => 'Vendor updated successfully', 'vendor' => $vendor], 201);
    }

    //Delete Vendor
    public function delete($id)
    {
        $vendor = Vendor::find($id);
        $vendor->delete();
        return response()->json(['success' => 'Vendor Deleted successfully'], 201);
    }

    //get all vendors
    public function getallvendors()
    {
        $vendor = Vendor::all();

        return response()->json(['vendor' => $vendor], 201);
    }

    public function getStatesAndCities()
    {
        //  get_states_and_cities

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_states_and_cities()');
        $stmt->execute();

        $cities = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $states = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['cities' => $cities, 'states' => $states], 201);
    }

}
