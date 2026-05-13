<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Machine Issues Master <span
                            class="badge badge-soft-primary border border-primary ms-2">Total Machine Issues : 6</span></h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_modal"><i class="ti ti-plus me-1"></i>Add New Machine Issues</a>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Issues Name</th>
                                    <th>Linked Machine</th>
                                    <th>Description</th>
                                    <th>Type</th>

                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($MachineIssues as $cat)
                                    <tr>
                                        <td>{{ $cat->IssuesName }}</td>
                                        <td>{{ $cat->Machine->MachineName ?? '-' }}</td>
                                        <td>{{ $cat->Description ?? '-' }}</td>
                                        <td>{{ $cat->Type }}</td>

                                        <td>
                                            @if ($cat->Status == 'Active')
                                                <span class="badge badge-soft-success border border-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-warning border border-warning">Inactive</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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
                        Showing {{ $MachineIssues->firstItem() }} to {{ $MachineIssues->lastItem() }}
                        of {{ $MachineIssues->total() }} entries
                    </div>

                </div>
                <div class="pagination-box">
                    {{ $MachineIssues->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
                </div>

            </div>
            <script>
                document.getElementById('perPageDept').addEventListener('change', function() {
                    let perPage = this.value;
                    let url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    window.location.href = url.toString();
                });
            </script>

            <div id="add_modal" class="modal fade">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="text-dark modal-title fw-bold">Add New Machine Issues</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>

                        <form id="MachineIssuesForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Issues Name</label>
                                    <input type="text" name="issues_name" id="issues_name" class="form-control"
                                        placeholder="Enter issues name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Linked Machine</label>

                                    <select name="machine_id" class="form-control">
                                        <option value="">Select Machine</option>
                                        @foreach ($machines as $machine)
                                            <option value="{{ $machine->MachineId }}">
                                                {{ $machine->MachineName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-control">
                                        <option value="spare">Spare Required</option>
                                        <option value="component">Component Required</option>
                                        <option value="service">service Issues</option>

                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="submitBtn" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer text-center bg-white p-2 border-top">
            <p class="text-dark mb-0">2026 &copy; <a href="javascript:void(0);" class="link-primary">Preclinic</a>, All
                Rights Reserved</p>
        </div>
    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $('#MachineIssuesForm').on('submit', function(e) {
                // alert('ok');
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#submitBtn');

                submitBtn.prop('disabled', true).text('Processing...');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback.dynamic').remove();

                $.ajax({
                    url: "{{ url('machine-issues/store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },

                    success: function(response) {

                        submitBtn.prop('disabled', false).text('Add New Category');

                        if (response.status) {

                            $('#add_modal').modal('hide');
                            form.reset();

                            Swal.fire({
                                icon: "success",
                                title: "Machine Issue Created Successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },

                    error: function(xhr) {

                        submitBtn.prop('disabled', false).text('Add New Category');

                        if (xhr.status === 422) {

                            let errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {

                                let input = $('[name="' + key + '"]');

                                input.addClass('is-invalid');

                                if (input.next('.invalid-feedback').length) {
                                    input.next('.invalid-feedback').text(value[0]);
                                } else {
                                    input.after(
                                        '<div class="invalid-feedback dynamic">' +
                                        value[0] + '</div>'
                                    );
                                }
                            });

                            $('html, body').animate({
                                scrollTop: $('.is-invalid:first').offset().top - 100
                            }, 500);

                        } else {

                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Something went wrong!",
                            });

                            console.log(xhr.responseText);
                        }
                    }
                });
            });

        });
    </script>
@endsection
