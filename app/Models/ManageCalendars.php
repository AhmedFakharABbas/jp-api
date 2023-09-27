<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/23/19
 * Time: 10:06 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ManageCalendars extends Model
{

    protected $fillable = ['id', 'name', 'created_at', 'updated_at'];
    protected $table = 'manage_calendars';


}
