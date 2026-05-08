<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintActionLog extends Model {
    //
    protected $table = 'ComplaintActionLogs';

    protected $fillable = [
        'ComplaintId',
        'Action',
        'Comment',
        'UserId',
        'UserName',
        'RoleId',
        'Level'
    ];

    public $timestamps = false;
}
