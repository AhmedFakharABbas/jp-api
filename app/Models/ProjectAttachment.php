<?php
/**
 * Created by PhpStorm.
 * User: Kamran Qayyum
 * Date: 10/11/19
 * Time: 7:28 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class ProjectAttachment extends Model
{
    protected $fillable = [
        'id', 'project_id', 'encrypted_name', 'original_name', 'created_at', 'updated_at'
    ];

    protected $table = 'project_attachments';
}
