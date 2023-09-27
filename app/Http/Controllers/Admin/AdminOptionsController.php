<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/19/19
 * Time: 2:13 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commissions;
use App\Models\manage_calendars_user;
use App\Models\ManageCalendars;
use App\Models\ManageCalendarsUser;
use App\Models\MarketingExpenditures;
use App\Models\PaintBrands;
use App\Models\ReferralSource;
use App\User;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class AdminOptionsController extends Controller
{

    public function createReferralSource(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:referralsources',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json(['error' => $error], 403);
        }

        $referral_source = new ReferralSource();
        $referral_source->name = $request->input('name');
        $referral_source->is_active = $request->input('is_active');
        $referral_source->save();

        return response()->json(['success' => 'Referral Source created successfully', 'id' => $referral_source->id], 201);
    }

//    referralSource/all

    public function getReferralSources()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_referral_sources()');
        $stmt->execute();

        $referral_sources = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['referral_sources' => $referral_sources], 201);
    }


    public function getReferralSource($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_referral_source(?)');
        $stmt->execute(array($id));

        $referral_source = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['referral_source' => $referral_source], 201);
    }

    public function updateReferralSource(Request $request, $id)
    {
        $paint_brand = ReferralSource::find($id);
        $paint_brand->name = $request->input('name');
        $paint_brand->is_active = $request->input('is_active');
        $paint_brand->save();

        return response()->json(['success' => 'Referral source updated successfully', 'id' => $paint_brand->id,
            'Referral_status' => $paint_brand->is_active], 201);
    }

    //Paint Brands


    public function createPaintBrand(Request $request)
    {
        $paint_brand = new PaintBrands();
        $paint_brand->name = $request->input('name');
        $paint_brand->is_active = $request->input('is_active');
        $paint_brand->save();

        return response()->json(['success' => 'Paint Brand created successfully', 'id' => $paint_brand->id], 201);
    }


    public function getPaintBrands()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_paint_brands()');
        $stmt->execute();

        $paint_brand = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['paint_brand' => $paint_brand], 201);
    }


    public function getPaintBrand($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_paint_brand(?)');
        $stmt->execute(array($id));

        $paint_brand = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['paint_brand' => $paint_brand], 201);
    }

    public function updatePaintBrand(Request $request, $id)
    {
        $paint_brand = PaintBrands::find($id);
        $paint_brand->name = $request->input('name');
        $paint_brand->is_active = $request->input('is_active');
        $paint_brand->save();

        return response()->json(['success' => 'Paint Brand updated successfully', 'id' => $paint_brand->id], 201);
    }

    //Commissions

    public function getUsers()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_all_users()');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['users' => $users], 201);
    }

//    public function createCommissions(Request $request)
//    {
//        $commission = new Commissions();
//        $commission->user_id = $request->input('user_id');
//        $commission->commission = $request->input('commission');
//        $commission->save();
//
//        return response()->json(['success' => 'Commission created successfully', 'id' => $commission->id], 201);
//    }
//    public function getCommissions()
////    {
////        $pdo = DB::connection()->getpdo();
////        $stmt = $pdo->prepare('CALL get_commissions()');
////        $stmt->execute();
////
////        $commission = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
////        $stmt->nextRowset();
////
////        return response()->json(['commission' => $commission], 201);
////    }
//    public function getCommission($id)
//    {
//        $pdo = DB::connection()->getpdo();
//        $stmt = $pdo->prepare('CALL get_commission(?)');
//        $stmt->execute(array($id));
//
//        $commission = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
//        $stmt->nextRowset();
//
//        return response()->json(['commission' => $commission], 201);
//    }


    public function allUserLoginFilter(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL all_users_login_filter(?,?)');
        $stmt->execute(array(
            $request->input('start_date'),
            $request->input('end_date')
        ));
        $filtered_user_logins = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['filtered_user_logins' => $filtered_user_logins], 201);

    }


    public function updateCommission(Request $request, $id)
    {
        $commission = User::find($id);
        $commission->commission = $request->input('commission');
        $commission->save();

        return response()->json(['success' => 'User Commission updated successfully', 'id' => $commission->id], 201);
    }

    //marketing expenditure

    public function createMarketingExpenditure(Request $request)
    {
        $marketing_expenditure = new MarketingExpenditures();
        $marketing_expenditure->referral_source_id = $request->input('referral_source_id');
        $marketing_expenditure->amount_spent = $request->input('amount_spent');
        $marketing_expenditure->applies_from = $request->input('applies_from');
        $marketing_expenditure->applies_until = $request->input('applies_until');
        $marketing_expenditure->note = $request->input('note');
        $marketing_expenditure->save();
        return response()->json(['success' => 'Marketing Expenditure created successfully', 'id' => $marketing_expenditure->id], 201);
    }

    public function getMarketingExpenditure($id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_marketing_expenditure(?)');
        $stmt->execute(array($id));

        $marketing_expenditure = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['marketing_expenditure' => $marketing_expenditure], 201);
    }

    public function deleteMarketingExpenditure($id)
    {
        $marketing_expenditure = MarketingExpenditures::find($id);
        $marketing_expenditure->delete();
        return response()->json(['success' => 'Marketing Expenditure deleted successfully'], 200);
    }


    public function getMarketingExpenditures()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_marketing_expenditures()');
        $stmt->execute();

        $marketing_expenditure = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['marketing_expenditure' => $marketing_expenditure], 201);
    }

