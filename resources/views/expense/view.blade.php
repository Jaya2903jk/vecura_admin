<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- page header start -->
            <div class="mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center"> <a href="{{ route('tickets') }}" class="text-dark"> <i
                            class="ti ti-chevron-left me-1"></i>View Ticket</a></h6>
            </div>
            <!-- page header end -->


            <div class="row">
                <div class="col-xl-12 d-flex">
                    <div class="card shadow-sm flex-fill w-100">
                        <div class="card-header">
                            <h5 class="fw-bold mb-0"><i class="ti ti-user-star me-1"></i>Client Details</h5>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"> <i
                                                class="ion-person" data-bs-toggle="tooltip" title="ion-person"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Client Name</h6>
                                            <p class="mb-0" id="view_customer_name">
                                                {{ $ticket->customer->FirstName ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                class="ti ti-droplet text-body fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Branch</h6>
                                            <p class="mb-0">{{ $ticket->location->LocationName ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                class="ti ti-droplet text-body fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Gender</h6>
                                            <p class="mb-0">{{ $ticket->customer->Sex ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                class="ti ti-droplet text-body fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Email</h6>
                                            <p class="mb-0">{{ $ticket->customer->EMail ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                class="ti ti-gender-male text-body fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Mobile No</h6>
                                            <p class="mb-0">{{ $ticket->customer->Mobile ?? '-' }}</p>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="avatar rounded-circle bg-light text-dark flex-shrink-0 me-2"><i
                                                class="ti ti-mail text-body fs-16"></i></span>
                                        <div>
                                            <h6 class="fs-13 fw-bold mb-1">Reg No.</h6>
                                            <p class="mb-0 text-break">{{ $ticket->customer->RegistrationNo ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- row end -->

            <!-- tab start -->

            <!-- tab end -->

            <!-- tab content start -->
            <div class="tab-content">

                <div class="tab-pane show active" id="transactions">
                    <!--  Start Filter -->
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

                            {{-- <div class="d-flex right-content align-items-center flex-wrap mb-3">
                                <div class="reportrange-picker d-flex align-items-center reportrange">
                                    <i class="ti ti-calendar text-gray-5 fs-14 me-1"></i><span
                                        class="reportrange-picker-field">16 Apr 25 - 16 Apr 25</span>
                                </div>
                            </div> --}}
                        </div>


                    </div>
                    <div class="table-responsive">
                        <table class="table table-nowrap datatable">
                            <thead class="thead-light ">
                                <thead class="thead-light ">

                                    <tr>
                                        <th class="bg-primary text-white p-2 mb-2">#</th>
                                        <th class="bg-primary text-white p-2 mb-2">Date</th>
                                        <th class="bg-primary text-white p-2 mb-2">Raised By</th>
                                        <th class="bg-primary text-white p-2 mb-2">Assigned To</th>
                                        <th class="bg-primary text-white p-2 mb-2">Escalation</th>

                                        <th class="bg-primary text-white p-2 mb-2">Type of Escalation</th>
                                        <th class="bg-primary text-white p-2 mb-2">Source</th>
                                        {{-- <th class="bg-primary text-white p-2 mb-2">Feedback</th> --}}
                                        {{-- <th class="bg-primary text-white p-2 mb-2">Status</th> --}}
                                        <th class="bg-primary text-white p-2 mb-2">Action</th>
                                    </tr>
                                </thead>
                            <tbody>
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
                                                    <button class="btn  bg-primary-gradient btn-primary btn-effect"
                                                        onclick="openFollowupModal({{ $firstComplaint->complaintid }})">
                                                        Follow-up
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
                    <!--  End Filter -->
                    <div class="mb-4">
                        <h4 class="fw-bold mb-0 d-flex align-items-center"> <a href="patients.html"
                                class="text-dark">Follow-Up List
                            </a></h4>
                    </div>
                    <!--  Start Table -->
                    <div class="table-responsive">
                        <table class="table table-nowrap datatable">
                            <thead class="thead-light ">
                                <thead class="thead-light ">

                                    <tr>
                                        <th class="bg-primary text-white p-2 mb-2">#</th>
                                        <th class="bg-primary text-white p-2 mb-2">Date</th>
                                        <th class="bg-primary text-white p-2 mb-2">Raised By</th>
                                        <th class="bg-primary text-white p-2 mb-2">Assigned To</th>

                                        <th class="bg-primary text-white p-2 mb-2">Source</th>
                                        <th class="bg-primary text-white p-2 mb-2">Feedback</th>
                                        <th class="bg-primary text-white p-2 mb-2">Status</th>
                                    </tr>
                                </thead>
                            <tbody>
                                @forelse($ticket->complaints as $key => $complaint)
                                    <tr>
                                        <td class="text-dark">{{ $key + 1 }}</td>
                                        <!-- Date -->
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
                                        <td class="text-dark">
                                            <div class="icons-list-item d-flex gap-2">



                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center">No complaints found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!--  End Table -->

                </div>
            </div>
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
