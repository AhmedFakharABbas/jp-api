<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 07/09/19
 * Time: 1:17 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class InteriorPaints extends Model
{

    protected $fillable = ['id','project_details_id','paint_area','coat_1','coat1_gallons','coat_2','coat2_gallons','trim','trim_coats','trim_gallons','ceiling','ceiling_coats'
        ,'ceiling_gallons','closet','price'];

    protected  $table = 'interior_paints';


}
