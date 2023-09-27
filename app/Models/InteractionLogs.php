<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 07/09/19
 * Time: 1:17 PM
 */
namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class InteractionLogs extends Model
{
    protected $fillable = ['InteractionLogID', 'CustomerID', 'InteractionTypeID', 'Notes', 'PerformedBy', 'CreatedOn'];
}