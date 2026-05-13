<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrManpowerCandidate extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'hr_manpower_candidates';

    protected $primaryKey = 'candidateId';

    public $timestamps = false;

    protected $fillable = [
        'manpowerRequestId',
        'candidateName',
        'mobile',
        'email',
        'interviewDate',
        'status',
        'remarks',
        'employeeId',
        'createdBy',
        'createdAt',
        'updatedBy',
        'updatedAt',
    ];

    public function manpower()
    {
        return $this->belongsTo(
            HrManpowerRequest::class,
            'manpowerRequestId',
            'manpowerRequestId'
        );
    }
}
