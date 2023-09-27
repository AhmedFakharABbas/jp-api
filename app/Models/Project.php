<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 07/09/19
 * Time: 1:17 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['id', 'customer_id', 'is_customer_address', 'address_1',
        'address_2', 'city_id','city_name', 'state_id', 'zip_code',
        'sub_division_name', 'major_intersection', 'project_type_id',
        'project_description', 'internal_notes', 'nick_names',
        'status_id', 'potential_type_id,', 'supervisor_id',
        'estimator_id', 'estimator_work_start_date',
        'crew_id', 'crew_work_start_date', 'crew_work_end_date',
        'start_date', 'end_date', 'location_map_url', 'total_cost',
        'created_by', 'created_at', 'updated_at', 'is_deleted'];

    public function attachments()
    {
        return $this->hasMany('App\Models\ProjectAttachment');
    }
}
