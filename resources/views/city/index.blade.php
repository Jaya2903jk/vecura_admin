<?php $page = 'masters'; ?>
@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content px-4 py-3">

        <!-- PAGE HEADER -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
            <div>
                <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                    <i class="ti ti-building-community text-primary fs-24"></i>City Master
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                        Total Cities: {{ $totalCount }}
                    </span>
                </h4>
                <p class="text-muted fs-13 mb-0">Manage city boundaries, state associations, pincodes, and active statuses.</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#add_modal">
                    <i class="ti ti-plus me-1"></i>New City Registration
                </button>
            </div>
        </div>

        <!-- CITY TABLE CARD -->
        <div class="card border-0 shadow-xs rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light border-bottom">
                            <tr>
                                <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">City Name</th>
                                <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">State</th>
                                <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Pincode</th>
                                <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($cities as $i => $city)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="patient-avatar-circle flex-shrink-0 text-decoration-none">
                                                <i class="ti ti-building-community text-primary fs-16"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold text-dark fs-13">{{ $city->city_name }}</span>
                                                <div class="fs-11 text-muted">ID: #{{ $city->city_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fs-13 text-dark fw-semibold">{{ $city->state?->state_name ?? '-' }}</div>
                                    </td>
                                    <td>
                                        <span class="patient-code-tag font-monospace">{{ $city->pincode ?? '-' }}</span>
                                    </td>
                                    <td>
                                        @if($city->is_active)
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
                                            <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs edit-btn" title="Edit City"
                                                data-bs-toggle="modal" data-bs-target="#edit_modal"
                                                data-id="{{ $city->city_id }}"
                                                data-name="{{ $city->city_name }}"
                                                data-state="{{ $city->state_id }}"
                                                data-pincode="{{ $city->pincode }}"
                                                data-active="{{ $city->is_active ? 1 : 0 }}">
                                                <i class="ti ti-edit fs-15 text-warning"></i>
                                            </button>
                                            <button type="button" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs delete-btn" title="Delete City"
                                                data-id="{{ $city->city_id }}"
                                                data-name="{{ $city->city_name }}"
                                                data-bs-toggle="modal" data-bs-target="#delete_modal">
                                                <i class="ti ti-trash fs-15 text-danger"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-5 fs-13">
                                        <i class="ti ti-building-community-off fs-36 text-muted mb-2 d-block"></i>
                                        No cities found matching criteria.
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
                    Showing <span class="fw-semibold text-dark">{{ $cities->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $cities->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $cities->total() }}</span> entries
                </div>
            </div>
            <div>
                <x-pagination :paginator="$cities" :append="['per_page' => $perPage]" />
            </div>
        </div>

    </div>
</div>

<!-- ADD MODAL -->
<div id="add_modal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom px-4 py-3 bg-light">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="ti ti-building-community text-primary fs-20"></i>Add New City
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold fs-13 text-dark">City Name <span class="text-danger">*</span></label>
                            <input type="text" name="city_name" class="form-control fs-13" placeholder="Enter city name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-13 text-dark">Pincode</label>
                            <input type="text" name="pincode" class="form-control fs-13" maxlength="20" placeholder="e.g. 600001">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-13 text-dark">State <span class="text-danger">*</span></label>
                            <select name="state_id" class="form-select fs-13" required>
                                <option value="">-- Select State --</option>
                                @foreach($states as $s)
                                    <option value="{{ $s->state_id }}">{{ $s->state_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                            <select name="is_active" class="form-select fs-13" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                    <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="addSubmitBtn" class="btn btn-primary btn-sm fw-bold px-3">
                        <i class="ti ti-plus me-1"></i>Add City
                    </button>
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
                    <i class="ti ti-edit text-warning fs-20"></i>Edit City
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                @csrf
                <input type="hidden" id="edit_id">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold fs-13 text-dark">City Name <span class="text-danger">*</span></label>
                            <input type="text" name="city_name" id="edit_city_name" class="form-control fs-13" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-13 text-dark">Pincode</label>
                            <input type="text" name="pincode" id="edit_pincode" class="form-control fs-13" maxlength="20">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-13 text-dark">State <span class="text-danger">*</span></label>
                            <select name="state_id" id="edit_state_id" class="form-select fs-13" required>
                                <option value="">-- Select State --</option>
                                @foreach($states as $s)
                                    <option value="{{ $s->state_id }}">{{ $s->state_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-13 text-dark">Status <span class="text-danger">*</span></label>
                            <select name="is_active" id="edit_is_active" class="form-select fs-13" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                    <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="editSubmitBtn" class="btn btn-warning text-white btn-sm fw-bold px-3">
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
                <h5 class="fw-bold text-dark mb-1">Delete City</h5>
                <p class="text-muted fs-13 mb-4" id="delete_name_text"></p>
                <input type="hidden" id="delete_id">
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light border btn-sm fw-semibold px-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger btn-sm fw-bold px-3">Delete</button>
                </div>
            </div>
        </div>
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
            $('#edit_city_name').val($(this).data('name'));
            $('#edit_state_id').val($(this).data('state').toString());
            $('#edit_pincode').val($(this).data('pincode') || '');
            $('#edit_is_active').val($(this).data('active').toString());
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
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback.dynamic').remove();
            $.ajax({
                url: "{{ route('city.store') }}", type: 'POST',
                data: new FormData(this), processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    btn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i>Add City');
                    if (res.status) {
                        $('#add_modal').modal('hide'); $('#addForm')[0].reset();
                        Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 });
                        setTimeout(() => location.reload(), 1600);
                    }
                },
                error: function (xhr) { btn.prop('disabled', false).html('<i class="ti ti-plus me-1"></i>Add City'); handleFormError(xhr, '#addForm'); }
            });
        });

        $('#editForm').on('submit', function (e) {
            e.preventDefault();
            let btn = $('#editSubmitBtn'), id = $('#edit_id').val();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
            $(this).find('.is-invalid').removeClass('is-invalid');
            $(this).find('.invalid-feedback.dynamic').remove();
            let data = new FormData(this); data.append('_method', 'PUT');
            $.ajax({
                url: "{{ url('city') }}/" + id, type: 'POST',
                data: data, processData: false, contentType: false,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (res) {
                    btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i>Save Changes');
                    if (res.status) {
                        $('#edit_modal').modal('hide');
                        Swal.fire({ icon: 'success', title: res.message, showConfirmButton: false, timer: 1500 });
                        setTimeout(() => location.reload(), 1600);
                    }
                },
                error: function (xhr) { btn.prop('disabled', false).html('<i class="ti ti-check me-1"></i>Save Changes'); handleFormError(xhr, '#editForm'); }
            });
        });

        $('#confirmDeleteBtn').on('click', function () {
            let id = $('#delete_id').val();
            $(this).prop('disabled', true).text('Deleting...');
            $.ajax({
                url: "{{ url('city') }}/" + id, type: 'POST',
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
