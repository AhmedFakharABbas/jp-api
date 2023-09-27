<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/19/19
 * Time: 2:50 AM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    public $table = "todo";
    protected $fillable = ['id', 'name', 'task_type_id', 'description', 'created_at', 'updated_at','user_id' , 'is_completed'];

}
