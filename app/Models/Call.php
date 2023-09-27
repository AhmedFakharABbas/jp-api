<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/16/19
 * Time: 12:12 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    public $table = "phone_calls";
    protected $fillable = ['id', 'customer_detail_obj', 'project_id', 'title', 'reason', 'call_date', 'call_time', 'assigned_to', 'created_by', 'created_at', 'modified_by', 'updated_at', 'is_deleted'];
}
