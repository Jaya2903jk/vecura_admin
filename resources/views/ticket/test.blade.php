<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content pb-0">

        <!-- Page Header -->
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 pb-3">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">View Ticket</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Tickets</a></li>
                    <li class="breadcrumb-item active">View Ticket</li>
                </ol>
            </div>
        </div>

        <!-- ── ROW 1: Staff Details Card ─────────────────── -->
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card mb-0">
                    <div class="card-header border-bottom py-3">
                        <h5 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-user-circle text-primary"></i>
                            Staff Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="p-3 rounded-2 bg-light h-100">
                                    <p class="text-muted small mb-1">Staff Name</p>
                                    <p class="fw-semibold text-dark mb-0">{{ $ticket->staff_name ?? 'Aishwarya' }}</p>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="p-3 rounded-2 bg-light h-100">
                                    <p class="text-muted small mb-1">Staff Email</p>
                                    <p class="fw-semibold text-dark mb-0">{{ $ticket->staff_email ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="p-3 rounded-2 bg-light h-100">
                                    <p class="text-muted small mb-1">Branch</p>
                                    <p class="fw-semibold text-dark mb-0">
                                        <span class="badge bg-primary-subtle text-primary fw-semibold">
                                            {{ $ticket->branch ?? 'PDY' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="p-3 rounded-2 bg-light h-100">
                                    <p class="text-muted small mb-1">Mobile No</p>
                                    <p class="fw-semibold text-dark mb-0">{{ $ticket->mobile ?? '-' }}</p>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="p-3 rounded-2 bg-light h-100">
                                    <p class="text-muted small mb-1">Designation</p>
                                    <p class="fw-semibold text-dark mb-0">{{ $ticket->designation ?? 'Nutritionist' }}</p>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                                <div class="p-3 rounded-2 bg-light h-100">
                                    <p class="text-muted small mb-1">Reg No.</p>
                                    <p class="fw-semibold text-dark mb-0">
                                        <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                            {{ $ticket->reg_no ?? 'HEC-1341' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                        </div><!-- /row -->
                    </div>
                </div>
            </div>
        </div>

        <!-- ── ROW 2: Ticket History + Status History ─────── -->
        <div class="row g-3 mb-4">

            <!-- Ticket History -->
            <div class="col-xl-7 col-lg-12">
                <div class="card h-100 mb-0">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-ticket text-warning"></i>
                            Ticket History
                        </h5>
                        <span class="badge bg-warning-subtle text-warning fw-semibold">
                            {{ count($ticketHistory ?? []) }} Record(s)
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:40px;">#</th>
                                        <th>Date</th>
                                        <th>Category</th>
                                        <th>Type of Escalation</th>
                                        <th>From</th>
                                        <th>To</th>
                                        <th class="text-center">Status</th>
                                        <th>Feedback</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ticketHistory ?? [] as $index => $history)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($history['date'])->format('d M Y') }}</td>
                                        <td>{{ $history['category'] ?? '-' }}</td>
                                        <td>{{ $history['type_of_escalation'] ?? '-' }}</td>
                                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($history['from'])->format('Y-m-d') }}</td>
                                        <td class="text-nowrap">{{ \Carbon\Carbon::parse($history['to'])->format('Y-m-d') }}</td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = match(strtolower($history['status'] ?? '')) {
                                                    'inprogress'  => 'bg-warning-subtle text-warning',
                                                    'resolved'    => 'bg-success-subtle text-success',
                                                    'closed'      => 'bg-secondary-subtle text-secondary',
                                                    'open'        => 'bg-info-subtle text-info',
                                                    'rejected'    => 'bg-danger-subtle text-danger',
                                                    default       => 'bg-light text-muted',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} fw-semibold text-nowrap">
                                                {{ $history['status'] ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if(!empty($history['feedback']))
                                                <span class="text-dark">{{ $history['feedback'] }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="javascript:void(0);"
                                                   class="btn btn-sm btn-icon btn-soft-primary"
                                                   title="View">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                   class="btn btn-sm btn-icon btn-soft-success"
                                                   title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    {{-- Static fallback row for preview --}}
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-nowrap">16 Jun 2026</td>
                                        <td>Employee Issues</td>
                                        <td>Leave request</td>
                                        <td class="text-nowrap">2026-05-14</td>
                                        <td class="text-nowrap">2026-05-15</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-subtle text-warning fw-semibold">InProgress</span>
                                        </td>
                                        <td><span class="text-dark">dddd</span></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-soft-primary" title="View"><i class="ti ti-eye"></i></a>
                                                <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-soft-success" title="Edit"><i class="ti ti-edit"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Status History -->
            <div class="col-xl-5 col-lg-12">
                <div class="card h-100 mb-0">
                    <div class="card-header border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="fw-semibold mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-history text-info"></i>
                            Ticket Status History
                        </h5>
                        <span class="badge bg-info-subtle text-info fw-semibold">
                            {{ count($statusHistory ?? []) }} Record(s)
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:40px;">#</th>
                                        <th>Date</th>
                                        <th class="text-center">Status</th>
                                        <th>Comment</th>
                                        <th>Updated By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($statusHistory ?? [] as $index => $status)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-nowrap" style="font-size:13px;">
                                            {{ \Carbon\Carbon::parse($status['date'])->format('d M Y H:i') }}
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $sc = match(strtolower($status['status'] ?? '')) {
                                                    'inprogress'  => 'bg-warning-subtle text-warning',
                                                    'resolved'    => 'bg-success-subtle text-success',
                                                    'closed'      => 'bg-secondary-subtle text-secondary',
                                                    'open'        => 'bg-info-subtle text-info',
                                                    'rejected'    => 'bg-danger-subtle text-danger',
                                                    default       => 'bg-light text-muted',
                                                };
                                            @endphp
                                            <span class="badge {{ $sc }} fw-semibold text-nowrap">
                                                {{ $status['status'] ?? '-' }}
                                            </span>
                                        </td>
                                        <td style="font-size:13px;">{{ $status['comment'] ?? '-' }}</td>
                                        <td style="font-size:13px;">
                                            <span class="d-flex align-items-center gap-1">
                                                <i class="ti ti-user-circle text-muted"></i>
                                                {{ $status['updated_by'] ?? 'Unknown User' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    {{-- Static fallback row for preview --}}
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td class="text-nowrap" style="font-size:13px;">13 May 2026 11:18</td>
                                        <td class="text-center">
                                            <span class="badge bg-warning-subtle text-warning fw-semibold">InProgress</span>
                                        </td>
                                        <td style="font-size:13px;">Ticket moved to InProgress</td>
                                        <td style="font-size:13px;">
                                            <span class="d-flex align-items-center gap-1">
                                                <i class="ti ti-user-circle text-muted"></i>
                                                Unknown User
                                            </span>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /row 2 -->

        @component('components.footer')
        @endcomponent

    </div>
</div>
@endsection
