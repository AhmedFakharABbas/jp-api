<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/28/19
 * Time: 11:45 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseItems extends Model
{

    protected $fillable = ['id','expense_id','product_name','description','color','formula','size','price','quantity','total_price'];
    protected  $table = 'expense_items';


}
