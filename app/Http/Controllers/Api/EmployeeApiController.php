<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserMaster;

class EmployeeApiController extends Controller
{
    public function show($id)
    {
        $employee = UserMaster::with(['roles', 'departments', 'designation', 'branch'])->findOrFail($id);

        return response()->json([
            'UserID' => $employee->UserID,
            'FullName' => $employee->FullName,
            'first_name' => $employee->first_name,
            'last_name' => $employee->last_name,
            'UserCode' => $employee->UserCode,
            'EmailId' => $employee->EmailId,
            'department_id' => $employee->department_id,
            'designation_code' => $employee->designation_code,
            'office_type' => $employee->office_type,
            'Designation' => $employee->Designation,
            'branch_id' => $employee->branch_id,
            'UserStatus' => $employee->UserStatus,
            'employee_status' => $employee->employee_status,
            'designation' => $employee->designation,
            'branch' => $employee->branch,
            'roles' => $employee->roles,
            'departments' => $employee->departments
        ]);
    }
}
