<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 09/14/19
 * Time: 6:06 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserManageAccess extends Model
{
    protected $fillable = ['id', 'estimator_id','user_manage_access_id' ,'created_at', 'updated_at'];
    protected $table = 'user_manage_accesses';

//    public function authorizecity()
//    {
//        return $this->hasMany('App\Models\AuthorizeCity','user_manage_access_id','id');
//    }
//
//    public function authorizezipcode()
//    {
//        return $this->hasMany('App\Models\AuthorizeZipCode','user_manage_access_id','id');
//    }

}
