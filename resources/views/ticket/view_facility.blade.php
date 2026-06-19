<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')

@section('content')
    <style>
        .td-page {
            padding: 4px 0 32px;
        }

        .td-crumb {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #22304d;
            text-decoration: none;
            margin-bottom: 14px;
        }

        .td-crumb:hover {
            color: #3741b0;
            text-decoration: none;
        }

        .td-card {
            background: #fff;
            border: 1px solid #e7e8eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .td-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid #e7e8eb;
        }

        .td-card-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .td-header-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: #eef0fb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3741b0;
            font-size: 17px;
            flex-shrink: 0;
        }

        .td-header-title {
            font-size: 17px;
            font-weight: 700;
            color: #132144;
            line-height: 1.2;
        }

        .td-header-sub {
            font-size: 13px;
            color: #8890a6;
            margin-top: 2px;
        }

        .td-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .badge-pending {
            background: #fef6e4;
            color: #8f5e00;
            border-color: #f0d48a;
        }

        .badge-progress {
            background: #e4f3fb;
            color: #125e80;
            border-color: #9fd5ed;
        }

        .badge-resolved {
            background: #e8f7ef;
            color: #1a7a3c;
            border-color: #a8dfc0;
        }

        .badge-closed {
            background: #f1f2f5;
            color: #4b5673;
            border-color: #d1d5e0;
        }

        .badge-rejected {
            background: #fdeaea;
            color: #b52020;
            border-color: #f5c0c0;
        }

        .td-section-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            padding: 14px 16px 8px;
            border-top: 1px solid #e7e8eb;
            margin-top: 4px;
        }

        .td-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #132144;
            margin: 0;
        }

        .td-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            border-top: 1px solid #e7e8eb;
        }

        .td-detail-cell {
            padding: 12px 16px;
            border-right: 1px solid #e7e8eb;
            border-bottom: 1px solid #e7e8eb;
        }

        .td-detail-cell:nth-child(3n) {
            border-right: none;
        }

        .td-detail-key {
            font-size: 12px;
            color: #8890a6;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .td-detail-val {
            font-size: 13px;
            color: #18284d;
            font-weight: 600;
        }

        .esc-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid transparent;
        }

        .esc-new {
            background: #e8f0fb;
            color: #2155a8;
            border-color: #b2cbf0;
        }

        .esc-replacement {
            background: #fef3e2;
            color: #8f5900;
            border-color: #f0d085;
        }

        .esc-service {
            background: #e8f7ef;
            color: #1a7a3c;
            border-color: #a8dfc0;
        }

        .td-table {
            width: 100%;
            border-collapse: collapse;
        }

        .td-table thead th {
            background: #3741b0;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 12px;
            text-align: left;
            white-space: nowrap;
            border: none;
        }

        .td-table tbody tr {
            border-bottom: 1px solid #e7e8eb;
            transition: background .12s;
        }

        .td-table tbody tr:last-child {
            border-bottom: none;
        }

        .td-table tbody tr:hover {
            background: #f7f8fc;
        }

        .td-table tbody td {
            padding: 10px 12px;
            font-size: 13px;
            color: #4f5d7c;
            vertical-align: middle;
            border-color: #e7e8eb;
        }

        .td-table tbody td.td-bold {
            color: #18284d;
            font-weight: 700;
        }

        .td-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .td-pill-success {
            background: #e8f7ef;
            color: #1a7a3c;
            border-color: #a8dfc0;
        }

        .td-pill-danger {
            background: #fdeaea;
            color: #b52020;
            border-color: #f5c0c0;
        }

        .td-pill-warning {
            background: #fef6e4;
            color: #8f5e00;
            border-color: #f0d48a;
        }

        .td-pill-info {
            background: #e4f3fb;
            color: #125e80;
            border-color: #9fd5ed;
        }

        .td-pill-secondary {
            background: #f1f2f5;
            color: #4b5673;
            border-color: #d1d5e0;
        }

        .td-footer {
            text-align: center;
            font-size: 12px;
            color: #6f7790;
            padding: 20px 0 10px;
        }

        /* Modal */
        .ap-modal-header {
            background: #3741b0;
            padding: 14px 20px;
            border-bottom: none;
            border-radius: 6px 6px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
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

        .ap-modal-header .btn-close {
            filter: invert(1) brightness(2);
            opacity: .8;
        }

        .ap-modal-body {
            padding: 20px 20px 10px;
        }

        .ap-field-label {
            font-size: 13px;
            font-weight: 700;
            color: #18284d;
            margin-bottom: 7px;
            display: block;
        }

        .ap-field-label .req {
            color: #e03535;
            margin-left: 2px;
        }

        .ap-textarea {
            width: 100%;
            font-size: 13px;
            color: #18284d;
            border: 1px solid #d0d3de;
            border-radius: 5px;
            padding: 8px 10px;
            outline: none;
            resize: vertical;
            transition: border-color .15s;
            font-family: inherit;
        }

        .ap-textarea:focus {
            border-color: #3741b0;
            box-shadow: 0 0 0 3px rgba(55, 65, 176, .10);
        }

        .ap-textarea::placeholder {
            color: #aab0bf;
        }

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

        @media (max-width:991.98px) {
            .page-wrapper {
                margin-left: 0;
            }

            .sidebar {
                left: -230px;
            }

            .td-card-header {
                flex-wrap: wrap;
            }

            .td-detail-cell:nth-child(3n) {
                border-right: 1px solid #e7e8eb;
            }

            .td-detail-cell:nth-child(2n) {
                border-right: none;
            }

            .td-detail-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width:575.98px) {
            .td-detail-grid {
                grid-template-columns: 1fr;
            }

            .td-detail-cell {
                border-right: none !important;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="content td-page">

            <a href="{{ route('tickets') }}" class="td-crumb">
                <i class="ti ti-chevron-left"></i> Back to Tickets
            </a>

            @php
                $st = $facility->status ?? 'Pending';
                $badgeCls = match ($st) {
                    'In Progress' => 'badge-progress',
                    'Resolved' => 'badge-resolved',
                    'Closed' => 'badge-closed',
                    'Rejected' => 'badge-rejected',
                    default => 'badge-pending',
                };
                $pillCls = match ($st) {
                    'In Progress' => 'td-pill-info',
                    'Resolved' => 'td-pill-success',
                    'Closed' => 'td-pill-secondary',
                    'Rejected' => 'td-pill-danger',
                    default => 'td-pill-warning',
                };
            @endphp

            <div class="td-card">

                {{-- ── Card Header ── --}}
                <div class="td-card-header">
                    <div class="td-card-header-left">
                        <div class="td-header-icon">
                            <i class="ti ti-building"></i>
                        </div>
                        <div>
                            <div class="td-header-title">Facility Request Details</div>
                            <div class="td-header-sub">
                                #TKT{{ $ticket->ticketId ?? '—' }}
                                &nbsp;·&nbsp;
                                Raised {{ $ticket->CreatedDate ? date('d M Y', strtotime($ticket->CreatedDate)) : '—' }}
                            </div>
                        </div>
                    </div>
                    <span class="td-status-badge {{ $badgeCls }}">{{ $st }}</span>
                </div>

                {{-- ── Ticket Details Table ── --}}
                <div class="td-section-row">
                    <div class="td-section-title">Ticket Details</div>
                </div>
                <div class="table-responsive">
                    <table class="td-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department</th>
                                <th>Facility Category</th>
                                <th>Issue Category</th>
                                <th>Request Type</th>
                                <th>Description</th>
                                <th>Status</th>
                                @if ($canApprove)
                                    <th>Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="td-bold">1</td>
                                <td>{{ $ticket->department->DepartmentName ?? '—' }}</td>
                                <td>{{ $facility->facilityCategory->name ?? '—' }}</td>
                                <td>{{ $facility->category->category_name ?? '—' }}</td>
                                <td>
                                    @if ($isNewRequest)
                                        <span class="esc-chip esc-new"><i class="ti ti-plus" style="font-size:11px;"></i>
                                            New Request</span>
                                    @elseif($isReplacement)
                                        <span class="esc-chip esc-replacement"><i class="ti ti-refresh"
                                                style="font-size:11px;"></i> Replacement</span>
                                    @else
                                        <span class="esc-chip esc-service"><i class="ti ti-tool"
                                                style="font-size:11px;"></i> Service</span>
                                    @endif
                                </td>
                                <td>{{ $facility->description ?? '—' }}</td>
                                <td><span class="td-pill {{ $pillCls }}">{{ $st }}</span></td>
                                @if ($canApprove)
                                    <td>
                                        <button type="button" class="btn btn-icon btn-outline-primary"
                                            onclick="openFacilityModal({{ $facility->id }})">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                    </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- ── Ticket Info Grid ── --}}
                <div class="td-section-row">
                    <div class="td-section-title">
                        <i class="ti ti-info-circle" style="margin-right:6px;color:#3741b0;"></i>
                        Ticket Info
                    </div>
                </div>
                <div class="td-detail-grid">
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Raised By</div>
                        <div class="td-detail-val">
                            {{ $facility->createdBy->FullName ?? ($facility->createdBy->UserName ?? '—') }}
                        </div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Branch / Location</div>
                        <div class="td-detail-val">
                            {{ $ticket->location->LocationName ?? ($ticket->Branch ?? '—') }}
                        </div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Raised On</div>
                        <div class="td-detail-val">
                            {{ $ticket->CreatedDate ? date('d M Y, h:i A', strtotime($ticket->CreatedDate)) : '—' }}
                        </div>
                    </div>
                    @if ($facility->reviewed_by)
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Reviewed By</div>
                            <div class="td-detail-val">
                                {{ $facility->reviewedBy->FullName ?? ($facility->reviewedBy->UserName ?? '—') }}
                            </div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Reviewed At</div>
                            <div class="td-detail-val">
                                {{ $facility->reviewed_at ? $facility->reviewed_at->format('d M Y, h:i A') : '—' }}
                            </div>
                        </div>
                    @endif
                    @if ($facility->remarks)
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Remarks</div>
                            <div class="td-detail-val">{{ $facility->remarks }}</div>
                        </div>
                    @endif
                </div>

                {{-- ── Client Details ── --}}
                @if ($ticket->customer)
                    <div class="td-section-row">
                        <div class="td-section-title">
                            <i class="ti ti-user-circle" style="margin-right:6px;color:#3741b0;"></i>
                            Client Details
                        </div>
                    </div>
                    <div class="td-detail-grid">
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Name</div>
                            <div class="td-detail-val">{{ $ticket->customer->FirstName ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Mobile</div>
                            <div class="td-detail-val">{{ $ticket->customer->Mobile ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Email</div>
                            <div class="td-detail-val">{{ $ticket->customer->EMail ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Reg No.</div>
                            <div class="td-detail-val">{{ $ticket->customer->RegistrationNo ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Gender</div>
                            <div class="td-detail-val">{{ $ticket->customer->Sex ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Branch</div>
                            <div class="td-detail-val">{{ $ticket->location->LocationName ?? '—' }}</div>
                        </div>
                    </div>
                @endif

                {{-- ── Status History ── --}}
                <div class="td-section-row">
                    <div class="td-section-title">Status History</div>
                </div>
                <div class="table-responsive">
                    <table class="td-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Changed By</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statusHistory as $key => $h)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $h->changedBy ?? '—' }}</td>
                                    <td>{{ ucwords(strtolower($h->action ?? '—')) }}</td>
                                    <td>{{ $h->remarks && $h->remarks !== '-' ? $h->remarks : '—' }}</td>
                                    <td>{{ $h->changedAt ? date('d-m-Y h:i A', strtotime($h->changedAt)) : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No history found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="height:8px;"></div>
            </div>{{-- /td-card --}}

            {{-- <div class="td-footer">Powered by Vecura &nbsp;·&nbsp; All rights reserved</div> --}}

        </div>
    </div>

    {{-- ── Update Status Modal ── --}}
    @if ($canApprove)
    <div class="modal fade" id="facilityStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(55,65,176,.18);">
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title">
                        <i class="ti ti-clipboard-check" style="font-size:17px;"></i>
                        Update Facility Ticket
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <input type="hidden" id="facility_ticket_id">
                    <div class="mb-3">
                        <label class="ap-field-label">Status <span class="req">*</span></label>
                        <select id="facility_status" class="form-control">
                            <option value="">Select Status</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Resolved">Resolved</option>
                            <option value="Closed">Closed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Remarks <span class="req">*</span></label>
                        <textarea id="facility_remarks" class="ap-textarea" rows="3" placeholder="Enter your remarks or reason…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit" id="facility_submit_btn"
                        onclick="submitFacilityStatus()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        function openFacilityModal(id) {
            $('#facility_ticket_id').val(id);
            $('#facility_status').val('');
            $('#facility_remarks').val('');
            new bootstrap.Modal(document.getElementById('facilityStatusModal')).show();
        }

        function submitFacilityStatus() {
            const id = $('#facility_ticket_id').val();
            const status = $('#facility_status').val();
            const remarks = $('#facility_remarks').val().trim();

            if (!status) {
                Swal.fire('Warning', 'Please select a status', 'warning');
                return;
            }
            if (!remarks) {
                Swal.fire('Warning', 'Please enter remarks', 'warning');
                return;
            }

            $('#facility_submit_btn').prop('disabled', true);

            fetch(`/facility-ticket/${id}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    body: JSON.stringify({
                        status,
                        remarks
                    }),
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire({
                                icon: 'success',
                                title: data.message || 'Status updated!',
                                timer: 1500,
                                showConfirmButton: false
                            })
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Something went wrong.', 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Network error. Please try again.', 'error'))
                .finally(() => $('#facility_submit_btn').prop('disabled', false));
        }
    </script>
@endsection
