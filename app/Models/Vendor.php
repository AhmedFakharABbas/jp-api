<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/03/19
 * Time: 12:45 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = ['id','company','first_name','last_name','email','extension','mobile_phone','fax','city' ,'city_name','state','zip_code','address_1','address_2'];
}


