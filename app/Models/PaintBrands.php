<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/19/19
 * Time: 4:36 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PaintBrands extends Model
{
    protected $fillable = ['id', 'name', 'is_active', 'created_at', 'updated_at'];
    protected $table = 'paint_brands';
}
