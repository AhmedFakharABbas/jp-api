<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/15/19
 * Time: 2:39 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Calendars extends Model
{

    public $table = "calendars";
    protected $fillable = [
        'id', 'role_id' ,'appointment_type', 'appointment_for', 'appointment_with','manage_calendar_id',
        'project_for_appointment', 'start_date', 'start_time',
        'end_date', 'end_time',
        'duration', 'display_text', 'use_default', 'appointment_details',
//        'event', 'event_data_type',
        'created_at', 'updated_at'];

}
