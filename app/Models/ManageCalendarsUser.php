<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/23/19
 * Time: 11:49 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ManageCalendarsUser extends Model
{

    protected $fillable = ['id', 'user_id', 'manage_calendars_id' , 'created_at', 'updated_at'];
    protected $table = 'manage_calendars_user';

}
