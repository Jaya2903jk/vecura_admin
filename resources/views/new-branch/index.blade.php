@extends('layout.mainlayout')

@section('content')
    {{-- SweetAlert2 (not loaded globally) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Branch Master
                        <span class="badge badge-soft-primary border border-primary page-header-badge ms-2">
                            Total Branches : {{ $totalCount }}
                        </span>
                    </h4>
                </div>
                <div class="text-end d-flex">
                    @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'branch'))
                        <button class="btn btn-primary" onclick="openAddModal()">
                            <i class="ti ti-plus me-1"></i> Add Branch
                        </button>
                    @endif
                </div>
            </div>

            {{-- Table Card --}}
            <div class="card border-0">

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Branch Code</th>
                                    <th>Branch Name</th>
                                    <th>Zone</th>
                                    <th>City</th>
                                    <th>Manager</th>
                                    <th>Contact</th>
                                    <th>Wallet Balance</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $i => $branch)
                                    <tr>
                                        <td>{{ ($branches->currentPage() - 1) * $branches->perPage() + $i + 1 }}</td>
                                        <td><span class="badge bg-secondary">{{ $branch->branch_code }}</span></td>
                                        <td class="fw-semibold">{{ $branch->branch_name }}</td>
                                        <td>{{ $branch->zone?->zone_name ?? '—' }}</td>
                                        <td>{{ $branch->city?->city_name ?? '—' }}</td>
                                        <td>{{ $branch->manager_name ?? '—' }}</td>
                                        <td>{{ $branch->contact_no ?? '—' }}</td>
                                        <td>
                                            @if ($branch->wallet)
                                                <span class="fw-semibold text-success">
                                                    ₹ {{ number_format($branch->wallet->current_balance, 2) }}
                                                </span>
                                                <div style="font-size:10px; color:#aaa;" class="text-dark">
                                                    Cr: ₹{{ number_format($branch->wallet->total_credited, 2) }} /
                                                    Dr: ₹{{ number_format($branch->wallet->total_debited, 2) }}
                                                </div>
                                            @else
                                                <span class="text-muted small">No wallet</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($branch->city->is_active)
                                                <span class="badge badge-soft-success border border-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-danger border border-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'branch'))
                                                <button class="btn btn-sm btn-outline-primary me-1" title="Edit"
                                                    onclick="openEditModal(
                                                {{ $branch->branch_id }},
                                                '{{ addslashes($branch->branch_name) }}',
                                                '{{ addslashes($branch->branch_code) }}',
                                                {{ $branch->zone_id }},
                                                {{ $branch->city_id }},
                                                '{{ addslashes($branch->manager_name ?? '') }}',
                                                '{{ addslashes($branch->contact_no ?? '') }}',
                                                {{ $branch->is_active ? 1 : 0 }}
                                            )">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                            @endif
                                            @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'branch'))
                                                <button class="btn btn-sm btn-outline-danger" title="Delete"
                                                    onclick="deleteBranch({{ $branch->branch_id }}, '{{ addslashes($branch->branch_name) }}')">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">No branches found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="table-footer-bar d-flex justify-content-between align-items-center mt-2">
                <div class="d-flex align-items-center gap-3">
                    <div>
                        Row Per Page
                        <select id="perPage" class="form-select form-select-sm d-inline-block" style="width:70px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div>Showing {{ $branches->firstItem() }} to {{ $branches->lastItem() }} of {{ $branches->total() }}
                        entries</div>
                </div>
            </div>

            <x-pagination :paginator="$branches" :append="['per_page' => $perPage]" />

        </div>
    </div>

    {{-- ===================== ADD MODAL ===================== --}}
    <div class="modal fade" id="addBranchModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ti ti-building me-2"></i>Add Branch</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" id="add_branch_name" class="form-control" placeholder="Enter branch name">
                            <div class="text-danger small mt-1 d-none" id="err_add_branch_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" id="add_branch_code" class="form-control" placeholder="e.g. BR001"
                                maxlength="20" style="text-transform:uppercase">
                            <div class="text-danger small mt-1 d-none" id="err_add_branch_code"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Zone <span class="text-danger">*</span></label>
                            <select id="add_zone_id" class="form-select">
                                <option value="">-- Select Zone --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->zone_id }}">{{ $zone->zone_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_add_zone_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                            <select id="add_city_id" class="form-select">
                                <option value="">-- Select City --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->city_id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_add_city_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manager Name</label>
                            <input type="text" id="add_manager_name" class="form-control"
                                placeholder="Enter manager name" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contact No</label>
                            <input type="text" id="add_contact_no" class="form-control"
                                placeholder="Enter contact number" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select id="add_is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnSave" onclick="saveBranch()">
                        <span id="btnSaveText"><i class="ti ti-device-floppy me-1"></i>Save Branch</span>
                        <span id="btnSaveLoader" class="d-none"><span
                                class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== EDIT MODAL ===================== --}}
    <div class="modal fade" id="editBranchModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="ti ti-edit me-2"></i>Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_branch_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit_branch_name" class="form-control">
                            <div class="text-danger small mt-1 d-none" id="err_edit_branch_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" id="edit_branch_code" class="form-control" maxlength="20"
                                style="text-transform:uppercase">
                            <div class="text-danger small mt-1 d-none" id="err_edit_branch_code"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Zone <span class="text-danger">*</span></label>
                            <select id="edit_zone_id" class="form-select">
                                <option value="">-- Select Zone --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->zone_id }}">{{ $zone->zone_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_edit_zone_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                            <select id="edit_city_id" class="form-select">
                                <option value="">-- Select City --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->city_id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_edit_city_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manager Name</label>
                            <input type="text" id="edit_manager_name" class="form-control" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Contact No</label>
                            <input type="text" id="edit_contact_no" class="form-control" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select id="edit_is_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btnUpdate" onclick="updateBranch()">
                        <span id="btnUpdateText"><i class="ti ti-refresh me-1"></i>Update Branch</span>
                        <span id="btnUpdateLoader" class="d-none"><span
                                class="spinner-border spinner-border-sm me-1"></span>Updating...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var CSRF = '{{ csrf_token() }}';

        /* ---- helpers ---- */
        function setLoading(btnId, loaderId, textId, state) {
            document.getElementById(btnId).disabled = state;
            document.getElementById(loaderId).classList.toggle('d-none', !state);
            document.getElementById(textId).classList.toggle('d-none', state);
        }

        function clearErrors(prefix) {
            ['branch_name', 'branch_code', 'zone_id', 'city_id'].forEach(function(f) {
                var el = document.getElementById(prefix + '_' + f);
                var err = document.getElementById('err_' + prefix + '_' + f);
                if (el) el.classList.remove('is-invalid');
                if (err) {
                    err.textContent = '';
                    err.classList.add('d-none');
                }
            });
        }

        function showErrors(prefix, errors) {
            Object.keys(errors).forEach(function(field) {
                var el = document.getElementById(prefix + '_' + field);
                var err = document.getElementById('err_' + prefix + '_' + field);
                if (el) el.classList.add('is-invalid');
                if (err) {
                    err.textContent = errors[field][0];
                    err.classList.remove('d-none');
                }
            });
        }

        function toast(icon, msg) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: msg,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        }

        function closeModal(id) {
            bootstrap.Modal.getInstance(document.getElementById(id)).hide();
        }

        /* ---- Open Add Modal (reset fields) ---- */
        function openAddModal() {
            clearErrors('add');
            ['add_branch_name', 'add_branch_code', 'add_manager_name', 'add_contact_no'].forEach(function(id) {
                document.getElementById(id).value = '';
                document.getElementById(id).classList.remove('is-invalid');
            });
            document.getElementById('add_zone_id').value = '';
            document.getElementById('add_city_id').value = '';
            document.getElementById('add_is_active').value = '1';
            var modal = new bootstrap.Modal(document.getElementById('addBranchModal'));
            modal.show();
        }

        /* ---- Open Edit Modal ---- */
        function openEditModal(id, name, code, zone, city, manager, contact, active) {
            clearErrors('edit');
            document.getElementById('edit_branch_id').value = id;
            document.getElementById('edit_branch_name').value = name;
            document.getElementById('edit_branch_code').value = code;
            document.getElementById('edit_zone_id').value = zone;
            document.getElementById('edit_city_id').value = city;
            document.getElementById('edit_manager_name').value = manager;
            document.getElementById('edit_contact_no').value = contact;
            document.getElementById('edit_is_active').value = active;
            var modal = new bootstrap.Modal(document.getElementById('editBranchModal'));
            modal.show();
        }

        /* ---- Save Branch ---- */
        function saveBranch() {
            clearErrors('add');
            setLoading('btnSave', 'btnSaveLoader', 'btnSaveText', true);
            $.ajax({
                url: '{{ route('new-branch.store') }}',
                method: 'POST',
                data: {
                    _token: CSRF,
                    branch_name: $('#add_branch_name').val(),
                    branch_code: $('#add_branch_code').val(),
                    zone_id: $('#add_zone_id').val(),
                    city_id: $('#add_city_id').val(),
                    manager_name: $('#add_manager_name').val(),
                    contact_no: $('#add_contact_no').val(),
                    is_active: $('#add_is_active').val()
                },
                success: function(res) {
                    setLoading('btnSave', 'btnSaveLoader', 'btnSaveText', false);
                    if (res.status) {
                        closeModal('addBranchModal');
                        toast('success', res.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1600);
                    }
                },
                error: function(xhr) {
                    setLoading('btnSave', 'btnSaveLoader', 'btnSaveText', false);
                    if (xhr.status === 422) {
                        showErrors('add', xhr.responseJSON.errors);
                    } else {
                        toast('error', 'Something went wrong! Please try again.');
                    }
                }
            });
        }

        /* ---- Update Branch ---- */
        function updateBranch() {
            clearErrors('edit');
            setLoading('btnUpdate', 'btnUpdateLoader', 'btnUpdateText', true);
            var id = $('#edit_branch_id').val();
            $.ajax({
                url: '/new-branch/' + id,
                method: 'POST',
                data: {
                    _token: CSRF,
                    _method: 'PUT',
                    branch_name: $('#edit_branch_name').val(),
                    branch_code: $('#edit_branch_code').val(),
                    zone_id: $('#edit_zone_id').val(),
                    city_id: $('#edit_city_id').val(),
                    manager_name: $('#edit_manager_name').val(),
                    contact_no: $('#edit_contact_no').val(),
                    is_active: $('#edit_is_active').val()
                },
                success: function(res) {
                    setLoading('btnUpdate', 'btnUpdateLoader', 'btnUpdateText', false);
                    if (res.status) {
                        closeModal('editBranchModal');
                        toast('success', res.message);
                        setTimeout(function() {
                            location.reload();
                        }, 1600);
                    }
                },
                error: function(xhr) {
                    setLoading('btnUpdate', 'btnUpdateLoader', 'btnUpdateText', false);
                    if (xhr.status === 422) {
                        showErrors('edit', xhr.responseJSON.errors);
                    } else {
                        toast('error', 'Something went wrong! Please try again.');
                    }
                }
            });
        }

        /* ---- Toggle Active/Inactive ---- */
        function toggleStatus(id, currentStatus, name) {
            var newStatus = currentStatus == 1 ? 0 : 1;
            var newLabel = newStatus == 1 ? 'Active' : 'Inactive';
            Swal.fire({
                title: 'Change Status?',
                html: 'Set <strong>' + name + '</strong> to <strong>' + newLabel + '</strong>?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: newStatus == 1 ? '#198754' : '#dc3545',
                confirmButtonText: 'Yes, ' + newLabel
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/new-branch/' + id,
                        method: 'POST',
                        data: {
                            _token: CSRF,
                            _method: 'PUT',
                            branch_name: name,
                            is_active: newStatus,
                            _status_only: 1
                        },
                        success: function(res) {
                            if (res.status) {
                                toast('success', 'Status updated to ' + newLabel);
                                setTimeout(function() {
                                    location.reload();
                                }, 1600);
                            }
                        },
                        error: function() {
                            toast('error', 'Status update failed.');
                        }
                    });
                }
            });
        }

        /* ---- Delete Branch ---- */
        function deleteBranch(id, name) {
            Swal.fire({
                title: 'Delete Branch?',
                html: 'This will permanently delete <strong>' + name + '</strong>.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete'
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/new-branch/' + id,
                        method: 'POST',
                        data: {
                            _token: CSRF,
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            if (res.status) {
                                toast('success', res.message);
                                setTimeout(function() {
                                    location.reload();
                                }, 1600);
                            }
                        },
                        error: function() {
                            toast('error', 'Could not delete branch.');
                        }
                    });
                }
            });
        }
    </script>
@endsection
