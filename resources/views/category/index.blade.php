<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Category Master <span
                            class="badge badge-soft-primary border border-primary ms-2">Total Categories : 6</span></h4>
                </div>
                <div class="text-end d-flex">
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                        data-bs-target="#add_modal"><i class="ti ti-plus me-1"></i>Add New Category</a>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Linked Department</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                    <tr>
                                        <td>{{ $cat->category_name }}</td>
                                        <td>{{ $cat->description ?? '-' }}</td>
                                        <td>{{ $cat->department->DepartmentName ?? '-' }}</td>
                                        <td>
                                            @if ($cat->status == 'Active')
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
                        Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }}
                        of {{ $categories->total() }} entries
                    </div>

                </div>
                <div class="pagination-box">
                    {{ $categories->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
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
                            <h4 class="text-dark modal-title fw-bold">Add New Category</h4>
                            <button type="button" class="btn-close btn-close-modal custom-btn-close"
                                data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                        </div>

                        <form id="categoryForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Category Name</label>
                                    <input type="text" name="category_name" id="category_name" class="form-control"
                                        placeholder="Enter category name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Linked Department</label>

                                    <select name="department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->Departmentid }}">
                                                {{ $dept->DepartmentName }}
                                            </option>
                                        @endforeach
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
                                <button type="submit" id="submitBtn" class="btn btn-primary">Add New Category</button>
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

            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#submitBtn');

                submitBtn.prop('disabled', true).text('Processing...');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback.dynamic').remove();

                $.ajax({
                    url: "{{ url('category/store') }}",
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
                                title: "Category Created Successfully",
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
