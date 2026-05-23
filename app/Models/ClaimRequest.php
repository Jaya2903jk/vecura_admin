<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimRequest extends Model
{
    protected $table = 'claim_requests';

    protected $primaryKey = 'claim_id';

    public $timestamps = false;

    protected $fillable = [
        'ticket_id',
        'iou_id',
        'employee_id',
        'expense_date',
        'expense_type',
        'expense_amount',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'expense_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =========================
    // RELATIONS
    // =========================

    public function iou()
    {
        return $this->belongsTo(
            IouRequest::class,
            'iou_id',
            'iou_id'
        );
    }

    public function employee()
    {
        return $this->belongsTo(
            UserMaster::class,
            'employee_id',
            'UserID'
        );
    }

    public function ticketTransactions()
    {
        return $this->hasMany(
            MoneyTransaction::class,
            'reference_id',
            'claim_id'
        )->where('type', 'claim');
    }
}
