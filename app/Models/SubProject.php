<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 07/09/19
 * Time: 1:17 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class SubProject extends Model
{
    protected $fillable = ['id', 'project_id', 'number', 'name', 'status', 'crew_id',
    'work_start_date', 'work_end_date', 'description', 'notes','is_sub_project'];

    protected $table = 'sub_projects';

    public function subProjectsItems()
    {
        return $this->hasMany('App\Models\SubProjectItems');
    }
}
