<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMaster extends Model
{
    protected $table = 'User_Master';

    protected $primaryKey = 'UserID';

    public $timestamps = false;

    protected $fillable = [
        'UserCode',
        'UserName',
        'Password',
        'FullName',
        'Loc_id',
        'CreatedBy',
        'CreatedDate',
        'ModifiedBy',
        'ModifiedDate',
        'UserGroupCode',
        'UserStatus',
        'ConsultantCode',
        'NewStatus',
        'IsNewUser',
        'EmailId',
        'SuberAdmin',
        'BlockedUserAccessDate',
        'AFTsms',
        'IPUser',
        'BlockUserLogin',
        'LastPwUpdate',
        'LoginAccess',
        'DasboardView',
        'notLogin',
        'LoginDateExtend',
        'LoginTimeExtend',
        'PublicIPDateExtend',
        'PublicIPTimeExtend',
        'Designation',
        'stateType',
        'ViewFlag',
        'CommonCode',
        'schedulepending',
        'Report',
        'LogoutTimeExtend',
        'popupWin',
        'headLocationGroup',
        'headsStatus',
        'POSignature',
        'team',
        'headsType',
        'branch_id',
        // Employee Management Fields
        'employee_code',
        'first_name',
        'last_name',
        'date_of_birth',
        'department_id',
        'designation_code',
        'office_type',
        'manager_id',
        'employee_status',
    ];
    // App\Models\UserMaster.php

    public function userGroup()
    {
        // Match UserGroupCode to UserGroupMaster.UserGroupCode
        return $this->belongsTo(UserGroupMaster::class, 'UserGroupCode', 'UserGroupCode');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'Designation', 'DesignationCode');
    }
     public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'DepartmentName', 'department_id');
    }

    public function branch()
    {
        return $this->belongsTo(NewBranch::class, 'branch_id', 'branch_id');
    }

    // RBAC Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'employee_roles', 'employee_id', 'role_id');
    }

    public function departments()
    {
        return $this->belongsToMany(
            IssueDepartment::class,
            'employee_departments',
            'employee_id',
            'department_id',
            'UserID',
            'Departmentid'
        );
    }

    public function hierarchyAccess()
    {
        return $this->hasMany(HierarchyAccess::class, 'employee_id', 'UserID');
    }

    // Employee Profile & Hierarchy
    public function profile()
    {
        return $this->hasOne(EmployeeProfile::class, 'user_id', 'UserID');
    }

    public function hierarchy()
    {
        return $this->hasOne(EmployeeHierarchy::class, 'employee_id', 'UserID');
    }

    public function manager()
    {
        return $this->belongsTo(UserMaster::class, 'manager_id', 'UserID');
    }

    public function subordinates()
    {
        return $this->hasMany(UserMaster::class, 'manager_id', 'UserID');
    }

    // Employee Workflow Relationships
    public function onboarding()
    {
        return $this->hasOne(EmployeeOnboarding::class, 'user_id', 'UserID');
    }

    public function offboarding()
    {
        return $this->hasOne(EmployeeOffboarding::class, 'user_id', 'UserID');
    }

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class, 'user_id', 'UserID');
    }

    public function certificates()
    {
        return $this->hasMany(EmployeeCertificate::class, 'user_id', 'UserID');
    }

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class, 'user_id', 'UserID');
    }

    public function educationalDocuments()
    {
        return $this->hasMany(EmployeeEducationalDocument::class, 'user_id', 'UserID');
    }

    public function bonds()
    {
        return $this->hasMany(EmployeeBond::class, 'user_id', 'UserID');
    }

    public function relieving()
    {
        return $this->hasOne(EmployeeRelieving::class, 'user_id', 'UserID');
    }
}
