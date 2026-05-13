<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrManpowerAssignment extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'hr_manpower_assignment';

    protected $primaryKey = 'assignmentId';

    public $timestamps = false;

    protected $fillable = [
        'manpowerRequestId',
        'assignedTo',
        'assignedBy',
        'assignedDate',
        'isSelfAssigned',
    ];
}
