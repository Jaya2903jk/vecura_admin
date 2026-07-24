<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleConsultant extends Model
{
    protected $table = 'ScheduleConsultant';
    protected $primaryKey = 'ScheduleId';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'ScheduleCode',
        'Sch_Custid',
        'Sch_Custname',
        'Sch_Doctname',
        'Sch_Datetime',
        'Sch_AppointFor',
        'Sch_Status',
        'Sch_Treatmentname',
        'Sch_Treatmenttype',
        'Sch_Flag',
        'LOCATION',
        'CreatedDate',
        'CreatedBy',
        'ModifiedDate',
        'Modifiedby',
        'FreeScheduleFrm',
        'ShedConsultationCode',
        'Sch_Time',
        'ampm',
        'TotalSitting',
        'CurrentSitting',
        'ConsultationCode',
        'TrtScheduledCode',
        'TherapistCode',
        'BillNo',
        'CancelRemarks',
        'CancelDate',
        'Sch_CusType',
        'IsConverted',
        'NextSittingDate',
        'VCSSCLocation',
        'VCSSCConsultant',
        'TimeSlot',
        'SurgeryStatus',
        'FormUploaded',
        'AFTBillCode',
        'SchQty',
        'Surgeonmobile',
        'slipno',
        'ClinicalProduct',
        'ClinicalUOM',
        'ClinicalQty',
        'LeadCreatedBy',
        'FormStatus',
        'BeforephotoStatus',
        'MicroscopyStatus',
        'TrichoscanStatus',
        'TrichologyformStatus',
        'AfterPhotoStatus',
        'RegularformStatus',
        'Sch_Technician',
        'Sch_Physiotherapist',
        'Sch_Nutritionist',
        'Sch_TreatmentPlannedBy',
        'Sch_AreaOfExecution',
        'Sch_BeforeWeight',
        'Sch_AfterWeight',
        'Sch_TotalWeightLoss',
        'Sch_BeforeUpper',
        'Sch_BeforeMiddle',
        'Sch_BeforeLower',
        'Sch_AfterUpper',
        'Sch_AfterMiddle',
        'Sch_AfterLower',
        'Sch_TotalInches',
        'DietChat',
        'BeforephotoMarkingArea',
        'BeforephotoStatusWOMarkingArea',
        'BeforephotoStatusRegularClient',
        'BCAStatus',
        'Sch_Physiotherapist2',
        'SessionStatus',
        'NutritionalReview',
        'closedBillNo',
        'TUMMY',
        'RIGHTARM',
        'LEFTARM',
        'FLANKS',
        'BACK',
        'HIP',
        'RIGHTTHIGH',
        'LEFTTHIGH',
        'CHEST',
        'TUMMY_A',
        'RIGHTARM_A',
        'LEFTARM_A',
        'FLANKS_A',
        'BACK_A',
        'HIP_A',
        'RIGHTTHIGH_A',
        'LEFTTHIGH_A',
        'CHEST_A',
        'TUMMY_B',
        'RIGHTARM_B',
        'LEFTARM_B',
        'FLANKS_B',
        'BACK_B',
        'HIP_B',
        'RIGHTTHIGH_B',
        'LEFTTHIGH_B',
        'CHEST_B',
        'BACK_C',
        'SlipStatus',
        'MedicalStatus',
        'Sch_AppointDatetime',
        'Sch_AppointDuration',
    ];

    protected $casts = [
        'Sch_Datetime' => 'datetime',
        'CreatedDate' => 'datetime',
        'ModifiedDate' => 'datetime',
        'CancelDate' => 'datetime',
        'NextSittingDate' => 'datetime',
        'Sch_AppointDatetime' => 'datetime',
        'TotalSitting' => 'integer',
        'CurrentSitting' => 'integer',
        'SchQty' => 'float',
        'Sch_BeforeWeight' => 'float',
        'Sch_AfterWeight' => 'float',
        'Sch_TotalWeightLoss' => 'float',
        'Sch_BeforeUpper' => 'float',
        'Sch_BeforeMiddle' => 'float',
        'Sch_BeforeLower' => 'float',
        'Sch_AfterUpper' => 'float',
        'Sch_AfterMiddle' => 'float',
        'Sch_AfterLower' => 'float',
        'Sch_TotalInches' => 'float',
        'TUMMY' => 'float',
        'RIGHTARM' => 'float',
        'LEFTARM' => 'float',
        'FLANKS' => 'float',
        'BACK' => 'float',
        'HIP' => 'float',
        'RIGHTTHIGH' => 'float',
        'LEFTTHIGH' => 'float',
        'CHEST' => 'float',
        'TUMMY_A' => 'float',
        'RIGHTARM_A' => 'float',
        'LEFTARM_A' => 'float',
        'FLANKS_A' => 'float',
        'BACK_A' => 'float',
        'HIP_A' => 'float',
        'RIGHTTHIGH_A' => 'float',
        'LEFTTHIGH_A' => 'float',
        'CHEST_A' => 'float',
        'TUMMY_B' => 'float',
        'RIGHTARM_B' => 'float',
        'LEFTARM_B' => 'float',
        'FLANKS_B' => 'float',
        'BACK_B' => 'float',
        'HIP_B' => 'float',
        'RIGHTTHIGH_B' => 'float',
        'LEFTTHIGH_B' => 'float',
        'CHEST_B' => 'float',
        'BACK_C' => 'float',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(PatientPersonalDetail::class, 'Sch_Custid', 'PatientID');
    }
    public function appointmentFor()
    {
        return $this->belongsTo(AppointmentFor::class, 'Sch_AppointFor', 'AppointtCode');
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorMaster::class, 'Sch_Doctname', 'Doctor_Id');
    }

    public function location()
    {
        return $this->belongsTo(LocationMaster::class, 'LOCATION', 'LocationCode');
    }

    // Scopes
    public function scopeScheduled($query)
    {
        return $query->where('Sch_Status', 'SCHEDULED');
    }

    public function scopeCompleted($query)
    {
        return $query->where('Sch_Status', 'COMPLETED');
    }

    public function scopeCancelled($query)
    {
        return $query->where('Sch_Status', 'CANCELLED');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('Sch_Datetime', '>=', now())->where('Sch_Status', 'SCHEDULED');
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('LOCATION', $location);
    }

    public function scopeByDoctor($query, $doctorName)
    {
        return $query->where('Sch_Doctname', $doctorName);
    }

    public function scopeByPatient($query, $custId)
    {
        return $query->where('Sch_Custid', $custId);
    }

    // Accessors
    public function getScheduleDateTimeAttribute()
    {
        return $this->attributes['Sch_Datetime'] ?? null;
    }

    public function getTreatmentStatusAttribute()
    {
        if ($this->Sch_Status === 'COMPLETED') {
            return 'Completed';
        } elseif ($this->Sch_Status === 'CANCELLED') {
            return 'Cancelled';
        }
        return 'Scheduled';
    }
}
