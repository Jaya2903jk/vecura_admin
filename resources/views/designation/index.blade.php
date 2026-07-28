<?php $page = 'staff'; ?>
@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-id-badge text-primary fs-24"></i>Designation Management
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total Designations: {{ $designations->total() }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage job designations, codes, and department mapping configurations.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button type="button" class="btn btn-light border btn-sm fw-semibold fs-13 text-secondary shadow-xs dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="ti ti-download me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end p-2 fs-13 shadow-sm border-0">
                            <li>
                                <a class="dropdown-item rounded-1" href="{{ route('designation.export.excel') }}">
                                    <i class="ti ti-file-spreadsheet me-2 text-success"></i>Download as Excel
                                </a>
                            </li>
                        </ul>
                    </div>

                    @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'staff'))
                        <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#add_modal" onclick="resetAddForm()">
                            <i class="ti ti-plus me-1"></i>New Designation
                        </button>
                    @endif
                </div>
            </div>

            <!-- FILTER BAR -->
            <div class="card border-0 shadow-xs mb-4 rounded-3">
                <div class="card-body p-3 bg-light-subtle rounded-3">
                    <div class="row g-2 align-items-center">
                        {{-- Search Input --}}
                        <div class="col-md-5">
                            <label class="form-label fw-semibold fs-13 text-dark mb-1">Search Designation</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-muted"></i></span>
                                <input type="text" id="searchInput" class="form-control border-start-0 fs-13 text-dark" placeholder="Search by code or name...">
                            </div>
                        </div>

                        {{-- Department Filter --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-13 text-dark mb-1">Department</label>
                            <select id="departmentFilter" class="form-select form-select-sm fs-13 text-dark">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->Departmentid }}">{{ $dept->DepartmentName }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="col-md-2">
                            <label class="form-label fw-semibold fs-13 text-dark mb-1">Status</label>
                            <select id="statusFilter" class="form-select form-select-sm fs-13 text-dark">
                                <option value="">All Status</option>
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </select>
                        </div>

                        {{-- Action Controls --}}
                        <div class="col-md-2 d-flex align-items-end gap-1 pt-3">
                            <button id="searchBtn" type="button" class="btn btn-primary btn-sm fw-semibold fs-13 flex-fill">
                                <i class="ti ti-filter me-1"></i>Filter
                            </button>
                            <button id="resetBtn" type="button" class="btn btn-light border btn-sm fw-semibold fs-13 text-secondary" title="Reset Filters">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DESIGNATION TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <div id="loadingSpinner" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted fs-13">Loading designations...</p>
                        </div>
                        <div id="tableContainer">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light border-bottom">
                                    <tr>
                                        <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">Code</th>
                                        <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Designation</th>
                                        <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Mapped Departments</th>
                                        <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                        <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100" id="designationTableBody">
                                    @forelse($designations as $des)
                                        <tr>
                                            <td class="ps-4">
                                                <span class="patient-code-tag font-monospace">{{ $des->DesignationCode }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark fs-13">{{ $des->Designation }}</div>
                                                <div class="fs-11 text-muted">ID: #{{ $des->id }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($des->departmentMappings->count() > 0)
                                                        @foreach($des->departmentMappings as $mapping)
                                                            <span class="badge bg-light text-dark border px-2.5 py-1 fs-11 fw-semibold rounded-2">
                                                                <i class="ti ti-building me-1 text-muted"></i>{{ $mapping->department->DepartmentName }}
                                                            </span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted fs-12">No departments mapped</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($des->status == 0)
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                        <i class="ti ti-point-filled me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                        <i class="ti ti-point-filled me-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4">
                                                <div class="d-flex align-items-center justify-content-end gap-1">
                                                    <button class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Edit Designation"
                                                        data-bs-toggle="modal" data-bs-target="#edit_modal" onclick="loadDesignation({{ $des->id }})">
                                                        <i class="ti ti-edit fs-15 text-warning"></i>
                                                    </button>
                                                    <button class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Delete Designation"
                                                        onclick="deleteDesignation({{ $des->id }})">
                                                        <i class="ti ti-trash fs-15 text-danger"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-5 fs-13">
                                                <i class="ti ti-id-badge-off fs-36 text-muted mb-2 d-block"></i>
                                                No designations found matching criteria.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABLE FOOTER / PAGINATION BAR -->
            <div class="table-footer-bar d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 fs-13 bg-white p-3 rounded-3 shadow-xs border gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        <span class="text-muted fw-medium">Rows per page:</span>
                        <select id="perPageDept" class="form-select form-select-sm d-inline-block border ms-1 fw-bold text-dark" style="width:75px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="text-muted border-start ps-3">
                        Showing <span class="fw-semibold text-dark">{{ $designations->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $designations->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $designations->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$designations" :append="['per_page' => $perPage]" />
                </div>
            </div>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="add_modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-id-badge text-primary fs-20"></i>Add New Designation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="addForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Designation Name <span class="text-danger">*</span></label>
                            <input type="text" name="designation_name" class="form-control fs-13" placeholder="Enter designation name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Map to Departments <span class="text-danger">*</span></label>
                            <div class="border rounded p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                                <div class="row g-2">
                                    @foreach($departments as $dept)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="department_ids[]"
                                                    value="{{ $dept->Departmentid }}" id="add_dept_{{ $dept->Departmentid }}">
                                                <label class="form-check-label fs-13 text-dark" for="add_dept_{{ $dept->Departmentid }}">
                                                    <i class="ti ti-building me-1 text-muted"></i>{{ $dept->DepartmentName }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <small class="text-muted fs-12 d-block mt-2">Select which departments this designation belongs to</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select fs-13" required>
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                        <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="ti ti-plus me-1"></i>Add New Designation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="edit_modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-edit text-warning fs-20"></i>Edit Designation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Designation Code (Read-only)</label>
                            <input type="text" class="form-control fs-13 bg-light font-monospace" id="edit_code" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Designation Name <span class="text-danger">*</span></label>
                            <input type="text" name="designation_name" id="edit_name" class="form-control fs-13" placeholder="Enter designation name" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Map to Departments <span class="text-danger">*</span></label>
                            <div class="border rounded p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                                <div class="row g-2" id="edit_departments">
                                    @foreach($departments as $dept)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input edit-dept-checkbox" type="checkbox" name="department_ids[]"
                                                    value="{{ $dept->Departmentid }}" id="edit_dept_{{ $dept->Departmentid }}">
                                                <label class="form-check-label fs-13 text-dark" for="edit_dept_{{ $dept->Departmentid }}">
                                                    <i class="ti ti-building me-1 text-muted"></i>{{ $dept->DepartmentName }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <small class="text-muted fs-12 d-block mt-2">Select which departments this designation belongs to</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                            <select name="status" id="edit_status" class="form-select fs-13" required>
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                        <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-white btn-sm fw-bold px-3">
                            <i class="ti ti-check me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="delete_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="mb-3">
                        <span class="avatar avatar-xl bg-danger-subtle rounded-circle text-danger">
                            <i class="ti ti-trash fs-28"></i>
                        </span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Delete Designation</h5>
                    <p class="text-muted fs-13 mb-4">Are you sure you want to delete this designation record?</p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light border btn-sm fw-semibold px-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger btn-sm fw-bold px-3" id="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        document.getElementById('perPageDept').addEventListener('change', function() {
            let perPage = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });

        let deleteId = null;

        // Add Designation
        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const designationName = document.querySelector('input[name="designation_name"]').value.trim();
            const status = document.querySelector('select[name="status"]').value;
            const checkedDepts = document.querySelectorAll('input[name="department_ids[]"]:checked').length;

            if (!designationName) {
                Swal.fire('Required', 'Designation name is required', 'warning');
                return;
            }

            if (checkedDepts === 0) {
                Swal.fire('Required', 'Please select at least one department', 'warning');
                return;
            }

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Adding...';

            fetch('{{ route("designation.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => {
                if (r.status === 302) {
                    throw new Error('Access denied. You do not have permission to create designations.');
                }
                if (r.status === 422) {
                    return r.json().then(data => {
                        const errors = data.errors;
                        const errorMessages = Object.entries(errors)
                            .map(([field, messages]) => `${field}: ${messages[0]}`)
                            .join('\n');
                        throw new Error(errorMessages);
                    });
                }
                if (!r.ok) {
                    throw new Error('HTTP Error: ' + r.status);
                }
                return r.json();
            })
            .then(data => {
                if (data.status) {
                    Swal.fire('Success', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="ti ti-plus me-1"></i>Add New Designation';
                }
            })
            .catch(e => {
                Swal.fire('Error', e.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ti ti-plus me-1"></i>Add New Designation';
            });
        });

        // Load Designation for Edit
        function loadDesignation(id) {
            fetch(`/designation/${id}`, {
                method: 'GET'
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    document.getElementById('edit_id').value = data.designation.id;
                    document.getElementById('edit_code').value = data.designation.DesignationCode;
                    document.getElementById('edit_name').value = data.designation.Designation;
                    document.getElementById('edit_status').value = data.designation.status;

                    document.querySelectorAll('.edit-dept-checkbox').forEach(cb => cb.checked = false);

                    data.mapped_departments.forEach(deptId => {
                        const checkbox = document.getElementById(`edit_dept_${deptId}`);
                        if (checkbox) checkbox.checked = true;
                    });
                }
            })
            .catch(e => console.error('Error:', e));
        }

        // Edit Designation
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const designationName = document.getElementById('edit_name').value.trim();
            const checkedDepts = document.querySelectorAll('.edit-dept-checkbox:checked').length;

            if (!designationName) {
                Swal.fire('Required', 'Designation name is required', 'warning');
                return;
            }

            if (checkedDepts === 0) {
                Swal.fire('Required', 'Please select at least one department', 'warning');
                return;
            }

            const id = document.getElementById('edit_id').value;
            const formData = new FormData(this);
            const jsonData = Object.fromEntries(formData);
            
            const deptIds = Array.from(document.querySelectorAll('.edit-dept-checkbox:checked'))
                .map(cb => cb.value);
            jsonData.department_ids = deptIds;

            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';

            fetch(`/designation/${id}`, {
                method: 'PUT',
                body: JSON.stringify(jsonData),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => {
                if (r.status === 302) {
                    throw new Error('Access denied. You do not have permission to edit designations.');
                }
                if (!r.ok) {
                    throw new Error('HTTP Error: ' + r.status);
                }
                return r.json();
            })
            .then(data => {
                Swal.fire('Success', data.message, 'success').then(() => {
                    location.reload();
                });
            })
            .catch(e => {
                Swal.fire('Error', e.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="ti ti-check me-1"></i>Save Changes';
            });
        });

        // Delete Designation
        function deleteDesignation(id) {
            deleteId = id;
            const deleteModal = new bootstrap.Modal(document.getElementById('delete_modal'));
            deleteModal.show();
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteId) {
                fetch(`/designation/${deleteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire('Deleted', data.message, 'success').then(() => {
                            location.reload();
                        });
                    }
                });
            }
        });

        function resetAddForm() {
            document.getElementById('addForm').reset();
            document.querySelectorAll('input[name="department_ids[]"]').forEach(cb => cb.checked = false);
        }

        // Search & Filter with AJAX
        function loadDesignations(search = '', status = '', department = '') {
            const spinner = document.getElementById('loadingSpinner');
            const tableBody = document.getElementById('designationTableBody');

            spinner.style.display = 'block';

            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (status !== '') params.append('status', status);
            if (department !== '') params.append('department_id', department);

            fetch(`/designation/search?${params.toString()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(r => r.json())
            .then(data => {
                spinner.style.display = 'none';

                if (data.designations && data.designations.length > 0) {
                    let html = '';
                    data.designations.forEach(des => {
                        const deptBadges = des.department_mappings && des.department_mappings.length > 0
                            ? des.department_mappings.map(m => `<span class="badge bg-light text-dark border px-2.5 py-1 fs-11 fw-semibold rounded-2 me-1 mb-1"><i class="ti ti-building me-1 text-muted"></i>${m.department_name}</span>`).join('')
                            : '<span class="text-muted fs-12">No departments mapped</span>';

                        const statusBadge = des.status == 0
                            ? '<span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill"><i class="ti ti-point-filled me-1"></i>Active</span>'
                            : '<span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill"><i class="ti ti-point-filled me-1"></i>Inactive</span>';

                        html += `
                            <tr>
                                <td class="ps-4">
                                    <span class="patient-code-tag font-monospace">${des.DesignationCode}</span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark fs-13">${des.Designation}</div>
                                    <div class="fs-11 text-muted">ID: #${des.id}</div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        ${deptBadges}
                                    </div>
                                </td>
                                <td>
                                    ${statusBadge}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex align-items-center justify-content-end gap-1">
                                        <button class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Edit Designation"
                                            data-bs-toggle="modal" data-bs-target="#edit_modal" onclick="loadDesignation(${des.id})">
                                            <i class="ti ti-edit fs-15 text-warning"></i>
                                        </button>
                                        <button class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Delete Designation"
                                            onclick="deleteDesignation(${des.id})">
                                            <i class="ti ti-trash fs-15 text-danger"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    tableBody.innerHTML = html;
                } else {
                    tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-5 fs-13"><i class="ti ti-id-badge-off fs-36 text-muted mb-2 d-block"></i>No designations found matching criteria.</td></tr>';
                }
            })
            .catch(e => {
                spinner.style.display = 'none';
                Swal.fire('Error', 'Failed to load designations', 'error');
                console.error('Error:', e);
            });
        }

        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const search = document.getElementById('searchInput').value;
                const status = document.getElementById('statusFilter').value;
                const department = document.getElementById('departmentFilter').value;
                loadDesignations(search, status, department);
            }, 500);
        });

        document.getElementById('searchBtn').addEventListener('click', function() {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            const department = document.getElementById('departmentFilter').value;
            loadDesignations(search, status, department);
        });

        document.getElementById('resetBtn').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('departmentFilter').value = '';
            loadDesignations();
        });

        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            const search = document.getElementById('searchInput').value;
            const status = this.value;
            const department = document.getElementById('departmentFilter').value;
            loadDesignations(search, status, department);
        });

        document.getElementById('departmentFilter').addEventListener('change', function() {
            const search = document.getElementById('searchInput').value;
            const status = document.getElementById('statusFilter').value;
            const department = this.value;
            loadDesignations(search, status, department);
        });
    </script>
@endsection
