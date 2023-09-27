<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 09/16/19
 * Time: 6:06 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class AuthorizeCity extends Model
{
    protected $fillable = ['id', 'estimator_id','city_id' , 'created_at', 'updated_at'];
    public $table = "authorize_cities";
}
