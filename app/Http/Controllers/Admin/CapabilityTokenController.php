<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/01/19
 * Time: 2:35 PM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Twilio\Jwt\ClientToken;

class CapabilityTokenController extends Controller
{

    public function getCapabilityToken()
    {
        //put your Twilio API credentials here
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $appSid = env('TWILIO_APP_SID');

        $capability = new ClientToken($accountSid, $authToken);
        $capability->allowClientOutgoing($appSid);
        $capability->allowClientIncoming('jp');
        $token = $capability->generateToken();

        return response()->json(['success' => 'Token Received Successfully', 'CapabilityToken' => $token], 200);
    }
}
