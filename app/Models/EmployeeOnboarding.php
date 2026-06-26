<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{
    protected $table = 'employee_onboarding';

    protected $fillable = [
        'user_id',
        'onboarding_date',
        'system_access_provided',
        'system_access_date',
        'id_card_issued',
        'id_card_date',
        'equipment_provided',
        'equipment_details',
        'equipment_date',
        'orientation_completed',
        'orientation_date',
        'training_started',
        'training_start_date',
        'documentation_submitted',
        'documentation_date',
        'onboarding_status',
        'notes',
        'conducted_by',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