//    Manage Calendars Start


    public function createManageCalendars(Request $request)
    {

//        if($request->input('id') != null)
//        {
//            $managecalendar = ManageCalendars::find($request->input('id'));
//        }
//        else
//        {
//            $managecalendar = new ManageCalendars();
//        }

        $managecalendar = new ManageCalendars();
        $managecalendar->name = $request->input('name');
        $managecalendar->save();

        $manage_calendars_user_array = $request->input('calendarUsers');
        foreach ($manage_calendars_user_array as $mcu_array) {

//            if(isset($mcu_array['id']))
//            {
//                $managecalendarsuser = ManageCalendarsUser::find($mcu_array['id']);
//            }
//            else
//            {
//                $managecalendarsuser = new ManageCalendarsUser();
//            }

            $managecalendarsuser = new ManageCalendarsUser();
            $managecalendarsuser->user_id = $mcu_array['id'];
            $managecalendarsuser->manage_calendars_id = $managecalendar->id;
            $managecalendarsuser->save();
        }

        return response()->json(['success' => 'Manage Calendar created successfully',
            'manage_calendars_user_array' => $manage_calendars_user_array,
            'id' => $managecalendar->id], 201);

    }


    public function getCalendarNames()
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_calendar_names()');
        $stmt->execute();

        $calendars = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['calendars' => $calendars], 201);
    }


    public function getCalendarUsers($id, $user_id)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_calendar_users(?,?)');
        $stmt->execute(array($id, $user_id));

        $calendar_users = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $calendar_user_name = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['calendar_users' => $calendar_users,
            'calendar_user_name' => $calendar_user_name], 201);
    }

    public function updateManageCalendars(Request $request, $id)
    {
        $manage_calendars_user_array = $request->input('calendarUsers');
        foreach ($manage_calendars_user_array as $mcu_array) {

            $managecalendarsuserold = ManageCalendarsUser::find($mcu_array['id']);
            if (isset($managecalendarsuserold)) {
                if (isset($mcu_array['user_id'])) {
                    $managecalendarsuserold->user_id = $mcu_array['user_id'];
                    $managecalendarsuserold->manage_calendars_id = $id;
                    $managecalendarsuserold->save();
                }

            } else {
//                $managecalendarsuser = ManageCalendarsUser::find($mcu_array['id']);
                $managecalendarsuser = new ManageCalendarsUser();
                $managecalendarsuser->user_id = $mcu_array['id'];
                $managecalendarsuser->manage_calendars_id = $id;
                $managecalendarsuser->save();
            }
        }

        return response()->json(['success' => 'Manage Calendar Updated successfully',
            'manage_calendars_user_array' => $manage_calendars_user_array], 201);
    }


    public function deleteManageCalendars($id)
    {
        $managecalendars = ManageCalendars::find($id);
        $managecalendars->delete();
        return response()->json(['success' => 'Manage Calendar Deleted successfully'], 201);

    }

    public function getAllUserLogins(Request $request,$page_no)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_all_users_login(:current_page_no,:start_date,:end_date)');
//        $stmt->execute(array('current_page_no' => $page_no));

        $stmt->execute(array
        ('current_page_no' => $page_no,
          'start_date' =>  $request->input('start_date'),
          'end_date' => $request->input('end_date')
        ));

        $users_login = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        $total_logins = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $total_logins = $total_logins[0]->total_logins;

        return response()->json(['users_login' => $users_login,
            'total_logins' => $total_logins], 201);
    }

    // Restore Customers

    public function getDeletedCustomerSearchFilterMeta(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL search_deleted_customer_filter(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)');
        $stmt->execute(array(
            $request->input('first_name'), $request->input('last_name'),
            $request->input('project_number'), $request->input('home_phone'),
            $request->input('work_phone'), $request->input('mobile_phone'),
            $request->input('fax'), $request->input('email'),
            $request->input('company'), $request->input('address_1'),
            $request->input('address_2'), $request->input('city_id'),
            $request->input('state_id'), $request->input('zip_code'),
            $request->input('sub_division_name'), $request->input('major_intersection'),
        ));

        $deleted_customers = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['deleted_customers' => $deleted_customers], 201);
    }


    public function deleteManageCalendarsCurrentUser($id)
    {
        $managecalendars = ManageCalendarsUser::find($id);
        if (isset($managecalendars)) {
            $managecalendars->delete();
            return response()->json(['success' => 'Manage Calendar Deleted successfully'], 201);
        } else {
            return response()->json(['error' => 'Unable to delete! Record not found'], 500);
        }
    }
    public function getCalendarMeta($id)
    {
        $users = DB::select('Call get_calendar_meta(?)',array($id));
        return response()->json(['users' => $users],200);
    }
}

