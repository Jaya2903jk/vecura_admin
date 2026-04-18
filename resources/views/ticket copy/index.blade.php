<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <!-- ========================
                                                                                                                                                                                        Start Page Content
                                                                                                                                                                                    ========================= -->

    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 me-2">Tickets</h4>
                    <span class="badge badge-soft-primary border pt-1 px-2 border-primary fw-medium">Total Ticket :
                        582</span>
                </div>
                <div class="text-end">
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#add_tickets"><i class="ti ti-plus me-1"></i>Raise Ticket</a>
                </div>
                @isset($assignList)
                    @component('components.modal-popup', ['assignList' => $assignList])
                    @endcomponent
                @endisset
            </div>
            <!-- End Page Header -->

            <div class=" d-flex align-items-center justify-content-between flex-wrap row-gap-3">
                <div class="search-set mb-3">
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <div class="table-search d-flex align-items-center mb-0">
                            <div class="search-input">
                                <a href="javascript:void(0);" class="btn-searchset"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex table-dropdown mb-3 pb-1 right-content align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="javascript:void(0);"
                            class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside">
                            <i class="ti ti-filter me-2 fs-14"></i>Filter
                        </a>
                        <div class="dropdown-menu dropdown-lg dropdown-menu-end filter-dropdown" id="filter-dropdown">
                            <div class="d-flex align-items-center justify-content-between border-bottom filter-header">
                                <h4 class="fs-18 fw-bold">Filter</h4>
                                <div class="d-flex align-items-center">
                                    <a href="javascript:void(0);" class="link-danger text-decoration-underline me-3">Clear
                                        All</a>
                                </div>
                            </div>
                            <form action="#">
                                <div class="filter-body pb-1">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <div class="dropdown">
                                            <a href="javascript:void(0);"
                                                class="dropdown-toggle btn bg-white  d-flex align-items-center justify-content-start fs-13 p-2 fw-normal border"
                                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                                                Select
                                            </a>
                                            <div class="dropdown-menu shadow-lg w-100 dropdown-info p-3">
                                                <div class="mb-3">
                                                    <div class="input-icon-start position-relative">
                                                        <span class="input-icon-addon fs-12">
                                                            <i class="isax isax-search-normal"></i>
                                                        </span>
                                                        <input type="text" class="form-control form-control-sm"
                                                            placeholder="Search">
                                                    </div>
                                                </div>
                                                <ul class="mb-3">
                                                    <li class="d-flex align-items-center justify-content-between mb-3">
                                                        <label class="d-inline-flex align-items-center text-gray-9">
                                                            <input class="form-check-input select-all m-0 me-2"
                                                                type="checkbox"> Select All
                                                        </label>
                                                        <a href="javascript:void(0);"
                                                            class="link-danger fw-medium text-decoration-underline">Reset</a>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-33.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Alberto Ripley
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-12.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Bernard Griffith
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-02.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Carol Lam
                                                        </label>
                                                    </li>
                                                    <li>
                                                        <label
                                                            class="dropdown-item px-2 d-flex align-items-center text-dark">
                                                            <input class="form-check-input m-0 me-2" type="checkbox">
                                                            <span class="avatar avatar-sm rounded-circle me-2"><img
                                                                    src="{{ URL::asset('build/img/users/user-08.jpg') }}"
                                                                    class="flex-shrink-0 rounded-circle"
                                                                    alt="img"></span>Ezra Belcher
                                                        </label>
                                                    </li>
                                                </ul>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-outline-white w-100 close-filter">Cancel</a>
                                                    </div>
                                                    <div class="col-6">
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-primary w-100">Select</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <label class="form-label">Date</label>
                                        </div>
                                        <div class="input-group position-relative">
                                            <input type="text"
                                                class="form-control date-range bookingrange rounded-end h-auto py-2 bg-white">
                                            <span class="input-icon-addon fs-16 text-gray-9">
                                                <i class="ti ti-calendar"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-footer d-flex align-items-center justify-content-end border-top">
                                    <a href="javascript:void(0);" class="btn btn-light btn-md me-2"
                                        id="close-filter">Close</a>
                                    <button type="submit" class="btn btn-primary btn-md">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a href="javascript:void(0);"
                            class="dropdown-toggle btn bg-white btn-md d-inline-flex align-items-center fw-normal rounded border text-dark px-2 py-1 fs-14"
                            data-bs-toggle="dropdown">
                            <span class="me-1"> Sort By : </span> Recent
                        </a>
                        <ul class="dropdown-menu  dropdown-menu-end p-2">
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Recent</a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="dropdown-item rounded-1">Oldest</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-nowrap datatable">
                    <thead class="thead-light">
                        <tr>
                            <th>Ticket ID</th>
                            <th>Department</th>
                            <th>Branch</th>
                            <th>Reg No</th>
                            <th>Issues</th>
                            <th>Type</th>
                            {{-- <th>Status</th> --}}
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $t)
                            <tr>
                                <td>
                                    <a href="{{ url('ticket-details/' . $t->ticketId) }}">
                                        #TKT{{ str_pad($t->ticketId, 3, '0', STR_PAD_LEFT) }}
                                    </a>
                                </td>
                                <td>{{ $t->department->DepartmentName ?? '-' }}</td>
                                <td>{{ $t->location->LocationName ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a href="javascript:void(0);"
                                            class="fw-medium">{{ $t->customer->RegistrationNo ?? '-' }}</a>
                                    </div>
                                </td>
                                <td>{{ $t->Subject ?? '' }}</td>
                                <td>{{ $t->type ?? 'Ticket' }}</td>
                                {{-- <td>
                                    @if ($t->Status == 7)
                                        <span class="badge bg-soft-success text-success border border-success">
                                            Closed
                                        </span>
                                    @elseif(in_array($t->Status, [4, 5]))
                                        <span class="badge bg-soft-warning text-warning border border-warning">
                                            In Progress
                                        </span>
                                    @elseif($t->Status == 0)
                                        <span class="badge bg-soft-danger text-danger border border-danger">
                                            Pending
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $t->Status }}
                                        </span>
                                    @endif
                                </td> --}}

                                <td class="action-item">
                                    <a href="javascript:void(0);" data-bs-toggle="dropdown"
                                        class="btn p-1 btn-white border">
                                        <i class="ti ti-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu p-2">
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item viewTicketBtn"
                                                data-id="{{ $t->ticketId }}" data-bs-toggle="modal"
                                                data-bs-target="#full-width-modal">
                                                View
                                            </a>

                                        </li>
                                        {{-- <li>
                                            <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#delete_tickets">
                                                Delete
                                            </a>
                                        </li> --}}
                                    </ul>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <!-- End Content -->

        @component('components.footer')
        @endcomponent

    </div>

    <!-- ========================
                                                                                                End Page Content
                                                                                ========================= -->
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
        });

        $(document).on('click', '.viewTicketBtn', function() {
            let id = $(this).data('id');
            loadTicket(id, 1);
        });
        $(document).on('click', '.pageBtn', function() {

            let page = $(this).data('page');
            let id = $(this).data('id');

            if (!$(this).parent().hasClass('disabled')) {
                loadTicket(id, page);
            }
        });


        function renderPagination(ticketId, p) {

            let html = '';

            //  PREVIOUS BUTTON
            html += `
    <li class="page-item ${p.current_page == 1 ? 'disabled' : ''}">
        <a class="page-link pageBtn" href="javascript:void(0)"
           data-page="${p.current_page - 1}" data-id="${ticketId}">
           Previous
        </a>
    </li>`;
            let start = Math.max(1, p.current_page - 2);
            let end = Math.min(p.last_page, p.current_page + 2);

            for (let i = start; i <= end; i++) {
                html += `
        <li class="page-item ${i == p.current_page ? 'active' : ''}">
            <a class="page-link pageBtn" href="javascript:void(0)"
               data-page="${i}" data-id="${ticketId}">
               ${i}
            </a>
        </li>`;
            }

            //  NEXT BUTTON
            html += `
    <li class="page-item ${p.current_page == p.last_page ? 'disabled' : ''}">
        <a class="page-link pageBtn" href="javascript:void(0)"
           data-page="${p.current_page + 1}" data-id="${ticketId}">
           Next
        </a>
    </li>`;

            $('#pagination').html(html);
        }

        function loadTicket(id, page = 1) {

            $('#complaintTable').html('<tr><td colspan="11" class="text-center">Loading...</td></tr>');

            $.ajax({
                url: "/ticket-details/" + id + "?page=" + page,
                type: "GET",

                success: function(res) {

                    if (res.status) {

                        let d = res.data;
                        $('#view_customer_name').text(d.CustomerName || '-');
                        $('#view_branch').text(d.Branch || '-');
                        $('#view_mobile').text(d.mobile || '-');
                        $('#view_customer_code').text(d.CustomerCode || '-');

                        $('#view_ticket_id').text('#TKT' + String(d.ticketId).padStart(3, '0'));
                        $('#view_status').text(d.Status || '-');
                        $('#view_created_by').text(d.CreatedBy || '-');
                        $('#view_assigned_to').text(d.AcceptedBy || '-');
                        $('#pendingCount').text(d.counts.pending || 0);
                        $('#inprogressCount').text(d.counts.inprogress || 0);
                        $('#closedCount').text(d.counts.closed || 0);

                        let rows = '';

                        if (d.complaints.length > 0) {

                            d.complaints.forEach(function(c, index) {

                                let statusBadge = 'bg-warning';
                                if (c.Status === 'Closed') statusBadge = 'bg-success';
                                if (c.Status === 'InProgress') statusBadge = 'bg-info';

                                let fullText = c.Comment || '-';
                                let shortText = fullText.length > 40 ? fullText.substring(0, 40) +
                                    '...' : fullText;

                                rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${c.CreatedDate || '-'}</td>
                            <td>${d.mobile || '-'}</td>
                            <td>${c.CreatedBy || '-'}</td>
                            <td>${c.AssignedTo || '-'}</td>
                            <td>${c.Category || '-'}</td>
                            <td>${c.Issue || '-'}</td>
                            <td>${c.Source || '-'}</td>
                                                 <td>
<span class="text-primary small">
    ${fullText}
</span>
                        </td>

                            <td><span class="badge ${statusBadge}">${c.Status}</span></td>
                              <td>
                                         <a href="javascript:void(0);"
                                           class="editStatusBtn link-reset fs-18 p-1"
                                            data-id="${c.complaintId}"
                                           data-status="${c.Status}">
                                           <i class="ti ti-pencil"></i>
                                        </a>
                                    </td>
                        </tr>`;
                            });

                        } else {
                            rows = `<tr><td colspan="11" class="text-center">No Data</td></tr>`;
                        }
                        $('#complaintTable').html(rows);
                        renderPagination(id, d.pagination);
                    }
                }
            });
        }
        // $(document).on('click', '.viewFeedback', function() {
        //     let text = $(this).data('text');
        //     Swal.fire({
        //         title: 'Customer Feedback',
        //         html: `
    //         <div style="text-align:left; max-height:300px; overflow:auto;">
    //             ${text}
    //         </div>
    //     `,
        //         width: 600,
        //         confirmButtonText: 'Close'
        //     });

        // });
        $(document).on('click', '.viewFeedback', function() {
            let text = $(this).data('text');
            Swal.fire({
                title: "Customer Feedback",
                html: `
            <div class="text-start" style="max-height:300px; overflow:auto;">
                ${text || 'No feedback available'}
            </div>
        `,
                width: 600,
                // icon: "info",
                showCancelButton: false,
                confirmButtonText: "Close",

                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });

        });
        $(document).on('click', '.editStatusBtn', function() {
            let complaintId = $(this).data('id');
            let currentStatus = $(this).data('status');

            Swal.fire({
                title: "Change Status",
                html: `
            <select id="statusSelect" class="form-select mt-2">
                <option value="">-- Select Status --</option>
                <option value="Pending">Pending</option>
                <option value="InProgress">InProgress</option>
                <option value="Closed">Closed</option>
            </select>
        `,
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Update",
                cancelButtonText: "Cancel",
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary me-2",
                    cancelButton: "btn btn-danger"
                },

                didOpen: () => {
                    $('#statusSelect').val(currentStatus);
                },

                preConfirm: () => {
                    let val = $('#statusSelect').val();

                    if (!val) {
                        Swal.showValidationMessage('Please select status');
                        return false;
                    }

                    return val;
                }

            }).then((result) => {
                if (result.isConfirmed) {
                    let newStatus = result.value;
                    Swal.fire({
                        title: "Updating...",
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $.ajax({
                        url: "/update-complaint-status",
                        type: "POST",
                        data: {
                            complaintId: complaintId,
                            status: newStatus,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },

                        success: function(res) {

                            if (res.status) {

                                Swal.fire({
                                    icon: "success",
                                    title: "Updated!",
                                    text: res.message,
                                    timer: 1200,
                                    showConfirmButton: false
                                });
                                loadTicket(res.ticketId, 1);

                            } else {
                                Swal.fire("Error", res.message || "Update failed", "error");
                            }
                        },

                        error: function() {
                            Swal.fire("Error", "Something went wrong", "error");
                        }
                    });
                }
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
