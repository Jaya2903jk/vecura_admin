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
                    Client Details
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
                                    Client Name
                                </div>
                                <div class="client-value">
                                    {{ $ticket->customer->FirstName ?? '-' }}
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
                                    Email
                                </div>
                                <div class="client-value">
                                    {{ $ticket->customer->EMail ?? '-' }}
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
                                    {{ $ticket->location->LocationName ?? '-' }}
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
                                    {{ $ticket->customer->Mobile ?? '-' }}
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
                                    Gender
                                </div>
                                <div class="client-value">
                                    {{ $ticket->customer->Sex ?? '-' }}
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
                                    {{ $ticket->customer->RegistrationNo ?? '-' }}
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
                                <th class="">#</th>
                                <th class="">Date</th>
                                <th class="">Raised By</th>
                                <th class="">Assigned To</th>
                                <th class="">Escalation</th>
                                <th class="">Type of Escalation</th>
                                <th class="">Source</th>
                                {{-- <th class="">Feedback</th> --}}
                                {{-- <th class="">Status</th> --}}
                                <th class="">Action</th>
                            </tr>
                        </thead>
                        <tbody id="followupBody">
                            @if ($firstComplaint)
                                <tr>
                                    <td>1</td>
                                    <td class="text-dark">
                                        {{ \Carbon\Carbon::parse($firstComplaint->CreatedDate)->format('d M Y') }}</td>
                                    <td class="text-dark">{{ $firstComplaint->createdUser->FullName ?? 'N/A' }}</td>
                                    <td class="text-dark">
                                        {{ $firstComplaint->acceptedUser->FullName ?? 'Not Assigned' }}</td>
                                    <td class="text-dark">{{ $firstComplaint->Complaint ?? '-' }}</td>
                                    <td class="text-dark">{{ $firstComplaint->TypeofEscalation ?? '-' }}</td>
                                    <td class="text-dark">{{ $firstComplaint->sources ?? '-' }}</td>
                                    {{-- <td>

                                            @if ($currentStatus == 'Resolved')

                                                <button class="btn bg-danger-gradient btn-danger"
                                                    onclick="closeComplaint({{ $firstComplaint->complaintid }}, {{ $ticket->ticketId }})">
                                                    Closed
                                                </button>
                                            @elseif($currentStatus == 'Closed')
                                                <span class="badge bg-secondary">Closed</span>
                                            @else
                                                <button class="btn  bg-primary-gradient btn-primary btn-effect"
                                                    onclick="openFollowupModal({{ $firstComplaint->complaintid }})">
                                                    Follow-up
                                                </button>
                                            @endif

                                        </td> --}}
                                    <td>

                                        @if ($currentStatus == 'Closed')
                                            <span class="badge bg-secondary">Closed</span>
                                        @elseif($isAdmin)
                                            @if ($currentStatus === 'Resolved')
                                                <button class="btn bg-danger-gradient btn-danger"
                                                    onclick="closeComplaint({{ $firstComplaint->complaintid }}, {{ $ticket->ticketId }})">
                                                    Closed
                                                </button>
                                            @else
                                                <button class="btn btn-primary"
                                                    onclick="openFollowupModal({{ $firstComplaint->complaintid }})">
                                                    <i class="ti ti-message-plus"></i> Add Follow up
                                                </button>
                                            @endif
                                        @elseif($isAuditTeam)
                                            <!-- Audit: only close -->
                                            @if ($currentStatus == 'Resolved')
                                                <button class="btn bg-danger-gradient btn-danger"
                                                    onclick="closeComplaint({{ $firstComplaint->complaintid }}, {{ $ticket->ticketId }})">
                                                    Closed
                                                </button>
                                            @else
                                                <span class="badge bg-warning">Waiting for resolution</span>
                                            @endif
                                        @elseif($isAssignee && !$isCreator)
                                            <!-- Only current assigned person can act -->
                                            @if ($currentStatus != 'Resolved')
                                                <button class="btn  bg-primary-gradient btn-primary btn-effect"
                                                    onclick="openFollowupModal({{ $firstComplaint->complaintid }})">
                                                    Follow-up
                                                </button>
                                            @else
                                                {{-- <button class="btn btn-danger"
                                                        onclick="closeComplaint({{ $firstComplaint->complaintid }}, {{ $ticket->ticketId }})">
                                                        Close
                                                    </button> --}}
                                            @endif
                                        @else
                                            <!-- Creator / previous handlers -->
                                            <span class="text-muted">No actions available</span>
                                        @endif

                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <br>
                <div class="table-title-row px-3">
                    <div class="table-title">
                        Follow-Up List
                    </div>

                </div>
                <div class="table-responsive">
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
\                                    <td class="text-dark">
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
                                    <!-- Status -->
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

                                    <!-- Action -->
                                    {{-- <td class="text-dark">
                                        <div class="icons-list-item d-flex gap-2">



                                        </div>
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center">No complaints found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>


            </div>


            {{-- <div class="tab-content">
                <div class="tab-pane show active" id="transactions">
                    <div class=" d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <div class="search-set mb-3">
                                <div class="d-flex align-items-center flex-wrap gap-2">
                                    <div class="table-search d-flex align-items-center mb-0">
                                        <div class="search-input">
                                            <a href="javascript:void(0);" class="btn-searchset"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div> --}}
            <!-- tab content end -->

        </div>
        <!-- End Content -->

        <!-- Footer Start -->
        {{-- <div class="footer text-center bg-white p-2 border-top">
            <p class="text-dark mb-0">2025 &copy; <a href="javascript:void(0);" class="link-primary">Preclinic</a>, All
                Rights Reserved</p>
        </div> --}}
        <!-- Footer End -->

    </div>
    <script></script>
    <script>
        function openFollowupModal(id) {
            console.log("ID:", id); // check in console

            document.getElementById('followupForm').reset();
            document.getElementById('complaint_id').value = id;

            let modal = new bootstrap.Modal(document.getElementById('followupModal'));
            modal.show();
        }
    </script>
    <script>
        function closeModal(id) {
            console.log("ID:", id); // check in console

            document.getElementById('followupForm').reset();
            document.getElementById('complaint_id').value = id;

            let modal = new bootstrap.Modal(document.getElementById('followupModal'));
            modal.show();
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).on('click', '.followup-btn', function(e) {
                e.preventDefault();

                let status = $(this).data('status');
                let assignVal = $('#assign_to').val();
                let remarks = $('textarea[name="remarks"]').val();

                console.log("STATUS:", status);
                if (status === 'Resolved' && assignVal) {
                    Swal.fire({
                        icon: "warning",
                        title: "Action Required",
                        text: "Please unselect 'Assign To' before clicking Resolved"
                    });
                    return;
                }
                if (status !== 'Resolved') {

                    if (!assignVal) {
                        Swal.fire("Error", "Assign To is required", "error");
                        return;
                    }

                    if (!remarks) {
                        Swal.fire("Error", "Remarks required", "error");
                        return;
                    }
                }

                let form = $('#followupForm')[0];
                let formData = new FormData(form);
                formData.append('status', status);

                $.ajax({
                    url: "{{ route('complaint.followup') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,

                    beforeSend: function() {
                        $('.followup-btn').prop('disabled', true);
                    },

                    success: function(res) {

                        if (res.status) {

                            Swal.fire({
                                icon: "success",
                                title: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            $('#followupModal').modal('hide');

                            setTimeout(() => location.reload(), 1500);

                        } else {
                            Swal.fire("Error", res.message, "error");
                        }
                    },

                    error: function(err) {
                        console.log(err.responseText);

                        Swal.fire({
                            icon: "error",
                            title: "Server Error",
                            text: "Something went wrong!"
                        });
                    },

                    complete: function() {
                        $('.followup-btn').prop('disabled', false);
                    }
                });

            });

            // function closeComplaint(complaintId, ticketId) {

            //     Swal.fire({
            //         title: "Are you sure?",
            //         text: "This will close the complaint permanently",
            //         icon: "warning",
            //         showCancelButton: true,
            //         confirmButtonText: "Yes, Close it"
            //     }).then((result) => {

            //         if (!result.isConfirmed) return;

            //         $.ajax({
            //             url: "{{ route('complaint.permanent') }}"
            //             type: "POST",
            //             data: {
            //                 complaint_id: complaintId,
            //                 ticket_id: ticketId,
            //                 _token: $('meta[name="csrf-token"]').attr('content')
            //             },
            //             success: function(res) {
            //                 if (res.status) {
            //                     Swal.fire("Closed!", res.message, "success");
            //                     setTimeout(() => location.reload(), 1000);
            //                 } else {
            //                     Swal.fire("Error", res.message, "error");
            //                 }
            //             },
            //             error: function(xhr) {
            //                 Swal.fire("Error", xhr.responseJSON?.message || "Server error",
            //                     "error");
            //             }
            //         });

            //     });
            // }

        });
    </script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            window.closeComplaint = function(complaintId, ticketId) {

                Swal.fire({
                    title: "Are you sure?",
                    text: "This will close the complaint permanently",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, Close it"
                }).then((result) => {

                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ route('complaint.permanent') }}",
                        type: "POST",
                        data: {
                            complaint_id: complaintId,
                            ticket_id: ticketId
                        },
                        success: function(res) {
                            if (res.status) {
                                Swal.fire("Closed!", res.message, "success");
                                setTimeout(() => location.reload(), 1000);
                            } else {
                                Swal.fire("Error", res.message, "error");
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                "Error",
                                xhr.responseJSON?.message || "Server error",
                                "error"
                            );
                        }
                    });

                });
            };

        });
    </script>
    <script>
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
    </script>


    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Followup Modal -->
    <div id="followupModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title fw-bold">Add Followup</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="followupForm">
                    @csrf
                    <input type="hidden" name="department_id" id="department">
                    <input type="hidden" name="customer_code" value="{{ $ticket->CustomerCode }}">
                    <input type="hidden" name="customer_name" value="{{ $ticket->CustomerName }}">
                    <input type="hidden" name="ticket_id" value="{{ $ticket->ticketId }}">
                    <input type="hidden" name="complaint_id" id="complaint_id">
                    <div class="modal-body">

                        <input type="hidden" id="department_id_from_ticket"
                            value="{{ $ticket->department->Departmentid ?? '' }}">

                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assign_to" id="assign_to" class="form-control">
                                <option value="">Select Any One</option>
                                @foreach ($assignList ?? [] as $user)
                                    <option value="{{ $user->UserCode }}">
                                        {{ optional($user->location)->UserGroupName ?? 'Corporate' }} -
                                        {{ $user->FullName }} -
                                        {{ $user->designation->Designation ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Feedback</label>
                            <textarea name="remarks" class="form-control" required></textarea>
                        </div>
                        <div class="col-lg-12">
                            <div class="mb-2">
                                <label class="form-label">File</label>
                                <div
                                    class="file-upload drag-file bg-light w-100 d-flex align-items-center justify-content-center flex-column">
                                    <span class="upload-img d-block mb-2"><i
                                            class="ti ti-folder-open text-primary"></i></span>
                                    <p class="mb-0 text-dark">Drop Your Files or <a href="javascript:void(0);"
                                            class="text-primary text-decoration-underline">
                                            browse</a></p>
                                    <input type="file" accept="video/image" name="file">
                                    <p class="fs-13">Maximum size : 50 MB</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary followup-btn" data-status="InProgress">Save
                            Followup</button>
                        <button type="button" class="btn btn-success followup-btn"
                            data-status="Resolved">Resolved</button>

                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
