<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintFollowup extends Model
{
    protected $table = 'complaint_followups';

    protected $primaryKey = 'id';

    public $timestamps = false;
    // since you are using created_at manually

    protected $fillable = [
        'complaint_id',
        'ticket_id',
        'level',
        'assigned_to',
        'action_by',
        'remarks',
        'status',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function assignedUser()
    {
        return $this->belongsTo(UserMaster::class, 'assigned_to', 'UserCode');
    }

    public function actionUser()
    {
        return $this->belongsTo(UserMaster::class, 'action_by', 'UserID');
    }
    // Each follow-up belongs to a complaint

    public function complaint()
    {
        return $this->belongsTo(CustomerRefundComplaint::class, 'complaint_id', 'complaintid');
    }
}
