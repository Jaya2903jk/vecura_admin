<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">
            <div class="mb-4">
                <h6 class="fw-bold mb-0 d-flex align-items-center"> <a href="patients.html" class="text-dark"> <i
                            class="ti ti-chevron-left me-1"></i>Refund Complaints Entry - REF123456</a></h6>
            </div>
            <div class="d-flex justify-content-end gap-2 mb-2">
                <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-dark">Back</a>
            </div>
            <div class="table-responsive mb-3">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th class="bg-primary text-white p-2 mb-2">Location Name</th>
                            <th class="bg-primary text-white p-2 mb-2">Registration No</th>
                            <th class="bg-primary text-white p-2 mb-2">Registration Date</th>
                            <th class="bg-primary text-white p-2 mb-2">Mobile</th>
                            <th class="bg-primary text-white p-2 mb-2">Customer Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $customer->location->LocationName ?? '-' }}</td>
                            <td>{{ $customer->RegistrationNo ?? '-' }}</td>
                            <td>{{ $customer->RegistrationDate ?? '-' }}</td>
                            <td>{{ $customer->Mobile ?? '-' }}</td>
                            <td>{{ $complaints->first()->CustomerName ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- <div class="container mt-3">
                <!-- page header end -->
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-primary text-white py-2">
                       Complaint
                    </div>

                    <div class="card-body py-3">
                        <form>

                            <div class="row g-2">

                                <!-- Left Column -->
                                <div class="col-md-6">

                                    <!-- Assign To -->
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Assign To</label>
                                        <select class="form-select form-select-sm">
                                            <option>Select</option>
                                            <option>Employee 1</option>
                                            <option>Employee 2</option>
                                        </select>
                                    </div>

                                    <!-- Source -->
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Source</label>
                                        <select class="form-select form-select-sm">
                                            <option>Call</option>
                                            <option>WhatsApp</option>
                                            <option>Email</option>
                                        </select>
                                    </div>

                                    <!-- Escalation -->
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Escalation</label>
                                        <select class="form-select form-select-sm">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </div>

                                </div>

                                <!-- Right Column -->
                                <div class="col-md-6">

                                    <!-- Escalation Type -->
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Escalation Type</label>
                                        <select class="form-select form-select-sm">
                                            <option>Level 1</option>
                                            <option>Level 2</option>
                                            <option>Critical</option>
                                        </select>
                                    </div>

                                    <!-- Call Status -->
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Call Status</label>
                                        <select class="form-select form-select-sm">
                                            <option>Pending</option>
                                            <option>InProgress</option>
                                            <option>Closed</option>
                                        </select>
                                    </div>

                                    <!-- Feedback -->
                                    <div class="mb-2">
                                        <label class="form-label small mb-1">Feedback</label>
                                        <textarea class="form-control form-control-sm" rows="3" placeholder="Enter feedback..."></textarea>
                                    </div>

                                </div>

                            </div>

                            <!-- Button -->
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-success btn-sm px-3">
                                    Save
                                </button>
                            </div>

                        </form>
                    </div>
                </div> --}}

            <div class="container mt-2">

                <!-- Header -->
                <h6 class="fw-bold mb-1">Complaint</h6>

                <!-- Outer Border Box -->
                <div class="border rounded p-2 bg-white">

                    <form>

                        <div class="row g-1">

                            <!-- Left Column -->
                            <div class="col-md-6">

                                <div class="mb-1">
                                    <label class="form-label small mb-1">Assign To</label>
                                    <select class="form-select form-select-sm">
                                        <option>Select</option>
                                        <option>Employee 1</option>
                                        <option>Employee 2</option>
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label small mb-1">Source</label>
                                    <select class="form-select form-select-sm">
                                        <option>Call</option>
                                        <option>WhatsApp</option>
                                        <option>Email</option>
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label small mb-1">Escalation</label>
                                    <select class="form-select form-select-sm">
                                        <option>No</option>
                                        <option>Yes</option>
                                    </select>
                                </div>

                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">

                                <div class="mb-1">
                                    <label class="form-label small mb-1">Escalation Type</label>
                                    <select class="form-select form-select-sm">
                                        <option>Level 1</option>
                                        <option>Level 2</option>
                                        <option>Critical</option>
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label small mb-1">Call Status</label>
                                    <select class="form-select form-select-sm">
                                        <option>Pending</option>
                                        <option>InProgress</option>
                                        <option>Closed</option>
                                    </select>
                                </div>

                                <div class="mb-1">
                                    <label class="form-label small mb-1">Feedback</label>
                                    <textarea class="form-control form-control-sm" rows="2" placeholder="Enter feedback..."></textarea>
                                </div>

                            </div>

                        </div>

                        <!-- Button -->
                        <div class="text-end mt-1">
                            <button type="button" class="btn btn-success btn-sm px-3">
                                Save
                            </button>
                        </div>

                    </form>

                </div>
                <br>
                <div class="table-responsive">
                    <table class="table table-borderless align-middle table-fixed">

                        <thead class="bg-light text-dark small ">
                            <tr>
                                <th>S.No</th>
                                <th>Complaint Date</th>
                                <th>Feedback</th>
                                <th>Created By</th>
                                <th>Assigned To</th>
                                <th>Call Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($complaints as $complaint)
                                <tr class="text-dark ">

                                    <td>{{ $loop->iteration }}</td>

                                    <td class="text-nowrap">
                                        {{ \Carbon\Carbon::parse($complaint->feedbackDate)->format('d-M-Y') }}
                                    </td>

                                    <td class="text-wrap" style="max-width: 300px;">
                                        {{ $complaint->feedback ?? '-' }}
                                    </td>

                                    <td class="text-wrap" style="max-width: 150px;">
                                        {{ $complaint->createdUser->FullName ?? '-' }}
                                    </td>

                                    <td class="text-wrap" style="max-width: 150px;">
                                        {{ $complaint->acceptedUser->FullName ?? '-' }}
                                    </td>

                                    <td>
                                        {{-- <span class="badge bg-warning text-dark"> --}}
                                        {{ $complaint->callStatus ?? 'Pending' }}
                                        {{-- </span> --}}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted ">
                                        No complaints found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>



        </div>


        <!-- tab content end -->

    </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endsection
