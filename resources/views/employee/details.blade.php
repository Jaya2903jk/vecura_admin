@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <div>
                <h3 class="fw-bold mb-1">{{ $employee->FullName }}</h3>
                <p class="text-muted mb-0">
                    <span class="badge bg-info">{{ $employee->UserCode }}</span>
                    <span class="badge bg-primary ms-2">{{ $employee->designation?->Designation ?? 'N/A' }}</span>
                    <span class="badge bg-{{ $employee->UserStatus === 'Active' ? 'success' : 'danger' }} ms-2">{{ $employee->UserStatus }}</span>
                </p>
            </div>
            <div>
                <a href="{{ route('staff.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>

        <div class="row">
            {{-- Left: Personal Details --}}
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="ti ti-user me-2"></i>Personal Information</h6>
                    </div>
                    <div class="card-body">
                        <form id="profileForm" class="needs-validation">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Employee Category <span class="text-danger">*</span></label>
                                <select name="employee_category" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    <option value="White Collar" {{ $employee->profile?->employee_category === 'White Collar' ? 'selected' : '' }}>
                                        👔 White Collar (Corporate/Office)
                                    </option>
                                    <option value="Blue Collar" {{ $employee->profile?->employee_category === 'Blue Collar' ? 'selected' : '' }}>
                                        🏗️ Blue Collar (Technical/Operational)
                                    </option>
                                </select>
                                <small class="text-muted d-block mt-1">Classify employee as corporate office staff or technical/operational staff</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Date of Birth</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ $employee->profile?->date_of_birth ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">-- Select --</option>
                                        <option value="Male" {{ $employee->profile?->gender === 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $employee->profile?->gender === 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ $employee->profile?->gender === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" name="phone_number" class="form-control" value="{{ $employee->profile?->phone_number ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Alternate Phone</label>
                                    <input type="tel" name="alternate_phone" class="form-control" value="{{ $employee->profile?->alternate_phone ?? '' }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <textarea name="address" class="form-control" rows="2">{{ $employee->profile?->address ?? '' }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ $employee->profile?->city ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">State</label>
                                    <input type="text" name="state" class="form-control" value="{{ $employee->profile?->state ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Postal Code</label>
                                    <input type="text" name="postal_code" class="form-control" value="{{ $employee->profile?->postal_code ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Blood Group</label>
                                    <input type="text" name="blood_group" class="form-control" placeholder="e.g., O+ve" value="{{ $employee->profile?->blood_group ?? '' }}">
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-bold mb-3"><i class="ti ti-phone-exclamation me-2"></i>Emergency Contact</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Contact Name</label>
                                    <input type="text" name="emergency_contact_name" class="form-control" value="{{ $employee->profile?->emergency_contact_name ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Contact Phone</label>
                                    <input type="tel" name="emergency_contact_phone" class="form-control" value="{{ $employee->profile?->emergency_contact_phone ?? '' }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="ti ti-device-floppy me-1"></i>Save Personal Details
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Employment Details --}}
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class="ti ti-briefcase me-2"></i>Employment Information</h6>
                    </div>
                    <div class="card-body">
                        <form id="employmentForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Employee Type <span class="text-danger">*</span></label>
                                    <select name="employee_type" class="form-select" required>
                                        <option value="Permanent" {{ $employee->profile?->employee_type === 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                        <option value="Temporary" {{ $employee->profile?->employee_type === 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                        <option value="Contract" {{ $employee->profile?->employee_type === 'Contract' ? 'selected' : '' }}>Contract</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Date of Joining</label>
                                    <input type="date" name="date_of_joining" class="form-control" value="{{ $employee->profile?->date_of_joining ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Date of Resignation</label>
                                    <input type="date" name="date_of_resignation" class="form-control" value="{{ $employee->profile?->date_of_resignation ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Aadhar Number</label>
                                    <input type="text" name="aadhar_number" class="form-control" placeholder="12-digit number" value="{{ $employee->profile?->aadhar_number ?? '' }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">PAN Number</label>
                                    <input type="text" name="pan_number" class="form-control" value="{{ $employee->profile?->pan_number ?? '' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Bank Account Number</label>
                                    <input type="text" name="bank_account_number" class="form-control" value="{{ $employee->profile?->bank_account_number ?? '' }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control" value="{{ $employee->profile?->ifsc_code ?? '' }}">
                            </div>

                            <button type="submit" class="btn btn-warning w-100">
                                <i class="ti ti-device-floppy me-1"></i>Save Employment Details
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right: Department, Manager, Roles --}}
            <div class="col-lg-6">
                {{-- Department & Manager --}}
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="ti ti-hierarchy-3 me-2"></i>Department & Manager</h6>
                    </div>
                    <div class="card-body">
                        <form id="hierarchyForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Department Assignment</label>
                                <select name="department_id" class="form-select">
                                    <option value="">-- Select Department --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->Departmentid }}" {{ $employee->hierarchy?->department_id === $dept->Departmentid ? 'selected' : '' }}>
                                            {{ $dept->DepartmentName }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">👔 Select the department this employee belongs to</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Manager (Reporting To)</label>
                                <select name="manager_id" class="form-select">
                                    <option value="">-- No Manager --</option>
                                    @foreach($managers as $manager)
                                        <option value="{{ $manager->UserID }}" {{ $employee->hierarchy?->manager_id === $manager->UserID ? 'selected' : '' }}>
                                            {{ $manager->FullName }} ({{ $manager->designation?->Designation ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-1">👨‍💼 Who does this employee report to?</small>
                            </div>

                            <button type="submit" class="btn btn-info w-100">
                                <i class="ti ti-device-floppy me-1"></i>Save Department & Manager
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Role Assignment (Two-way Toggle) --}}
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="ti ti-shield-lock me-2"></i>Role & Permission Management</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Available Roles</label>
                            <div id="rolesList" class="border rounded p-3 bg-light" style="max-height: 400px; overflow-y: auto;">
                                <div class="text-center text-muted py-4">Loading roles...</div>
                            </div>
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="ti ti-info-circle me-2"></i>
                            <small>Toggle roles ON/OFF to assign or remove permissions. Admin and ZoneManager roles are hierarchical.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    const employeeId = {{ $employee->UserID }};
    const allRoles = {!! json_encode($roles) !!};
    const assignedRoles = {!! json_encode($employee->roles->pluck('id')->toArray()) !!};

    // Load roles with toggle
    function loadRoles() {
        const rolesList = document.getElementById('rolesList');
        rolesList.innerHTML = allRoles.map(role => {
            const isAssigned = assignedRoles.includes(role.id);
            return `
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                    <div class="flex-grow-1">
                        <strong class="d-block">${role.name}</strong>
                        <small class="text-muted">Level ${role.level} • ${role.description || 'No description'}</small>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input role-toggle" type="checkbox" ${isAssigned ? 'checked' : ''}
                            data-role-id="${role.id}" data-role-name="${role.name}" id="role_${role.id}" style="width: 3.5rem; height: 2rem; cursor: pointer;">
                    </div>
                </div>
            `;
        }).join('');

        // Add toggle handlers
        document.querySelectorAll('.role-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const roleId = this.dataset.roleId;
                const roleName = this.dataset.roleName;
                if (this.checked) {
                    assignRole(roleId, roleName);
                } else {
                    removeRole(roleId, roleName);
                }
            });
        });
    }

    function assignRole(roleId, roleName) {
        fetch(`/employee/${employeeId}/role`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ role_id: roleId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                Swal.fire('Success', `${roleName} role assigned`, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
                document.getElementById(`role_${roleId}`).checked = false;
            }
        });
    }

    function removeRole(roleId, roleName) {
        Swal.fire({
            title: 'Remove Role?',
            text: `Are you sure you want to remove ${roleName}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Remove'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/employee/${employeeId}/role/${roleId}`, {
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire('Removed', `${roleName} role removed`, 'success');
                    }
                });
            } else {
                document.getElementById(`role_${roleId}`).checked = true;
            }
        });
    }

    // Form submissions
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(`/employee/${employeeId}/profile`, {
            method: 'PUT',
            body: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                Swal.fire('Success', data.message, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    document.getElementById('employmentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(`/employee/${employeeId}/profile`, {
            method: 'PUT',
            body: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                Swal.fire('Success', data.message, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    document.getElementById('hierarchyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch(`/employee/${employeeId}/hierarchy`, {
            method: 'PUT',
            body: formData,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                Swal.fire('Success', data.message, 'success');
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        });
    });

    // Initialize
    loadRoles();
</script>
@endsection
