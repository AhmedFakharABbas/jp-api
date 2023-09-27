<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 07/09/19
 * Time: 1:17 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = ['company', 'first_name', 'last_name', 'address_1', 'address_2',
        'city_id','city_name', 'state_id', 'zip_code', 'sub_division_name', 'major_intersection',
        'home_phone', 'referral_source_id', 'referral_source_note', 'potential_type_id', 'is_deleted', 'deleted_at',
        'reference_status_type_id', 'home_phone,', 'work_phone', 'extention',
        'mobile_phone', 'deleted_at', 'fax', 'email', 'notes'];
}
