<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/08/19
 * Time: 2:47 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PhoneCallsDetail extends Model
{
    public $table = "phone_calls_detail";
    protected $fillable = ['id', 'phone_calls_id', 'status', 'result', 'created_at', 'updated_at'];
}
