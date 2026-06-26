<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeRelieving extends Model
{
    protected $table = 'employee_relieving';

    protected $fillable = [
        'user_id',
        'resignation_date',
        'notice_completion_date',
        'relieving_date',
        'reason_for_resignation',
        'all_dues_cleared',
        'equipment_returned',
        'final_remarks',
        'relieving_status',
    ];

    protected $casts = [
        'resignation_date' => 'date',
        'notice_completion_date' => 'date',
        'relieving_date' => 'date',
        'all_dues_cleared' => 'boolean',
        'equipment_returned' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
