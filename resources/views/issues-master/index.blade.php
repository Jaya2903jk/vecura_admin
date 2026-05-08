<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Issues Master <span
                            class="badge badge-soft-primary border border-primary ms-2">Total Issues : 6</span></h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_modal"><i class="ti ti-plus me-1"></i>Add New Issues</a>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Issues Name</th>
                                    <th>Department Name</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($issues as $issue)
                                    <tr>
                                        <td>{{ $issue->IssueName }}</td>

                                        <td>{{ $issue->department->DepartmentName ?? '-' }}</td>

                                        <td>{{ $issue->category->category_name ?? '-' }}</td>

                                        <td>
                                            @if ($issue->Status == 1)
                                                <span class="badge badge-soft-success border border-success">Active</span>
                                            @else
                                                <span class="badge badge-soft-danger border border-danger">Inactive</span>
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
                        Showing {{ $issues->firstItem() }} to {{ $issues->lastItem() }}
                        of {{ $issues->total() }} entries
                    </div>

                </div>
                <div class="pagination-box">
                    {{ $issues->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
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
                            <h4 class="text-dark modal-title fw-bold">Add Issues Name</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>

                        <form id="issuesForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="modal-body">


                                <div class="mb-3">
                                    <label class="form-label">Linked Department</label>

                                    <select name="department_id" id="department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->Departmentid }}">
                                                {{ $dept->DepartmentName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" id="category_id" class="form-control">
                                        <option value="">Select Category</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Issues Name</label>
                                    <input type="text" name="issues_name" id="issues_name" class="form-control"
                                        placeholder="Enter Issues name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex align-items-center gap-1">
                                <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" id="submitBtn" class="btn btn-primary">Add New Issues</button>
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
        $('#department_id').on('change', function() {
            let deptId = $(this).val();
            $('#category_id').html('<option>Loading...</option>');

            if (deptId) {
                $.ajax({
                    url: '/get-categories/' + deptId,
                    type: 'GET',
                    success: function(data) {
                        // console.log(data);
                        let options = '<option value="">Select Category</option>';
                        data.forEach(function(item) {
                            options +=
                                `<option value="${item.category_id}">${item.category_name}</option>`;
                        });
                        $('#category_id').html(options);
                    }
                });
            } else {
                $('#category_id').html('<option value="">Select Category</option>');
            }
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#issuesForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#submitBtn');

                submitBtn.prop('disabled', true).text('Processing...');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback.dynamic').remove();

                $.ajax({
                    url: "{{ url('issues-master/store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(response) {

                        submitBtn.prop('disabled', false).text('Add New Issues');

                        if (response.status) {

                            $('#add_modal').modal('hide');
                            form.reset();

                            Swal.fire({
                                icon: "success",
                                title: "Issues Created Successfully",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        }
                    },

                    error: function(xhr) {

                        submitBtn.prop('disabled', false).text('Add New Issues');

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
