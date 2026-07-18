<?php

namespace App\Http\Controllers;

use App\Models\UserMaster;
use App\Models\EmployeeProfile;
use App\Models\EmployeeHierarchy;
use App\Models\IssueDepartment;
use App\Models\Role;
use Illuminate\Http\Request;

class EmployeeDetailsController extends Controller
{
    public function show($id)
    {
        $employee = UserMaster::with([
            'profile',
            'hierarchy',
            'hierarchy.manager',
            'hierarchy.department',
            'roles',
            'designation',
            'branch'
        ])->findOrFail($id);

        $departments = IssueDepartment::all();
        $roles = Role::all();
        $managers = UserMaster::where('UserID', '!=', $id)->get();

        return view('employee.details', [
            'employee' => $employee,
            'departments' => $departments,
            'roles' => $roles,
            'managers' => $managers
        ]);
    }

    public function updateProfile(Request $request, $id)
    {
        $validated = $request->validate([
            'employee_category' => 'required|in:White Collar,Blue Collar',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Other',
            'phone_number' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'employee_type' => 'required|in:Permanent,Temporary,Contract',
            'date_of_joining' => 'nullable|date',
            'date_of_resignation' => 'nullable|date',
            'blood_group' => 'nullable|string|max:5',
            'aadhar_number' => 'nullable|string|max:20',
            'pan_number' => 'nullable|string|max:20',
            'bank_account_number' => 'nullable|string|max:20',
            'ifsc_code' => 'nullable|string|max:20',
        ]);

        try {
            $profile = EmployeeProfile::firstOrCreate(
                ['user_id' => $id],
                $validated
            );

            if (!$profile->wasRecentlyCreated) {
                $profile->update($validated);
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee profile updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateHierarchy(Request $request, $id)
    {
        $validated = $request->validate([
            'manager_id' => 'nullable|exists:User_Master,UserID',
            'department_id' => 'nullable|exists:IssueDepartment,Departmentid',
        ]);

        try {
            $hierarchy = EmployeeHierarchy::firstOrCreate(
                ['employee_id' => $id],
                $validated + ['is_active' => true]
            );

            if (!$hierarchy->wasRecentlyCreated) {
                $hierarchy->update($validated);
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee hierarchy updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function assignRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $employee = UserMaster::findOrFail($id);

            if ($employee->roles()->where('role_id', $validated['role_id'])->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Role already assigned'
                ], 422);
            }

            $employee->roles()->attach($validated['role_id'], [
                'is_active' => 1,
                'assigned_date' => now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Role assigned successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeRole($id, $roleId)
    {
        try {
            $employee = UserMaster::findOrFail($id);
            $employee->roles()->detach($roleId);

            return response()->json([
                'status' => true,
                'message' => 'Role removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
