<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/20/19
 * Time: 3:43 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MarketingExpenditures extends Model
{
    protected $fillable = ['id', 'referral_source_id' , 'amount_spent', 'applies_from',
        'applies_until', 'note', 'created_at', 'updated_at'];
    protected $table = 'marketing_expenditures';
}
