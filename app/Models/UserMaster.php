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
}
