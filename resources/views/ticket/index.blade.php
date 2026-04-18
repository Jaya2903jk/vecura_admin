<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- Page Header -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 pb-3 mb-3 border-bottom">
                <div class="d-flex align-items-center">
                    <h4 class="fw-bold mb-0 me-2">Tickets</h4>
                    <span class="badge badge-soft-primary border pt-1 px-2 border-primary fw-medium">Total Ticket :
                        {{ $totalTickets }}</span>
                </div>
                <div class="text-end">
                    <a href="javascript:void(0);" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#add_tickets"><i class="ti ti-plus me-1"></i>Raise Ticket</a>
                </div>
                @isset($assignList)
                    @component('components.modal-popup', ['assignList' => $assignList, 'tickets' => $tickets])
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
            <ul class="nav nav-tabs nav-bordered mb-3">
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 0]) }}"
                        class="nav-link {{ request('status', 0) == 0 ? 'active' : '' }}">
                        <span>Pending</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 1]) }}"
                        class="nav-link {{ request('status') == 1 ? 'active' : '' }}">
                        <span>Inprogress</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 2]) }}"
                        class="nav-link {{ request('status') == 2 ? 'active' : '' }}">
                        <span>Resolved</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 3]) }}"
                        class="nav-link {{ request('status') == 3 ? 'active' : '' }}">
                        <span>Closed</span>
                    </a>
                </li>
            </ul>
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
                            <th>Status</th>
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
                                <td>
                                    <span
                                        class="badge
                  @if ($t->Status == 0) bg-warning
                  @elseif($t->Status == 1) bg-info
                  @elseif($t->Status == 2) bg-success
                  @else bg-secondary @endif">

                                        @if ($t->Status == 0)
                                            Pending
                                        @elseif($t->Status == 1)
                                            In Progress
                                        @elseif($t->Status == 2)
                                            Resolved
                                        @else
                                            Closed
                                        @endif

                                    </span>
                                </td>
                                <td class="action-item">
                                    <a href="{{ route('ticket.view', $t->ticketId) }}">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        @component('components.footer')
        @endcomponent

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
