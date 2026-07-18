<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOffboarding extends Model
{
    protected $table = 'employee_offboarding';

    protected $fillable = [
        'user_id',
        'resignation_date',
        'notice_period_days',
        'notice_period_end_date',
        'relieving_date',
        'reason_for_resignation',
        'relieving_certificate_submitted',
        'relieving_certificate_file',
        'relieving_certificate_date',
        'experience_certificate_submitted',
        'experience_certificate_file',
        'experience_certificate_date',
        'all_certificates_returned',
        'certificate_return_date',
        'certificate_return_notes',
        'all_dues_cleared',
        'dues_clear_date',
        'dues_clearance_notes',
        'equipment_returned',
        'equipment_return_date',
        'equipment_return_notes',
        'offboarding_status',
        'final_remarks',
        'processed_by',
        'processed_date',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
