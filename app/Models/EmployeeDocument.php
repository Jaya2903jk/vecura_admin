<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    protected $table = 'employee_documents';

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'issue_date',
        'expiry_date',
        'file_path',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }

    public function getDocumentTypeBadge()
    {
        return match($this->document_type) {
            'Degree' => 'bg-primary',
            '10th Certificate' => 'bg-info',
            '12th Certificate' => 'bg-warning',
            'Aadhar' => 'bg-success',
            'PAN' => 'bg-danger',
            default => 'bg-secondary'
        };
    }
}
