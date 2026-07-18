<?php $page = 'facility'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            {{-- Header --}}
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">
                        Facility Issue Category
                        <span class="badge badge-soft-primary border border-primary fs-13 fw-medium ms-2">
                            Total: {{ $totalCount }}
                        </span>
                    </h4>
                </div>
                <div class="text-end">
                    @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('create', 'facility-issues'))
                        <a href="javascript:void(0);" class="btn btn-primary fs-13 btn-md" data-bs-toggle="modal"
                            data-bs-target="#add_modal">
                            <i class="ti ti-plus me-1"></i>Add New Category
                        </a>
                    @endif


                </div>
            </div>

            {{-- Table --}}
            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table datatable mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $i => $cat)
                                    <tr>
                                        <td>{{ ($categories->currentPage() - 1) * $categories->perPage() + $i + 1 }}</td>
                                        <td class="fw-semibold">{{ $cat->name }}</td>
                                        <td>{{ $cat->description ?? '—' }}</td>
                                        <td>
                                            @if ($cat->status == 1)
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
                                                        @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'facility-issues'))
                                                            <a href="#" class="dropdown-item"
                                                                onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}', {{ $cat->status }})">
                                                                Edit
                                                            </a>
                                                        @endif
                                                    </li>
                                                    <li>
                                                        @if (session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('delete', 'facility-issues'))
                                                            <a href="#" class="dropdown-item text-danger"
                                                                onclick="confirmDelete({{ $cat->id }}, '{{ addslashes($cat->name) }}')">
                                                                Delete
                                                            </a>
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No categories found.</td>
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
                        <select id="perPageSelect" class="form-select form-select-sm d-inline-block" style="width:70px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div>
                        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
                        of {{ $categories->total() }} entries
                    </div>
                </div>
                <x-pagination :paginator="$categories" :append="['per_page' => $perPage]" />
            </div>

        </div>

        {{-- ── Add Modal ─────────────────────────────────────────────────── --}}
        <div id="add_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="text-dark modal-title fw-bold">Add New Category</h4>
                        <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"><i
                                class="ti ti-x"></i></button>
                    </div>
                    <form id="addForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Enter category name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Enter description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer gap-1">
                            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="addBtn" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Edit Modal ────────────────────────────────────────────────── --}}
        <div id="edit_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="text-dark modal-title fw-bold">Edit Category</h4>
                        <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal"><i
                                class="ti ti-x"></i></button>
                    </div>
                    <form id="editForm">
                        @csrf
                        <input type="hidden" id="edit_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" id="edit_status" class="form-control">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer gap-1">
                            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="editBtn" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="footer text-center bg-white p-2 border-top">
            <p class="text-dark mb-0">Copyright &copy; 2026 - Vecura.</p>
        </div>
    </div>

    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // ── Per page ──────────────────────────────────────────────────────────────
        document.getElementById('perPageSelect').addEventListener('change', function() {
            let url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });

        // ── Add ───────────────────────────────────────────────────────────────────
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
                                .after('<div class="invalid-feedback dynamic">' + val[0] +
                                    '</div>');
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

        // ── Open Edit ─────────────────────────────────────────────────────────────
        function openEdit(id, name, description, status) {
            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_description').val(description);
            $('#edit_status').val(status == 1 ? 'Active' : 'Inactive');
            $('#edit_modal').modal('show');
        }

        // ── Edit Submit ───────────────────────────────────────────────────────────
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

        // ── Delete ────────────────────────────────────────────────────────────────
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
