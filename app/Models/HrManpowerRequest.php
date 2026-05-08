<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrManpowerRequest extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'hr_manpower_requests_new';

    protected $primaryKey = 'manpowerRequestId';

    public $timestamps = false;

    protected $fillable = [
        'ticketId',
        'departmentId',
        'categoryId',
        'escalationTypeId',
        'designation',
        'vacancies',
        'jobDescription',
        'ageMin',
        'ageMax',
        'gender',
        'experience',
        'qualification',
        'skills',
        'workLocation',
        'requestType',
        'replacementFor',
        'approvalStatus',
        'recruitmentStatus',
        'onboardingStatus',
        'assigned_hr_id',
        'remarks',
        'attachmentPath',
        'originalFileName',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Department
    public function department()
    {
        return $this->belongsTo(
            IssueDepartment::class,
            'departmentId',
            'Departmentid'
        );
    }

    // Category
    public function category()
    {
        return $this->belongsTo(
            IssueCategory::class,
            'categoryId',
            'category_id'
        );
    }

    // Escalation
    public function escalation()
    {
        return $this->belongsTo(
            IssueMaster::class,
            'escalationTypeId',
            'IssueId'
        );
    }
}
