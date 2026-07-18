<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducationalDocument extends Model
{
    protected $table = 'employee_educational_documents';

    protected $fillable = [
        'user_id',
        'document_type',
        'document_number',
        'issue_date',
        'file_path',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
