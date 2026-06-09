<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">

                    <h4 class="fw-bold mb-0">Location Master<span
                            class="badge badge-soft-primary border border-primary page-header-badge ms-2">Total
                            Locations :
                            {{ $totalCount }}</span></h4>
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
                            <li><a class="dropdown-item" href="#">Download as Excel</a></li>
                        </ul>
                    </div>

                </div>
                <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal"
                    data-bs-target="#add_modal"><i class="ti ti-plus me-1"></i>Add New Location</a>
            </div>


            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="search-set">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn-searchset"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex table-dropdown pb-1 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);"
                            class="btn btn-white bg-white fs-14 py-1 border d-inline-flex text-dark align-items-center"
                            data-bs-toggle="dropdown">
                            <i class="ti ti-filter text-gray-5 me-1"></i>Filters
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width:280px;">
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <select class="select">
                                    <option>Select</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                            </div>
                            <div class="mb-0 d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-light btn-sm">Reset</button>
                                <button type="button" class="btn btn-primary btn-sm">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0">
                <div class="card-body p-0">

                    <div class="table-responsive">
                        <table class="table datatable mb-0">
                            <thead>
                                <tr>
                                    <th>Location Code</th>
                                    <th>Location Name</th>
                                    <th>City</th>
                                    <th>State</th>
                                    <th>Address</th>
                                    <th>Pincode</th>
                                    <th>GST No</th>
                                    <th>Phone No</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locations as $location)
                                    <tr>
                                        <td>{{ $location->LocationCode }}</td>
                                        <td>{{ $location->LocationName }}</td>
                                        <td>{{ $location->city ? $location->city->city_name : '-' }}</td>
                                        <td>{{ $location->state ? $location->state->state_name : '-' }}</td>
                                        <td> {{ $location->LocationAddress }}</td>
                                        <td>{{ $location->LocPinCode }}</td>
                                        <td>{{ $location->GSTNo ?? '-' }}</td>
                                        <td>{{ $location->phoneno ?? '-' }}</td>

                                        <td>
                                            <div class="action-item">
                                                <a href="javascript:void(0);" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </a>
                                                <ul class="dropdown-menu p-2">
                                                    <li>
                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#edit_modal">
                                                            Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
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
                                        <td colspan="8" class="text-center">No data found</td>
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

                        Showing {{ $locations->firstItem() }} to {{ $locations->lastItem() }}
                        of {{ $locations->total() }} entries
                    </div>

                </div>
            </div>
            <div class="pagination-box">
                {{ $locations->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
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
                        <h4 class="text-dark modal-title fw-bold">Add New Location</h4>
                        <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                            aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form id="locationForm" class="needs-validation" novalidate>
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Location Code<span class="text-danger ms-1">*</span>
                                    </label>
                                    <input type="text" name="location_code" id="location_code" class="form-control"
                                        placeholder="Enter location code">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Location Name<span class="text-danger ms-1">*</span>
                                    </label>
                                    <input type="text" name="location_name" id="location_name" class="form-control"
                                        placeholder="Enter location name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Pincode<span class="text-danger ms-1">*</span>
                                    </label>
                                    <input type="number" name="pincode" id="pincode" class="form-control"
                                        placeholder="Enter pincode">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">State Code</label>
                                    <input type="text" name="state_code" id="state_code" class="form-control"
                                        placeholder="Enter state code">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City Code</label>
                                    <input type="text" name="city_code" id="city_code" class="form-control"
                                        placeholder="Enter city code">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Zone State</label>
                                    <input type="text" name="zone_state" id="zone_state" class="form-control"
                                        placeholder="Enter zone state">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">GST No</label>
                                    <input type="text" name="gst_no" id="gst_no" class="form-control"
                                        placeholder="Enter GST number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone No</label>
                                    <input type="number" name="phone_no" id="phone_no" class="form-control"
                                        placeholder="Enter phone number">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">
                                        Location Address<span class="text-danger ms-1">*</span>
                                    </label>
                                    <textarea name="location_address" id="location_address" class="form-control" rows="3"
                                        placeholder="Enter location address"></textarea>
                                </div>

                            </div>

                        </div>
                        <div class="modal-footer d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" id="submitBtn" class="btn btn-primary">Add New Location</button>
                        </div>


                    </form>
                </div>
            </div>
        </div>

        <div id="edit_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="text-dark modal-title fw-bold">Edit Location</h4>
                        <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                            aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="department.html">
                        <div class="modal-body">

                            <div class="mb-3">
                                <label class="form-label">Department Name<span class="text-danger ms-1">*</span></label>
                                <input type="text" class="form-control" placeholder="Enter department name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description<span class="text-danger ms-1">*</span></label>
                                <textarea class="form-control" rows="3" placeholder="Enter description"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                <div class="mb-3">
                                    <label class="form-label">Location Code<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="location_code" class="form-control"
                                        placeholder="Enter location code">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Location Name<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="location_name" class="form-control"
                                        placeholder="Enter location name">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Location Address<span
                                            class="text-danger ms-1">*</span></label>
                                    <textarea name="location_address" class="form-control" rows="3" placeholder="Enter location address"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pincode<span class="text-danger ms-1">*</span></label>
                                    <input type="text" name="pincode" class="form-control"
                                        placeholder="Enter pincode">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">State Code</label>
                                    <input type="text" name="state_code" class="form-control"
                                        placeholder="Enter state code">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City Code</label>
                                    <input type="text" name="city_code" class="form-control"
                                        placeholder="Enter city code">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Zone State</label>
                                    <input type="text" name="zone_state" class="form-control"
                                        placeholder="Enter zone state">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">GST No</label>
                                    <input type="text" name="gst_no" class="form-control"
                                        placeholder="Enter GST number">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone No</label>
                                    <input type="text" name="phone_no" class="form-control"
                                        placeholder="Enter phone number">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                                    <select class="select">
                                        <option>Active</option>
                                        <option>Inactive</option>
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

        <div class="modal fade" id="delete_modal">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <div class="mb-3">
                            <span class="avatar avatar-xl bg-danger-transparent rounded-circle text-danger">
                                <i class="ti ti-trash fs-24"></i>
                            </span>
                        </div>
                        <h5 class="mb-2">Delete Department</h5>
                        <p class="mb-3">Are you sure you want to delete this department record?</p>
                        <h5 class="mb-2">Delete Location</h5>
                        <p class="mb-3">Are you sure you want to delete this location record?</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer text-center bg-white p-2 border-top">
        <p class="text-dark mb-0">Copyright &copy; 2026 - Vecura.</p>
    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#locationForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $('#submitBtn');

                submitBtn.prop('disabled', true).text('Processing...');

                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback.dynamic').remove();

                $.ajax({
                    url: "{{ route('location.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // ← fixed
                    },
                    success: function(response) {
                        submitBtn.prop('disabled', false).text('Add New Location');
                        if (response.status) {
                            $('#add_modal').modal('hide');
                            form.reset();
                            Swal.fire({
                                icon: 'success',
                                title: 'Location Created Successfully',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function() {
                                location.reload();
                            }, 1600);
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('Add New Location');
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
                                        value[0] + '</div>');
                                }
                            });
                            $('html, body').animate({
                                scrollTop: $('.is-invalid:first').offset().top - 100
                            }, 500);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Something went wrong!'
                            });
                            console.error(xhr.responseText);
                        }
                    }
                });
            });

        });
    </script>
@endsection
