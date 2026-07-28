<?php

namespace App\Http\Controllers;

use App\Models\UserMaster;
use App\Models\NewBranch;
use App\Models\Designation;
use App\Models\IssueDepartment;
use App\Models\Role;
use App\Models\EmployeeRole;
use App\Models\DoctorMaster;
use App\Models\EmployeeOnboarding;
use App\Models\EmployeeOffboarding;
use App\Mail\EmployeeWelcomeMail;
use App\Services\OfferLetterService;
use App\Services\MasterDataCacheService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class StaffController extends Controller
{
    // public function index(Request $request
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $department = $request->get('department', '');
        $designation = $request->get('designation', '');
        $status = $request->get('status', '');
        $branch = $request->get('branch', '');

        $query = UserMaster::query();

        // Manager sees only their subordinates, Admin sees all
        if (!session('is_admin')) {
            $userId = session('user_id');
            $query->where('manager_id', $userId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('FullName', 'like', "%{$search}%")
                    ->orWhere('UserCode', 'like', "%{$search}%")
                    ->orWhere('EmailId', 'like', "%{$search}%");
            });
        }

        if ($department) {
            $query->whereHas('departments', function ($q) use ($department) {
                $q->where('Departmentid', $department);
            });
        }

        if ($designation) {
            $query->where('Designation', $designation);
        }

        if ($status) {
            $query->where('UserStatus', $status);
        }

        if ($branch) {
            $query->where('branch_id', $branch);
        }

        $employees = $query->with(['roles', 'departments', 'department', 'designation', 'branch', 'manager', 'manager.designation'])
            ->paginate($perPage);

        // CHECK IF THIS IS AN AJAX REQUEST - Return JSON
        if ($request->ajax()) {
            return response()->json([
                'employees' => $employees->items(),
                'total' => $employees->total(),
                'per_page' => $employees->perPage(),
                'current_page' => $employees->currentPage()
            ]);
        }

        // Return view for regular page load (using cached master data)
        $branches = MasterDataCacheService::getBranches();
        $designationsList = MasterDataCacheService::getDesignations();
        $departmentsList = MasterDataCacheService::getDepartments();
        $rolesList = MasterDataCacheService::getRoles();

        return view('staff.management', [
            'employees' => $employees,
            'branches' => $branches,
            'designations' => $designationsList,
            'departments' => $departmentsList,
            'roles' => $rolesList,
            'perPage' => $perPage,
            'search' => $search,
            'department' => $department,
            'designation' => $designation,
            'status' => $status,
            'branch' => $branch
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
        // Ensure JSON response for JSON requests
        $request->headers->set('Accept', 'application/json');
        // dd($request->all());
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
            'branch_id' => 'nullable|exists:Branch,branch_id',

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

            $currentUserCode = $this->getCurrentUserCode();

            $employee = UserMaster::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'employee_code' => $empCode,
                'UserName' => $username,
                'Password' => bcrypt('Vecura@123'), // Default temporary password
                'EmailId' => $validated['email'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'department_id' => $validated['department_id'],
                'designation_code' => $validated['designation_code'],
                'Designation' => $validated['designation_code'],
                'office_type' => $validated['office_type'],
                'branch_id' => $validated['branch_id'] ?? null, 
                'manager_id' => $validated['manager_id'] ?? null,
                'employee_status' => $validated['employee_status'],
                'UserStatus' => $validated['employee_status'] === 'Active' ? 'Active' : 'InActive',
                'FullName' => $validated['first_name'] . ' ' . $validated['last_name'],
                'UserCode' => $empCode,
                'CreatedBy' => $currentUserCode,
                'CreatedDate' => now(),
            ]);

            // Create Employee Profile
            DB::table('employee_profiles')->insert([
                'user_id' => $employee->UserID,
                'employee_category' => $validated['employee_category'] ?? 'White Collar',
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'phone_number' => $validated['phone'] ?? null,
                'alternate_phone' => $validated['alternate_phone'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'employee_type' => $validated['employee_type'] ?? 'Permanent',
                'date_of_joining' => $validated['date_of_joining'] ?? null,
                'blood_group' => $validated['blood_group'] ?? null,
                'aadhar_number' => $validated['aadhar_number'] ?? null,
                'pan_number' => $validated['pan_number'] ?? null,
                'bank_account_number' => $validated['bank_account'] ?? null,
                'ifsc_code' => $validated['ifsc_code'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create Employee Medical
            DB::table('employee_medical')->insert([
                'user_id' => $employee->UserID,
                'blood_group' => $validated['blood_group'] ?? null,
                'medical_conditions' => $validated['medical_conditions'] ?? null,
                'allergies' => $validated['allergies'] ?? null,
                'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Assign roles if provided
            if (!empty($request->input('role_ids'))) {
                $roleIds = is_array($request->input('role_ids')) ? $request->input('role_ids') : [$request->input('role_ids')];
                foreach ($roleIds as $rId) {
                    if ($rId) {
                        $employee->roles()->attach($rId, ['is_active' => 1]);
                    }
                }
            }

            // Create Doctor_Master entry if Consultant/Doctor/Wellness Expert designation or role is assigned
            $designationObj = Designation::where('DesignationCode', $validated['designation_code'])->first();
            $desigName = strtolower($designationObj->Designation ?? $validated['designation_code']);
            $assignedRoleIds = array_map('intval', (array) ($request->input('role_ids') ?? []));
            $isDoctor = str_contains($desigName, 'doctor')
                || str_contains($desigName, 'Consultant')
                || str_contains($desigName, 'wellness')
                || str_contains($desigName, 'dietitian')
                || in_array(8, $assignedRoleIds);
            if ($isDoctor) {
                $branchObj = NewBranch::where('branch_id', $validated['branch_id'] ?? null)
                    ->orWhere('branch_id', $validated['branch_id'] ?? null)
                    ->first();

                $locationCode = $branchObj->branch_code ?? $branchObj->branch_code ?? 'ANR';
                $doctorId = $this->generateDoctorId();
                $creatorUserCode = optional(UserMaster::find(session('user_id')))->UserCode ?? 'USE-0209';

                DoctorMaster::create([
                    'Doctor_Id' => $doctorId,
                    'DoctorName' => $employee->FullName,
                    'Locations' => $locationCode,
                    'CreatedBy' => $creatorUserCode,
                    'CreatedDate' => now(),
                    'ModifiedBy' => $creatorUserCode,
                    'ModifiedDate' => now(),
                    'Status' => $employee->UserStatus === 'Active' ? 'Active' : 'Inactive',
                    'UserCode' => $empCode,
                    'CommonCode' => 'VEC-' . rand(1000, 9999),
                ]);
            }

            DB::commit();

            // Send welcome email with offer letter (optional - doesn't block employee creation)
            try {
                $employee->load(['designation', 'department', 'branch']);

                if ($employee->EmailId) {
                    $offerLetterPath = null;

                    // Generate PDF offer letter
                    if (class_exists('Barryvdh\DomPDF\Facade\Pdf')) {
                        try {
                            $offerLetterService = new OfferLetterService();
                            $generatedPath = $offerLetterService->generateOfferLetter($employee);

                            if ($generatedPath && file_exists($generatedPath)) {
                                $offerLetterPath = $generatedPath;
                                Log::info('PDF generated successfully: ' . $offerLetterPath);
                            } else {
                                Log::warning('PDF generation failed: File not found at ' . $generatedPath);
                            }
                        } catch (\Exception $pdfError) {
                            Log::warning('PDF generation exception: ' . $pdfError->getMessage());
                        }
                    }

                    // Queue welcome email with PDF attachment (sends in background)
                    Mail::queue(new EmployeeWelcomeMail($employee, $offerLetterPath));
                    Log::info('Welcome email queued for ' . $employee->EmailId . ' for employee ' . $employee->UserCode . (($offerLetterPath) ? ' with PDF attached' : ' without PDF'));
                }
            } catch (\Exception $e) {
                Log::error('Failed to send welcome email for employee ' . $employee->UserID . ': ' . $e->getMessage());
            }

            return response()->json([
                'status' => true,
                'message' => 'Employee created successfully',
                'employee' => [
                    'id' => $employee->UserID,
                    'code' => $employee->employee_code,
                    'name' => $employee->FullName,
                    'email' => $employee->EmailId
                ],
                'default_password' => 'Vecura@123'
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
            'profile',
            'bonds',
            'relieving',
            'documents',
            'educationalDocuments'
        ])->findOrFail($id);

        // Load medical data
        $medical = DB::table('employee_medical')->where('user_id', $id)->first();
        $employee->medical = $medical;

        $departments = IssueDepartment::all();
        $designations = Designation::all();
        $employees = UserMaster::where('UserStatus', 'Active')->get();
        $branches = NewBranch::where('is_active', 1)->get();

        return view('staff.employee-details', [
            'employee' => $employee,
            'departments' => $departments,
            'designations' => $designations,
            'employees' => $employees,
            'branches' => $branches,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Check if this is a status-only update
        if (($request->has('user_status') || $request->has('employee_status')) && count($request->all()) <= 3) {
            // User Status update
            if ($request->has('user_status')) {
                $validated = $request->validate([
                    'user_status' => 'required|in:Active,InActive',
                ]);

                try {
                    $employee = UserMaster::findOrFail($id);
                    $employee->update(['UserStatus' => $validated['user_status']]);

                    return response()->json([
                        'status' => true,
                        'message' => 'User status updated successfully'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ], 500);
                }
            }

            // Employee Status update
            if ($request->has('employee_status')) {
                $validated = $request->validate([
                    'employee_status' => 'required|in:Active,Inactive,On Probation,Confirmed,Notice Period,Resigned,Terminated,Absconding,On Leave,Relieved',
                ]);

                try {
                    $employee = UserMaster::findOrFail($id);
                    $employee->update(['employee_status' => $validated['employee_status']]);

                    return response()->json([
                        'status' => true,
                        'message' => 'Employee status updated successfully'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ], 500);
                }
            }
        }

        // Full update validation
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'full_name' => 'nullable|string|max:200',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date',
            'department_id' => 'nullable',
            'designation_code' => 'nullable',
            'designation' => 'nullable',
            'office_type' => 'nullable',
            'branch_id' => 'nullable',
            'user_status' => 'nullable',
            'manager_id' => 'nullable',
            'role_ids' => 'nullable|array',

            // Personal/Other Details
            'gender' => 'nullable',
            'employee_category' => 'nullable',
            'phone' => 'nullable',
            'alternate_phone' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'state' => 'nullable',
            'postal_code' => 'nullable',
            'emergency_contact_name' => 'nullable',
            'emergency_contact_phone' => 'nullable',
            'date_of_joining' => 'nullable',
            'employee_type' => 'nullable',
            'aadhar_number' => 'nullable',
            'pan_number' => 'nullable',
            'bank_account' => 'nullable',
            'ifsc_code' => 'nullable',
            'blood_group' => 'nullable',
            'medical_conditions' => 'nullable',
            'allergies' => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $employee = UserMaster::findOrFail($id);

            $fullName = $validated['full_name'] ?? trim(($validated['first_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
            if (!$fullName) {
                $fullName = $employee->FullName;
            }
            $firstName = $validated['first_name'] ?? (explode(' ', $fullName)[0] ?? $employee->first_name);
            $lastName = $validated['last_name'] ?? (implode(' ', array_slice(explode(' ', $fullName), 1)) ?? $employee->last_name);
            $designationCode = $validated['designation_code'] ?? $validated['designation'] ?? $employee->designation_code;

            $currentUserCode = $this->getCurrentUserCode();

            $employee->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'FullName' => $fullName,
                'EmailId' => $validated['email'] ?? $employee->EmailId,
                'date_of_birth' => $validated['date_of_birth'] ?? $employee->date_of_birth,
                'department_id' => $validated['department_id'] ?? $employee->department_id,
                'designation_code' => $designationCode,
                'Designation' => $designationCode,
                'office_type' => $validated['office_type'] ?? $employee->office_type,
                'branch_id' => $validated['branch_id'] ?? $employee->branch_id,
                'manager_id' => $validated['manager_id'] ?? $employee->manager_id,
                'UserStatus' => $validated['user_status'] ?? $employee->UserStatus,
                'ModifiedBy' => $currentUserCode,
                'ModifiedDate' => now(),
            ]);

            // Sync roles if provided
            if ($request->has('role_ids')) {
                $roleIds = array_filter((array) $request->input('role_ids'));
                $syncData = [];
                foreach ($roleIds as $rId) {
                    $syncData[$rId] = ['is_active' => 1];
                }
                $employee->roles()->sync($syncData);
            }

            // Update employee_profiles if exists
            if ($employee->profile) {
                $employee->profile->update(array_filter([
                    'gender' => $validated['gender'] ?? null,
                    'employee_category' => $validated['employee_category'] ?? null,
                    'phone_number' => $validated['phone'] ?? null,
                    'alternate_phone' => $validated['alternate_phone'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'city' => $validated['city'] ?? null,
                    'state' => $validated['state'] ?? null,
                    'postal_code' => $validated['postal_code'] ?? null,
                    'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                    'date_of_joining' => $validated['date_of_joining'] ?? null,
                    'employee_type' => $validated['employee_type'] ?? null,
                    'aadhar_number' => $validated['aadhar_number'] ?? null,
                    'pan_number' => $validated['pan_number'] ?? null,
                    'bank_account_number' => $validated['bank_account'] ?? null,
                    'ifsc_code' => $validated['ifsc_code'] ?? null,
                    'blood_group' => $validated['blood_group'] ?? null,
                ], fn($val) => $val !== null));
            }

            // Sync with Doctor_Master if Consultant/Doctor/Wellness Expert
            $designationObj = Designation::where('DesignationCode', $designationCode)->first();
            $desigName = strtolower($designationObj->Designation ?? $designationCode ?? '');
            $assignedRoleIds = $employee->roles()->pluck('roles.id')->toArray();

            $isDoctor = str_contains($desigName, 'doctor')
                || str_contains($desigName, 'consultant')
                || str_contains($desigName, 'wellness')
                || str_contains($desigName, 'dietitian')
                || in_array(8, $assignedRoleIds);

            if ($isDoctor) {
                $doctorMaster = DoctorMaster::where('UserCode', $employee->UserCode)->first();
                $branchObj = NewBranch::where('branch_id', $employee->branch_id)->first();
                $locationCode = $branchObj->branch_code ?? $branchObj->Branchcode ?? 'ANR';
                $updaterUserCode = $currentUserCode;

                if ($doctorMaster) {
                    $doctorMaster->update([
                        'DoctorName' => $employee->FullName,
                        'Locations' => $locationCode,
                        'ModifiedBy' => $updaterUserCode,
                        'ModifiedDate' => now(),
                        'Status' => $employee->UserStatus === 'Active' ? 'Active' : 'Inactive',
                    ]);
                } else {
                    DoctorMaster::create([
                        'Doctor_Id' => $this->generateDoctorId(),
                        'DoctorName' => $employee->FullName,
                        'Locations' => $locationCode,
                        'CreatedBy' => $updaterUserCode,
                        'CreatedDate' => now(),
                        'ModifiedBy' => $updaterUserCode,
                        'ModifiedDate' => now(),
                        'Status' => $employee->UserStatus === 'Active' ? 'Active' : 'Inactive',
                        'UserCode' => $employee->UserCode,
                        'CommonCode' => 'VEC-' . rand(1000, 9999),
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function generateDoctorId()
    {
        $lastCode = DoctorMaster::where('Doctor_Id', 'like', 'HDC-%')
            ->orderByRaw('TRY_CAST(SUBSTRING(Doctor_Id, 5, 20) AS INT) DESC')
            ->value('Doctor_Id');

        if ($lastCode && preg_match('/HDC-(\d+)/', $lastCode, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        } else {
            $nextNumber = 1;
        }

        return 'HDC-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
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
                // 'assigned_date' => now()
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

            if (
                $onboarding->system_access_provided && $onboarding->id_card_issued &&
                $onboarding->equipment_provided && $onboarding->orientation_completed &&
                $onboarding->documentation_submitted
            ) {
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

    public function getEmployeeRoles($employeeId)
    {
        try {
            $employee = UserMaster::findOrFail($employeeId);
            $roles = $employee->roles()->get();

            return response()->json([
                'status' => true,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getCurrentUserCode()
    {
        if (session('user_code')) {
            return session('user_code');
        }
        if (session('user_id')) {
            $userCode = UserMaster::where('UserID', session('user_id'))->value('UserCode');
            if ($userCode) return $userCode;
        }
        return 'USE-0209';
    }
}
