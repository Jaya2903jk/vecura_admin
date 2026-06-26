<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">

                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Designation<span
                            class="badge badge-soft-primary border border-primary fs-13 fw-medium ms-2">Total Designation :
                            {{ $designations->total() }}</span></h4>
                </div>
                <div class="text-end d-flex">
                    <div class="dropdown me-1">
                        <a href="javascript:void(0);"
                            class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center"
                            data-bs-toggle="dropdown">
                            Export<i class="ti ti-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li><a class="dropdown-item" href="#">Download as PDF</a></li>
                            <li><a class="dropdown-item" href="{{ route('designation.export.excel') }}">Download as Excel</a></li>
                        </ul>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_modal" onclick="resetAddForm()"><i class="ti ti-plus me-1"></i>Add New Designation</a>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="card mb-3 border-0">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by code or name...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">-- All --</option>
                                <option value="0">Active</option>
                                <option value="1">Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Department</label>
                            <select id="departmentFilter" class="form-select">
                                <option value="">-- All --</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->Departmentid }}">{{ $dept->DepartmentName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <button id="searchBtn" class="btn btn-primary">
                            <i class="ti ti-search me-1"></i>Search
                        </button>
                        <button id="resetBtn" class="btn btn-secondary">
                            <i class="ti ti-refresh me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <div id="loadingSpinner" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2 text-muted">Loading...</p>
                        </div>
                        <div id="tableContainer">
                            <table class="table datatable mb-0">
                                <thead>
                                    <tr>
                                        <th>Designation Code</th>
                                        <th>Designation</th>
                                        <th>Mapped Departments</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="designationTableBody">
                                    @forelse($designations as $des)
                                        <tr>
                                            <td>
                                                <span class="badge badge-soft-info">{{ $des->DesignationCode }}</span>
                                            </td>
                                            <td>{{ $des->Designation }}</td>
                                            <td>
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($des->departmentMappings->count() > 0)
                                                        @foreach($des->departmentMappings as $mapping)
                                                            <span class="badge bg-primary">{{ $mapping->department->DepartmentName }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">No departments mapped</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if ($des->status == 0)
                                                    <span class="badge badge-soft-success border border-success">Active</span>
                                                @else
                                                    <span class="badge badge-soft-danger border border-danger">Inactive</span>
                                                @endif
                                            </td>

                                            <td>
                                                <div class="action-item">
                                                    <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical"></i>
                                                    </a>
                                                    <ul class="dropdown-menu p-2">
                                                        <li>
                                                            <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal"
                                                                data-bs-target="#edit_modal" onclick="loadDesignation({{ $des->id }})">
                                                                <i class="ti ti-pencil me-1"></i>Edit
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0);" class="dropdown-item text-danger" onclick="deleteDesignation({{ $des->id }})">
                                                                <i class="ti ti-trash me-1"></i>Delete
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No data found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

            <div class="table-footer-bar d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">

                    <div>
                        Row Per Page
                        <select id="perPageDept" class="form-select form-select-sm d-inline-block" style="width:70px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>

                    <div>
                        Showing {{ $designations->firstItem() }} to {{ $designations->lastItem() }}
                        of {{ $designations->total() }} entries
                    </div>

                </div>
                <x-pagination :paginator="$designations" :append="['per_page' => $perPage]" />
            </div>

            <script>
                document.getElementById('perPageDept').addEventListener('change', function() {
                    let perPage = this.value;
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                });
            </script>

            {{-- ADD MODAL --}}
            <div id="add_modal" class="modal fade">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="text-dark modal-title fw-bold">Add New Designation</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>
                        <form id="addForm">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Designation Name<span
                                            class="text-danger ms-1">*</span></label>
                                    <input type="text" name="designation_name" class="form-control" placeholder="Enter designation name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Map to Departments<span class="text-danger ms-1">*</span></label>
                                    <div class="border rounded p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                                        <div class="row g-2">
                                            @foreach($departments as $dept)
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="department_ids[]"
                                                            value="{{ $dept->Departmentid }}" id="add_dept_{{ $dept->Departmentid }}">
                                                        <label class="form-check-label" for="add_dept_{{ $dept->Departmentid }}">
                                                            <i class="ti ti-building me-1"></i>{{ $dept->DepartmentName }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">Select which departments this designation belongs to</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                    <select name="status" class="form-select" required>
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-white border"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Add New Designation</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- EDIT MODAL --}}
            <div id="edit_modal" class="modal fade">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="text-dark modal-title fw-bold">Edit Designation</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>
                        <form id="editForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="edit_id" name="id">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Designation Code (Read-only)</label>
                                    <input type="text" class="form-control" id="edit_code" readonly>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Designation Name<span
                                            class="text-danger ms-1">*</span></label>
                                    <input type="text" name="designation_name" id="edit_name" class="form-control" placeholder="Enter designation name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Map to Departments<span class="text-danger ms-1">*</span></label>
                                    <div class="border rounded p-3 bg-light" style="max-height: 250px; overflow-y: auto;">
                                        <div class="row g-2" id="edit_departments">
                                            @foreach($departments as $dept)
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input edit-dept-checkbox" type="checkbox" name="department_ids[]"
                                                            value="{{ $dept->Departmentid }}" id="edit_dept_{{ $dept->Departmentid }}">
                                                        <label class="form-check-label" for="edit_dept_{{ $dept->Departmentid }}">
                                                            <i class="ti ti-building me-1"></i>{{ $dept->DepartmentName }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2">Select which departments this designation belongs to</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                    <select name="status" id="edit_status" class="form-select" required>
                                        <option value="0">Active</option>
                                        <option value="1">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-white border"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DELETE MODAL --}}
            <div class="modal fade" id="delete_modal">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body text-center">
                            <div class="mb-3">
                                <span class="avatar avatar-xl bg-danger-transparent rounded-circle text-danger">
                                    <i class="ti ti-trash fs-24"></i>
                                </span>
                            </div>
                            <h5 class="mb-2">Delete Designation</h5>
                            <p class="mb-3">Are you sure you want to delete this designation record?</p>
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="footer text-center bg-white p-2 border-top">
            <p class="text-dark mb-0">Copyright &copy; 2026 - Vecura.</p>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let deleteId = null;

        // Add Designation
        document.getElementById('addForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Validate form fields
            const designationName = document.querySelector('input[name="designation_name"]').value.trim();
            const status = document.querySelector('select[name="status"]').value;
            const checkedDepts = document.querySelectorAll('input[name="department_ids[]"]:checked').length;

            if (!designationName) {
                Swal.fire('Required', 'Designation name is required', 'warning');
                return;
            }

            if (!status || status === '') {
                Swal.fire('Required', 'Status is required', 'warning');
                return;
            }

            if (checkedDepts === 0) {
                Swal.fire('Required', 'Please select at least one department', 'warning');
                return;
            }

            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Adding...';

            fetch('{{ route("designation.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => {
                // Handle 302 redirect (permission denied)
                if (r.status === 302) {
                    throw new Error('Access denied. You do not have permission to create designations.');
                }
                // Handle validation errors (422)
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
                    submitBtn.textContent = 'Add New Designation';
                }
            })
            .catch(e => {
                Swal.fire('Error', e.message, 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Add New Designation';
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

                    // Uncheck all
                    document.querySelectorAll('.edit-dept-checkbox').forEach(cb => cb.checked = false);

                    // Check assigned
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

            // Validate form fields
            const designationName = document.getElementById('edit_name').value.trim();
            const status = document.getElementById('edit_status').value;
            const checkedDepts = document.querySelectorAll('.edit-dept-checkbox:checked').length;

            if (!designationName) {
                Swal.fire('Required', 'Designation name is required', 'warning');
                return;
            }

            if (!status || status === '') {
                Swal.fire('Required', 'Status is required', 'warning');
                return;
            }

            if (checkedDepts === 0) {
                Swal.fire('Required', 'Please select at least one department', 'warning');
                return;
            }

            const id = document.getElementById('edit_id').value;
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Updating...';

            fetch(`/designation/${id}`, {
                method: 'PUT',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(r => {
                if (r.status === 302) {
                    throw new Error('Access denied. You do not have permission to edit designations.');
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
                    submitBtn.textContent = 'Save Changes';
                }
            })
            .catch(e => {
                Swal.fire('Error', e.message, 'error');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save Changes';
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
                            ? des.department_mappings.map(m => `<span class="badge bg-primary">${m.department_name}</span>`).join('')
                            : '<span class="text-muted">No departments mapped</span>';

                        const statusBadge = des.status == 0
                            ? '<span class="badge badge-soft-success border border-success">Active</span>'
                            : '<span class="badge badge-soft-danger border border-danger">Inactive</span>';

                        html += `
                            <tr>
                                <td>
                                    <span class="badge badge-soft-info">${des.DesignationCode}</span>
                                </td>
                                <td>${des.Designation}</td>
                                <td>
                                    <div class="d-flex flex-wrap gap-1">
                                        ${deptBadges}
                                    </div>
                                </td>
                                <td>
                                    ${statusBadge}
                                </td>
                                <td>
                                    <div class="action-item">
                                        <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu p-2">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal"
                                                    data-bs-target="#edit_modal" onclick="loadDesignation(${des.id})">
                                                    <i class="ti ti-pencil me-1"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item text-danger" onclick="deleteDesignation(${des.id})">
                                                    <i class="ti ti-trash me-1"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    tableBody.innerHTML = html;
                } else {
                    tableBody.innerHTML = '<tr><td colspan="5" class="text-center">No data found</td></tr>';
                }
            })
            .catch(e => {
                spinner.style.display = 'none';
                Swal.fire('Error', 'Failed to load designations', 'error');
                console.error('Error:', e);
            });
        }

        // Event Listeners
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

        // Live search on Enter key
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });

        // Live filter on select change
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
