<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 08/21/19
 * Time: 6:21 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProjectExpenses extends Model
{

    protected $fillable = ['id','project_id','expense_type','pay_to','collected_by',
        'description',
        'expense_notes','expense_date','paid_date','amount','ordered_by', 'status','created_by','modified_by','created_at','updated_at'
    ];

    public function expenseitems()
    {
        return $this->hasMany('App\Models\ExpenseItems');
    }

}
