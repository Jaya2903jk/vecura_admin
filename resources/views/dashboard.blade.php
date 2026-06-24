@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="add-item d-flex align-items-center justify-content-between">
                <div>
                    <h1>{{ session('rbac_role_name') ?? 'Dashboard' }} Dashboard</h1>
                    <p class="text-muted mb-0">Welcome, {{ session('user_name') }}</p>
                </div>
                <div>
                    <span class="badge bg-primary">{{ session('rbac_role_name') ?? 'Employee' }}</span>
                    @if(session('is_admin'))
                        <span class="badge bg-danger">Admin</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            @if(session('is_admin') || in_array('Admin', session('rbac_roles', [])))
                <!-- Admin Only Stats -->
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Total Users</p>
                                    <h3 class="mb-0">{{ $totalUsers ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-users fs-32 text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Active Roles</p>
                                    <h3 class="mb-0">{{ $activeRoles ?? 5 }}</h3>
                                </div>
                                <i class="ti ti-shield-lock fs-32 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Total Permissions</p>
                                    <h3 class="mb-0">{{ $totalPermissions ?? 60 }}</h3>
                                </div>
                                <i class="ti ti-key fs-32 text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Departments</p>
                                    <h3 class="mb-0">{{ $totalDepartments ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-building fs-32 text-info opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(in_array('ZoneManager', session('rbac_roles', [])))
                <!-- Zone Manager Stats -->
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Zone Branches</p>
                                    <h3 class="mb-0">{{ $zoneBranches ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-building-skyscraper fs-32 text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Zone Locations</p>
                                    <h3 class="mb-0">{{ $zoneLocations ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-map-pin fs-32 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Zone Staff</p>
                                    <h3 class="mb-0">{{ $zoneStaff ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-users-group fs-32 text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Pending Approvals</p>
                                    <h3 class="mb-0">{{ $pendingApprovals ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-clock fs-32 text-danger opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(in_array('BranchManager', session('rbac_roles', [])))
                <!-- Branch Manager Stats -->
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Locations</p>
                                    <h3 class="mb-0">{{ $branchLocations ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-map-pin fs-32 text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Staff</p>
                                    <h3 class="mb-0">{{ $branchStaff ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-users-group fs-32 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Tickets</p>
                                    <h3 class="mb-0">{{ $branchTickets ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-ticket fs-32 text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Pending</p>
                                    <h3 class="mb-0">{{ $branchPending ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-clock fs-32 text-danger opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(in_array('LocationManager', session('rbac_roles', [])))
                <!-- Location Manager Stats -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Department Staff</p>
                                    <h3 class="mb-0">{{ $departmentStaff ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-users fs-32 text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Tickets Assigned</p>
                                    <h3 class="mb-0">{{ $assignedTickets ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-ticket fs-32 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Pending Approval</p>
                                    <h3 class="mb-0">{{ $myPending ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-clock fs-32 text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Employee/Default Stats -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">My Tickets</p>
                                    <h3 class="mb-0">{{ $myTickets ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-ticket fs-32 text-primary opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Completed</p>
                                    <h3 class="mb-0">{{ $myCompleted ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-check fs-32 text-success opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <p class="text-muted small mb-1">Pending</p>
                                    <h3 class="mb-0">{{ $myPending ?? 0 }}</h3>
                                </div>
                                <i class="ti ti-clock fs-32 text-warning opacity-50"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Role Information -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Your Access & Permissions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Current Role</p>
                                <p class="fw-bold">
                                    <span class="badge bg-primary">{{ session('rbac_role_name') }}</span>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">All Assigned Roles</p>
                                <div>
                                    @forelse(session('rbac_roles', []) as $role)
                                        <span class="badge bg-secondary me-1">{{ $role }}</span>
                                    @empty
                                        <span class="text-muted">No roles assigned</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Quick Actions</p>
                                <a href="{{ route('rbac.employee-access', session('user_id')) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-eye me-1"></i> View My Permissions
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity / Data Section -->
        <div class="row">
            @if(session('is_admin') || in_array('Admin', session('rbac_roles', [])))
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">System Overview</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Admin dashboard showing system-wide metrics and user management options.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('rbac.manage-roles') }}" class="btn btn-sm btn-primary">
                                    <i class="ti ti-shield-lock me-1"></i> Manage Roles
                                </a>
                                <a href="{{ route('rbac.assign-role.form') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-user-plus me-1"></i> Assign Roles
                                </a>
                                <a href="{{ route('staff.index') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ti ti-users me-1"></i> Manage Staff
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Available Actions</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted">Based on your role ({{ session('rbac_role_name') }}), you have access to:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-light text-dark">
                                    <i class="ti ti-eye me-1"></i> Read Access
                                </span>
                                @if(!in_array('Employee', session('rbac_roles', [])) || in_array('LocationManager', session('rbac_roles', [])))
                                    <span class="badge bg-light text-dark">
                                        <i class="ti ti-plus me-1"></i> Create Records
                                    </span>
                                    <span class="badge bg-light text-dark">
                                        <i class="ti ti-edit me-1"></i> Edit Records
                                    </span>
                                @endif
                                @if(in_array('ZoneManager', session('rbac_roles', [])) || in_array('BranchManager', session('rbac_roles', [])) || in_array('LocationManager', session('rbac_roles', [])))
                                    <span class="badge bg-light text-dark">
                                        <i class="ti ti-check me-1"></i> Approve
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
