@extends('layout.mainlayout')

@section('content')
    <style>
        .staff-stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            transition: all 0.2s ease;
            height: 100%;
        }

        .staff-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
            border-color: #cbd5e1;
        }

        .staff-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .staff-avatar-initials {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #eff6ff;
            color: #2563eb;
            font-weight: 400;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-transform: uppercase;
            border: 1px solid #dbeafe;
        }

        .filter-card-erp {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        }

        .table-erp {
            width: 100%;
            margin-bottom: 0;
        }

        .table-erp th {
            background-color: #f8fafc;
            color: #334155;
            font-size: 12px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 11px 14px;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-erp td {
            padding: 11px 14px;
            vertical-align: middle;
            font-size: 13px;
            color: #1e293b;
            white-space: nowrap;
        }

        .user-status-dropdown {
            border-radius: 20px;
            font-size: 12px;
            font-weight: 400;
            padding-left: 10px;
            padding-right: 24px;
            height: 30px;
        }

        .role-pill-badge {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            font-size: 11.5px;
            font-weight: 400;
            padding: 3px 9px;
            border-radius: 12px;
        }

        .emp-code-tag {
            font-size: 12.5px;
            font-weight: 400;
            color: #334155;
            background-color: #f8fafc;
            padding: 3px 8px;
            border-radius: 5px;
            border: 1px solid #e2e8f0;
            letter-spacing: 0.3px;
        }
    </style>

    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-users-group text-primary fs-24"></i>Employee Management Directory
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total Staff: {{ $employees->total() }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage staff profiles, departmental allocations, system RBAC roles, and branch assignments.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" onclick="loadEmployeesAjax()" class="btn btn-light border shadow-xs btn-sm px-3 fw-semibold text-secondary">
                        <i class="ti ti-refresh me-1"></i>Refresh
                    </button>
                    @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'staff'))
                        <button type="button" class="btn btn-primary shadow-xs btn-sm px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                            <i class="ti ti-user-plus me-1"></i>Add New Employee
                        </button>
                    @endif
                </div>
            </div>

            <!-- DEFAULT LOGIN NOTICE -->
            <div class="alert alert-info alert-dismissible fade show mb-4 fs-13 py-2.5 px-3 border-info-subtle shadow-xs rounded-3 d-flex align-items-center justify-content-between" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="ti ti-info-circle fs-18 text-info"></i>
                    <span><strong>Default System Password for New Staff:</strong></span>
                    <span class="bg-white text-dark fw-bold px-2 py-0.5 rounded border shadow-2xs">Vecura@123</span>
                    <span class="text-muted fs-12">(Must be updated by employee upon first login)</span>
                </div>
                <button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- ERP STAT METRIC WIDGETS -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-sm-6">
                    <div class="staff-stat-card d-flex align-items-center gap-3">
                        <div class="staff-stat-icon bg-primary-subtle text-primary border border-primary-subtle">
                            <i class="ti ti-users"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-12 fw-semibold d-block uppercase">Total Employees</span>
                            <h4 class="fw-bold text-dark mb-0">{{ $employees->total() }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="staff-stat-card d-flex align-items-center gap-3">
                        <div class="staff-stat-icon bg-success-subtle text-success border border-success-subtle">
                            <i class="ti ti-user-check"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-12 fw-semibold d-block uppercase">Active Staff</span>
                            <h4 class="fw-bold text-dark mb-0">
                                {{ $employees->where('UserStatus', 'Active')->count() }}
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="staff-stat-card d-flex align-items-center gap-3">
                        <div class="staff-stat-icon bg-light text-dark border">
                            <i class="ti ti-building-community"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-12 fw-semibold d-block uppercase">Departments</span>
                            <h4 class="fw-bold text-dark mb-0">{{ count($departments) }}</h4>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="staff-stat-card d-flex align-items-center gap-3">
                        <div class="staff-stat-icon bg-info-subtle text-info border border-info-subtle">
                            <i class="ti ti-map-pin"></i>
                        </div>
                        <div>
                            <span class="text-muted fs-12 fw-semibold d-block uppercase">Branch Locations</span>
                            <h4 class="fw-bold text-dark mb-0">{{ count($branches) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ERP FILTER BAR -->
            <div class="card border-0 shadow-xs mb-4 rounded-3">
                <div class="card-body p-3 bg-light-subtle rounded-3">
                    <form id="filterForm">
                        <div class="row g-2 align-items-center">
                            {{-- Search Employee --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Search Employee</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-muted"></i></span>
                                    <input type="text" name="search" id="searchInput" class="form-control border-start-0 fs-13 text-dark"
                                        placeholder="Name, Code, Email..." value="{{ $search }}">
                                </div>
                            </div>

                            {{-- Department --}}
                            <div class="col-md-2 col-sm-6">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Department</label>
                                <select name="department" id="departmentFilter" class="form-select form-select-sm fs-13 text-dark">
                                    <option value="">All Departments</option>
                                    @foreach ($departments as $dept)
                                        <option value="{{ $dept->Departmentid }}" {{ $department == $dept->Departmentid ? 'selected' : '' }}>
                                            {{ $dept->DepartmentName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Designation --}}
                            <div class="col-md-2 col-sm-6">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Designation</label>
                                <select name="designation" id="designationFilter" class="form-select form-select-sm fs-13 text-dark">
                                    <option value="">All Designations</option>
                                    @foreach ($designations as $des)
                                        <option value="{{ $des->DesignationCode }}" {{ $designation == $des->DesignationCode ? 'selected' : '' }}>
                                            {{ $des->Designation }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Branch --}}
                            <div class="col-md-2 col-sm-6">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Branch</label>
                                <select name="branch" id="branchFilter" class="form-select form-select-sm fs-13 text-dark">
                                    <option value="">All Branches</option>
                                    @foreach ($branches as $br)
                                        <option value="{{ $br->branch_id ?? $br->BranchID }}" {{ $branch == ($br->branch_id ?? $br->BranchID) ? 'selected' : '' }}>
                                            {{ $br->branch_name ?? ($br->Branchname ?? $br->BranchName) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- User Status --}}
                            <div class="col-md-2 col-sm-6">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">User Status</label>
                                <select name="status" id="statusFilter" class="form-select form-select-sm fs-13 text-dark">
                                    <option value="">All Statuses</option>
                                    <option value="Active" {{ $status == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="InActive" {{ $status == 'InActive' ? 'selected' : '' }}>InActive</option>
                                </select>
                            </div>

                            {{-- Reset Button --}}
                            <div class="col-md-1 pt-3">
                                <button type="button" id="resetBtn" class="btn btn-light border btn-sm w-100 fs-13 fw-semibold text-secondary" onclick="resetFiltersAjax()" title="Reset Filters">
                                    <i class="ti ti-refresh me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- EMPLOYEE TABLE CARD WITH STANDALONE COLUMNS -->
            <div class="card border-0 shadow-xs rounded-3 overflow-hidden mb-4" id="employeeTableCard">
                <div class="card-body p-0 position-relative">
                    <div id="loadingSpinner" class="text-center py-5" style="display: none; position: absolute; width: 100%; height: 100%; background: rgba(255,255,255,0.85); z-index: 100;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted fw-semibold fs-13">Loading employees data...</p>
                    </div>

                    <div class="table-responsive" id="tableContainer" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4" style="width: 60px;">Profile</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Emp Code</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Employee Name</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Email Address</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Department</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Designation</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Assigned Roles</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Branch</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">User Status</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($employees as $emp)
                                    @php
                                        $nameParts = explode(' ', trim($emp->FullName));
                                        $initials = strtoupper(substr($nameParts[0] ?? 'E', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
                                    @endphp
                                    <tr>
                                        <td class="ps-4">
                                            <div class="staff-avatar-initials">{{ $initials }}</div>
                                        </td>
                                        <td>
                                            <span class="emp-code-tag font-monospace">{{ $emp->UserCode }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.details', $emp->UserID) }}" class="fw-bold text-dark text-decoration-none fs-13 hover-primary">
                                                {{ $emp->FullName }}
                                            </a>
                                        </td>
                                        <td class="fs-13 text-dark">
                                            @if (!empty($emp->EmailId))
                                                <i class="ti ti-mail me-1 text-muted fs-12"></i>{{ $emp->EmailId }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="fs-13">
                                            @if($emp->department?->DepartmentName)
                                                <span class="badge bg-light text-dark border px-2.5 py-1 fs-12 fw-semibold">
                                                    {{ $emp->department->DepartmentName }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="fs-13 fw-semibold text-dark">
                                            {{ $emp->designation?->Designation ?? '—' }}
                                        </td>
                                        <td>
                                            @forelse($emp->roles as $r)
                                                <span class="role-pill-badge me-1">{{ $r->name }}</span>
                                            @empty
                                                <span class="text-muted fs-12">—</span>
                                            @endforelse
                                        </td>
                                        <td class="fs-13">
                                            @if($emp->branch?->branch_name)
                                                <span class="text-dark fw-semibold fs-12">
                                                    <i class="ti ti-map-pin me-1 text-primary"></i>{{ $emp->branch->branch_name }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <select class="form-select form-select-sm user-status-dropdown fw-bold {{ $emp->UserStatus == 'Active' ? 'text-success border-success-subtle bg-success-subtle' : 'text-danger border-danger-subtle bg-danger-subtle' }}"
                                                data-emp-id="{{ $emp->UserID }}" onchange="updateUserStatus({{ $emp->UserID }}, this.value, this)">
                                                <option value="Active" {{ $emp->UserStatus == 'Active' ? 'selected' : '' }}>Active</option>
                                                <option value="InActive" {{ $emp->UserStatus == 'InActive' ? 'selected' : '' }}>InActive</option>
                                            </select>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <a href="{{ route('staff.details', $emp->UserID) }}" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Full Staff Details">
                                                    <i class="ti ti-eye fs-15 text-primary"></i>
                                                </a>
                                                <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Quick Summary" onclick="loadEmployeeDetails({{ $emp->UserID }})" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal">
                                                    <i class="ti ti-info-circle fs-15 text-info"></i>
                                                </button>
                                                <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Edit Profile" onclick="loadEmployeeForEdit({{ $emp->UserID }})" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                                                    <i class="ti ti-edit fs-15 text-warning"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-users-minus fs-36 text-muted mb-2 d-block"></i>
                                            No employee records found matching criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TABLE FOOTER / PAGINATION BAR -->
            <div class="table-footer-bar d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 fs-13 bg-white p-3 rounded-3 shadow-xs border gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <span class="text-muted fw-medium">Rows per page:</span>
                        <select id="perPage" class="form-select form-select-sm d-inline-block border ms-1 fw-bold text-dark" style="width:75px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="text-muted border-start ps-3">
                        Showing <span class="fw-semibold text-dark">{{ $employees->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $employees->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $employees->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$employees" :append="['per_page' => $perPage, 'search' => $search, 'department' => $department, 'designation' => $designation, 'branch' => $branch, 'status' => $status]" />
                </div>
            </div>
        </div>
    </div>

    <!-- ======== ADD EMPLOYEE MODAL ======== -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-16"><i class="ti ti-user-plus me-2"></i>Add New Employee</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="addEmployeeForm">
                    @csrf
                    <div class="modal-body p-4" style="max-height: 72vh; overflow-y: auto;">
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active fw-semibold fs-13" href="#basic-info" data-bs-toggle="tab">
                                    <i class="ti ti-info-circle me-1"></i>Basic Info &amp; Roles
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold fs-13" href="#personal-details" data-bs-toggle="tab">
                                    <i class="ti ti-user me-1"></i>Personal Details
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold fs-13" href="#employment-details" data-bs-toggle="tab">
                                    <i class="ti ti-briefcase me-1"></i>Employment
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-semibold fs-13" href="#financial-info" data-bs-toggle="tab">
                                    <i class="ti ti-credit-card me-1"></i>Financial &amp; ID
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- TAB 1: BASIC INFO -->
                            <div class="tab-pane fade show active" id="basic-info">
                                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2 fs-14">Basic Information</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold fs-13">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control form-control-sm fs-13" placeholder="First name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold fs-13">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control form-control-sm fs-13" placeholder="Last name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold fs-13">Employee Code <span class="badge bg-light text-secondary border ms-2 fs-11">Auto-Generated</span></label>
                                        <input type="text" name="employee_code" id="employeeCodeField" class="form-control form-control-sm bg-light fw-bold text-primary fs-13" placeholder="Auto-generated" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold fs-13">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control form-control-sm fs-13" placeholder="employee@company.com" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold fs-13">Department <span class="text-danger">*</span></label>
                                        <select name="department_id" class="form-select form-select-sm fs-13" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->Departmentid }}">{{ $dept->DepartmentName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold fs-13">Designation <span class="text-danger">*</span></label>
                                        <select name="designation_code" class="form-select form-select-sm fs-13" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($designations as $des)
                                                <option value="{{ $des->DesignationCode }}">{{ $des->Designation }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold fs-13">Office Type <span class="text-danger">*</span></label>
                                        <select name="office_type" id="officeTypeSelect" class="form-select form-select-sm fs-13" required>
                                            <option value="">-- Select --</option>
                                            <option value="Branch Location">Branch Location</option>
                                            <option value="Corporate Office">Corporate Office</option>
                                            <option value="Head Office">Head Office</option>
                                            <option value="Regional Office">Regional Office</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold fs-13">Branch <span class="text-danger" id="branchRequired">*</span></label>
                                        <select name="branch_id" id="branchSelect" class="form-select form-select-sm fs-13" required>
                                            <option value="">-- Select --</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->BranchID ?? $branch->branch_id }}">
                                                    {{ $branch->BranchName ?? ($branch->Branchname ?? $branch->branch_name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold fs-13">Reporting Manager</label>
                                        <select name="manager_id" id="managerSelect" class="form-select form-select-sm fs-13">
                                            <option value="">-- Select Manager (Optional) --</option>
                                            @foreach ($employees as $emp)
                                                @if ($emp->UserStatus == 'Active')
                                                    <option value="{{ $emp->UserID }}">{{ $emp->FullName }} ({{ $emp->designation?->Designation ?? '-' }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold fs-13">Assign Role(s) <span class="text-danger">*</span></label>
                                        <select name="role_ids[]" id="addEmployeeRoles" class="form-select form-select-sm fs-13" multiple size="3" required>
                                            @foreach ($roles as $r)
                                                <option value="{{ $r->id }}">{{ $r->name }} (Level {{ $r->level }})</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted fs-11">Hold Ctrl/Cmd to select multiple roles</small>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 2: PERSONAL DETAILS -->
                            <div class="tab-pane fade" id="personal-details">
                                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2 fs-14">Personal Details</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fs-13 fw-semibold">Date of Birth</label>
                                        <input type="date" name="date_of_birth" class="form-control form-control-sm fs-13">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fs-13 fw-semibold">Gender</label>
                                        <select name="gender" class="form-select form-select-sm fs-13">
                                            <option value="">-- Select --</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fs-13 fw-semibold">Phone Number</label>
                                        <input type="text" name="phone" class="form-control form-control-sm fs-13" placeholder="Primary phone">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-13 fw-semibold">Address</label>
                                        <textarea name="address" class="form-control form-control-sm fs-13" rows="2" placeholder="Full address"></textarea>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-13 fw-semibold">City</label>
                                        <input type="text" name="city" class="form-control form-control-sm fs-13" placeholder="City">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fs-13 fw-semibold">State</label>
                                        <input type="text" name="state" class="form-control form-control-sm fs-13" placeholder="State">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 3: EMPLOYMENT -->
                            <div class="tab-pane fade" id="employment-details">
                                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2 fs-14">Employment Status &amp; Joining</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fs-13 fw-semibold">Employee Status <span class="text-danger">*</span></label>
                                        <select name="employee_status" class="form-select form-select-sm fs-13" required>
                                            <option value="Active" selected>Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="On Leave">On Leave</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fs-13 fw-semibold">Employee Type</label>
                                        <select name="employee_type" class="form-select form-select-sm fs-13">
                                            <option value="Permanent" selected>Permanent</option>
                                            <option value="Temporary">Temporary</option>
                                            <option value="Contract">Contract</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fs-13 fw-semibold">Date of Joining</label>
                                        <input type="date" name="date_of_joining" class="form-control form-control-sm fs-13">
                                    </div>
                                </div>
                            </div>

                            <!-- TAB 4: FINANCIAL & ID -->
                            <div class="tab-pane fade" id="financial-info">
                                <h6 class="fw-bold mb-3 text-dark border-bottom pb-2 fs-14">Financial &amp; Government IDs</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fs-13 fw-semibold">Aadhar Number</label>
                                        <input type="text" name="aadhar_number" class="form-control form-control-sm fs-13" placeholder="12-digit Aadhar">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-13 fw-semibold">PAN Number</label>
                                        <input type="text" name="pan_number" class="form-control form-control-sm fs-13" placeholder="10-character PAN">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-13 fw-semibold">Bank Account Number</label>
                                        <input type="text" name="bank_account" class="form-control form-control-sm fs-13" placeholder="Account number">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fs-13 fw-semibold">IFSC Code</label>
                                        <input type="text" name="ifsc_code" class="form-control form-control-sm fs-13" placeholder="IFSC code">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fs-13 fw-bold"><i class="ti ti-check me-1"></i>Create Employee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ======== VIEW EMPLOYEE QUICK MODAL ======== -->
    <div class="modal fade" id="viewEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-info text-white py-3">
                    <h5 class="modal-title fw-bold fs-16"><i class="ti ti-eye me-2"></i>Quick Staff Summary</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="employeeDetailsContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ======== EDIT EMPLOYEE MODAL ======== -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-primary text-white py-3">
                    <h5 class="modal-title fw-bold fs-16"><i class="ti ti-pencil me-2"></i>Edit Employee Profile</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editEmployeeForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editEmployeeId">
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control form-control-sm fs-13" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-sm fs-13">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13">Designation <span class="text-danger">*</span></label>
                                <select name="designation" class="form-select form-select-sm fs-13" required>
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $des)
                                        <option value="{{ $des->DesignationCode }}">{{ $des->Designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13">Branch <span class="text-danger">*</span></label>
                                <select name="branch_id" class="form-select form-select-sm fs-13" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->branch_id ?? $branch->BranchID }}">
                                            {{ $branch->branch_name ?? ($branch->Branchname ?? $branch->BranchName) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13">User Status <span class="text-danger">*</span></label>
                                <select name="user_status" class="form-select form-select-sm fs-13" required>
                                    <option value="Active">Active</option>
                                    <option value="InActive">InActive</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold fs-13">Assign Role(s)</label>
                                <select name="role_ids[]" id="editEmployeeRoles" class="form-select form-select-sm fs-13" multiple size="3">
                                    @foreach ($roles as $r)
                                        <option value="{{ $r->id }}">{{ $r->name }} (Level {{ $r->level }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted fs-11">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-4 py-3">
                        <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary fs-13 fw-bold"><i class="ti ti-check me-1"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        function updateUserStatus(userId, newStatus, selectElem) {
            fetch(`/staff/${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ user_status: newStatus })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status || data.success || data.message) {
                    if (selectElem) {
                        if (newStatus === 'Active') {
                            selectElem.className = 'form-select form-select-sm user-status-dropdown fw-bold text-success border-success-subtle bg-success-subtle';
                        } else {
                            selectElem.className = 'form-select form-select-sm user-status-dropdown fw-bold text-danger border-danger-subtle bg-danger-subtle';
                        }
                    }
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: 'Employee status changed to ' + newStatus,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } else {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', data.message || 'Failed to update user status', 'error');
                    }
                }
            })
            .catch(err => {
                console.error(err);
                if (typeof Swal !== 'undefined') {
                    Swal.fire('Error', 'Failed to update user status: ' + err.message, 'error');
                }
            });
        }

        function loadEmployeeDetails(userId) {
            const container = document.getElementById('employeeDetailsContent');
            container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';
            fetch(`/staff/${userId}/details`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                let rolesBadges = (data.roles && data.roles.length > 0)
                    ? data.roles.map(r => `<span class="role-pill-badge me-1">${r.name}</span>`).join('')
                    : '<span class="text-muted fs-12">—</span>';

                container.innerHTML = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="text-muted fw-semibold">Employee Name</label>
                            <p class="fw-bold text-dark fs-14 mb-0">${data.FullName || '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted fw-semibold">Employee Code</label>
                            <p class="fw-semibold text-secondary fs-13 mb-0">${data.UserCode || '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted fw-semibold">Email Address</label>
                            <p class="fw-semibold text-dark fs-13 mb-0">${data.EmailId || '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted fw-semibold">Designation</label>
                            <p class="fw-semibold text-dark fs-13 mb-0">${data.designation?.Designation || '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted fw-semibold">Branch Location</label>
                            <p class="fw-semibold text-dark fs-13 mb-0">${data.branch?.branch_name || data.branch?.Branchname || '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted fw-semibold">Assigned Roles</label>
                            <div class="mt-1">${rolesBadges}</div>
                        </div>
                    </div>
                `;
            });
        }

        function loadEmployeeForEdit(userId) {
            document.getElementById('editEmployeeId').value = userId;
            fetch(`/staff/${userId}/details`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                const form = document.getElementById('editEmployeeForm');
                form.querySelector('[name="full_name"]').value = data.FullName || '';
                form.querySelector('[name="email"]').value = data.EmailId || '';
                form.querySelector('[name="designation"]').value = data.Designation || '';
                form.querySelector('[name="branch_id"]').value = data.branch_id || '';
                form.querySelector('[name="user_status"]').value = data.UserStatus || 'Active';

                const rolesSelect = document.getElementById('editEmployeeRoles');
                if (rolesSelect && data.roles) {
                    const roleIds = data.roles.map(r => r.id);
                    Array.from(rolesSelect.options).forEach(opt => {
                        opt.selected = roleIds.includes(parseInt(opt.value));
                    });
                }
            });
        }

        let autoFilterTimeout = null;
        function triggerAutoFilter() {
            clearTimeout(autoFilterTimeout);
            autoFilterTimeout = setTimeout(() => {
                loadEmployeesAjax();
            }, 300);
        }

        function loadEmployeesAjax() {
            const search = document.getElementById('searchInput').value;
            const department = document.getElementById('departmentFilter').value;
            const designation = document.getElementById('designationFilter').value;
            const branch = document.getElementById('branchFilter').value;
            const status = document.getElementById('statusFilter').value;

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (department) params.append('department', department);
            if (designation) params.append('designation', designation);
            if (branch) params.append('branch', branch);
            if (status) params.append('status', status);

            const spinner = document.getElementById('loadingSpinner');
            const tableContainer = document.getElementById('tableContainer');

            if (spinner) spinner.style.display = 'block';
            if (tableContainer) tableContainer.style.opacity = '0.3';

            fetch(`{{ route('staff.index') }}?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (spinner) spinner.style.display = 'none';
                if (tableContainer) tableContainer.style.opacity = '1';

                if (data.employees && data.employees.length > 0) {
                    renderEmployeeTableAjax(data.employees);
                } else {
                    document.querySelector('tbody').innerHTML = `
                        <tr>
                            <td colspan="10" class="text-center text-muted py-5 fs-13">
                                <i class="ti ti-users-minus fs-36 text-muted mb-2 d-block"></i>
                                No employee records found matching criteria.
                            </td>
                        </tr>`;
                }
            })
            .catch(error => {
                if (spinner) spinner.style.display = 'none';
                if (tableContainer) tableContainer.style.opacity = '1';
                console.error('Error:', error);
            });
        }

        function renderEmployeeTableAjax(employees) {
            let html = '';
            employees.forEach(emp => {
                const deptName = emp.department?.DepartmentName ?? '—';
                const desName = emp.designation?.Designation ?? '—';
                const branchName = emp.branch?.branch_name ?? emp.branch?.Branchname ?? '—';

                const nameParts = (emp.FullName || 'E').trim().split(' ');
                const initials = ((nameParts[0]?.[0] || 'E') + (nameParts[1]?.[0] || '')).toUpperCase();

                const rolesBadges = (emp.roles && emp.roles.length > 0)
                    ? emp.roles.map(r => `<span class="role-pill-badge me-1">${r.name}</span>`).join('')
                    : '<span class="text-muted fs-12">—</span>';

                const userStatusDropdown = `
                    <select class="form-select form-select-sm user-status-dropdown fw-bold ${emp.UserStatus === 'Active' ? 'text-success border-success-subtle bg-success-subtle' : 'text-danger border-danger-subtle bg-danger-subtle'}"
                        data-emp-id="${emp.UserID}" onchange="updateUserStatus(${emp.UserID}, this.value, this)">
                        <option value="Active" ${emp.UserStatus === 'Active' ? 'selected' : ''}>Active</option>
                        <option value="InActive" ${emp.UserStatus === 'InActive' ? 'selected' : ''}>InActive</option>
                    </select>
                `;

                html += `
                    <tr>
                        <td class="text-center">
                            <div class="staff-avatar-initials mx-auto">${initials}</div>
                        </td>
                        <td>
                            <span class="emp-code-tag">${emp.UserCode}</span>
                        </td>
                        <td>
                            <a href="/staff/${emp.UserID}/details" class="fw-bold text-dark text-decoration-none fs-13">
                                ${emp.FullName}
                            </a>
                        </td>
                        <td class="fs-13 text-dark">${emp.EmailId ? `<i class="ti ti-mail me-1 text-muted"></i>${emp.EmailId}` : '—'}</td>
                        <td class="fs-13">${deptName !== '—' ? `<span class="badge bg-light text-dark border px-2.5 py-1 fs-12 fw-semibold">${deptName}</span>` : '—'}</td>
                        <td class="fs-13 fw-semibold text-dark">${desName}</td>
                        <td>${rolesBadges}</td>
                        <td class="fs-13">${branchName !== '—' ? `<span class="text-dark fw-semibold fs-12"><i class="ti ti-map-pin me-1 text-primary"></i>${branchName}</span>` : '—'}</td>
                        <td>${userStatusDropdown}</td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-light border btn-sm p-1 shadow-2xs" data-bs-toggle="dropdown">
                                    <i class="ti ti-dots-vertical fs-16 text-dark"></i>
                                </button>
                                <ul class="dropdown-menu p-2 dropdown-menu-end shadow-sm border-0 rounded-3">
                                    <li>
                                        <a href="/staff/${emp.UserID}/details" class="dropdown-item fs-13 py-1.5 rounded">
                                            <i class="ti ti-user-check me-2 text-primary"></i>Full Staff Details
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item fs-13 py-1.5 rounded" data-bs-toggle="modal" data-bs-target="#viewEmployeeModal" onclick="loadEmployeeDetails(${emp.UserID})">
                                            <i class="ti ti-eye me-2 text-info"></i>Quick Summary
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="dropdown-item fs-13 py-1.5 rounded" data-bs-toggle="modal" data-bs-target="#editEmployeeModal" onclick="loadEmployeeForEdit(${emp.UserID})">
                                            <i class="ti ti-edit me-2 text-warning"></i>Edit Profile
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                `;
            });
            document.querySelector('tbody').innerHTML = html;
        }

        // Auto filter triggers
        document.getElementById('searchInput')?.addEventListener('keyup', triggerAutoFilter);
        document.getElementById('departmentFilter')?.addEventListener('change', triggerAutoFilter);
        document.getElementById('designationFilter')?.addEventListener('change', triggerAutoFilter);
        document.getElementById('branchFilter')?.addEventListener('change', triggerAutoFilter);
        document.getElementById('statusFilter')?.addEventListener('change', triggerAutoFilter);

        function resetFiltersAjax() {
            document.getElementById('searchInput').value = '';
            document.getElementById('departmentFilter').value = '';
            document.getElementById('designationFilter').value = '';
            document.getElementById('branchFilter').value = '';
            document.getElementById('statusFilter').value = '';
            loadEmployeesAjax();
        }
    </script>
@endsection
