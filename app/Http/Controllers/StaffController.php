<?php

namespace App\Http\Controllers;

use App\Models\UserMaster;
use App\Models\NewBranch;
use App\Models\Designation;
use App\Models\IssueDepartment;
use App\Models\Role;
use App\Models\EmployeeRole;
use App\Models\EmployeeOnboarding;
use App\Models\EmployeeOffboarding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $department = $request->get('department', '');
        $designation = $request->get('designation', '');
        $status = $request->get('status', '');

        $query = UserMaster::query();

        if ($search) {
            $query->where('FullName', 'like', "%$search%")
                ->orWhere('UserCode', 'like', "%$search%")
                ->orWhere('EmailId', 'like', "%$search%");
        }

        if ($department) {
            $query->whereHas('departments', function($q) use ($department) {
                $q->where('Departmentid', $department);
            });
        }

        if ($designation) {
            $query->where('Designation', $designation);
        }

        if ($status) {
            $query->where('UserStatus', $status);
        }

        $employees = $query->with(['roles', 'departments', 'designation', 'branch', 'manager', 'manager.designation'])
            ->paginate($perPage);

        $branches = NewBranch::where('is_active', 1)->get();
        $designations = Designation::all();
        $departments = IssueDepartment::all();
        $roles = Role::all();
        return view('staff.management', [
            'employees' => $employees,
            'branches' => $branches,
            'designations' => $designations,
            'departments' => $departments,
            'roles' => $roles,
            'perPage' => $perPage,
            'search' => $search,
            'department' => $department,
            'designation' => $designation,
            'status' => $status
        ]);
    }

    public function generateEmployeeCode()
    {
        try {
            // Get the last employee code from database
            $lastEmployee = DB::table('User_Master')
                ->where('employee_code', '!=', null)
                ->whereNotNull('employee_code')
                ->orderBy('UserID', 'desc')
                ->first(['employee_code']);

            if ($lastEmployee && $lastEmployee->employee_code) {
                // Extract number from code (e.g., "EMP-001" -> 1)
                preg_match('/(\d+)/', $lastEmployee->employee_code, $matches);
                $lastNumber = isset($matches[1]) ? intval($matches[1]) : 0;
            } else {
                $lastNumber = 0;
            }

            // Generate next code
            $nextNumber = $lastNumber + 1;
            $nextCode = 'EMP-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            return response()->json([
                'status' => true,
                'employee_code' => $nextCode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic Info
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'employee_code' => 'nullable|string|max:50|unique:User_Master,employee_code',
            'email' => 'nullable|email|max:255|unique:User_Master,EmailId',
            'date_of_birth' => 'nullable|date',

            // Employee Assignment
            'department_id' => 'required|exists:issueDepartmentMaster,Departmentid',
            'designation_code' => 'required|exists:DesignationMaster,DesignationCode',
            'office_type' => 'required|in:Branch Location,Corporate Office,Head Office,Regional Office',
            'branch_id' => 'nullable|exists:Branch,BranchID',

            // Personal Details
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Male,Female,Other',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',

            // Employment Details
            'employee_type' => 'nullable|in:Permanent,Temporary,Contract',
            'employee_category' => 'nullable|in:White Collar,Blue Collar',
            'date_of_joining' => 'nullable|date',

            // Financial/ID Info
            'blood_group' => 'nullable|string|max:5',
            'aadhar_number' => 'nullable|string|max:20|unique:employee_profiles,aadhar_number',
            'pan_number' => 'nullable|string|max:20|unique:employee_profiles,pan_number',
            'bank_account' => 'nullable|string|max:20',
            'ifsc_code' => 'nullable|string|max:20',

            // Emergency Contact
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_phone' => 'nullable|string|max:20',

            // Medical
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|string',

            // Status
            'employee_status' => 'required|in:Active,Inactive,On Leave',

            // Manager/Hierarchy
            'manager_id' => 'nullable|exists:User_Master,UserID',
        ]);

        try {
            DB::beginTransaction();

            // Create User Master record
            $empCode = $validated['employee_code'] ?? 'EMP-' . time();
            $username = strtolower(str_replace(' ', '.', $validated['first_name'] . '.' . $validated['last_name']));

            $employee = UserMaster::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'employee_code' => $empCode,
                'UserName' => $username,
                'Password' => bcrypt('Vecura@123'), // Default temporary password
                'EmailId' => $validated['email'],
                'date_of_birth' => $validated['date_of_birth'],
                'department_id' => $validated['department_id'],
                'designation_code' => $validated['designation_code'],
                'office_type' => $validated['office_type'],
                'branch_id' => $validated['branch_id'] ?? null,
                'manager_id' => $validated['manager_id'] ?? null,
                'employee_status' => $validated['employee_status'],
                'UserStatus' => $validated['employee_status'] === 'Active' ? 'Active' : 'InActive',
                'FullName' => $validated['first_name'] . ' ' . $validated['last_name'],
                'UserCode' => $empCode,
                'CreatedBy' => session('user_id'),
                'CreatedDate' => now(),
            ]);

            // Create Employee Profile
            DB::table('employee_profiles')->insert([
                'user_id' => $employee->UserID,
                'employee_category' => $validated['employee_category'] ?? 'White Collar',
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'phone_number' => $validated['phone'],
                'alternate_phone' => $validated['alternate_phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'employee_type' => $validated['employee_type'] ?? 'Permanent',
                'date_of_joining' => $validated['date_of_joining'],
                'blood_group' => $validated['blood_group'],
                'aadhar_number' => $validated['aadhar_number'],
                'pan_number' => $validated['pan_number'],
                'bank_account_number' => $validated['bank_account'],
                'ifsc_code' => $validated['ifsc_code'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Employee Medical
            DB::table('employee_medical')->insert([
                'user_id' => $employee->UserID,
                'blood_group' => $validated['blood_group'],
                'medical_conditions' => $validated['medical_conditions'],
                'allergies' => $validated['allergies'],
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Employee created successfully with all details',
                'employee' => $employee
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $employee = UserMaster::with(['roles', 'departments', 'designation', 'branch'])->findOrFail($id);
        $branches = NewBranch::where('Status', 0)->get();
        $designations = Designation::all();
        $departments = IssueDepartment::all();
        $roles = Role::all();

        return view('staff.show', [
            'employee' => $employee,
            'branches' => $branches,
            'designations' => $designations,
            'departments' => $departments,
            'roles' => $roles
        ]);
    }

    public function details($id)
    {
        $employee = UserMaster::with([
            'roles',
            'departments',
            'designation',
            'branch',
            'manager',
            'profile'
        ])->findOrFail($id);

        return view('staff.employee-details', [
            'employee' => $employee
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'designation' => 'required|string',
            'branch_id' => 'required|integer',
            'user_status' => 'required|in:Active,InActive',
        ]);

        try {
            $employee = UserMaster::findOrFail($id);
            $employee->update([
                'FullName' => $validated['full_name'],
                'EmailId' => $validated['email'],
                'Designation' => $validated['designation'],
                'branch_id' => $validated['branch_id'],
                'UserStatus' => $validated['user_status'],
                'ModifiedBy' => session('user_id'),
                'ModifiedDate' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function assignRole(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        try {
            $employee = UserMaster::findOrFail($employeeId);

            // Check if role already assigned
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

    public function removeRole(Request $request, $employeeId, $roleId)
    {
        try {
            $employee = UserMaster::findOrFail($employeeId);
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

    public function assignDepartment(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:IssueDepartment,Departmentid'
        ]);

        try {
            $employee = UserMaster::findOrFail($employeeId);

            if ($employee->departments()->where('department_id', $validated['department_id'])->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Department already assigned'
                ], 422);
            }

            $employee->departments()->attach($validated['department_id']);

            return response()->json([
                'status' => true,
                'message' => 'Department assigned successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function removeDepartment(Request $request, $employeeId, $departmentId)
    {
        try {
            $employee = UserMaster::findOrFail($employeeId);
            $employee->departments()->detach($departmentId);

            return response()->json([
                'status' => true,
                'message' => 'Department removed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function initiateOnboarding(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'onboarding_date' => 'required|date',
            'conducted_by' => 'required|string|max:100',
        ]);

        try {
            $onboarding = EmployeeOnboarding::firstOrCreate(
                ['user_id' => $employeeId],
                [
                    'onboarding_date' => $validated['onboarding_date'],
                    'conducted_by' => $validated['conducted_by'],
                    'onboarding_status' => 'In Progress',
                ]
            );

            return response()->json([
                'status' => true,
                'message' => 'Onboarding initiated successfully',
                'onboarding' => $onboarding
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOnboarding(Request $request, $employeeId)
    {
        try {
            $onboarding = EmployeeOnboarding::where('user_id', $employeeId)->firstOrFail();

            $updateData = [];
            if ($request->has('system_access_provided')) {
                $updateData['system_access_provided'] = $request->boolean('system_access_provided');
                $updateData['system_access_date'] = $request->boolean('system_access_provided') ? now()->toDateString() : null;
            }
            if ($request->has('id_card_issued')) {
                $updateData['id_card_issued'] = $request->boolean('id_card_issued');
                $updateData['id_card_date'] = $request->boolean('id_card_issued') ? now()->toDateString() : null;
            }
            if ($request->has('equipment_provided')) {
                $updateData['equipment_provided'] = $request->boolean('equipment_provided');
                $updateData['equipment_date'] = $request->boolean('equipment_provided') ? now()->toDateString() : null;
            }
            if ($request->has('orientation_completed')) {
                $updateData['orientation_completed'] = $request->boolean('orientation_completed');
                $updateData['orientation_date'] = $request->boolean('orientation_completed') ? now()->toDateString() : null;
            }
            if ($request->has('training_started')) {
                $updateData['training_started'] = $request->boolean('training_started');
                $updateData['training_start_date'] = $request->boolean('training_started') ? now()->toDateString() : null;
            }
            if ($request->has('documentation_submitted')) {
                $updateData['documentation_submitted'] = $request->boolean('documentation_submitted');
                $updateData['documentation_date'] = $request->boolean('documentation_submitted') ? now()->toDateString() : null;
            }

            $onboarding->update($updateData);

            if ($onboarding->system_access_provided && $onboarding->id_card_issued &&
                $onboarding->equipment_provided && $onboarding->orientation_completed &&
                $onboarding->documentation_submitted) {
                $onboarding->update(['onboarding_status' => 'Completed']);
            }

            return response()->json([
                'status' => true,
                'message' => 'Onboarding updated successfully',
                'onboarding' => $onboarding->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function initiateOffboarding(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'resignation_date' => 'required|date',
            'notice_period_days' => 'required|integer|min:0',
            'reason_for_resignation' => 'nullable|string',
        ]);

        try {
            $offboarding = EmployeeOffboarding::firstOrCreate(
                ['user_id' => $employeeId],
                [
                    'resignation_date' => $validated['resignation_date'],
                    'notice_period_days' => $validated['notice_period_days'],
                    'notice_period_end_date' => \Carbon\Carbon::parse($validated['resignation_date'])
                        ->addDays($validated['notice_period_days'])->toDateString(),
                    'reason_for_resignation' => $validated['reason_for_resignation'],
                    'offboarding_status' => 'In Progress',
                    'processed_by' => session('user_id'),
                    'processed_date' => now(),
                ]
            );

            UserMaster::findOrFail($employeeId)->update(['employee_status' => 'On Leave']);

            return response()->json([
                'status' => true,
                'message' => 'Offboarding initiated successfully',
                'offboarding' => $offboarding
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function submitRelievingCertificate(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'relieving_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificate_type' => 'required|in:relieving,experience',
        ]);

        try {
            $offboarding = EmployeeOffboarding::where('user_id', $employeeId)->firstOrFail();

            $file = $request->file('relieving_certificate');
            $filename = 'emp_' . $employeeId . '_' . $validated['certificate_type'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filepath = $file->storeAs('certificates', $filename, 'public');

            if ($validated['certificate_type'] === 'relieving') {
                $offboarding->update([
                    'relieving_certificate_submitted' => true,
                    'relieving_certificate_file' => $filepath,
                    'relieving_certificate_date' => now(),
                ]);
            } else {
                $offboarding->update([
                    'experience_certificate_submitted' => true,
                    'experience_certificate_file' => $filepath,
                    'experience_certificate_date' => now(),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => ucfirst($validated['certificate_type']) . ' certificate submitted successfully',
                'offboarding' => $offboarding->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completeOffboarding(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'relieving_date' => 'required|date',
            'all_dues_cleared' => 'required|boolean',
            'equipment_returned' => 'required|boolean',
            'final_remarks' => 'nullable|string',
        ]);

        try {
            $offboarding = EmployeeOffboarding::where('user_id', $employeeId)->firstOrFail();

            $offboarding->update([
                'relieving_date' => $validated['relieving_date'],
                'all_dues_cleared' => $validated['all_dues_cleared'],
                'equipment_returned' => $validated['equipment_returned'],
                'final_remarks' => $validated['final_remarks'],
                'offboarding_status' => 'Completed',
            ]);

            UserMaster::findOrFail($employeeId)->update(['employee_status' => 'Inactive']);

            return response()->json([
                'status' => true,
                'message' => 'Employee offboarding completed successfully',
                'offboarding' => $offboarding->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOnboardingStatus($employeeId)
    {
        try {
            $onboarding = EmployeeOnboarding::where('user_id', $employeeId)->first();

            return response()->json([
                'status' => true,
                'data' => $onboarding ?? ['message' => 'No onboarding record found']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getOffboardingStatus($employeeId)
    {
        try {
            $offboarding = EmployeeOffboarding::where('user_id', $employeeId)->first();

            return response()->json([
                'status' => true,
                'data' => $offboarding ?? ['message' => 'No offboarding record found']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
