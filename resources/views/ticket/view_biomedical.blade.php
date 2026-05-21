{{-- =====================================================================
     FILE: resources/views/ticket/view_biomedical.blade.php
     Controller passes:
       $ticket        – IssueTicket (sqlsrv)
       $biomedical    – BiomedicalTicket (with category, issue, machine, createdBy)
       $machineIssues – Collection<MachineIssueMaster>
       $statusHistory – Collection<BiomedicalTicketHistory>
       $isNewRequest  – bool
       $isReplacement – bool
       $isService     – bool
       $canApprove    – bool  (Admin + Pending)
===================================================================== --}}
<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')

@section('content')
    <style>
        /* ── Reset & Base ────────────────────────────────────────── */
        .td-page {
            padding: 4px 0 32px;
        }

        /* ── Breadcrumb ──────────────────────────────────────────── */
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

        /* ── Outer card ──────────────────────────────────────────── */
        .td-card {
            background: #fff;
            border: 1px solid #e7e8eb;
            border-radius: 6px;
            overflow: hidden;
        }

        /* ── Card header ─────────────────────────────────────────── */
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

        /* ── Status badge ────────────────────────────────────────── */
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

        .badge-approved {
            background: #e8f7ef;
            color: #1a7a3c;
            border-color: #a8dfc0;
        }

        .badge-rejected {
            background: #fdeaea;
            color: #b52020;
            border-color: #f5c0c0;
        }

        /* ── Meta strip ──────────────────────────────────────────── */
        .td-meta-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            border-bottom: 1px solid #e7e8eb;
        }

        .td-meta-item {
            padding: 14px 16px;
            border-right: 1px solid #e7e8eb;
            display: flex;
            gap: 11px;
            align-items: flex-start;
        }

        .td-meta-item:last-child {
            border-right: none;
        }

        .td-meta-icon-wrap {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f3f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b738a;
            flex-shrink: 0;
            font-size: 16px;
        }

        .td-meta-label {
            font-size: 13px;
            font-weight: 700;
            color: #18284d;
            margin-bottom: 2px;
        }

        .td-meta-value {
            font-size: 13px;
            color: #737b90;
        }

        /* ── Escalation type chip ────────────────────────────────── */
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

        /* ── Section title ───────────────────────────────────────── */
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

        /* ── Detail grid (key-value) ─────────────────────────────── */
        .td-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 0;
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

        /* ── Issue tags ──────────────────────────────────────────── */
        .issue-tag {
            display: inline-block;
            background: #f3f4f8;
            color: #4b5673;
            border: 1px solid #d1d5e0;
            border-radius: 4px;
            font-size: 12px;
            padding: 2px 8px;
            margin: 2px 2px 0 0;
        }

        /* ── Comments box ────────────────────────────────────────── */
        .td-comments-box {
            background: #f8f9fc;
            border: 1px solid #e7e8eb;
            border-radius: 5px;
            padding: 12px 14px;
            font-size: 13px;
            color: #4f5d7c;
            line-height: 1.6;
            margin: 0 16px 16px;
        }

        /* ── Table ───────────────────────────────────────────────── */
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

        /* ── Status pills ────────────────────────────────────────── */
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

        /* ── Approve button ──────────────────────────────────────── */
        .td-btn-approve {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 14px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            background: #e8f7ef;
            color: #1a7a3c;
            border: 1px solid #a8dfc0;
            transition: opacity .15s;
        }

        .td-btn-approve:hover {
            opacity: .82;
        }

        /* ── Footer ──────────────────────────────────────────────── */
        .td-footer {
            text-align: center;
            font-size: 12px;
            color: #6f7790;
            padding: 20px 0 10px;
        }

        /* ════════════════════════════════════════════════════════════
                                                                                                                                                                                                                                                                                                                                                                                                                   MODAL STYLES
                                                                                                                                                                                                                                                                                                                                                                                                                ════════════════════════════════════════════════════════════ */
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

        .ap-info-strip {
            background: #f3f4f8;
            border-radius: 5px;
            padding: 10px 14px;
            margin-bottom: 16px;
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }

        .ap-info-item {
            font-size: 13px;
            color: #6b748a;
        }

        .ap-info-item strong {
            color: #18284d;
            font-weight: 700;
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

        .ap-status-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 4px;
        }

        .ap-status-opt {
            border: 1px solid #d0d3de;
            border-radius: 6px;
            padding: 11px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            font-size: 13px;
            font-weight: 600;
            color: #18284d;
            position: relative;
            user-select: none;
        }

        .ap-status-opt input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .ap-opt-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #c0c6d4;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: border-color .15s;
        }

        .ap-opt-dot::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: transparent;
            transition: background .15s;
        }

        .ap-opt-icon {
            font-size: 18px;
            color: #8890a6;
            transition: color .15s;
        }

        .ap-opt-text {
            flex: 1;
        }

        .ap-status-opt.sel-approved {
            border-color: #1a7a3c;
            background: #f0fbf5;
        }

        .ap-status-opt.sel-approved .ap-opt-dot {
            border-color: #1a7a3c;
        }

        .ap-status-opt.sel-approved .ap-opt-dot::after {
            background: #1a7a3c;
        }

        .ap-status-opt.sel-approved .ap-opt-icon {
            color: #1a7a3c;
        }

        .ap-status-opt.sel-rejected {
            border-color: #b52020;
            background: #fff5f5;
        }

        .ap-status-opt.sel-rejected .ap-opt-dot {
            border-color: #b52020;
        }

        .ap-status-opt.sel-rejected .ap-opt-dot::after {
            background: #b52020;
        }

        .ap-status-opt.sel-rejected .ap-opt-icon {
            color: #b52020;
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

        .ap-error {
            font-size: 12px;
            color: #b52020;
            margin-top: 5px;
            display: none;
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

        /* ── Responsive ──────────────────────────────────────────── */
        @media (max-width:991.98px) {
            .page-wrapper {
                margin-left: 0;
            }

            .sidebar {
                left: -230px;
            }

            .td-meta-strip {
                grid-template-columns: repeat(2, 1fr);
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
            .td-meta-strip {
                grid-template-columns: 1fr;
            }

            .td-meta-item {
                border-right: none;
                border-bottom: 1px solid #e7e8eb;
            }

            .td-meta-item:last-child {
                border-bottom: none;
            }

            .ap-status-row {
                grid-template-columns: 1fr;
            }

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

            {{-- ── Breadcrumb ─────────────────────────────────── --}}
            <a href="{{ route('tickets') }}" class="td-crumb">
                <i class="ti ti-chevron-left"></i> Back to Tickets
            </a>

            {{-- ── Main card ──────────────────────────────────── --}}
            <div class="td-card">

                {{-- ── Card Header ────────────────────────────── --}}
                <div class="td-card-header">
                    <div class="td-card-header-left">
                        <div class="td-header-icon">
                            <i class="ti ti-heart-rate-monitor"></i>
                        </div>
                        <div>
                            <div class="td-header-title">BioMedical Request Details</div>
                            <div class="td-header-sub">
                                #TKT{{ $ticket->ticketId ?? '—' }}
                                &nbsp;·&nbsp;
                                Raised {{ $ticket->CreatedDate ? date('d M Y', strtotime($ticket->CreatedDate)) : '—' }}
                            </div>
                        </div>
                    </div>
                </div>
                {{-- ── Meta Strip ─────────────────────────────── --}}
                {{-- <div class="td-meta-strip">

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-building-hospital"></i></div>
                        <div>
                            <div class="td-meta-label">Department</div>
                            <div class="td-meta-value">{{ $ticket->department->DepartmentName ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-tag"></i></div>
                        <div>
                            <div class="td-meta-label">Category</div>
                            <div class="td-meta-value">{{ $biomedical->category->category_name ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-arrows-exchange"></i></div>
                        <div>
                            <div class="td-meta-label">Type of Request</div>
                            <div class="td-meta-value">
                                @if ($isNewRequest)
                                    <span class="esc-chip esc-new"><i class="ti ti-plus" style="font-size:11px;"></i> New
                                        Request</span>
                                @elseif($isReplacement)
                                    <span class="esc-chip esc-replacement"><i class="ti ti-refresh"
                                            style="font-size:11px;"></i> Replacement</span>
                                @elseif($isService)
                                    <span class="esc-chip esc-service"><i class="ti ti-tool" style="font-size:11px;"></i>
                                        Service Request</span>
                                @else
                                    {{ $biomedical->issue->EscalationName ?? '—' }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-device-desktop"></i></div>
                        <div>
                            <div class="td-meta-label">Machine</div>
                            <div class="td-meta-value">{{ $biomedical->machine->MachineName ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-user"></i></div>
                        <div>
                            <div class="td-meta-label">Raised By</div>
                            <div class="td-meta-value">{{ $biomedical->createdBy->UserName ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-map-pin"></i></div>
                        <div>
                            <div class="td-meta-label">Branch / Location</div>
                            <div class="td-meta-value">{{ $ticket->location->LocationName ?? ($ticket->Branch ?? '—') }}
                            </div>
                        </div>
                    </div>

                </div> --}}
                <div class="td-section-row">
                    <div class="td-section-title">Ticket History</div>
                </div>
                <div class="table-responsive">
                    <table class="td-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Department</th>
                                <th>Category</th>
                                <th>Type of Escalation</th>
                                <th>Feedback</th>
                                <th>Request Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($biomedical)
                                <tr>
                                    <td class="td-bold">1</td>
                                    <td>{{ $biomedical->department->DepartmentName ?? '—' }}</td>
                                    <td>{{ $biomedical->category->category_name ?? '—' }}</td>
                                    <td>{{ $biomedical->issue->EscalationName ?? '—' }}</td>
                                    <td>{{ $biomedical->comments ?? '—' }}</td>
                                    <td>
                                        @if ($isNewRequest)
                                            <span class="esc-chip esc-new"><i class="ti ti-plus"
                                                    style="font-size:11px;"></i> New
                                                Request</span>
                                        @elseif($isReplacement)
                                            <span class="esc-chip esc-replacement"><i class="ti ti-refresh"
                                                    style="font-size:11px;"></i> Replacement</span>
                                        @elseif($isService)
                                            <span class="esc-chip esc-service"><i class="ti ti-tool"
                                                    style="font-size:11px;"></i>
                                                Service Request</span>
                                        @else
                                            {{ $biomedical->issue->EscalationName ?? '—' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($biomedical->status === 'Pending')
                                            <span class="badge badge-outline-warning">Pending</span>
                                        @elseif ($biomedical->status === 'InProgress')
                                            <span class="badge badge-outline-info">InProgress</span>
                                        @elseif($biomedical->status === 'Resolved')
                                            <span class="badge badge-outline-success">Resolved</span>
                                        @elseif($biomedical->status === 'Closed')
                                            <span class="badge badge-outline-secondary">Closed</span>
                                        @else
                                            <span class="badge badge-outline-secondary">
                                                {{ $biomedical->status ?? '—' }}
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($biomedical->status !== 'Closed')
                                            <button type="button" class="btn bg-primary-gradient btn-primary btn-effect"
                                                onclick="openBiomedicalModal(
                                                {{ $biomedical->id }},
                                            )">
                                                <i class="ti ti-circle-check"></i>
                                                Update

                                            </button>
                                        @endif

                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="7" style="text-align:center;color:#8890a6;">
                                        No history available
                                    </td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="td-section-row">
                    <div class="td-section-title">
                        <i class="ti ti-device-desktop" style="margin-right:6px;color:#3741b0;"></i>
                        Machine Details
                    </div>
                </div>
                <div class="td-detail-grid">
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Serial No</div>
                        <div class="td-detail-val">{{ $biomedical->machine->SerialNo ?? '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Model No</div>
                        <div class="td-detail-val">{{ $biomedical->machine->ModelNo ?? '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Machine Name</div>
                        <div class="td-detail-val">{{ $biomedical->machine->MachineName ?? '—' }}</div>
                    </div>
                    @if ($isService)
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Issue Type</div>
                            <div class="td-detail-val">{{ $biomedical->machineIssueType ?? '—' }}</div>
                        </div>
                    @endif

                </div>
                {{-- @if ($isNewRequest)
                    <div class="td-section-row">
                        <div class="td-section-title">
                            <i class="ti ti-plus-circle" style="margin-right:6px;color:#3741b0;"></i>
                            New Equipment Request
                        </div>
                    </div>
                    <div class="td-detail-grid">
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Requested Machine / Equipment</div>
                            <div class="td-detail-val">{{ $biomedical->machine->MachineName ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Purpose / Justification</div>
                            <div class="td-detail-val">{{ $biomedical->comments ?? '—' }}</div>
                        </div>
                    </div>
                @endif --}}

                @if ($isReplacement)
                    <div class="td-section-row">
                        <div class="td-section-title">
                            <i class="ti ti-refresh" style="margin-right:6px;color:#3741b0;"></i>
                            Replacement Request
                        </div>
                    </div>
                    <div class="td-detail-grid">
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Machine to Replace</div>
                            <div class="td-detail-val">{{ $biomedical->machine->MachineName ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Reason for Replacement</div>
                            <div class="td-detail-val">{{ $biomedical->comments ?? '—' }}</div>
                        </div>
                    </div>
                @endif

                @if ($isService)
                    <div class="td-section-row">
                        <div class="td-section-title">
                            <i class="ti ti-tool" style="margin-right:6px;color:#3741b0;"></i>
                            Service List
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Issues </th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($machineIssues as $key => $issue)
                                    <tr>
                                        <td class="td-bold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $issue->IssuesName ?? '—' }}
                                        </td>
                                        <td>
                                            {{ $issue->Type ?? '—' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            style="text-align:center; padding:28px; color:#8890a6; font-size:13px;">
                                            No machine issues/spares linked.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="td-section-row">
                    <div class="td-section-title">Status History</div>
                </div>
                <div class="table-responsive ">
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
                            @forelse($actionHistory as $key => $history)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        {{ $history->changedBy }}
                                    </td>
                                    <td>
                                        {{ $history->status }}
                                    </td>

                                    <td>
                                        {{ $history->remarks }}
                                    </td>
                                    <td>
                                        {{ $history->changedAt ? date('d-m-Y h:i A', strtotime($history->changedAt)) : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        No history found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                <div style="height:8px;"></div>
            </div>{{-- /td-card --}}

            <div class="td-footer">Powered by Vecura &nbsp;·&nbsp; All rights reserved</div>
        </div>
    </div>


    <div class="modal fade" id="candidateModal" tabindex="-1" aria-labelledby="candidateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content"
                style="border-radius:6px;
                   border:none;
                   overflow:hidden;
                   box-shadow:0 8px 32px rgba(55,65,176,.18);">
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title" id="candidateModalLabel">
                        <i class="ti ti-clipboard-check" style="font-size:17px;"></i>
                        Biomedical Ticket Update
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <input type="hidden" id="biomedical_ticket_id">

                    <div class="mb-3">
                        <label class="ap-field-label">
                            Status
                            <span class="req">*</span>
                        </label>
                        <select id="biomedical_status" class="form-control">
                            <option value="">
                                Select Status
                            </option>
                            <option value="InProgress">
                                InProgress
                            </option>
                            <option value="Resolved">
                                Resolved
                            </option>
                            <option value="Closed">
                                Closed
                            </option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">
                            Remarks
                            <span class="req">*</span>
                        </label>
                        <textarea id="biomedical_remarks" class="ap-textarea" rows="3" placeholder="Enter your remarks or reason…"></textarea>
                    </div>
                </div>
                {{-- Footer --}}
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="ap-btn-submit" id="candidate_submit_btn"
                        onclick="submitBiomedicalStatus()">
                        <i class="ti ti-send" style="font-size:13px;"></i>
                        Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        let isBiomedicalSubmitting = false;

        function openBiomedicalModal(ticketId) {
            $('#biomedical_ticket_id').val(ticketId);
            $('#biomedical_status').val('');
            $('#biomedical_remarks').val('');
            new bootstrap.Modal(
                document.getElementById('candidateModal')
            ).show();
        }

        function submitBiomedicalStatus() {

            if (isBiomedicalSubmitting) {
                return;
            }
            let biomedicalId = $('#biomedical_ticket_id').val();
            let status = $('#biomedical_status').val();
            let remarks = $('#biomedical_remarks').val();
            if (status == '') {
                Swal.fire(
                    'Warning',
                    'Please select status',
                    'warning'
                );

                return;
            }

            if (remarks.trim() == '') {

                Swal.fire(
                    'Warning',
                    'Please enter remarks',
                    'warning'
                );
                return;
            }
            isBiomedicalSubmitting = true;
            $('#candidate_submit_btn').prop('disabled', true);
            $.ajax({
                url: "/biomedical-ticket/" + biomedicalId + "/update-status",
                type: "POST",
                data: {
                    status: status,
                    remarks: remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                },

                error: function(xhr) {

                    isBiomedicalSubmitting = false;

                    $('#candidate_submit_btn').prop('disabled', false);

                    let errorMessage = 'Something went wrong';

                    if (xhr.responseJSON && xhr.responseJSON.message) {

                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire(
                        'Error',
                        errorMessage,
                        'error'
                    );

                }

            });

        }
    </script>

@endsection
