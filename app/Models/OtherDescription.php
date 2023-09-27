<?php
/**
 * Created by PhpStorm.
 * User: wasee
 * Date: 27-Jul-19
 * Time: 4:37 PM
 */

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class OtherDescription extends Model
{
    protected $fillable = ['id','project_details_id','other_price','other_project_descriptions','created_at','updated_at'];

    protected  $table = 'other_descriptions';

}
