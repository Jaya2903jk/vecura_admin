<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-ticket text-primary fs-24"></i>Tickets Management
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total Tickets: {{ $totalTickets }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Track and manage customer complaints, internal requests, HR, Biomedical, and Support tickets.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#add_tickets">
                        <i class="ti ti-plus me-1"></i>Raise Ticket
                    </button>
                </div>
                @isset($assignList)
                    @component('components.modal-popup', ['assignList' => $assignList, 'tickets' => $tickets])
                    @endcomponent
                @endisset
            </div>

            <!-- FILTER BAR CARD -->
            <div class="card border-0 shadow-xs mb-4 rounded-3">
                <div class="card-body p-3 bg-light-subtle rounded-3">
                    <div class="row g-2 align-items-center">
                        {{-- Search Input --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold fs-13 text-dark mb-1">Search Ticket</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-muted"></i></span>
                                <input id="searchInput" class="form-control border-start-0 fs-13 text-dark" placeholder="Search ticket / reg no / issue">
                            </div>
                        </div>

                        {{-- Type Filter --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold fs-13 text-dark mb-1">Ticket Type</label>
                            <select id="typeFilter" class="form-select form-select-sm fs-13 text-dark">
                                <option value="">All Types</option>
                                <option value="vsupport" {{ request('type') == 'vsupport' ? 'selected' : '' }}>Vsupport</option>
                                <option value="hr" {{ request('type') == 'hr' ? 'selected' : '' }}>HR</option>
                                <option value="biomedical" {{ request('type') == 'biomedical' ? 'selected' : '' }}>Biomedical</option>
                                <option value="accounts" {{ request('type') == 'accounts' ? 'selected' : '' }}>Accounts</option>
                                <option value="Settlement" {{ request('type') == 'Settlement' ? 'selected' : '' }}>Settlement</option>
                                <option value="petty cash" {{ request('type') == 'petty cash' ? 'selected' : '' }}>Petty Cash</option>
                                <option value="petty bill" {{ request('type') == 'petty bill' ? 'selected' : '' }}>Petty Bill</option>
                                <option value="facility" {{ request('type') == 'facility' ? 'selected' : '' }}>Facility</option>
                            </select>
                        </div>

                        {{-- Status Filter --}}
                        <div class="col-md-2">
                            <label class="form-label fw-semibold fs-13 text-dark mb-1">Status</label>
                            <select id="statusFilter" class="form-select form-select-sm fs-13 text-dark">
                                <option value="">All Status</option>
                                <option value="Closed">Closed</option>
                                <option value="Resolved">Resolved</option>
                                <option value="Pending">Pending</option>
                                <option value="In Progress">In Progress</option>
                            </select>
                        </div>

                        {{-- Branch Filter --}}
                        <div class="col-md-2">
                            <label class="form-label fw-semibold fs-13 text-dark mb-1">Branch</label>
                            <select id="branchFilter" class="form-select form-select-sm fs-13 text-dark"></select>
                        </div>

                        {{-- Reset Button --}}
                        <div class="col-md-1 d-flex align-items-end pt-3">
                            <button class="btn btn-light border btn-sm w-100 fw-semibold fs-13 text-secondary" id="clearFilters" title="Clear Filters">
                                <i class="ti ti-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STATUS TABS -->
            <ul class="nav nav-tabs nav-tabs-bottom border-bottom mb-4">
                <li class="nav-item">
                    <a href="{{ route('tickets') }}" class="nav-link fw-semibold fs-13 {{ request()->has('status') ? '' : 'active' }}">
                        <i class="ti ti-list me-1"></i>ALL
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 0]) }}" class="nav-link fw-semibold fs-13 {{ request('status') === '0' ? 'active' : '' }}">
                        <i class="ti ti-clock me-1 text-warning"></i>Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 1]) }}" class="nav-link fw-semibold fs-13 {{ request('status') == 1 ? 'active' : '' }}">
                        <i class="ti ti-loader me-1 text-info"></i>In Progress
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 2]) }}" class="nav-link fw-semibold fs-13 {{ request('status') == 2 ? 'active' : '' }}">
                        <i class="ti ti-circle-check me-1 text-success"></i>Resolved
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 3]) }}" class="nav-link fw-semibold fs-13 {{ request('status') == 3 ? 'active' : '' }}">
                        <i class="ti ti-lock me-1 text-secondary"></i>Closed
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets', ['status' => 4]) }}" class="nav-link fw-semibold fs-13 {{ request('status') == 4 ? 'active' : '' }}">
                        <i class="ti ti-x me-1 text-danger"></i>Rejected
                    </a>
                </li>
            </ul>

            <!-- TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">Ticket ID</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Department</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Branch</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Reg No / Code</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Subject / Issue</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Type</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Status</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Date/Time</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ticketTableBody" class="divide-y divide-gray-100">
                                @forelse ($tickets as $t)
                                    <tr data-ticket="#TKT{{ $t->ticketId }}"
                                        data-department="{{ $t->department->DepartmentName ?? '-' }}"
                                        data-branch="{{ $t->location->LocationName ?? '-' }}"
                                        data-reg="{{ $t->customer->RegistrationNo ?? '-' }}"
                                        data-issue="{{ $t->Subject ?? '' }}" data-type="{{ $t->type ?? 'Ticket' }}"
                                        data-status="{{ $t->Status }}">
                                        <td class="ps-4">
                                            <span class="patient-code-tag font-monospace">#TKT{{ str_pad($t->ticketId, 3, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2.5 py-1 fs-11 fw-semibold rounded-2">
                                                <i class="ti ti-building me-1 text-muted"></i>{{ $t->department->DepartmentName ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fs-13 text-dark">{{ $t->location->LocationName ?? 'Coop' }}</div>
                                        </td>
                                        <td>
                                            <span class="patient-code-tag font-monospace">{{ $t->customer->RegistrationNo ?? ($t->get_employee->UserCode ?? '-') }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-13" style="max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                {{ $t->Subject ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0.5 fs-11 fw-bold rounded-2">
                                                {{ strtoupper($t->type ?? 'TICKET') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                $isLeave = false;
                                                if ($t->type == 'hr' && isset($t->hr[0])) {
                                                    $isLeave = $t->hr[0]->escalationTypeId == $leaveRequestId;
                                                }
                                            @endphp

                                            @if ($t->Status == 0)
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-clock me-1"></i>Pending
                                                </span>
                                            @elseif ($t->Status == 1)
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-loader me-1"></i>In Progress
                                                </span>
                                            @elseif ($t->Status == 2)
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-circle-check me-1"></i>{{ $isLeave ? 'Approved' : 'Resolved' }}
                                                </span>
                                            @elseif ($t->Status == 4)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-x me-1"></i>Rejected
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-lock me-1"></i>Closed
                                                </span>
                                            @endif
                                        </td>
                                        <td class="fs-12 text-muted">
                                            <i class="ti ti-calendar me-1"></i>{{ $t->CreatedDate ?? '-' }}
                                        </td>
                                        <td class="text-end pe-4">
                                            @php
                                                $ticketType = strtolower(trim($t->type ?? ''));
                                                $subject = strtolower(trim($t->Subject ?? ''));
                                                $isManpower = $subject === 'manpower';
                                                $isBiomedical = $subject === 'biomedical';
                                                $isIOURequest = $subject === 'iou request';
                                                $isPCRequest = $ticketType === 'petty cash';
                                                $isPCBill = $ticketType === 'petty bill';
                                                $isFacility = $ticketType === 'facility';
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <a href="@if ($isManpower) {{ route('manpower.view', $t->ticketId) }}
                                                      @elseif($isBiomedical) {{ route('biomedical.view', $t->ticketId) }}
                                                      @elseif($isIOURequest) {{ route('iou.view', $t->ticketId) }}
                                                      @elseif($isPCRequest) {{ route('pc.view', $t->ticketId) }}
                                                      @elseif($isPCBill) {{ route('pc.bill.view', $t->ticketId) }}
                                                      @elseif($isFacility) {{ route('facility.view', $t->ticketId) }}
                                                      @else {{ route('ticket.view', $t->ticketId) }} @endif"
                                                    class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="View Ticket Details">
                                                    <i class="ti ti-eye fs-15 text-primary"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-ticket-off fs-36 text-muted mb-2 d-block"></i>
                                            No tickets found matching criteria.
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
                    <div class="d-flex align-items-center gap-2">
                        <span>Row Per Page</span>
                        <select id="perPageDept" class="form-select form-select-sm d-inline-block" style="width:75px;">
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="text-muted border-start ps-3">
                        Showing <span class="fw-semibold text-dark">{{ $tickets->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $tickets->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $tickets->total() }}</span> entries
                    </div>
                </div>
                <div>
                    <x-pagination :paginator="$tickets" :append="['per_page' => $perPage]" />
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

                .status-rejected {
                    background: #dc3545;
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

        </div>
        @component('components.footer')
        @endcomponent

    </div>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const LEAVE_REQUEST_ID = "{{ $leaveRequestId }}";
            const ATTENDANCE_ISSUE_ID = "{{ config('ticket.ATTENDANCE_ISSUE') }}";
            const NEW_JOINEE = "{{ config('ticket.NEW_JOINEE') }}";
            const HR_ID = "{{ config('ticket.HR') }}";
            const IOU_REQUEST_ID = "{{ config('ticket.IOU') }}";
            const CLAIM_REQUEST_ID = "{{ config('ticket.CLAIM_REQUEST') }}";
            const SETTELMENT_ID = "{{ config('ticket.SETTLEMENT') }}";
            const PC_REQUEST_ID = "{{ config('ticket.PC_REQUEST') }}";
            const PC_SETTLEMENT_ID = "{{ config('ticket.PC_SETTLEMENT') }}";

            const FL_NEW = "{{ config('ticket.FL_NEW') }}";
            const FL_REPLACEMENT = "{{ config('ticket.FL_REPLACEMENT') }}";
            const FL_SERVICE = "{{ config('ticket.FL_SERVICE') }}";

            // const CLAIM_REQUEST_ID = 25;
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

                        $("#employee_common").html(options); //

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
                $("#employee_common_block").hide();
                $("#leave_request_block").hide();
                $("#attendance_block").hide();
                $("input[name='from_date'], input[name='to_date'], input[name='attendance_date']")
                    .val('')
                    .prop('required', false);
                if (deptId == HR_ID) {
                    $("#employee_common_block").show();
                    $("#employee_common").prop('required', true);
                } else {
                    $("#employee_common").prop('required', false);
                }
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
                $("#leave_request_block").hide();
                $("#attendance_block").hide();
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
                    // url: "{{ url('tickets') }}",
                    url: "{{ route('tickets.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },


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
                        } else {
                            $('#customerTicketsBlock').hide();
                            $('#submitBtn')
                                .prop('disabled', false)
                                .text('Add Ticket');
                        }
                    }
                });
            });

            $(document).on('change', '#employee_common', function() {
                let employeeId = $(this).val();
                resetSettlementForm();
                if (employeeId == '') {
                    return;
                }
                $.ajax({
                    url: "/get-employee-iou-balance",
                    type: "GET",
                    data: {
                        employee_id: employeeId
                    },
                    success: function(response) {
                        let balance =
                            parseFloat(response.balance) || 0;
                        $('input[name="settlement_current_balance"]')
                            .val(balance.toFixed(2));
                        $('#remaining_balance')
                            .val(balance.toFixed(2));

                        addBillRow();
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Unable to fetch employee balance'
                        });
                    }
                });
            });

            function addBillRow() {
                let row = `
             <tr class="bill-row">
            <td>
                <select name="expense_type[]"
                    class="form-control">
                    <option value="">Select</option>
                    <option value="Travel">Travel</option>
                    <option value="Food">Food</option>
                    <option value="Hotel">Hotel</option>
                    <option value="Fuel">Fuel</option>

                </select>

            </td>

            <td>

                <input type="date"
                    name="bill_date[]"
                    class="form-control">

            </td>

            <td>

                <input type="number"
                    step="0.01"
                    name="bill_amount[]"
                    class="form-control bill-amount"
                    placeholder="0.00">

            </td>

            <td>

                <input type="text"
                    name="settlement_amount[]"
                    class="form-control settlement-amount bg-light"
                    value="0.00"
                    readonly>

            </td>
            <td>
                <input type="text"
                    name="employee_extra_amount[]"
                    class="form-control employee-extra bg-danger text-white"
                    value="0.00"
                    readonly>
            </td>
            <td>
                <input type="file"
                    name="settlement_files[]"
                    class="form-control">

            </td>

            <td>

                <button type="button"
                    class="btn btn-danger remove-row">

                    Remove

                </button>

            </td>

        </tr>
         `;
                $('#bill_table_body').append(row);

            }

            function resetSettlementForm() {
                $('input[name="settlement_current_balance"]')
                    .val('0.00');

                $('#remaining_balance')
                    .val('0.00');

                $('#total_bill_amount')
                    .val('0.00');

                $('#total_settlement_amount')
                    .val('0.00');

                $('#total_employee_extra')
                    .val('0.00');

                $('#settlement_type')
                    .val('');
                $('#bill_section')
                    .hide();
                $('#bill_table_body')
                    .html('');

            }

            $(document).on('change', '#settlement_type', function() {
                let type = $(this).val();
                if (type === 'BILL') {
                    $('#bill_section').show();
                } else {
                    $('#bill_section').hide();
                }
            });

            $(document).on('click', '#add_bill_row', function() {
                let row = `
        <tr class="bill-row">

            <td>

                <select name="expense_type[]"
                    class="form-control">

                    <option value="">Select</option>

                    <option value="Travel">Travel</option>

                    <option value="Food">Food</option>

                    <option value="Hotel">Hotel</option>

                    <option value="Fuel">Fuel</option>

                </select>

            </td>

            <td>

                <input type="date"
                    name="bill_date[]"
                    class="form-control">

            </td>

            <td>

                <input type="number"
                    step="0.01"
                    name="bill_amount[]"
                    class="form-control bill-amount"
                    placeholder="0.00">

            </td>

            <td>

                <input type="text"
                    name="settlement_amount[]"
                    class="form-control settlement-amount bg-light"
                    value="0.00"
                    readonly>

            </td>

            <td>

                <input type="text"
                    name="employee_extra_amount[]"
                    class="form-control employee-extra bg-danger text-white"
                    value="0.00"
                    readonly>

            </td>

            <td>

                <input type="file"
                    name="settlement_files[]"
                    class="form-control">

            </td>

            <td>

                <button type="button"
                    class="btn btn-danger remove-row">

                    Remove

                </button>

            </td>

        </tr>
          `;
                $('#bill_table_body').append(row);
            });
            $(document).on('click', '.remove-row', function() {
                $(this).closest('tr').remove();
                calculateSettlement();
            });
            $(document).on('keyup change', '.bill-amount', function() {
                calculateSettlement();
            });

            function calculateSettlement() {
                let currentBalance =
                    parseFloat(
                        $('input[name="settlement_current_balance"]').val()
                    ) || 0;
                let remainingBalance = currentBalance;
                let totalBill = 0;
                let totalSettlement = 0;
                let totalExtra = 0;
                $('#bill_table_body tr').each(function() {

                    let row = $(this);

                    let billAmount =
                        parseFloat(
                            row.find('.bill-amount').val()
                        ) || 0;

                    totalBill += billAmount;

                    let settlementAmount = 0;
                    let employeeExtra = 0;
                    if (remainingBalance >= billAmount) {
                        settlementAmount = billAmount;
                    } else {
                        settlementAmount = remainingBalance;
                    }
                    employeeExtra =
                        billAmount - settlementAmount;
                    remainingBalance =
                        remainingBalance - settlementAmount;
                    if (remainingBalance < 0) {
                        remainingBalance = 0;
                    }
                    row.find('.settlement-amount')
                        .val(settlementAmount.toFixed(2));
                    row.find('.employee-extra')
                        .val(employeeExtra.toFixed(2));
                    totalSettlement += settlementAmount;
                    totalExtra += employeeExtra;
                });
                $('#total_bill_amount')
                    .val(totalBill.toFixed(2));
                $('#total_settlement_amount')
                    .val(totalSettlement.toFixed(2));
                $('#total_employee_extra')
                    .val(totalExtra.toFixed(2));
                $('#remaining_balance')
                    .val(remainingBalance.toFixed(2));
            }

            $("#issue").on("change", function() {
                let issueId = $(this).val();
                let deptId = $("#department").val();
                // ======================================
                // RESET BLOCKS
                // ======================================
                $("#leave_request_block").hide();
                $("#attendance_block").hide();
                $("#new_joinee_block").hide();
                $("#machine_block").hide();
                $("#machine_issue_type_block").hide();
                $("#machine_issues_checkbox_block").hide();
                $("#machine_issues_checkbox").html('');
                $("#machine_id").html(
                    '<option value="">Select Machine</option>'
                );
                $("#machine_issue_type").val('');
                $("#iou_request_block").hide();
                $("#claim_request_block").hide();
                $("#settlement_request_block").hide();

                $("#pc_request_block").hide();
                $('#pc_wallet_balance').val('').removeData('balance');
                $('#pc_request_amount').val('');
                $('#pc_balance_warning').hide();

                $("#pc_bill_block").hide();
                $('#pc_bill_wallet_balance').val('').removeData('balance');
                $('#pc_bill_items_body').html('');
                $('#pc_bill_total').val('₹ 0.00');
                $('#pc_bill_exceed_warning').hide();

                $("#fl_facility_block").hide();
                $("#facility_category_id").html('<option value="">Select Facility Category</option>');
                $("select[name='facility_category_id']").prop('required', false);


                $("#employee_common_block").hide();
                $("#employee_common")
                    .prop('required', false)
                    .val('')
                    .trigger('change');

                $("input[name='from_date'], input[name='to_date'], input[name='attendance_date']")
                    .val('')
                    .prop('required', false);
                $("input[name='vacancies'], input[name='designation'], input[name='job_description'], input[name='age_min'], input[name='age_max'], input[name='experience'], input[name='qualification'], input[name='skills'], input[name='work_location']")
                    .prop('required', false);
                $("select[name='gender']").prop('required', false);
                $("input[name='iou_request_date'], input[name='iou_amount']")
                    .prop('required', false);
                $("input[name='expense_date'], input[name='expense_amount']")
                    .prop('required', false);

                $("select[name='expense_type']")
                    .prop('required', false);

                $("select[name='settlement_type']")
                    .prop('required', false)
                    .val('');
                $("input[name='settlement_amount']")
                    .prop('required', false);
                // ======================================
                // HR EMPLOYEE
                // ======================================
                if (deptId == HR_ID) {
                    $("#employee_common_block").show();
                }
                if (issueId == LEAVE_REQUEST_ID) {
                    $("#leave_request_block").slideDown();
                    $("input[name='from_date']").prop('required', true);
                    $("input[name='to_date']").prop('required', true);
                } else if (issueId == ATTENDANCE_ISSUE_ID) {
                    $("#attendance_block").slideDown();
                    $("input[name='attendance_date']").prop('required', true);
                } else if (issueId == NEW_JOINEE) {

                    $("#new_joinee_block").slideDown();

                    $("input[name='vacancies']").prop('required', true);

                    $("input[name='designation']").prop('required', true);

                    $("textarea[name='job_description']").prop('required', true);

                    $("input[name='age_min']").prop('required', true);

                    $("input[name='age_max']").prop('required', true);

                    $("select[name='gender']").prop('required', true);

                    $("input[name='experience']").prop('required', true);

                    $("input[name='qualification']").prop('required', true);

                    $("input[name='skills']").prop('required', true);

                    $("input[name='work_location']").prop('required', true);

                    $("#employee_common_block").hide();

                    $("#employee_common")
                        .prop('required', false)
                        .val('')
                        .trigger('change');
                } else if (issueId == IOU_REQUEST_ID) {
                    $("#employee_common_block").show();
                    $("#iou_request_block").slideDown();
                      $("#employee_common").prop('required', true);
                    $("input[name='employee_common']")
                        .prop('required', true);
                    $("input[name='iou_request_date']")
                        .prop('required', true);

                    $("input[name='iou_amount']")
                        .prop('required', true);
                } else if (issueId == CLAIM_REQUEST_ID) {
                    $("#claim_request_block").slideDown();
                    $("input[name='expense_date']")
                        .prop('required', true);
                    $("select[name='expense_type']")
                        .prop('required', true);
                    $("input[name='expense_amount']")
                        .prop('required', true);
                } else if (issueId == SETTELMENT_ID) {
                    $("#employee_common_block").show();
                    $("#settlement_request_block").slideDown();
                     $("#employee_common").prop('required', true);
                    $("select[name='employee_common']")
                        .prop('required', true);
                    $("input[name='settlement_amount']")
                        .prop('required', true);
                    $("select[name='settlement_type']")
                        .prop('required', true);
                } else if (issueId == PC_REQUEST_ID) {
                    $("#pc_request_block").slideDown();
                    fetchPettyCashBalance();
                } else if (issueId == PC_SETTLEMENT_ID) {
                    $("#pc_bill_block").slideDown();
                    fetchPcBillWalletBalance();
                } else if (issueId == FL_NEW || issueId == FL_REPLACEMENT || issueId == FL_SERVICE) {
                    $("#fl_facility_block").slideDown();
                    loadFacilityCategories();
                }

                if (issueId == 21 || issueId == 22 || issueId == 23) {
                    $("#machine_block").slideDown();
                    if (issueId == 23) {
                        $("#machine_issue_type_block").slideDown();
                    } else {
                        $("#machine_issue_type_block").hide();
                    }
                    $.ajax({
                        url: "/machines",
                        type: "GET",
                        success: function(res) {
                            let options =
                                '<option value="">Select Machine</option>';
                            res.data.forEach(function(machine) {

                                options += `
                        <option value="${machine.MachineId}">
                            ${machine.MachineName}
                        </option>
                        `;
                            });
                            $("#machine_id").html(options);
                        }
                    });
                }

            });
            // ======================================
            // MACHINE ISSUE TYPE CHANGE
            // ======================================
            $("#machine_issue_type, #machine_id").on("change", function() {

                let issueId = $("#issue").val();
                let machineId = $("#machine_id").val();
                let type = $("#machine_issue_type").val();
                $("#machine_issues_checkbox").html('');
                $("#machine_issues_checkbox_block").hide();
                if (issueId != 23) {
                    return;
                }
                if (
                    machineId != '' &&
                    type != ''
                ) {
                    $.ajax({
                        url: "/machine-issues-list",
                        type: "GET",
                        data: {
                            machine_id: machineId,
                            type: type
                        },
                        success: function(res) {
                            let html = '';
                            if (res.data.length > 0) {

                                $("#machine_issues_checkbox_block")
                                    .slideDown();

                                res.data.forEach(function(issue) {


                                    html += `

                    <div class="col-lg-4 mb-3">

                 <div class="border rounded p-2 h-100">

                    <div class="form-check d-flex align-items-center">

                       <input
                    class="form-check-input me-2"
                    type="checkbox"
                    name="machine_issue_ids[]"
                    value="${issue.machineIssueId}"
                    id="issue_${issue.machineIssueId}"
                     >

                  <label
                    class="form-check-label"
                    for="issue_${issue.machineIssueId}"
                 >
                    ${issue.IssuesName}
                 </label>

                        </div>

                     </div>

                   </div>

                              `;
                                });

                            } else {

                                $("#machine_issues_checkbox_block")
                                    .slideDown();

                                html = `
                        <div class="text-danger">
                            No Machine Issues Found
                        </div>
                    `;
                            }

                            $("#machine_issues_checkbox").html(
                                '<div class="row">' + html + '</div>'
                            );
                        }
                    });
                }
            });
            // ── Fetch branch petty cash balance ──────────────────────────────────────
            // 1. function

            function fetchPettyCashBalance() {
                $('#pc_wallet_balance').val('Loading...');
                $('#pc_request_amount').val('');
                $('#pc_balance_warning').hide();

                $.ajax({
                    url: '/get-petty-cash-balance',
                    type: 'GET',
                    success: function(res) {
                        let balance = parseFloat(res.balance) || 0;
                        $('#pc_wallet_balance')
                            .val('₹ ' + balance.toFixed(2))
                            .data('balance', balance);
                    },
                    error: function() {
                        $('#pc_wallet_balance').val('Unable to fetch');
                    }
                });
            }
            $(document).on('keyup change', '#pc_request_amount', function() {
                let requested = parseFloat($(this).val()) || 0;
                let balance = parseFloat($('#pc_wallet_balance').data('balance')) || 0;
                if (requested > balance && balance > 0) {
                    $('#pc_balance_warning').show();
                } else {
                    $('#pc_balance_warning').hide();
                }
            });
            // ── Expense master options ─────────────────────────────────────────────────
            let expenseOptions = '<option value="">Select Category</option>';

            function loadExpenseMaster(callback) {
                if (expenseOptions !== '<option value="">Select Category</option>') {
                    if (callback) callback();
                    return;
                }
                $.ajax({
                    url: '/expense-master',
                    type: 'GET',
                    success: function(res) {
                        res.data.forEach(function(e) {
                            expenseOptions +=
                                `<option value="${e.ExpenseId}">${e.ExpenseName}</option>`;
                        });
                        if (callback) callback();
                    },
                    error: function() {
                        console.log('Failed to load expense master');
                    }
                });
            }

            // ── Fetch wallet for bill ──────────────────────────────────────────────────
            function fetchPcBillWalletBalance() {
                $('#pc_bill_wallet_balance').val('Loading...');

                $.ajax({
                    url: '/get-petty-cash-balance',
                    type: 'GET',
                    success: function(res) {
                        let balance = parseFloat(res.balance) || 0;
                        $('#pc_bill_wallet_balance')
                            .val('₹ ' + balance.toFixed(2))
                            .data('balance', balance);

                        loadExpenseMaster(function() {
                            addBillItemRow();
                        });
                    },
                    error: function() {
                        $('#pc_bill_wallet_balance').val('Unable to fetch');
                    }
                });
            }

            let billRowIndex = 0;

            function addBillItemRow() {
                let idx = billRowIndex++;

                let row = `
             <tr class="bill-item-row" data-index="${idx}">
             <td style="padding:8px;">
             <select name="pc_expense_id[]"
                    class="form-control form-control-sm">
                ${expenseOptions}
             </select>
             </td>
             <td style="padding:8px;">
             <input type="text"
                   name="pc_bill_number[]"
                   class="form-control form-control-sm"
                   placeholder="Bill No">
              </td>
             <td style="padding:8px;">
                  <input type="number"
                   name="pc_bill_amount[]"
                   class="form-control form-control-sm pc-bill-amount"
                   placeholder="0.00"
                   step="0.01"
                   min="0.01">
              </td>
              <td style="padding:8px;">
                  <input type="file"
                   name="bill_files[${idx}]"
                   class="form-control form-control-sm bill-file-input"
                   accept=".jpg,.jpeg,.png,.pdf"
                   data-index="${idx}">
                  <div id="preview_${idx}" style="display:none; margin-top:4px;">
                <small class="text-success">
                    <i class="ti ti-paperclip me-1"></i>
                    <span class="file-name"></span>
                    <a href="#" class="text-danger ms-1 remove-file" data-index="${idx}">
                        <i class="ti ti-x" style="font-size:11px;"></i>
                    </a>
                </small>
            </div>
        </td>
        <td style="padding:8px; text-align:center; vertical-align:middle;">
            <button type="button"
                    class="btn btn-sm btn-danger remove-bill-item"
                    style="padding:4px 8px;">
                <i class="ti ti-trash"></i>
            </button>
        </td>
    </tr>`;

                $('#pc_bill_items_body').append(row);
            }
            $(document).on('change', '.bill-file-input', function() {
                let idx = $(this).data('index');
                let file = this.files[0];
                let preview = $(`#preview_${idx}`);

                if (file) {
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire('Error', 'File size must be less than 2MB', 'error');
                        $(this).val('');
                        preview.hide();
                        return;
                    }
                    let allowed = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                    if (!allowed.includes(file.type)) {
                        Swal.fire('Error', 'Only JPG, PNG, PDF files allowed', 'error');
                        $(this).val('');
                        preview.hide();
                        return;
                    }

                    preview.find('.file-name').text(file.name);
                    preview.show();
                } else {
                    preview.hide();
                }
            });

            $(document).on('click', '.remove-file', function(e) {
                e.preventDefault();
                let idx = $(this).data('index');
                $(`input[name="bill_files[${idx}]"]`).val('');
                $(`#preview_${idx}`).hide();
            });
            $(document).on('click', '#add_bill_item', function() {
                if (expenseOptions === '<option value="">Select Category</option>') {
                    loadExpenseMaster(function() {
                        addBillItemRow();
                    });
                } else {
                    addBillItemRow();
                }
            });
            $(document).on('click', '.remove-bill-item', function() {
                if ($('#pc_bill_items_body tr').length <= 1) {
                    Swal.fire('Info', 'At least one bill item is required', 'info');
                    return;
                }
                $(this).closest('tr').remove();
                calculateBillTotal();
            });

            $(document).on('keyup change', '.pc-bill-amount', function() {
                calculateBillTotal();
            });

            function calculateBillTotal() {
                let total = 0;
                let balance = parseFloat($('#pc_bill_wallet_balance').data('balance')) || 0;

                $('.pc-bill-amount').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#pc_bill_total_display').text('₹ ' + total.toFixed(2));
                $('#pc_bill_total').val(total.toFixed(2));

                if (total > balance && balance > 0) {
                    $('#pc_bill_exceed_warning').show();
                    $('#pc_bill_total_display').removeClass('text-primary').addClass('text-danger');
                } else {
                    $('#pc_bill_exceed_warning').hide();
                    $('#pc_bill_total_display').removeClass('text-danger').addClass('text-primary');
                }
            }

            $("#pc_bill_block").hide();
            $('#pc_bill_wallet_balance').val('').removeData('balance');
            $('#pc_bill_items_body').html('');
            $('#pc_bill_total_display').text('₹ 0.00').removeClass('text-danger').addClass('text-primary');
            $('#pc_bill_total').val('0');
            $('#pc_bill_exceed_warning').hide();
            billRowIndex = 0;

            function loadFacilityCategories() {
                $.ajax({
                    url: "/facility-categories",
                    type: "GET",
                    success: function(res) {
                        let options = '<option value="">Select Facility Category</option>';
                        res.data.forEach(function(f) {
                            options += `<option value="${f.id}">${f.name}</option>`;
                        });
                        $("#facility_category_id").html(options);
                    }
                });
            }
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
