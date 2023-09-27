<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/19/19
 * Time: 2:08 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ReferralSource extends Model
{
    protected $fillable = ['id', 'name', 'is_active', 'created_at', 'updated_at'];
    protected $table = 'referralsources';
}
