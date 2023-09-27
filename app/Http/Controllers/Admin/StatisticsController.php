<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 01/15/20
 * Time: 5:52 PM
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDO;
use DB;

class StatisticsController extends Controller
{
    public function onFilterCustomerReferralSourceMarketingCosts(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_customer_referral_source_marketing_costs(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $customerReferralSourceMarketingCosts = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['customerReferralSourceMarketingCosts' => $customerReferralSourceMarketingCosts], 201);

    }

    public function onFilterOverallEstimateStatistics(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_overall_estimate_statistics(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $OverallEstimateStatistics = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['OverallEstimateStatistics' => $OverallEstimateStatistics], 201);
    }


    public function onFilterOverallWorkStatistics(Request $request)
    {

        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_overall_work_statistics(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $OverallWorkStatistics = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['OverallWorkStatistics' => $OverallWorkStatistics], 201);

    }

    public function onFilterEstimatesByCustomerZipCode(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_estimates_by_customer_zip_codes(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $EstimatesByCustomerZipCode = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['EstimatesByCustomerZipCode' => $EstimatesByCustomerZipCode], 201);
    }


    public function onFilterWorkByCustomerZipCode(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_work_by_customer_zip_code(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $WorkByCustomerZipCode = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['WorkByCustomerZipCode' => $WorkByCustomerZipCode], 201);
    }

    public function onFilterCSRCustomerToWorkStat(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_csr_customer_to_work_percentage(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $CsrCustomerToWorkStats = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['CsrCustomerToWorkStats' => $CsrCustomerToWorkStats], 201);
    }

    public function onFilterEstimatorBreakdowns(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_estimator_break_downs(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $EstimatorBreakdowns = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['EstimatorBreakdowns' => $EstimatorBreakdowns], 201);
    }

    public function onFilterEstimateToWorkStat(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_estimate_to_work_percentage(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $EstimateToWorkStats = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['EstimateToWorkStats' => $EstimateToWorkStats], 201);
    }

    public function onFilterMissingEstimates(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_missing_estimates(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $MissingEstimates = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['MissingEstimates' => $MissingEstimates], 201);
    }

    public function onFilterCrewBreakdowns(Request $request)
    {
        $pdo = DB::connection()->getpdo();
        $stmt = $pdo->prepare('CALL get_crew_break_downs(
        :start_date,
        :end_date )');

        $stmt->execute(array(
            ':start_date' => $request->input('start_date'),
            ':end_date' => $request->input('end_date')
        ));

        $CrewBreakdowns = $stmt->fetchAll(PDO::FETCH_CLASS, 'stdClass');
        $stmt->nextRowset();

        return response()->json(['CrewBreakdowns' => $CrewBreakdowns], 201);
    }


}
