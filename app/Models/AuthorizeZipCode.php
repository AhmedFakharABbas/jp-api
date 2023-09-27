<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 09/16/19
 * Time: 6:15 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class AuthorizeZipCode extends Model
{
    protected $fillable = ['id','estimator_id', 'zip_code', 'created_at', 'updated_at'];
    public $table = "authorize_zip_codes";
}
