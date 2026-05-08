<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <style>
        .crumb-back {
            color: #22304d;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 14px;
            font-weight: 600
        }

        .details-card {
            background: #fff;
            border: 1px solid #e7e8eb;
            border-radius: 6px;
            overflow: hidden
        }

        .details-card-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e7e8eb;
            font-size: 17px;
            font-weight: 700;
            color: #132144;
            display: flex;
            align-items: center;
            gap: 8px
        }

        .client-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            padding: 16px
        }

        .client-item {
            display: flex;
            gap: 14px;
            align-items: flex-start
        }

        .client-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f3f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b738a;
            flex-shrink: 0
        }

        .client-label {
            font-size: 13px;
            font-weight: 700;
            color: #18284d;
            margin-bottom: 2px
        }

        .client-value {
            font-size: 13px;
            color: #737b90
        }

        .table-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin: 18px 0 10px
        }

        .table-title {
            font-size: 15px;
            font-weight: 700;
            color: #132144;
            margin: 0
        }

        .blue-table thead th {
            background: #3741b0;
            color: #fff;
            border-bottom: none;
            padding: 9px 12px;
            font-size: 13px
        }

        .blue-table tbody td {
            padding: 10px 12px;
            font-size: 13px;
            color: #4f5d7c;
            border-color: #e7e8eb
        }

        .pill {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            color: #fff
        }

        .pill-red {
            background: #ff1e1e
        }

        .pill-green {
            background: #32b35f
        }

        .pill-blue {
            background: linear-gradient(180deg, #37a3d6, #2f6bcf)
        }

        .pill-yellow {
            background: #f2b21b
        }

        .toolbar-inline {
            display: flex;
            gap: 10px;
            flex-wrap: wrap
        }

        .toolbar-inline .form-select,
        .toolbar-inline .form-control {
            min-width: 180px;
            height: 38px;
            font-size: 13px
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
            z-index: 5
        }

        .footer-line {
            text-align: center;
            font-size: 12px;
            color: #6f7790;
            padding: 20px 0 10px
        }

        .empty-row {
            text-align: center;
            color: #7d8598;
            padding: 18px
        }

        .attach-name {
            font-size: 12px;
            color: #7a8295;
            margin-top: 6px
        }

        @media (max-width:991.98px) {
            .page-wrapper {
                margin-left: 0
            }

            .sidebar {
                left: -230px
            }

            .client-grid {
                grid-template-columns: 1fr
            }
        }
    </style>
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">


            <a href="{{ route('tickets') }}" class="crumb-back">
                <i class="ti ti-chevron-left">
                </i>
                View Ticket
            </a>

            <div class="details-card">
                <div class="details-card-header">
                    <i class="ti ti-user">
                    </i>
                    Staff Details
                </div>
                <div class="client-grid">
                    <div>
                        <div class="client-item">
                            <div class="client-icon">
                                <i class="ti ti-user">
                                </i>
                            </div>
                            <div>
                                <div class="client-label">
                                    Staff Name
                                </div>
                                <div class="client-value">
                                    {{ $hrData->employee->FullName ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="client-item mt-3">
                            <div class="client-icon">
                                <i class="ti ti-mail">
                                </i>
                            </div>
                            <div>
                                <div class="client-label">
                                    Staff Email
                                </div>
                                <div class="client-value">
                                    {{ '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="client-item">
                            <div class="client-icon">
                                <i class="ti ti-building">
                                </i>
                            </div>
                            <div>
                                <div class="client-label">
                                    Branch
                                </div>
                                <div class="client-value">
                                    {{ $hrData->employee->Loc_id ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="client-item mt-3">
                            <div class="client-icon">
                                <i class="ti ti-phone">
                                </i>
                            </div>
                            <div>
                                <div class="client-label">
                                    Mobile No
                                </div>
                                <div class="client-value">
                                    {{ '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="client-item">
                            <div class="client-icon">
                                <i class="ti ti-user-scan">
                                </i>
                            </div>
                            <div>
                                <div class="client-label">
                                    Designation
                                </div>
                                <div class="client-value">
                                    {{-- {{ $hrData->employee->FullName ?? '-' }} --}}
                                    {{ $hrData->employee->designation->Designation ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="client-item mt-3">
                            <div class="client-icon">
                                <i class="ti ti-id-badge-2">
                                </i>
                            </div>
                            <div>
                                <div class="client-label">
                                    Reg No.
                                </div>
                                <div class="client-value">
                                    {{ $hrData->employee->UserCode ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="table-title-row px-3">
                    <div class="table-title">
                        Ticket History
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table blue-table mb-0">
                        <thead class="thead-light ">

                            <tr>
                                <th class="text-light">#</th>
                                <th class="text-light">Date</th>
                                {{-- <th class="text-light">Raised By</th> --}}
                                <th class="text-light">Category</th>
                                <th class="text-light">Type of Escalation</th>
                                @php
                                    $leaveRequestId = config('ticket.LEAVE_REQUEST');
                                    $attendanceId = config('ticket.ATTENDANCE_ISSUE');

                                    $hasLeave = $ticket->hr->contains(function ($hr) use ($leaveRequestId) {
                                        return $hr->escalationTypeId == $leaveRequestId;
                                    });

                                    $hasAttendance = $ticket->hr->contains(function ($hr) use ($attendanceId) {
                                        return $hr->escalationTypeId == $attendanceId;
                                    });
                                @endphp
                                {{-- <th class="text-light">From</th>
                                <th class="text-light">To</th>
                                 <th class="text-light">Date</th> --}}
                                @if ($hasLeave)
                                    <th>From</th>
                                    <th>To</th>
                                @endif
                                {{--  ATTENDANCE --}}
                                @if ($hasAttendance)
                                    <th>Date</th>
                                @endif

                                <th class="text-light">Status</th>
                                <th class="text-light">Feedback</th>

                                <th class="text-light">Action</th>
                            </tr>
                        </thead>
                        <tbody id="followupBody">
                            @forelse($ticket->hr as $key => $hr)
                                <tr>
                                    <td class="text-dark">{{ $key + 1 }}</td>
                                    <td class="text-dark">
                                        {{ \Carbon\Carbon::parse($hr->CreatedDate)->format('d M Y') }}
                                    </td>

                                    {{-- <td class="text-dark">{{ $hr->createdUser->FullName ?? 'N/A' }} --}}

                                    </td>
                                    <td class="text-dark">{{ $hr->category->category_name ?? '-' }}</td>
                                    <td class="text-dark">{{ $hr->escalationType->IssueName ?? '-' }}</td>
                                    {{-- <td class="text-dark">{{ $hr->fromDate ?? '-' }}</td>
                                    <td class="text-dark">{{ $hr->toDate ?? '-' }}</td>
                                    <td class="text-dark">{{ $hr->attendanceDate ?? '-' }}</td> --}}
                                    @if ($hr->escalationTypeId == $leaveRequestId)
                                        <td>{{ $hr->fromDate ?? '-' }}</td>
                                        <td>{{ $hr->toDate ?? '-' }}</td>
                                    @elseif($hasLeave)
                                        <td>-</td>
                                        <td>-</td>
                                    @endif

                                    {{-- ✅ ATTENDANCE --}}
                                    @if ($hr->escalationTypeId == $attendanceId)
                                        <td>{{ $hr->attendanceDate ?? '-' }}</td>
                                    @elseif($hasAttendance)
                                        <td>-</td>
                                    @endif
                                    <td>
                                        <span
                                            class="badge
        @if ($hr->status == 'Pending') bg-warning
        @elseif($hr->status == 'InProgress') bg-info
        @elseif($hr->status == 'Resolved') bg-success
        @elseif($hr->status == 'Approved') bg-success
        @elseif($hr->status == 'Rejected') bg-danger
        @elseif($hr->status == 'Closed') bg-danger
        @else bg-secondary @endif">
                                            {{ $hr->status }}
                                        </span>
                                    </td>
                                    <td class="text-dark" style="max-width:800px;">
                                        <div style="white-space: normal;">
                                            <small>{{ $hr->comments }}</small>
                                        </div>
                                    </td>

                                    {{-- <td>
                                        @if ($hr->status != 'Closed')
                                        <button class="btn  bg-primary-gradient btn-primary btn-effect"
                                            onclick="openHrModal({{ $hr->hrTicketId }})">
                                            Update
                                        </button>
                                        @endif
                                    </td> --}}
                                    <td>
                                        @php
                                            $createdDate = \Carbon\Carbon::parse($hr->CreatedDate);
                                            $canAdminClose = $createdDate->diffInDays($today) >= 2;

                                            $isLeave = $hr->escalationTypeId == config('ticket.LEAVE_REQUEST');
                                        @endphp

                                        {{-- ========================= --}}
                                        {{-- ✅ LEAVE REQUEST FLOW --}}
                                        {{-- ========================= --}}
                                        @if ($isLeave)
                                            {{-- Admin can Approve/Reject only when Pending --}}
                                            {{-- @if (($isAdmin || $isAuditTeam) && $hr->status == 'Pending') --}}
                                            @if (($isAdmin || $isAuditTeam) && in_array($hr->status, ['Pending', 'InProgress']))
                                                <button class="btn bg-primary-gradient btn-primary btn-effect"
                                                    onclick="openHrModal(
                                                 '{{ $hr->hrTicketId }}',
                                                 '{{ $hr->status }}',
                                                 '{{ $hr->CreatedDate }}',
                                                 true
                                             )">
                                                    Update
                                                </button>
                                            @endif

                                            {{-- Employee can Close after Approved/Rejected --}}
                                            @if ($isCreator && in_array($hr->status, ['Approved', 'Rejected']))
                                                <button class="btn bg-primary-gradient btn-primary btn-effect"
                                                    onclick="openHrModal(
                                                          '{{ $hr->hrTicketId }}',
                                                   '{{ $hr->status }}',
                                                   '{{ $hr->CreatedDate }}',
                                                   true
                                               )">
                                                    Close
                                                </button>
                                            @endif

                                            {{-- ========================= --}}
                                            {{-- ✅ NORMAL HR FLOW --}}
                                            {{-- ========================= --}}
                                        @else
                                            {{-- Admin Resolve --}}
                                            @if (($isAdmin || $isAuditTeam) && $hr->status != 'Closed')
                                                <button class="btn bg-primary-gradient btn-primary btn-effect"
                                                    onclick="openHrModal(
                    '{{ $hr->hrTicketId }}',
                    '{{ $hr->status }}',
                    '{{ $hr->CreatedDate }}',
                    false
                )">
                                                    Update
                                                </button>
                                            @endif

                                            {{-- Employee Close --}}
                                            @if ($isCreator && $hr->status == 'Resolved')
                                                <button class="btn bg-primary-gradient btn-primary btn-effect"
                                                    onclick="openHrModal(
                    '{{ $hr->hrTicketId }}',
                    '{{ $hr->status }}',
                    '{{ $hr->CreatedDate }}',
                    false
                )">
                                                    Close
                                                </button>
                                            @endif
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No history found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <br>


                <div class="table-title-row px-3">
                    <div class="table-title">Ticket Status History</div>
                </div>

                <div class="table-responsive">
                    <table class="table blue-table mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-light">#</th>
                                <th class="text-light">Date</th>
                                <th class="text-light">Status</th>
                                <th class="text-light">Comment</th>
                                <th class="text-light">Updated By</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($statusHistory as $key => $history)
                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($history['updated_at'])->format('d M Y H:i') }}
                                    </td>

                                    <td>
                                        <span
                                            class="badge
        @if ($history['status'] == 'Pending') bg-warning
        @elseif($history['status'] == 'InProgress') bg-info
        @elseif($history['status'] == 'Resolved') bg-success
        @elseif($history['status'] == 'Approved') bg-success
        @elseif($history['status'] == 'Rejected') bg-danger
        @elseif($history['status'] == 'Closed') bg-danger
        @else bg-secondary @endif">
                                            {{ $history['status'] }}
                                        </span>
                                    </td>

                                    <td>
                                        <small>{{ $history['comment'] ?? '-' }}</small>
                                    </td>

                                    <td>
                                        {{ $users[$history['updated_by']] ?? 'Unknown User' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No status history found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>



            </div>




        </div>


    </div>

    <style>
        .ap-modal-header {
            background: #3741b0;
            padding: 14px 20px;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .ap-modal-header .btn-close {
            filter: invert(1) brightness(2);
            opacity: .8;
        }

        .ap-modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        /* Modal footer */
        .ap-modal-footer {
            padding: 12px 20px 16px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            border-top: 1px solid #e7e8eb;
        }

        .ap-btn-cancel {
            padding: 7px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 5px;
            border: 1px solid #d0d3de;
            background: #fff;
            color: #4b5673;
            cursor: pointer;
            transition: background .15s;
        }

        .ap-btn-cancel:hover {
            background: #f3f4f8;
        }

        .ap-btn-submit {
            padding: 7px 22px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 5px;
            border: none;
            background: #3741b0;
            color: #fff;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: opacity .15s;
        }

        .ap-btn-submit:hover {
            opacity: .88;
        }

        .ap-btn-submit:disabled {
            opacity: .6;
            cursor: not-allowed;
        }
    </style>
    <div class="modal fade" id="hrStatusModal" tabindex="-1" aria-labelledby="hrStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">

            <div class="modal-content"
                style="border-radius:6px; border:none; overflow:hidden; box-shadow:0 8px 32px rgba(55,65,176,.18);">
                {{-- <div class="modal-header">
                    <h5 class="modal-title" id="hrStatusModalLabel">Update HR Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div> --}}
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title" id="hrStatusModalLabel">
                        <i class="ti ti-clipboard-check" style="font-size:17px;"></i>
                        Update HR Ticket
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal_hr_id">
                    <div class="mb-3">
                        <label>Status</label>

                        <select id="modal_status" class="form-control">

                            <option value="" disabled selected>Select</option>

                            @if ($isAdmin || $isAuditTeam)
                                <option value="Resolved">Resolved</option>

                                @if ($canAdminClose)
                                    <option value="Closed">Closed</option>
                                @endif
                            @elseif($isCreator && $hr->status == 'Resolved')
                                <option value="Closed">Closed</option>
                            @endif

                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Feedback</label>
                        <textarea id="modal_comments" class="form-control" required></textarea>
                    </div>
                </div>
                {{-- <div class="modal-footer">
                    <button class="btn btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" onclick="saveHrStatus()">Save</button>
                </div> --}}
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="ap-btn-submit" id="ap_submit_btn" onclick="saveHrStatus()">
                        <i class="ti ti-send" style="font-size:13px;"></i>
                        Submit
                    </button>
                </div>

            </div>
        </div>
    </div>
    <script>
        // function openHrModal(id, status, createdDate) {

        //     document.getElementById('modal_hr_id').value = id;
        //     document.getElementById('modal_comments').value = "";

        //     let select = document.getElementById('modal_status');
        //     select.innerHTML = '<option value="" disabled selected>Select</option>';

        //     let isAdmin = @json($isAdmin);
        //     let isAuditTeam = @json($isAuditTeam);
        //     let isCreator = @json($isCreator);

        //     let created = new Date(createdDate);
        //     let today = new Date();

        //     let diffDays = Math.floor((today - created) / (1000 * 60 * 60 * 24));

        //     if (isAdmin || isAuditTeam) {

        //         // Admin can resolve
        //         select.innerHTML += `<option value="Resolved">Resolved</option>`;


        //         // Admin can close only after 2 days
        //         if (diffDays >= 2) {
        //             select.innerHTML += `<option value="Closed">Closed</option>`;
        //         }

        //     } else if (isCreator && status === 'Resolved') {

        //         // Employee only close
        //         select.innerHTML += `<option value="Closed">Closed</option>`;
        //     }

        //     let modal = new bootstrap.Modal(document.getElementById('hrStatusModal'));
        //     modal.show();
        // }
        // function openHrModal(id, status, createdDate, isLeaveRequest = false) {

        //     document.getElementById('modal_hr_id').value = id;
        //     document.getElementById('modal_comments').value = "";

        //     let select = document.getElementById('modal_status');
        //     select.innerHTML = '<option value="" disabled selected>Select</option>';

        //     let isAdmin = @json($isAdmin);
        //     let isAuditTeam = @json($isAuditTeam);
        //     let isCreator = @json($isCreator);

        //     let created = new Date(createdDate);
        //     let today = new Date();
        //     let diffDays = Math.floor((today - created) / (1000 * 60 * 60 * 24));

        //     if (isLeaveRequest) {

        //         if (isAdmin || isAuditTeam) {
        //             select.innerHTML += `<option value="Approved">Approved</option>`;
        //             select.innerHTML += `<option value="Rejected">Rejected</option>`;
        //         }

        //         if (isCreator && (status === 'Approved' || status === 'Rejected')) {
        //             select.innerHTML += `<option value="Closed">Closed</option>`;
        //         }

        //     }
        //     else {

        //         if (isAdmin || isAuditTeam) {
        //             select.innerHTML += `<option value="Resolved">Resolved</option>`;

        //             if (diffDays >= 2) {
        //                 select.innerHTML += `<option value="Closed">Closed</option>`;
        //             }
        //         } else if (isCreator && status === 'Resolved') {
        //             select.innerHTML += `<option value="Closed">Closed</option>`;
        //         }
        //     }

        //     let modal = new bootstrap.Modal(document.getElementById('hrStatusModal'));
        //     modal.show();
        // }
    </script>
    <script>
        function openHrModal(id, status, createdDate, isLeaveRequest = false) {

            document.getElementById('modal_hr_id').value = id;
            document.getElementById('modal_comments').value = "";

            let select = document.getElementById('modal_status');
            select.innerHTML = '<option value="" disabled selected>Select</option>';

            let isAdmin = @json($isAdmin);
            let isAuditTeam = @json($isAuditTeam);
            let isCreator = @json($isCreator);

            let created = new Date(createdDate);
            let today = new Date();
            let diffDays = Math.floor((today - created) / (1000 * 60 * 60 * 24));

            if (status === 'Closed') {
                Swal.fire("Info", "Ticket already closed", "info");
                return;
            }

            // =========================
            // ✅ LEAVE REQUEST FLOW
            // =========================
            if (isLeaveRequest) {

                // Already Approved / Rejected → only Close
                if (status === 'Approved' || status === 'Rejected') {

                    if (isCreator) {
                        select.innerHTML += `<option value="Closed">Closed</option>`;
                    }

                } else {
                    // Pending → Admin can Approve/Reject
                    if (isAdmin || isAuditTeam) {
                        select.innerHTML += `<option value="Approved">Approved</option>`;
                        select.innerHTML += `<option value="Rejected">Rejected</option>`;
                    }
                }

            }
            // =========================
            // ✅ NORMAL HR FLOW
            // =========================
            else {

                if (isAdmin || isAuditTeam) {

                    if (status !== 'Resolved') {
                        select.innerHTML += `<option value="Resolved">Resolved</option>`;
                    }

                    if (diffDays >= 2 || status === 'Resolved') {
                        select.innerHTML += `<option value="Closed">Closed</option>`;
                    }

                } else if (isCreator && status === 'Resolved') {
                    select.innerHTML += `<option value="Closed">Closed</option>`;
                }
            }

            let modal = new bootstrap.Modal(document.getElementById('hrStatusModal'));
            modal.show();
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        function saveHrStatus() {

            let hrId = document.getElementById('modal_hr_id').value;
            let status = document.getElementById('modal_status').value;
            let comments = document.getElementById('modal_comments').value;

            if (!status) {
                Swal.fire("Error", "Please select status", "error");
                return;
            }

            $.ajax({
                url: "{{ route('hr.update.status') }}",
                type: "POST",
                data: {
                    hr_id: hrId,
                    status: status,
                    comments: comments,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {

                    if (res.status) {
                        Swal.fire("Success", res.message, "success");

                        let modalEl = document.getElementById('hrStatusModal');
                        let modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();

                        setTimeout(() => location.reload(), 800);
                    } else {
                        Swal.fire("Error", res.message, "error");
                    }
                },
                error: function() {
                    Swal.fire("Error", "Server error", "error");
                }
            });
        }
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
