<?php $page = 'masters'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">

        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Occupation Master
                    <span class="badge badge-soft-primary border border-primary page-header-badge ms-2">
                        Total Occupations : {{ $totalCount }}
                    </span>
                </h4>
            </div>
            <div class="text-end d-flex">
                <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md"
                    data-bs-toggle="modal" data-bs-target="#add_modal">
                    <i class="ti ti-plus me-1"></i>Add New Occupation
                </a>
            </div>
        </div>

        <div class="card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Occupation Name</th>
                                <th>Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($occupations as $i => $occupation)
                                <tr>
                                    <td>{{ $occupations->firstItem() + $i }}</td>
                                    <td>{{ $occupation->occupation_name }}</td>
                                    <td>{{ $occupation->occupationType ?? '-' }}</td>
                                    <td>
                                        <div class="action-item">
                                            <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                                <i class="ti ti-dots-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu p-2">
                                                <li>
                                                    <a href="#" class="dropdown-item edit-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#edit_modal"
                                                        data-id="{{ $occupation->occupation_id }}"
                                                        data-name="{{ $occupation->occupation_name }}"
                                                        data-type="{{ $occupation->occupationType }}">
                                                        Edit
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#" class="dropdown-item delete-btn"
                                                        data-id="{{ $occupation->occupation_id }}"
                                                        data-name="{{ $occupation->occupation_name }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#delete_modal">
                                                        Delete
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No occupations found</td>
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
                <div>Showing {{ $occupations->firstItem() }} to {{ $occupations->lastItem() }} of {{ $occupations->total() }} entries</div>
            </div>
        </div>
        <div class="pagination-box">
            {{ $occupations->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
        </div>

    </div>

    {{-- Add Modal --}}
    <div id="add_modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-dark modal-title fw-bold">Add New Occupation</h4>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                </div>
                <form id="addForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Occupation Name <span class="text-danger">*</span></label>
                                <input type="text" name="occupation_name" class="form-control" placeholder="Enter occupation name">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Type</label>
                                <input type="text" name="occupationType" class="form-control" placeholder="e.g. Professional, Manual">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="addSubmitBtn" class="btn btn-primary">Add Occupation</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit_modal" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="text-dark modal-title fw-bold">Edit Occupation</h4>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                </div>
                <form id="editForm">
                    @csrf
                    <input type="hidden" id="edit_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Occupation Name <span class="text-danger">*</span></label>
                                <input type="text" name="occupation_name" id="edit_occupation_name" class="form-control">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Type</label>
                                <input type="text" name="occupationType" id="edit_occupationType" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="editSubmitBtn" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="delete_modal">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <span class="avatar avatar-xl bg-danger-transparent rounded-circle text-danger">
                            <i class="ti ti-trash fs-24"></i>
                        </span>
                    </div>
                    <h5 class="mb-1">Delete Occupation</h5>
                    <p class="mb-3 text-muted" id="delete_name_text"></p>
                    <input type="hidden" id="delete_id">
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
                    </div>
                </div>
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
    document.getElementById('perPage').addEventListener('change', function () {
        let url = new URL(window.location.href);
        url.searchParams.set('per_page', this.value);
        window.location.href = url.toString();
    });

    $(document).ready(function () {

        $(document).on('click', '.edit-btn', function () {
            $('#edit_id').val($(this).data('id'));
            $('#edit_occupation_name').val($(this).data('name'));
            $('#edit_occupationType').val($(this).data('type') || '');
        });

        $(document).on('click', '.delete-btn', function () {
            $('#delete_id').val($(this).data('id'));
            $('#delete_name_text').text('Are you sure you want to delete "' + $(this).data('name') + '"?');
        });

        function handleFormError(xhr, form) {
            if (xhr.status === 422) {
                $.each(xhr.responseJSON.errors, function (key, value) {
                    let input = $(form).find('[name="' + key + '"]');
                    input.addClass('is-invalid');
                    input.after('<div class="invalid-feedback dynamic">' + value[0] + '</div>');
                });
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseJSON?.message || 'Something went wrong!' });
            }
        }

        $('#addForm').on('submit', function (e) {
            e.preventDefault();
            let btn = $('#addSubmitBtn');
            btn.prop('disabled', true).text('Saving...');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback.dynamic').remove();
            $.ajax({
                url: "{{ route('occupation.store') }}", type: 'POST',
                data: new FormData(this), processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    btn.prop('disabled', false).text('Add Occupation');
                    if (res.status) {
                        $('#add_modal').modal('hide'); $('#addForm')[0].reset();
                        Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 });
                        setTimeout(() => location.reload(), 1600);
                    }
                },
                error: function (xhr) { btn.prop('disabled', false).text('Add Occupation'); handleFormError(xhr, '#addForm'); }
            });
        });

        $('#editForm').on('submit', function (e) {
            e.preventDefault();
            let btn = $('#editSubmitBtn'), id = $('#edit_id').val();
            btn.prop('disabled', true).text('Saving...');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback.dynamic').remove();
            let data = new FormData(this); data.append('_method', 'PUT');
            $.ajax({
                url: "{{ url('occupation') }}/" + id, type: 'POST',
                data: data, processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    btn.prop('disabled', false).text('Save Changes');
                    if (res.status) {
                        $('#edit_modal').modal('hide');
                        Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 });
                        setTimeout(() => location.reload(), 1600);
                    }
                },
                error: function (xhr) { btn.prop('disabled', false).text('Save Changes'); handleFormError(xhr, '#editForm'); }
            });
        });

        $('#confirmDeleteBtn').on('click', function () {
            let id = $('#delete_id').val();
            $(this).prop('disabled', true).text('Deleting...');
            $.ajax({
                url: "{{ url('occupation') }}/" + id, type: 'POST',
                data: { _method: 'DELETE', _token: $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    $('#delete_modal').modal('hide');
                    Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 });
                    setTimeout(() => location.reload(), 1600);
                },
                error: function () {
                    $('#confirmDeleteBtn').prop('disabled', false).text('Delete');
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong!' });
                }
            });
        });
    });
</script>
@endsection
