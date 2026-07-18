<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    protected $table = 'employee_education';

    protected $fillable = [
        'user_id',
        'education_level',
        'institution_name',
        'course_name',
        'field_of_study',
        'graduation_date',
        'grade_percentage',
        'certificate_file',
        'certificate_handover_received',
        'handover_date',
        'received_by',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
