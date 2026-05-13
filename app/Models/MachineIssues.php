<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineIssues extends Model
{
    use HasFactory;

    protected $table = 'MachineIssuesTable';

    protected $primaryKey = 'machineIssueId';

    public $timestamps = false;

    protected $fillable = [
        'IssuesName',
        'MachineId',
        'Type',
        'Status',
        'CreatedBy',
        'CreatedDate',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'MachineId', 'MachineId');
    }
}
