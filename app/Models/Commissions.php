<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/19/19
 * Time: 6:03 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Commissions extends Model
{
    protected $fillable = ['id', 'user_id', 'commission', 'created_at', 'updated_at'];
    protected $table = 'commission';
}
