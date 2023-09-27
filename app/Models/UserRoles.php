<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 12/10/19
 * Time: 6:49 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserRoles extends Model
{

    public $table = "user_roles";
    protected $fillable = [
        'id', 'user_id', 'role_id', 'created_at', 'updated_at'];


}
