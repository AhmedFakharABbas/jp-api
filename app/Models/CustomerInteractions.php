<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 11/08/19
 * Time: 3:15 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerInteractions extends Model
{
    protected $fillable = ['id', 'customer_id', 'project_id',
        'interaction_date', 'interaction_type',
        'interaction_notes', 'performed_by_id', 'is_show_notes',
        'is_show_projects', 'is_show_appointments', 'is_show_calls', 'is_show_expenses',
        'is_show_payments'];

    protected $table = 'customer_interactions';
}
