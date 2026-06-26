@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Employee Management <span
                            class="badge badge-soft-primary border border-primary page-header-badge ms-2">Total Employees :
                            {{ $employees->total() }}</span></h4>
                </div>
                <div class="text-end d-flex gap-2">
                    @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'staff'))
                        <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal"
                            data-bs-target="#addEmployeeModal">
                            <i class="ti ti-plus me-1"></i>Add New Employee
                        </button>
                    @endif
                </div>
            </div>

            {{-- Advanced Filters --}}
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('staff.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Search</label>
                                <input type="text" name="search" class="form-control" placeholder="Name, Code, Email..."
                                    value="{{ $search }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Department</label>
                                <select name="department" class="form-select">
                                    <option value="">-- All --</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->Departmentid }}"
                                            {{ $department == $dept->Departmentid ? 'selected' : '' }}>
                                            {{ $dept->DepartmentName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Designation</label>
                                <select name="designation" class="form-select">
                                    <option value="">-- All --</option>
                                    @foreach ($designations as $des)
                                        <option value="{{ $des->DesignationCode }}"
                                            {{ $designation == $des->DesignationCode ? 'selected' : '' }}>
                                            {{ $des->Designation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">-- All --</option>
                                    <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="InActive" {{ $status == 'InActive' ? 'selected' : '' }}>InActive
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary w-50">
                                    <i class="ti ti-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('staff.index') }}" class="btn btn-secondary w-50">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Employees Table --}}
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 18%">Name / Code</th>
                                    <th style="width: 12%">Email</th>
                                    <th style="width: 12%">Department</th>
                                    <th style="width: 12%">Designation</th>
                                    <th style="width: 12%">Manager</th>
                                    <th style="width: 12%">Branch</th>
                                    <th style="width: 8%">Status</th>
                                    <th style="width: 8%">Roles</th>
                                    <th style="width: 18%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $emp)
                                    <tr>
                                        <td>
                                            <strong>{{ $emp->FullName }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $emp->UserCode }}</small>
                                        </td>
                                        <td>{{ $emp->EmailId ?? '-' }}</td>
                                        <td>
                                            @if($emp->departments && $emp->departments->count() > 0)
                                                {{ $emp->departments->pluck('DepartmentName')->implode(', ') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $emp->designation?->Designation ?? '-' }}</td>
                                        <td>
                                            @if ($emp->manager_id)
                                                {{ $emp->manager?->FullName ?? '-' }}
                                                <br><small
                                                    class="text-muted">{{ $emp->manager?->designation?->Designation ?? '' }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $emp->branch?->Branchname ?? '-' }}</td>
                                        <td>
                                            @if ($emp->UserStatus == 'Active')
                                                <span class="badge badge-soft-success border border-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-danger border border-danger">InActive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $emp->roles()->count() }}</span>
                                        </td>
                                        <td>
                                            <div class="action-item">
                                                @if (session('is_admin') ||
                                                        \App\Helpers\RbacHelper::canPerformAction('read', 'staff') ||
                                                        \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                                                    <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu p-2">
                                                        @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('read', 'staff'))
                                                            <li>
                                                                <a href="{{ route('staff.details', $emp->UserID) }}"
                                                                    class="dropdown-item">
                                                                    <i class="ti ti-eye me-1"></i>Full Details
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#viewEmployeeModal"
                                                                    onclick="loadEmployeeDetails({{ $emp->UserID }})">
                                                                    <i class="ti ti-info-circle me-1"></i>Quick View
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                                                            <li>
                                                                <a href="#" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editEmployeeModal"
                                                                    onclick="loadEmployeeForEdit({{ $emp->UserID }})">
                                                                    <i class="ti ti-pencil me-1"></i>Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="#" class="dropdown-item"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#manageRolesModal"
                                                                    onclick="loadEmployeeRoles({{ $emp->UserID }}, '{{ $emp->FullName }}')">
                                                                    <i class="ti ti-lock me-1"></i>Roles & Permissions
                                                                </a>
                                                            </li>
                                                        @endif
                                                    </ul>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">No employees found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="table-footer-bar d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        Row Per Page
                        <select id="perPage" class="form-select form-select-sm d-inline-block" style="width:70px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div>Showing {{ $employees->firstItem() }} to {{ $employees->lastItem() }} of
                        {{ $employees->total() }} entries</div>
                </div>
            </div>
            <x-pagination :paginator="$employees" :append="['per_page' => $perPage, 'search' => $search]" />
        </div>
    </div>

    {{-- ======== ADD EMPLOYEE MODAL - COMPREHENSIVE FORM ======== --}}
    <div class="modal fade" id="addEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ti ti-users me-2"></i>Add New Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form id="addEmployeeForm">
                    @csrf
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item"><a class="nav-link active" href="#basic-info" data-bs-toggle="tab"><i
                                        class="ti ti-info-circle me-1"></i>Basic</a></li>
                            <li class="nav-item"><a class="nav-link" href="#personal-details" data-bs-toggle="tab"><i
                                        class="ti ti-user me-1"></i>Personal</a></li>
                            <li class="nav-item"><a class="nav-link" href="#employment-details" data-bs-toggle="tab"><i
                                        class="ti ti-briefcase me-1"></i>Employment</a></li>
                            <li class="nav-item"><a class="nav-link" href="#financial-info" data-bs-toggle="tab"><i
                                        class="ti ti-credit-card me-1"></i>Financial</a></li>
                            <li class="nav-item"><a class="nav-link" href="#medical-info" data-bs-toggle="tab"><i
                                        class="ti ti-heart me-1"></i>Medical</a></li>
                        </ul>

                        <div class="tab-content">
                            {{-- TAB 1: BASIC INFO --}}
                            <div class="tab-pane fade show active" id="basic-info">
                                <h6 class="fw-bold mb-3">Basic Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control"
                                            placeholder="First name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control"
                                            placeholder="Last name" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employee Code <span
                                                class="badge badge-soft-info ms-2">Auto-Generated</span></label>
                                        <input type="text" name="employee_code" id="employeeCodeField"
                                            class="form-control" placeholder="Auto-generated" readonly
                                            style="background-color: #f8f9fa; cursor: not-allowed;">
                                        <small class="text-muted d-block mt-1">✓ Automatically incremented from the last
                                            employee code</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control"
                                        placeholder="employee@company.com">
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Department <span class="text-danger">*</span></label>
                                        <select name="department_id" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->Departmentid }}">{{ $dept->DepartmentName }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Designation <span class="text-danger">*</span></label>
                                        <select name="designation_code" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($designations as $des)
                                                <option value="{{ $des->DesignationCode }}">{{ $des->Designation }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Office Type <span class="text-danger">*</span></label>
                                        <select name="office_type" id="officeTypeSelect" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            <option value="Branch Location">Branch Location</option>
                                            <option value="Corporate Office">Corporate Office</option>
                                            <option value="Head Office">Head Office</option>
                                            <option value="Regional Office">Regional Office</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Branch <span class="text-danger"
                                                id="branchRequired">*</span></label>
                                        <select name="branch_id" id="branchSelect" class="form-select" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->BranchID ?? $branch->branch_id }}">
                                                    {{ $branch->BranchName ?? ($branch->Branchname ?? $branch->branch_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1" id="branchHelp">Required for Branch
                                            Location employees</small>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Manager</label>
                                        <select name="manager_id" id="managerSelect" class="form-select">
                                            <option value="">-- Select Manager (Optional) --</option>
                                            @foreach ($employees as $emp)
                                                @if ($emp->UserStatus == 'Active')
                                                    <option value="{{ $emp->UserID }}">{{ $emp->FullName }}
                                                        ({{ $emp->designation?->Designation ?? '-' }})
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1">Department managers will
                                            auto-populate</small>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 2: PERSONAL DETAILS --}}
                            <div class="tab-pane fade" id="personal-details">
                                <h6 class="fw-bold mb-3">Personal Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" class="form-select">
                                            <option value="">-- Select --</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Category</label>
                                        <select name="employee_category" class="form-select">
                                            <option value="White Collar">White Collar</option>
                                            <option value="Blue Collar">Blue Collar</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" name="phone" class="form-control" placeholder="Phone">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Alternate Phone</label>
                                        <input type="tel" name="alternate_phone" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">City</label>
                                        <input type="text" name="city" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">State</label>
                                        <input type="text" name="state" class="form-control">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Postal Code</label>
                                        <input type="text" name="postal_code" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Emergency Contact</label>
                                        <input type="text" name="emergency_contact_name" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Emergency Phone</label>
                                        <input type="tel" name="emergency_contact_phone" class="form-control">
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 3: EMPLOYMENT DETAILS --}}
                            <div class="tab-pane fade" id="employment-details">
                                <h6 class="fw-bold mb-3">Employment Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Employee Type</label>
                                        <select name="employee_type" class="form-select">
                                            <option value="Permanent">Permanent</option>
                                            <option value="Temporary">Temporary</option>
                                            <option value="Contract">Contract</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date of Joining</label>
                                        <input type="date" name="date_of_joining" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Employee Status <span class="text-danger">*</span></label>
                                    <select name="employee_status" class="form-select" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="On Leave">On Leave</option>
                                    </select>
                                </div>
                            </div>

                            {{-- TAB 4: FINANCIAL & ID INFO --}}
                            <div class="tab-pane fade" id="financial-info">
                                <h6 class="fw-bold mb-3">Financial & ID Information</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Aadhar Number</label>
                                        <input type="text" name="aadhar_number" class="form-control"
                                            placeholder="12-digit">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">PAN Number</label>
                                        <input type="text" name="pan_number" class="form-control">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Bank Account</label>
                                        <input type="text" name="bank_account" class="form-control">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">IFSC Code</label>
                                        <input type="text" name="ifsc_code" class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Blood Group</label>
                                    <select name="blood_group" class="form-select">
                                        <option value="">-- Select --</option>
                                        <option value="A+">A+</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B-">B-</option>
                                        <option value="AB+">AB+</option>
                                        <option value="AB-">AB-</option>
                                        <option value="O+">O+</option>
                                        <option value="O-">O-</option>
                                    </select>
                                </div>
                            </div>

                            {{-- TAB 5: MEDICAL INFO --}}
                            <div class="tab-pane fade" id="medical-info">
                                <h6 class="fw-bold mb-3">Medical Information</h6>
                                <div class="mb-3">
                                    <label class="form-label">Medical Conditions</label>
                                    <textarea name="medical_conditions" class="form-control" rows="2" placeholder="Any medical conditions"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Allergies</label>
                                    <textarea name="allergies" class="form-control" rows="2" placeholder="Any allergies"></textarea>
                                </div>
                                <div class="alert alert-info alert-sm">
                                    <i class="ti ti-info-circle me-1"></i>Medical details can be updated later
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><i class="ti ti-check me-1"></i>Create
                            Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ======== VIEW EMPLOYEE MODAL ======== --}}
    <div class="modal fade" id="viewEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="ti ti-eye me-2"></i>Employee Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="employeeDetailsContent">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ======== EDIT EMPLOYEE MODAL ======== --}}
    <div class="modal fade" id="editEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title"><i class="ti ti-pencil me-2"></i>Edit Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="editEmployeeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editEmployeeId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control"
                                    placeholder="Enter full name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="employee@example.com">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Designation <span
                                        class="text-danger">*</span></label>
                                <select name="designation" class="form-select" required>
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $des)
                                        <option value="{{ $des->DesignationCode }}">{{ $des->Designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Branch <span class="text-danger">*</span></label>
                                <select name="branch_id" class="form-select" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->branch_id }}">{{ $branch->Branchname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select name="user_status" class="form-select" required>
                                    <option value="Active">Active</option>
                                    <option value="InActive">InActive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Update Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ======== MANAGE ROLES MODAL ======== --}}
    <div class="modal fade" id="manageRolesModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="ti ti-lock me-2"></i>Manage Roles & Permissions - <span
                            id="rolesEmployeeName"></span></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3"><i class="ti ti-lock-plus me-2"></i>Assign Role</h6>
                        <div class="mb-3">
                            <label class="form-label">Role Hierarchy</label>
                            <select id="roleSelect" class="form-select">
                                <option value="">-- Select a Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" data-level="{{ $role->level }}"
                                        data-desc="{{ $role->description }}">
                                        {{ $role->name }} (Level {{ $role->level }})
                                    </option>
                                @endforeach
                            </select>
                            <small id="roleDescription" class="text-muted d-block mt-2"></small>
                        </div>
                        <button type="button" class="btn btn-success w-100" onclick="assignRole()">
                            <i class="ti ti-plus me-1"></i>Assign This Role
                        </button>
                    </div>

                    <hr>

                    <div>
                        <h6 class="fw-bold mb-3"><i class="ti ti-list-check me-2"></i>Assigned Roles</h6>
                        <div id="assignedRolesList" class="border rounded p-3 bg-light">
                            <div class="text-center text-muted py-3">Loading roles...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        let currentEmployeeId = null;

        // Show role description when selected
        document.getElementById('roleSelect')?.addEventListener('change', function() {
            const option = this.options[this.selectedIndex];
            const desc = option.getAttribute('data-desc');
            const descEl = document.getElementById('roleDescription');
            if (desc && desc !== 'null') {
                descEl.textContent = '📋 ' + desc;
            } else {
                descEl.textContent = '';
            }
        });

        document.getElementById('perPage')?.addEventListener('change', function() {
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });

        // Generate Employee Code when modal opens
        document.getElementById('addEmployeeModal').addEventListener('show.bs.modal', function(e) {
            // Fetch the next employee code
            fetch('{{ route('staff.generate-code') }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        // Populate the employee code field with auto-generated code
                        document.getElementById('employeeCodeField').value = data.employee_code;
                    }
                })
                .catch(e => console.error('Error generating employee code:', e));
        });

        // Handle Office Type change - make Branch optional for Corporate/Head Office
        document.getElementById('officeTypeSelect').addEventListener('change', function(e) {
            const officeType = this.value;
            const branchSelect = document.getElementById('branchSelect');
            const branchRequired = document.getElementById('branchRequired');
            const branchHelp = document.getElementById('branchHelp');

            if (officeType === 'Corporate Office' || officeType === 'Head Office' || officeType ===
                'Regional Office') {
                // Corporate/Head Office - Branch is optional
                branchSelect.removeAttribute('required');
                branchRequired.style.display = 'none';
                branchSelect.value = '';
                branchSelect.disabled = true;
                branchSelect.style.backgroundColor = '#f8f9fa';
                branchHelp.textContent = '(Not applicable for ' + officeType + ')';
                branchHelp.classList.add('text-secondary');
            } else if (officeType === 'Branch Location') {
                // Branch Location - Branch is required
                branchSelect.setAttribute('required', 'required');
                branchRequired.style.display = 'inline';
                branchSelect.disabled = false;
                branchSelect.style.backgroundColor = 'white';
                branchHelp.textContent = 'Required for Branch Location employees';
                branchHelp.classList.remove('text-secondary');
            } else {
                // Nothing selected - reset
                branchSelect.removeAttribute('required');
                branchRequired.style.display = 'none';
                branchSelect.disabled = true;
                branchSelect.style.backgroundColor = '#f8f9fa';
                branchHelp.textContent = 'Select an office type first';
            }
        });

        // Reset form when modal is hidden
        document.getElementById('addEmployeeModal').addEventListener('hide.bs.modal', function(e) {
            document.getElementById('addEmployeeForm').reset();
            document.getElementById('employeeCodeField').value = '';
            // Reset branch field state
            document.getElementById('branchSelect').disabled = false;
            document.getElementById('branchSelect').style.backgroundColor = 'white';
            document.getElementById('branchSelect').setAttribute('required', 'required');
            document.getElementById('branchRequired').style.display = 'inline';
        });

        // Add Employee
        document.getElementById('addEmployeeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route('staff.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire('Success', 'Employee created successfully', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(e => Swal.fire('Error', 'Something went wrong', 'error'));
        });

        // Edit Employee
        function loadEmployeeForEdit(empId) {
            // Load employee data and populate form
            fetch(`/api/employees/${empId}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('editEmployeeId').value = empId;
                    document.querySelector('#editEmployeeForm input[name="full_name"]').value = data.FullName;
                    document.querySelector('#editEmployeeForm input[name="email"]').value = data.EmailId || '';
                    document.querySelector('#editEmployeeForm select[name="designation"]').value = data.Designation;
                    document.querySelector('#editEmployeeForm select[name="branch_id"]').value = data.branch_id;
                    document.querySelector('#editEmployeeForm select[name="user_status"]').value = data.UserStatus;
                });
        }

        document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const empId = document.getElementById('editEmployeeId').value;
            const formData = new FormData(this);

            fetch(`/staff/${empId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire('Success', 'Employee updated successfully', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(e => Swal.fire('Error', 'Something went wrong', 'error'));
        });

        // Load Employee Details
        function loadEmployeeDetails(empId) {
            const content = document.getElementById('employeeDetailsContent');
            fetch(`/api/employees/${empId}`)
                .then(r => r.json())
                .then(data => {
                    content.innerHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Full Name</label>
                            <p class="fw-bold">${data.FullName}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Employee Code</label>
                            <p class="fw-bold">${data.UserCode}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Email</label>
                            <p class="fw-bold">${data.EmailId || '-'}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Designation</label>
                            <p class="fw-bold">${data.designation?.Designation || '-'}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Branch</label>
                            <p class="fw-bold">${data.branch?.Branchname || '-'}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted">Status</label>
                            <p><span class="badge bg-${data.UserStatus === 'Active' ? 'success' : 'danger'}">${data.UserStatus}</span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted">Assigned Roles</label>
                            <div class="d-flex flex-wrap gap-2">
                                ${data.roles && data.roles.length > 0 ?
                                    data.roles.map(r => `<span class="badge bg-info">${r.name}</span>`).join('') :
                                    '<span class="text-muted">No roles assigned</span>'
                                }
                            </div>
                        </div>
                    </div>
                `;
                });
        }

        // Load Employee Roles
        function loadEmployeeRoles(empId, empName) {
            currentEmployeeId = empId;
            document.getElementById('rolesEmployeeName').textContent = empName;

            fetch(`/api/employees/${empId}`)
                .then(r => r.json())
                .then(data => {
                    const rolesList = document.getElementById('assignedRolesList');
                    if (data.roles && data.roles.length > 0) {
                        rolesList.innerHTML = data.roles.map(role => `
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <div>
                                <strong>${role.name}</strong>
                                <br>
                                <small class="text-muted">Level ${role.level}</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeRole(${empId}, ${role.id})">
                                <i class="ti ti-trash"></i> Remove
                            </button>
                        </div>
                    `).join('');
                    } else {
                        rolesList.innerHTML = '<div class="text-center text-muted py-3">No roles assigned yet</div>';
                    }
                });
        }

        function assignRole() {
            const roleId = document.getElementById('roleSelect').value;
            if (!roleId) {
                Swal.fire('Warning', 'Please select a role', 'warning');
                return;
            }

            fetch(`/staff/${currentEmployeeId}/role`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        role_id: roleId
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire('Success', data.message, 'success');
                        document.getElementById('roleSelect').value = '';
                        loadEmployeeRoles(currentEmployeeId, document.getElementById('rolesEmployeeName').textContent);
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(e => Swal.fire('Error', 'Something went wrong', 'error'));
        }

        function removeRole(empId, roleId) {
            Swal.fire({
                title: 'Remove Role?',
                text: 'Are you sure you want to remove this role?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Remove'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/staff/${empId}/role/${roleId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.status) {
                                Swal.fire('Removed', data.message, 'success');
                                loadEmployeeRoles(empId, document.getElementById('rolesEmployeeName')
                                    .textContent);
                            }
                        });
                }
            });
        }
    </script>
@endsection
