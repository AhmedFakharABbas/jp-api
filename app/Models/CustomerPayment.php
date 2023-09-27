<?php
/**
 * Created by PhpStorm.
 * User: wasee
 * Date: 31-Jul-19
 * Time: 3:15 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    protected $fillable = ['id', 'project_id', 'payment_date', 'payment_amount', 'payment_method', 'cheque_number',
        'payment_collected_by', 'payment_notes', 'created_at','updated_at'];
}
