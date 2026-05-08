<?php $page = 'staff'; ?>
@extends('layout.mainlayout')
@section('content')

<div class="page-wrapper">
        <div class="content">

            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">Staff<span class="badge badge-soft-primary border border-primary page-header-badge ms-2">Total Staffs : 12</span></h4>
                </div>
                <div class="text-end d-flex">
                    <div class="dropdown me-1">
                        <a href="javascript:void(0);" class="btn btn-md fs-14 fw-normal border bg-white rounded text-dark d-inline-flex align-items-center" data-bs-toggle="dropdown">
                            Export<i class="ti ti-chevron-down ms-2"></i>
                        </a>
                        <ul class="dropdown-menu p-2">
                            <li><a class="dropdown-item" href="#">Download as PDF</a></li>
                            <li><a class="dropdown-item" href="#">Download as Excel</a></li>
                        </ul>
                    </div>
                    <a href="javascript:void(0);" class="btn btn-primary ms-2 fs-13 btn-md" data-bs-toggle="modal" data-bs-target="#add_modal"><i class="ti ti-plus me-1"></i>Add Staff</a>
                </div>
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
                        <a href="javascript:void(0);" class="btn btn-white bg-white fs-14 py-1 border d-inline-flex text-dark align-items-center" data-bs-toggle="dropdown">
                            <i class="ti ti-filter text-gray-5 me-1"></i>Filters
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-3" style="min-width:280px;">
                            <div class="mb-3">
                                <label class="form-label">Staff</label>
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
                        <tr><th>Staff Name</th><th>Role</th><th>Designation</th><th>Phone</th><th>Email</th><th>Status</th><th>Action</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><div class="d-flex align-items-center"><img src="assets/img/users/user-08.jpg" class="table-avatar-img me-2" alt=""><div><h6 class="mb-0 fs-14 fw-semibold">James Adair</h6></div></div></td><td>Admin Staff</td><td>HR Executive</td><td>+1 45212 45874</td><td>james@trustcare.com</td><td><span class="badge badge-soft-success border border-success">Available</span></td><td>
    <div class="action-item">
        <a href="javascript:void(0);" data-bs-toggle="dropdown">
            <i class="ti ti-dots-vertical"></i>
        </a>
        <ul class="dropdown-menu p-2">
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#edit_modal">Edit</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#delete_modal">Delete</a></li>
        </ul>
    </div>
    </td></tr><tr><td><div class="d-flex align-items-center"><img src="assets/img/users/user-07.jpg" class="table-avatar-img me-2" alt=""><div><h6 class="mb-0 fs-14 fw-semibold">Cheryl Bilodeau</h6></div></div></td><td>Staff Nurse</td><td>Nurse (RN)</td><td>+1 51247 56574</td><td>cheryl@trustcare.com</td><td><span class="badge badge-soft-success border border-success">Available</span></td><td>
    <div class="action-item">
        <a href="javascript:void(0);" data-bs-toggle="dropdown">
            <i class="ti ti-dots-vertical"></i>
        </a>
        <ul class="dropdown-menu p-2">
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#edit_modal">Edit</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#delete_modal">Delete</a></li>
        </ul>
    </div>
    </td></tr><tr><td><div class="d-flex align-items-center"><img src="assets/img/users/user-02.jpg" class="table-avatar-img me-2" alt=""><div><h6 class="mb-0 fs-14 fw-semibold">Valerie Padgett</h6></div></div></td><td>HR Executive</td><td>Nurse Practitioner</td><td>+1 41452 25741</td><td>valerie@trustcare.com</td><td><span class="badge badge-soft-warning border border-warning">On Leave</span></td><td>
    <div class="action-item">
        <a href="javascript:void(0);" data-bs-toggle="dropdown">
            <i class="ti ti-dots-vertical"></i>
        </a>
        <ul class="dropdown-menu p-2">
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#edit_modal">Edit</a></li>
            <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#delete_modal">Delete</a></li>
        </ul>
    </div>
    </td></tr>
                    </tbody>
                </table>
            </div>

                </div>
            </div>

        <div id="add_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="text-dark modal-title fw-bold">Add Staff</h4>
                        <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="staff.html">
                        <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter staff name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                            <select class="select"><option>Admin Staff</option><option>Staff Nurse</option><option>HR Executive</option><option>Receptionist</option></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Designation<span class="text-danger ms-1">*</span></label>
                            <select class="select"><option>HR Executive</option><option>Nurse (RN)</option><option>Nurse Practitioner</option><option>Receptionist</option></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter phone number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                            <input type="email" class="form-control" placeholder="Enter email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                            <select class="select"><option>Available</option><option>On Leave</option><option>Unavailable</option></select>
                        </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Staff</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="edit_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="text-dark modal-title fw-bold">Edit Staff</h4>
                        <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-x"></i></button>
                    </div>
                    <form action="staff.html">
                        <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter staff name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                            <select class="select"><option>Admin Staff</option><option>Staff Nurse</option><option>HR Executive</option><option>Receptionist</option></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Designation<span class="text-danger ms-1">*</span></label>
                            <select class="select"><option>HR Executive</option><option>Nurse (RN)</option><option>Nurse Practitioner</option><option>Receptionist</option></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" placeholder="Enter phone number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                            <input type="email" class="form-control" placeholder="Enter email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status<span class="text-danger ms-1">*</span></label>
                            <select class="select"><option>Available</option><option>On Leave</option><option>Unavailable</option></select>
                        </div>
                        </div>
                        <div class="modal-footer d-flex align-items-center gap-1">
                            <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
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
                        <h5 class="mb-2">Delete Staff</h5>
                        <p class="mb-3">Are you sure you want to delete this staff record?</p>
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
    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                url: "/departments",
                type: "GET",
                success: function(res) {
                    if (res.status) {
                        let options = '<option value="">Select Department</option>';
                        res.data.forEach(function(d) {
                            options +=
                                `<option value="${d.Departmentid}">${d.DepartmentName}</option>`;
                        });
                        $("#department").html(options);
                    }
                }
            });
            //  Show only if V Support (ID = 33)
            $("#department").on("change", function() {
                let deptId = $(this).val();

                if (deptId == 33) {
                    $("#vsupport_block").show();
                } else {
                    $("#vsupport_block").hide();

                    // reset values
                    $("#assign_to").val("");
                    $("#source").val("");
                    $("#call_status").val("");
                }
            });

            $("#department").change(function() {
                let deptId = $(this).val();

                $("#category").html('<option value="">Loading...</option>');
                $("#issue").html('<option value="">Select Issue</option>');

                if (deptId != "") {
                    $.ajax({
                        url: "/issue-categories",
                        type: "GET",
                        data: {
                            department_id: deptId
                        },
                        success: function(res) {
                            let options = '<option value="">Select Category</option>';
                            res.data.forEach(function(c) {
                                options +=
                                    `<option value="${c.category_id}">${c.category_name}</option>`;
                            });
                            $("#category").html(options);
                        }
                    });
                }
            });
            $("#category").change(function() {
                let categoryId = $(this).val();

                $("#issue").html('<option value="">Loading...</option>');

                if (categoryId != "") {
                    $.ajax({
                        url: "/issues/" + categoryId,
                        type: "GET",
                        success: function(res) {
                            let options = '<option value="">Select Issue</option>';
                            res.data.forEach(function(i) {
                                options +=
                                    `<option value="${i.IssueId}">${i.IssueName}</option>`;
                            });
                            $("#issue").html(options);
                        }
                    });
                }
            });

            $('#ticketForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                let submitBtn = $(form).find('button[type="submit"]');
                submitBtn.prop('disabled', true).text('Processing...');
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback.dynamic').remove();

                $.ajax({
                    url: "{{ url('tickets') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },

                    success: function(response) {
                        Swal.fire({
                            // position: "top-end",
                            icon: "success", // fixed (was type)
                            title: "Ticket Created Successfully",
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.href = "{{ route('tickets') }}";
                        }, 1500);
                    },

                    error: function(xhr) {
                        submitBtn.prop('disabled', false).text('Add Ticket');

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

                            // Scroll to first error
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
            // Set customer name
            $('#customerSelect').on('change', function() {
                let selected = this.options[this.selectedIndex];
                $('#customerName').val(selected.getAttribute('data-name') || '');
            });

            // Load tickets
            $('#customerSelect').on('change', function() {

                let customerCode = $(this).val();

                if (!customerCode) {
                    $('#customerTicketsBlock').hide();
                    return;
                }

                $.ajax({
                    url: "/customer-tickets",
                    type: "GET",
                    data: {
                        customer_code: customerCode
                    },

                    success: function(res) {

                        let table = $('#customerTicketsTable');
                        table.empty();

                        if (res.tickets.length > 0) {

                            $('#customerTicketsBlock').show();

                            let hasOpen = false;

                            res.tickets.forEach(function(t) {

                                let statusBadge = '';

                                if (t.Status === "0") {
                                    statusBadge =
                                        '<span class="badge bg-warning">Pending</span>';
                                    hasOpen = true;
                                } else if (t.Status === "1") {
                                    statusBadge =
                                        '<span class="badge bg-info">InProgress</span>';
                                    hasOpen = true;
                                } else if (t.Status === "2") {
                                    statusBadge =
                                        '<span class="badge bg-success">Resolved</span>';
                                    hasOpen = true;
                                } else {
                                    statusBadge =
                                        '<span class="badge bg-danger">Closed</span>';
                                }

                                let date = new Date(t.CreatedDate).toLocaleString();

                                let row = `
                        <tr>
                            <td>
                                <a href="/ticket/${t.ticketId}" target="_blank">
                                    #TKT${String(t.ticketId).padStart(5, '0')}
                                </a>
                            </td>
                            <td>${t.Subject ?? '-'}</td>
                            <td>${statusBadge}</td>
                            <td>${date}</td>
                            <td>
                                <a href="/ticket/${t.ticketId}" class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    `;

                                table.append(row);
                            });
                            // if (hasOpen) {
                            //     $('#submitBtn')
                            //         .prop('disabled', true)
                            //         .text('Already Active Ticket Exists');
                            // } else {
                            //     $('#submitBtn')
                            //         .prop('disabled', false)
                            //         .text('Add Ticket');
                            // }

                        } else {
                            $('#customerTicketsBlock').hide();
                            $('#submitBtn').prop('disabled', false).text('Add Ticket');
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#customer_search').select2({
                placeholder: "Type Name / Mobile / Reg No",
                minimumInputLength: 2,
                width: '100%',

                ajax: {
                    url: "{{ url('/search-customer') }}",
                    dataType: 'json',
                    delay: 250,

                    data: function(params) {
                        return {
                            search: params.term
                        };
                    },

                    processResults: function(response) {
                        return {
                            results: response.data.map(item => ({
                                id: item.RegistrationNo,
                                text: `${item.PatientName} (${item.RegistrationNo}) - ${item.Mobile}`
                            }))
                        };
                    },

                    cache: true
                }
            });

        });
    </script>
@endsection
