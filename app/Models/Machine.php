<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    use HasFactory;

    protected $table = 'MachineTable';

    protected $primaryKey = 'MachineId';

    public $timestamps = false;

    protected $fillable = [
        'MachineName',
        'MachineRelated',
        'Status',
        'CreatedBy',
        'CreatedDate',
    ];

    public function MachineIssues()
    {
        return $this->hasMany(MachineIssues::class, 'MachineId', 'MachineId');
    }
}
