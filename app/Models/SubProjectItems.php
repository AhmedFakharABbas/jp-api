<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 09/14/19
 * Time: 11:11 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProjectItems extends Model
{
    public $table = "sub_project_items";
    protected $fillable = ['id', 'sub_project_id', 'item_id', 'created_at','updated_at'];
}
