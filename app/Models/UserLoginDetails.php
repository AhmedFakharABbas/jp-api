<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/01/19
 * Time: 4:01 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class UserLoginDetails extends Model
{
    protected $fillable = ['id', 'users_id', 'username', 'login_date', 'ip_address', 'is_activate'];
}
