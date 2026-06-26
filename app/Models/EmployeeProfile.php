<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeProfile extends Model
{
    protected $table = 'employee_profiles';

    protected $fillable = [
        'user_id',
        'employee_category', // White Collar, Blue Collar
        'date_of_birth',
        'gender',
        'phone_number',
        'alternate_phone',
        'address',
        'city',
        'state',
        'postal_code',
        'emergency_contact_name',
        'emergency_contact_phone',
        'employee_type', // Permanent, Temporary, Contract
        'date_of_joining',
        'date_of_resignation',
        'blood_group',
        'aadhar_number',
        'pan_number',
        'bank_account_number',
        'ifsc_code',
    ];

    public function employee()
    {
        return $this->belongsTo(UserMaster::class, 'user_id', 'UserID');
    }
}
