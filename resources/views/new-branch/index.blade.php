@extends('layout.mainlayout')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-git-branch text-primary fs-24"></i>Branch Master
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total Branches: {{ $totalCount }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage organization branches, locations, managers, contact details, and wallets.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'branch'))
                        <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" onclick="openAddModal()">
                            <i class="ti ti-plus me-1"></i>New Branch Registration
                        </button>
                    @endif
                </div>
            </div>

            <!-- BRANCH TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">Branch Info</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Code</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Zone / City</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Manager / Contact</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Wallet Balance</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($branches as $i => $branch)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="patient-avatar-circle flex-shrink-0 text-decoration-none">
                                                    <i class="ti ti-building text-primary fs-16"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark fs-13">{{ $branch->branch_name }}</span>
                                                    <div class="fs-11 text-muted">ID: #{{ $branch->branch_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="patient-code-tag font-monospace">{{ $branch->branch_code }}</span>
                                        </td>
                                        <td>
                                            <div class="fs-13 text-dark fw-semibold">{{ $branch->city?->city_name ?? '—' }}</div>
                                            <div class="fs-11 text-muted">{{ $branch->zone?->zone_name ?? '—' }}</div>
                                        </td>
                                        <td>
                                            <div class="fs-13 text-dark fw-semibold"><i class="ti ti-user me-1 text-muted fs-12"></i>{{ $branch->manager_name ?? '—' }}</div>
                                            <div class="fs-11 text-muted"><i class="ti ti-phone me-1 text-muted fs-11"></i>{{ $branch->contact_no ?? '—' }}</div>
                                        </td>
                                        <td>
                                            @if ($branch->wallet)
                                                <span class="fw-bold text-success fs-13">
                                                    ₹{{ number_format($branch->wallet->current_balance, 2) }}
                                                </span>
                                                <div class="fs-11 text-muted">
                                                    Cr: ₹{{ number_format($branch->wallet->total_credited, 2) }} / Dr: ₹{{ number_format($branch->wallet->total_debited, 2) }}
                                                </div>
                                            @else
                                                <span class="text-muted fs-12">No wallet</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($branch->is_active)
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
                                                @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'branch'))
                                                    <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Edit Branch"
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
                                                        <i class="ti ti-edit fs-15 text-warning"></i>
                                                    </button>
                                                @endif
                                                @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'branch'))
                                                    <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Delete Branch"
                                                        onclick="deleteBranch({{ $branch->branch_id }}, '{{ addslashes($branch->branch_name) }}')">
                                                        <i class="ti ti-trash fs-15 text-danger"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-building-community-off fs-36 text-muted mb-2 d-block"></i>
                                            No branches found matching criteria.
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
                        Showing <span class="fw-semibold text-dark">{{ $branches->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $branches->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $branches->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$branches" :append="['per_page' => $perPage]" />
                </div>
            </div>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addBranchModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-building text-primary fs-20"></i>Add New Branch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" id="add_branch_name" class="form-control fs-13" placeholder="Enter branch name">
                            <div class="text-danger small mt-1 d-none" id="err_add_branch_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" id="add_branch_code" class="form-control fs-13" placeholder="e.g. BR001"
                                maxlength="20" style="text-transform:uppercase">
                            <div class="text-danger small mt-1 d-none" id="err_add_branch_code"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Zone <span class="text-danger">*</span></label>
                            <select id="add_zone_id" class="form-select fs-13">
                                <option value="">-- Select Zone --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->zone_id }}">{{ $zone->zone_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_add_zone_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">City <span class="text-danger">*</span></label>
                            <select id="add_city_id" class="form-select fs-13">
                                <option value="">-- Select City --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->city_id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_add_city_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Manager Name</label>
                            <input type="text" id="add_manager_name" class="form-control fs-13"
                                placeholder="Enter manager name" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Contact No</label>
                            <input type="text" id="add_contact_no" class="form-control fs-13"
                                placeholder="Enter contact number" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                            <select id="add_is_active" class="form-select fs-13">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                    <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm fw-bold px-3" id="btnSave" onclick="saveBranch()">
                        <span id="btnSaveText"><i class="ti ti-plus me-1"></i>Save Branch</span>
                        <span id="btnSaveLoader" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Saving...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal fade" id="editBranchModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                        <i class="ti ti-edit text-warning fs-20"></i>Edit Branch
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="edit_branch_id">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" id="edit_branch_name" class="form-control fs-13">
                            <div class="text-danger small mt-1 d-none" id="err_edit_branch_name"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" id="edit_branch_code" class="form-control fs-13" maxlength="20"
                                style="text-transform:uppercase">
                            <div class="text-danger small mt-1 d-none" id="err_edit_branch_code"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Zone <span class="text-danger">*</span></label>
                            <select id="edit_zone_id" class="form-select fs-13">
                                <option value="">-- Select Zone --</option>
                                @foreach ($zones as $zone)
                                    <option value="{{ $zone->zone_id }}">{{ $zone->zone_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_edit_zone_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">City <span class="text-danger">*</span></label>
                            <select id="edit_city_id" class="form-select fs-13">
                                <option value="">-- Select City --</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->city_id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger small mt-1 d-none" id="err_edit_city_id"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Manager Name</label>
                            <input type="text" id="edit_manager_name" class="form-control fs-13" maxlength="100">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Contact No</label>
                            <input type="text" id="edit_contact_no" class="form-control fs-13" maxlength="20">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                            <select id="edit_is_active" class="form-select fs-13">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                    <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning text-white btn-sm fw-bold px-3" id="btnUpdate" onclick="updateBranch()">
                        <span id="btnUpdateText"><i class="ti ti-check me-1"></i>Update Branch</span>
                        <span id="btnUpdateLoader" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Updating...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        var CSRF = '{{ csrf_token() }}';

        document.getElementById('perPage').addEventListener('change', function() {
            let perPage = this.value;
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', perPage);
            window.location.href = url.toString();
        });

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

        function deleteBranch(id, name) {
            Swal.fire({
                title: 'Delete Branch?',
                html: 'Are you sure you want to delete <strong>' + name + '</strong>?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
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
                            toast('error', 'Failed to delete. Please try again.');
                        }
                    });
                }
            });
        }
    </script>
@endsection
