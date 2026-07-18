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
            'UserCode' => $employee->UserCode,
            'EmailId' => $employee->EmailId,
            'Designation' => $employee->Designation,
            'branch_id' => $employee->branch_id,
            'UserStatus' => $employee->UserStatus,
            'designation' => $employee->designation,
            'branch' => $employee->branch,
            'roles' => $employee->roles,
            'departments' => $employee->departments
        ]);
    }
}
