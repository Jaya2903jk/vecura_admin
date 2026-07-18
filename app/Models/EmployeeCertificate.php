<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCertificate extends Model
{
    protected $table = 'employee_certificates';

    protected $fillable = [
        'user_id',
        'certificate_name',
        'issuing_organization',
        'issue_date',
        'expiry_date',
        'certificate_number',
        'description',
        'file_path',
        'is_original_submitted',
        'is_handover_received',
        'handover_date',
        'received_by',
        'notes',
        'status',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
