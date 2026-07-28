<?php $page = 'facility'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-building text-primary fs-24"></i>Facility Issue Category Master
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total: {{ $totalCount }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage issue categories and classification options for facility maintenance tickets.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'facility-issues'))
                        <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#add_modal">
                            <i class="ti ti-plus me-1"></i>Add New Category
                        </button>
                    @endif
                </div>
            </div>

            <!-- TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">#</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Category Name</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Description</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($categories as $i => $cat)
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted fs-13">{{ ($categories->currentPage() - 1) * $categories->perPage() + $i + 1 }}</td>
                                        <td>
                                            <span class="patient-code-tag font-monospace fw-bold fs-13 text-dark">{{ $cat->name }}</span>
                                        </td>
                                        <td class="fs-13 text-secondary">{{ $cat->description ?? '—' }}</td>
                                        <td>
                                            @if ($cat->status == 1)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-circle-check me-1"></i>Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-x me-1"></i>Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'facility-issues'))
                                                    <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Edit Category"
                                                            onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}', {{ $cat->status }})">
                                                        <i class="ti ti-edit fs-15 text-primary"></i>
                                                    </button>
                                                @endif
                                                @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'facility-issues'))
                                                    <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Delete Category"
                                                            onclick="confirmDelete({{ $cat->id }}, '{{ addslashes($cat->name) }}')">
                                                        <i class="ti ti-trash fs-15 text-danger"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-building-off fs-36 text-muted mb-2 d-block"></i>
                                            No facility categories found.
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
                    <div class="d-flex align-items-center gap-2">
                        <span>Row Per Page</span>
                        <select id="perPageSelect" class="form-select form-select-sm" style="width:75px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="text-muted border-start ps-3">
                        Showing <span class="fw-semibold text-dark">{{ $categories->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $categories->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $categories->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$categories" :append="['per_page' => $perPage]" />
                </div>
            </div>

        </div>

        <!-- ADD MODAL -->
        <div id="add_modal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom px-4 py-3 bg-light">
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="ti ti-plus text-primary fs-20"></i>Add New Category
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addForm">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold fs-13 text-dark">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control fs-13" placeholder="Enter category name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold fs-13 text-dark">Description</label>
                                <textarea name="description" class="form-control fs-13" rows="3" placeholder="Enter description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold fs-13 text-dark">Status</label>
                                <select name="status" class="form-select fs-13">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                            <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="addBtn" class="btn btn-primary btn-sm fw-bold px-3">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- EDIT MODAL -->
        <div id="edit_modal" class="modal fade" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header border-bottom px-4 py-3 bg-light">
                        <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="ti ti-edit text-primary fs-20"></i>Edit Category
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="edit_id">
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="form-label fw-semibold fs-13 text-dark">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control fs-13">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold fs-13 text-dark">Description</label>
                                <textarea name="description" id="edit_description" class="form-control fs-13" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold fs-13 text-dark">Status</label>
                                <select name="status" id="edit_status" class="form-select fs-13">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                            <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="editBtn" class="btn btn-primary btn-sm fw-bold px-3">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('perPageSelect').addEventListener('change', function() {
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });

        $('#addForm').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#addBtn');
            btn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: '{{ route('facility.issue.category.store') }}',
                type: 'POST',
                data: new FormData(this),
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(res) {
                    if (res.status) {
                        $('#add_modal').modal('hide');
                        $('#addForm')[0].reset();
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => location.reload(), 1600);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('Add Category');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, val) {
                            $('[name="' + key + '"]', '#addForm').addClass('is-invalid')
                                .after('<div class="invalid-feedback dynamic">' + val[0] + '</div>');
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong!'
                        });
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Add Category');
                }
            });
        });

        function openEdit(id, name, description, status) {
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_description').val(description);
            $('#edit_status').val(status == 1 ? 'Active' : 'Inactive');
            $('#edit_modal').modal('show');
        }

        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_id').val();
            let btn = $('#editBtn');
            btn.prop('disabled', true).text('Saving...');

            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            $.ajax({
                url: '/facility-issue-category/' + id,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                success: function(res) {
                    if (res.status) {
                        $('#edit_modal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => location.reload(), 1600);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).text('Save Changes');
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Something went wrong!'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save Changes');
                }
            });
        });

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Delete Category?',
                text: 'Are you sure you want to delete "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/facility-issue-category/' + id,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: $('input[name="_token"]').val()
                        },
                        success: function(res) {
                            if (res.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: res.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                setTimeout(() => location.reload(), 1600);
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong!'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
