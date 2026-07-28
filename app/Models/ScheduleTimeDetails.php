<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleTimeDetails extends Model
{
    protected $table = 'ScheduleTimeDetails';
    protected $primaryKey = 'AppointmentId';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'AppointmentId',
        'AppointmentCode',
        'ScheduleCode',
        'DoctorCode',
        'CustomerCode',
        'ScheduleDate',
        'ScheduleFromTime',
        'ScheduleToTime',
        'ScheduleInterval',
        'Status',
        'Location',
        'CreatedBy',
        'CreatedDate',
        'ModifiedBy',
        'ModifiedDate',
        'VCSSCLocation',
        'VCSSCConsultant',
        'SurgeryStatus',
        'Paidstatus',
        'StockLocation',
        'BatchCode',
        'StockQty',
        'StockRemarks',
        'ProductCode',
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorMaster::class, 'DoctorCode', 'Doctor_Id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientPersonalDetail::class, 'CustomerCode', 'RegistrationNo');
    }
}
