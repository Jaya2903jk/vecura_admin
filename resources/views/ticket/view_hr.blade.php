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
                                <th class="text-light">From</th>
                                <th class="text-light">To</th>
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
                                    <td class="text-dark">{{ $hr->fromDate ?? '-' }}</td>
                                    <td class="text-dark">{{ $hr->toDate ?? '-' }}</td>

                                    <td>
                                        <span
                                            class="badge
                                           @if ($hr->status == 'Pending') bg-warning
                                           @elseif($hr->status == 'InProgress') bg-info
                                            @elseif($hr->status == 'Resolved') bg-success
                                           @else bg-danger @endif">
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
                                        @endphp

                                        {{-- ADMIN / HR --}}
                                        @if (($isAdmin || $isAuditTeam) && $hr->status != 'Closed')
                                            <button class="btn bg-primary-gradient btn-primary btn-effect"
                                               onclick="openHrModal('{{ $hr->hrTicketId }}', '{{ $hr->status }}', '{{ $hr->CreatedDate }}')">
                                                Update
                                            </button>

                                            {{-- EMPLOYEE --}}
                                        @elseif($isCreator && $hr->status == 'Resolved')
                                            <button class="btn bg-primary-gradient btn-primary btn-effect"
                                               onclick="openHrModal('{{ $hr->hrTicketId }}', '{{ $hr->status }}', '{{ $hr->CreatedDate }}')">
                                                Close
                                            </button>
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
                            @else bg-danger @endif">
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
                {{-- <div class="table-title-row px-3">
                    <div class="table-title">
                        Follow-Up List
                    </div>

                </div> --}}
                {{-- <div class="table-responsive">
                    <table class="table blue-table mb-0">
                        <thead class="thead-light ">
                            <thead class="thead-light ">

                                <tr>
                                    <th class="">#</th>
                                    <th class="">Date</th>
                                    <th class="">Raised By</th>
                                    <th class="">Assigned To</th>

                                    <th class="">Source</th>
                                    <th class="">Feedback</th>
                                    <th class="">Status</th>
                                </tr>
                            </thead>
                        <tbody>
                            @forelse($ticket->complaints as $key => $complaint)
                                <tr>
                                    <td class="text-dark">{{ $key + 1 }}</td>
                                     <td class="text-dark">
                                        {{ \Carbon\Carbon::parse($complaint->CreatedDate)->format('d M Y') }}</td>

                                    <td class="text-dark">{{ $complaint->createdUser->FullName ?? 'N/A' }}</td>
                                    <td class="text-dark">{{ $complaint->acceptedUser->FullName ?? 'Not Assigned' }}
                                    </td>

                                    <td class="text-dark">{{ $complaint->sources ?? '-' }}</td>

                                    <td class="text-dark" style="max-width:800px;">
                                        <div style="white-space: normal;">
                                            <small>{{ $complaint->feedback }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge
                                           @if ($complaint->callStatus == 'Pending') bg-warning
                                           @elseif($complaint->callStatus == 'InProgress') bg-info
                                            @elseif($complaint->callStatus == 'Resolved') bg-success
                                           @else bg-danger @endif">
                                            {{ $complaint->callStatus }}
                                        </span>
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No complaints found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> --}}


            </div>




        </div>


    </div>
    <div class="modal fade" id="hrStatusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update HR Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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
                <div class="modal-footer">
                    <button class="btn btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" onclick="saveHrStatus()">Save</button>
                </div>

            </div>
        </div>
    </div>
    <script>
        function openHrModal(id, status, createdDate) {

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

            if (isAdmin || isAuditTeam) {

                // Admin can resolve
                select.innerHTML += `<option value="Resolved">Resolved</option>`;

                // Admin can close only after 2 days
                if (diffDays >= 2) {
                    select.innerHTML += `<option value="Closed">Closed</option>`;
                }

            } else if (isCreator && status === 'Resolved') {

                // Employee only close
                select.innerHTML += `<option value="Closed">Closed</option>`;
            }

            let modal = new bootstrap.Modal(document.getElementById('hrStatusModal'));
            modal.show();
        }
    </script>
    {{-- <script>
            function openHrModal(id) {
                document.getElementById('modal_hr_id').value = id;
                document.getElementById('modal_status').value = "";
                document.getElementById('modal_comments').value = "";

                let modal = new bootstrap.Modal(document.getElementById('hrStatusModal'));
                modal.show();
            }
        </script> --}}
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
    {{-- <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // $(document).on('click', '.followup-btn', function(e) {
            //     e.preventDefault();

            //     let status = $(this).data('status');
            //     let assignVal = $('#assign_to').val();
            //     let remarks = $('textarea[name="remarks"]').val();

            //     console.log("STATUS:", status);
            //     if (status === 'Resolved' && assignVal) {
            //         Swal.fire({
            //             icon: "warning",
            //             title: "Action Required",
            //             text: "Please unselect 'Assign To' before clicking Resolved"
            //         });
            //         return;
            //     }
            //     if (status !== 'Resolved') {

            //         if (!assignVal) {
            //             Swal.fire("Error", "Assign To is required", "error");
            //             return;
            //         }

            //         if (!remarks) {
            //             Swal.fire("Error", "Remarks required", "error");
            //             return;
            //         }
            //     }

            //     let form = $('#followupForm')[0];
            //     let formData = new FormData(form);
            //     formData.append('status', status);

            //     $.ajax({
            //         url: "{{ route('complaint.followup') }}",
            //         type: "POST",
            //         data: formData,
            //         processData: false,
            //         contentType: false,

            //         beforeSend: function() {
            //             $('.followup-btn').prop('disabled', true);
            //         },

            //         success: function(res) {

            //             if (res.status) {

            //                 Swal.fire({
            //                     icon: "success",
            //                     title: res.message,
            //                     timer: 1500,
            //                     showConfirmButton: false
            //                 });

            //                 $('#followupModal').modal('hide');

            //                 setTimeout(() => location.reload(), 1500);

            //             } else {
            //                 Swal.fire("Error", res.message, "error");
            //             }
            //         },

            //         error: function(err) {
            //             console.log(err.responseText);

            //             Swal.fire({
            //                 icon: "error",
            //                 title: "Server Error",
            //                 text: "Something went wrong!"
            //             });
            //         },

            //         complete: function() {
            //             $('.followup-btn').prop('disabled', false);
            //         }
            //     });

            // });


        });
    </script> --}}

    {{-- <script>
        $(document).ready(function() {

            let deptId = $("#department_id_from_ticket").val();

            if (deptId) {
                $("#department").val(deptId);

                // Trigger category load directly
                loadCategories(deptId);
            }

            function loadCategories(deptId) {
                $("#category").html('<option>Loading...</option>');
                $("#issue").html('<option>Select Issue</option>');

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

            $("#category").change(function() {
                let categoryId = $(this).val();

                $("#issue").html('<option>Loading...</option>');

                if (categoryId) {
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

        });
    </script> --}}


    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
