<?php
/**
 * Created by PhpStorm.
 * User: wasee
 * Date: 27-Jul-19
 * Time: 4:31 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteriorDescription extends Model
{
    protected $fillable = ['id','project_details_id','interior_description_area','interior_special_notes','interior_final_price','created_at','updated_at'];

    protected  $table = 'interior_descriptions';

}
