<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content">

            <!-- page header start -->
            <div class="mb-4">
                <h6 class=" mb-0 d-flex align-items-center"> <a href="patients.html" class="text-dark"> <i
                            class="ti ti-chevron-left me-1"></i>View Complaints</a></h6>
            </div>
            <!-- page header end -->


            <div class=" ">

                <!-- TOP ACTIONS -->
                <div class="d-flex justify-content-end gap-2 mb-2">
                    <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-dark">Back</a>
                    <button onclick="window.print()" class="btn btn-sm btn-primary">Print View</button>
                </div>

                <!-- HEADER TABLE (Customer Info) -->
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

                <!-- COMPLAINT DETAILS TABLE -->
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
                                <tr class="text-dark">

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
                                    <td colspan="6" class="text-center text-muted fw-semibold">
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
