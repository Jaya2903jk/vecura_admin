<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrManpowerStatusHistory extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'hr_manpower_status_history';

    protected $primaryKey = 'historyId';

    public $timestamps = false;

    protected $fillable = [
        'manpowerRequestId',
        'candidateId',
        'oldStatus',
        'newStatus',
        'remarks',
        'changedBy',
        'changedAt',
    ];
}
