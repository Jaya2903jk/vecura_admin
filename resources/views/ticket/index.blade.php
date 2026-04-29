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


            <div class="toolbar-card">
                <div class="ticket-toolbar">
                    <div class="toolbar-group">
                        <div class="toolbar-field">
                            <label for="searchInput">Search</label>
                            <input id="searchInput" class="form-control" placeholder="Search ticket / reg no / issue">
                        </div>
                        <div class="toolbar-field">
                            <label for="statusFilter">Status</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">All Status</option>
                                <option value="Closed">Closed</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                            </select>
                        </div>
                        <div class="toolbar-field">
                            <label for="typeFilter">Type</label>
                            {{-- <select id="typeFilter" class="form-select">
                                <option value="">All Type</option>
                                <option value="complaint">Complaint</option>
                                <option value="request">Request</option>
                            </select> --}}
                            <select id="typeFilter" class="form-select">
                                <option value="">All Type</option>
                                <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>
                                    Complaint
                                </option>
                                <option value="hr" {{ request('type') == 'hr' ? 'selected' : '' }}>
                                    Hr
                                </option>
                            </select>
                        </div>

                        <div class="toolbar-field">
                            <label for="branchFilter">Branch</label>
                            <select id="branchFilter" class="form-select"></select>
                        </div>
                        <div class="toolbar-field">
                            <label>&nbsp;</label>
                            <button class="btn toolbar-btn" id="clearFilters">Clear</button>
                        </div>
                    </div>
                    <div class="toolbar-group">
                        <div class="toolbar-field">
                            <label for="sortSelect">Sort By</label>
                            <select id="sortSelect" class="form-select">
                                <option value="recent">Recent</option>
                                <option value="oldest">Oldest</option>
                                <option value="status">Status</option>
                            </select>
                        </div>
                    </div>
                </div>
                {{-- <div class="master-note">
                    Master data ready in code: Issue Master, Category Master, Branch, Department, Role, Permission,
                    Designation, Patients, Staff.
                    Assign To and Follow Up now use the <strong>Staff</strong> master list as a dropdown selection.
                </div> --}}
            </div>
            <ul class="nav nav-tabs nav-bordered mb-3">

                <!-- ALL -->
                <li class="nav-item">
                    <a href="{{ route('tickets') }}" class="nav-link {{ request()->has('status') ? '' : 'active' }}">
                        <span>ALL</span>
                    </a>
                </li>

                <!-- Pending -->
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 0]) }}"
                        class="nav-link {{ request('status') === '0' ? 'active' : '' }}">
                        <span>Pending</span>
                    </a>
                </li>

                <!-- Inprogress -->
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 1]) }}"
                        class="nav-link {{ request('status') == 1 ? 'active' : '' }}">
                        <span>Inprogress</span>
                    </a>
                </li>

                <!-- Resolved -->
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 2]) }}"
                        class="nav-link {{ request('status') == 2 ? 'active' : '' }}">
                        <span>Resolved</span>
                    </a>
                </li>

                <!-- Closed -->
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 3]) }}"
                        class="nav-link {{ request('status') == 3 ? 'active' : '' }}">
                        <span>Closed</span>
                    </a>
                </li>

            </ul>

            <div class="ticket-card">
                <div class="table-responsive">
                    <table class="table ticket-table mb-0">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Department</th>
                                <th>Branch</th>
                                <th>Reg No</th>
                                <th>Issues</th>
                                <th>Type</th>
                                <th>Status</th>
                                {{-- <th>Created</th> --}}
                                <th>Date/Time</th>
                                {{-- <th>Follow Up</th> --}}
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="ticketTableBody">
                            @forelse ($tickets as $t)
                                <tr data-ticket="#TKT{{ $t->ticketId }}"
                                    data-department="{{ $t->department->DepartmentName ?? '-' }}"
                                    data-branch="{{ $t->location->LocationName ?? '-' }}"
                                    data-reg="{{ $t->customer->RegistrationNo ?? '-' }}"
                                    data-issue="{{ $t->Subject ?? '' }}" data-type="{{ $t->type ?? 'Ticket' }}"
                                    data-status="{{ $t->Status }}">
                                    {{-- Ticket ID --}}
                                    <td>
                                        <strong>
                                            #TKT{{ str_pad($t->ticketId, 3, '0', STR_PAD_LEFT) }}
                                        </strong>
                                    </td>

                                    {{-- Department --}}
                                    <td>{{ $t->department->DepartmentName ?? '-' }}</td>

                                    {{-- Branch --}}
                                    <td>{{ $t->location->LocationName ?? 'Coop' }}</td>

                                    {{-- Reg No --}}
                                    <td>
                                        <strong>
                                            {{ $t->customer->RegistrationNo ?? ($t->get_employee->UserCode ?? '-') }}
                                        </strong>
                                    </td>

                                    {{-- Issue --}}
                                    <td>{{ $t->Subject ?? '-' }}</td>

                                    {{-- Type --}}
                                    <td>{{ $t->type ?? 'Ticket' }}</td>

                                    {{-- Status --}}
                                    <td>
                                        <span
                                            class="status-badge
                @if ($t->Status == 0) status-pending
                @elseif ($t->Status == 1) status-progress
                @elseif ($t->Status == 2) status-resolved
                @else status-closed @endif
              ">
                                            @if ($t->Status == 0)
                                                Pending
                                            @elseif ($t->Status == 1)
                                                In Progress
                                            @elseif ($t->Status == 2)
                                                Resolved
                                            @else
                                                Closed
                                            @endif
                                        </span>
                                    </td>

                                    {{-- Follow Up --}}
                                    {{-- <td>
                                        <button class="followup-btn" data-ticket="#TKT{{ $t->ticketId }}"
                                            data-bs-toggle="modal" data-bs-target="#followupModal">
                                            <i class="ti ti-message-plus"></i> Follow Up
                                        </button>
                                    </td> --}}

                                    {{-- Action --}}
                                    <td>{{ $t->CreatedDate ?? '-' }}</td>

                                    <td>
                                        <div class="d-flex gap-1">
                                            <a class="action-btn" href="{{ route('ticket.view', $t->ticketId) }}">
                                                <i class="ti ti-eye"></i>
                                            </a>

                                            {{-- <button class="action-btn edit-ticket-btn" type="button"
                                                data-bs-toggle="modal" data-bs-target="#editTicketModal">
                                                <i class="ti ti-edit"></i>
                                            </button> --}}
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        No tickets found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div id="emptyState" class="empty-state">
                        No tickets found for the selected filter.
                    </div>
                </div>
            </div>
            <style>
                .toolbar-card,
                .ticket-card {
                    background: #fff;
                    border: 1px solid #e7e8eb;
                    border-radius: 6px;
                    overflow: hidden;
                }

                .toolbar-card {
                    padding: 14px;
                    margin-bottom: 14px;
                }

                .ticket-toolbar {
                    display: flex;
                    align-items: end;
                    justify-content: space-between;
                    gap: 12px;
                    flex-wrap: wrap;
                }

                .toolbar-group {
                    display: flex;
                    align-items: end;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .toolbar-field label {
                    display: block;
                    font-size: 12px;
                    font-weight: 600;
                    color: #6f7790;
                    margin-bottom: 6px;
                }

                .toolbar-field .form-control,
                .toolbar-field .form-select {
                    min-width: 160px;
                    height: 38px;
                    border-color: #e0e3ea;
                    font-size: 13px;
                }

                .toolbar-btn {
                    height: 38px;
                    padding: 6px 12px;
                    border: 1px solid #dfe3eb;
                    background: #fff;
                    color: #22304d;
                    border-radius: 6px;
                    font-size: 13px;
                    font-weight: 500;
                }

                .raise-btn {
                    background: #3947b8;
                    border-color: #3947b8;
                    color: #fff;
                }

                .ticket-table thead th {
                    background: #fff;
                    color: #18284d;
                    font-size: 13px;
                    font-weight: 700;
                    padding: 11px 14px;
                    border-bottom: 1px solid #e7e8eb;
                    white-space: nowrap;
                }

                .ticket-table tbody td {
                    font-size: 13px;
                    color: #677189;
                    padding: 10px 14px;
                    vertical-align: middle;
                    border-color: #e7e8eb;
                }

                .ticket-table tbody td strong {
                    color: #1e2b4a;
                    font-weight: 700;
                }

                .status-badge {
                    display: inline-block;
                    min-width: 78px;
                    text-align: center;
                    padding: 4px 10px;
                    border-radius: 5px;
                    font-size: 11px;
                    font-weight: 700;
                    color: #fff;
                }

                .status-closed {
                    background: #14c6c4;
                }

                .status-resolved {
                    background: #2eb85c;
                }

                .status-pending {
                    background: #f2b21b;
                }

                .status-progress {
                    background: #2f80ed;
                }

                .action-btn {
                    width: 30px;
                    height: 30px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border: 1px solid #e7e8eb;
                    border-radius: 6px;
                    color: #9098a8;
                    background: #fff;
                    text-decoration: none;
                }

                .followup-btn {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    padding: 5px 10px;
                    border-radius: 6px;
                    background: linear-gradient(180deg, #37a3d6, #2f6bcf);
                    color: #fff;
                    font-size: 12px;
                    font-weight: 700;
                    text-decoration: none;
                    border: none;
                }

                .table-footer-bar {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    font-size: 12px;
                    color: #687389;
                    padding: 16px 0 0;
                    flex-wrap: wrap;
                    gap: 10px;
                }



                .footer-line {
                    text-align: center;
                    font-size: 12px;
                    color: #6f7790;
                    padding: 20px 0 10px;
                }

                .empty-state {
                    text-align: center;
                    padding: 24px;
                    color: #7d8598;
                    display: none;
                }

                .float-settings {
                    position: fixed;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 28px;
                    height: 28px;
                    border-radius: 4px;
                    background: #3741b0;
                    color: #fff;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 5;
                }

                .modal .form-label {
                    font-size: 13px;
                    font-weight: 600;
                    color: #22304d;
                }

                .upload-box {
                    border: 1px dashed #d7dbe4;
                    background: #f8f9fb;
                    border-radius: 8px;
                    text-align: center;
                    padding: 22px 15px;
                    color: #73809a;
                    font-size: 13px;
                }

                .master-note {
                    font-size: 12px;
                    color: #7a8295;
                    background: #f8f9fc;
                    border: 1px solid #e7e8eb;
                    border-radius: 6px;
                    padding: 10px 12px;
                    margin-top: 12px;
                }

                @media (max-width: 991.98px) {
                    .page-wrapper {
                        margin-left: 0;
                    }

                    .sidebar {
                        left: -230px;
                    }
                }
            </style>
            {{-- Footer --}}
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
                        Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }}
                        of {{ $tickets->total() }} entries
                    </div>

                </div>
                <div class="pagination-box">
                    {{ $tickets->appends(['per_page' => $perPage])->links('pagination::bootstrap-5') }}
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
        </div>
        @component('components.footer')
        @endcomponent

    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const LEAVE_REQUEST_ID = "{{ $leaveRequestId }}";
            // =========================
            // LOAD EMPLOYEES (only once)
            // =========================
            $.ajax({
                url: "/employees",
                type: "GET",
                success: function(res) {
                    if (res.status) {
                        let options = '<option value="">Select Employee</option>';
                        res.data.forEach(function(e) {
                            options +=
                                `<option value="${e.UserID}">${e.FullName} (${e.UserCode})</option>`;
                        });
                        $("#employee_id").html(options);
                    }
                }
            });

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
                $("#leave_request_block").hide();

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
                $("#leave_request_block").hide();
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

                    // success: function(response) {
                    //     Livewire.emit('notificationAdded');
                    //     Livewire.emit('refreshNotification');
                    //     Swal.fire({
                    //         // position: "top-end",
                    //         icon: "success", // fixed (was type)
                    //         title: "Ticket Created Successfully",
                    //         showConfirmButton: false,
                    //         timer: 1500
                    //     });
                    //     setTimeout(function() {
                    //         window.location.href = "{{ route('tickets') }}";
                    //     }, 2000);
                    // },
                    success: function(response) {
                        // Livewire.emit('notificationAdded');
                        // Livewire.emit('refreshNotification');
                        Swal.fire({
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(function() {
                            window.location.href = "{{ route('tickets') }}";
                        }, 2000);
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
            $('#customerSelect, #department, #category').on('change', function() {

                let customerCode = $('#customerSelect').val();
                let department = $('#department').val();
                let category = $('#category').val();

                //  validation
                if (!customerCode || !department || !category) {
                    $('#customerTicketsBlock').hide();
                    return;
                }

                $.ajax({
                    url: "/customer-tickets",
                    type: "GET",
                    data: {
                        customer_code: customerCode,
                        department: department,
                        category: category
                    },

                    success: function(res) {

                        let table = $('#customerTicketsTable');
                        table.empty();

                        if (res.tickets.length > 0) {

                            $('#customerTicketsBlock').show();

                            let hasOpen = false;

                            res.tickets.forEach(function(t) {

                                let statusBadge = '';

                                if (t.Status == 0) {
                                    statusBadge =
                                        '<span class="badge bg-warning">Pending</span>';
                                    hasOpen = true;
                                } else if (t.Status == 1) {
                                    statusBadge =
                                        '<span class="badge bg-info">InProgress</span>';
                                    hasOpen = true;
                                } else if (t.Status == 2) {
                                    statusBadge =
                                        '<span class="badge bg-success">Resolved</span>';
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

                            // ✅ block duplicate ticket
                            // if (hasOpen) {
                            //     $('#submitBtn')
                            //         .prop('disabled', true)
                            //         .text('Active Ticket Exists');
                            // } else {
                            //     $('#submitBtn')
                            //         .prop('disabled', false)
                            //         .text('Add Ticket');
                            // }

                        } else {
                            $('#customerTicketsBlock').hide();
                            $('#submitBtn')
                                .prop('disabled', false)
                                .text('Add Ticket');
                        }
                    }
                });
            });

            $("#issue").on("change", function() {

                let escalationId = $(this).val();

                if (escalationId == LEAVE_REQUEST_ID) {

                    $("#leave_request_block").slideDown();

                    $("input[name='from_date']").prop('required', true);
                    $("input[name='to_date']").prop('required', true);
                    $("select[name='employee_id']").prop('required', true);

                } else {

                    $("#leave_request_block").slideUp();

                    $("input[name='from_date']").val('').prop('required', false);
                    $("input[name='to_date']").val('').prop('required', false);
                    $("select[name='employee_id']").val('').prop('required', false);
                }
            });
            // $("#issue").on("change", function() {

            //     let issueId = $(this).val();

            //     // =========================
            //     // LEAVE REQUEST (11)
            //     // =========================
            //     if (issueId == "{{ config('ticket.LEAVE_REQUEST') }}") {

            //         $("#leave_request_block").slideDown();
            //         $("#attendance_block").hide();

            //         $("input[name='from_date']").prop('required', true);
            //         $("input[name='to_date']").prop('required', true);
            //         $("select[name='employee_id']").prop('required', true);

            //         $("input[name='attendance_date']").prop('required', false);

            //     }

            //     // =========================
            //     // ATTENDANCE ISSUE (13)
            //     // =========================
            //     else if (issueId == "{{ config('ticket.ATTENDANCE_ISSUE') }}") {

            //         $("#attendance_block").slideDown();
            //         $("#leave_request_block").hide();

            //         $("input[name='attendance_date']").prop('required', true);
            //         $("select[name='employee_id']").prop('required', true);

            //         $("input[name='from_date']").prop('required', false);
            //         $("input[name='to_date']").prop('required', false);

            //     }

            //     // =========================
            //     // DEFAULT
            //     // =========================
            //     else {
            //         $("#leave_request_block").hide();
            //         $("#attendance_block").hide();
            //     }
            // });


        });
    </script>
    <script>
        document.getElementById('typeFilter').addEventListener('change', function() {
            let type = this.value;

            let url = new URL(window.location.href);

            if (type) {
                url.searchParams.set('type', type);
            } else {
                url.searchParams.delete('type');
            }

            window.location.href = url.toString();
        });
        document.getElementById('clearFilters').addEventListener('click', function() {
            window.location.href = "{{ route('tickets') }}";
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
