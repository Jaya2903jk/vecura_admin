<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeBond extends Model
{
    protected $table = 'employee_bonds';

    protected $fillable = [
        'user_id',
        'bond_duration_years',
        'bond_start_date',
        'bond_end_date',
        'bond_amount',
        'bond_conditions',
        'bond_document_file',
        'bond_status',
    ];

    protected $casts = [
        'bond_start_date' => 'date',
        'bond_end_date' => 'date',
        'bond_amount' => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
