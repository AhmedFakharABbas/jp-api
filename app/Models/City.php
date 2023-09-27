<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/16/19
 * Time: 12:12 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $table = "cities";
    protected $fillable = ['id', 'name'];
}

