<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <style>
        /* ── Reset & Base ─────────────────────────────────── */
        .td-page {
            padding: 4px 0 32px;
        }

        /* ── Breadcrumb ───────────────────────────────────── */
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

        /* ── Outer card ───────────────────────────────────── */
        .td-card {
            background: #fff;
            border: 1px solid #e7e8eb;
            border-radius: 6px;
            overflow: hidden;
        }

        /* ── Card header ──────────────────────────────────── */
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

        .td-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #fff0f0;
            color: #b52020;
            border: 1px solid #fac5c5;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            padding: 4px 12px;
            white-space: nowrap;
        }

        .td-role-badge i {
            font-size: 13px;
        }

        /* ── Meta strip ───────────────────────────────────── */
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

        /* ── Section title ────────────────────────────────── */
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

        /* ── Table ────────────────────────────────────────── */
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

        /* ── Status pills ─────────────────────────────────── */
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

        /* ── Approve button in table ──────────────────────── */
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

        /* ── Footer ───────────────────────────────────────── */
        .td-footer {
            text-align: center;
            font-size: 12px;
            color: #6f7790;
            padding: 20px 0 10px;
        }

        /* ════════════════════════════════════════════════════
                                                                                                                                                                                                                                                                                                                                                                                                                                                       MODAL STYLES
                                                                                                                                                                                                                                                                                                                                                                                                                                                    ════════════════════════════════════════════════════ */
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

        /* Info strip */
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

        /* Field label */
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

        /* Radio cards */
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

        /* Selected states */
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

        /* Textarea */
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

        /* Validation error */
        .ap-error {
            font-size: 12px;
            color: #b52020;
            margin-top: 5px;
            display: none;
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

        /* ── Responsive ───────────────────────────────────── */
        @media (max-width: 991.98px) {
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
        }

        @media (max-width: 575.98px) {
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
        }
    </style>

    <div class="page-wrapper">
        <div class="content td-page">

            {{-- ── Breadcrumb ──────────────────────────────── --}}
            <a href="{{ route('tickets') }}" class="td-crumb">
                <i class="ti ti-chevron-left"></i> View Ticket
            </a>

            {{-- ── Main card ───────────────────────────────── --}}
            <div class="td-card">

                {{-- Header --}}
                <div class="td-card-header">
                    <div class="td-card-header-left">
                        <div class="td-header-icon">
                            <i class="ti ti-file-description"></i>
                        </div>
                        <div>
                            <div class="td-header-title">Manpower Request Details</div>
                            <div class="td-header-sub">
                                #TKT{{ $ticket->ticketId ?? '—' }}
                                &nbsp;·&nbsp;
                                Raised {{ $ticket->created_at ? date('d M Y', strtotime($ticket->created_at)) : '—' }}
                            </div>
                        </div>
                    </div>
                    <span class="td-role-badge">
                        <i class="ti ti-user-scan"></i>
                        {{ $manpower->designation ?? '—' }}
                    </span>
                </div>

                {{-- Meta strip --}}
                <div class="td-meta-strip">

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-users-group"></i></div>
                        <div>
                            <div class="td-meta-label">Vacancies</div>
                            <div class="td-meta-value">{{ $manpower->vacancies ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-briefcase"></i></div>
                        <div>
                            <div class="td-meta-label">Experience</div>
                            <div class="td-meta-value">{{ $manpower->experience ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-map-pin"></i></div>
                        <div>
                            <div class="td-meta-label">Work Location</div>
                            <div class="td-meta-value">{{ $manpower->workLocation ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-school"></i></div>
                        <div>
                            <div class="td-meta-label">Qualification</div>
                            <div class="td-meta-value">{{ $manpower->qualification ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-tools"></i></div>
                        <div>
                            <div class="td-meta-label">Skills</div>
                            <div class="td-meta-value">{{ $manpower->skills ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-gender-bigender"></i></div>
                        <div>
                            <div class="td-meta-label">Gender</div>
                            <div class="td-meta-value">{{ $manpower->gender ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-calendar-user"></i></div>
                        <div>
                            <div class="td-meta-label">Age Range</div>
                            <div class="td-meta-value">
                                {{ $manpower->ageMin ?? '—' }} – {{ $manpower->ageMax ?? '—' }} Yrs
                            </div>
                        </div>
                    </div>

                    <div class="td-meta-item">
                        <div class="td-meta-icon-wrap"><i class="ti ti-file-text"></i></div>
                        <div>
                            <div class="td-meta-label">Request Type</div>
                            <div class="td-meta-value">{{ $manpower->requestType ?? '—' }}</div>
                        </div>
                    </div>

                </div>

                {{-- Section: Ticket History --}}
                <div class="td-section-row">
                    <div class="td-section-title">Ticket History</div>
                </div>

                <div class="table-responsive">
                    <table class="td-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Department</th>
                                <th>Category</th>
                                <th>Type of Escalation</th>
                                <th>Feedback</th>
                                <th>JD</th>
                                <th>Admin Status</th>
                                <th>HR Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($workflowHistory as $key => $item)
                                <tr>
                                    <td class="td-bold">{{ $key + 1 }}</td>

                                    <td>
                                        {{ $item->created_at ? date('d-m-Y', strtotime($item->created_at)) : '—' }}</td>

                                    <td class="td-bold">{{ $item->department->DepartmentName ?? '—' }}</td>

                                    <td>{{ $item->category->category_name ?? '—' }}</td>

                                    <td>{{ $item->escalation->EscalationName ?? '—' }}</td>

                                    <td style="min-width:160px; max-width:200px;">{{ $item->remarks ?? '—' }}</td>

                                    {{-- <td style="min-width:180px; max-width:220px;">{{ $item->jobDescription ?? '—' }}</td> --}}
                                    <td class="text-dark" style="max-width:180px;">
                                        <div style="white-space: normal;">
                                            <small>{{ $item->jobDescription }}</small>
                                        </div>
                                    </td>
                                    {{-- Admin Status --}}
                                    <td>
                                        @if ($item->approvalStatus === 'Approved')
                                            <span class="badge badge-outline-success">Approved</span>
                                        @elseif ($item->approvalStatus === 'Rejected')
                                            <span class="badge badge-outline-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-outline-warning">Pending</span>
                                        @endif
                                    </td>


                                    {{-- HR Status --}}
                                    <td>
                                        @if ($item->recruitmentStatus)
                                            <span class="badge badge-outline-info">{{ $item->recruitmentStatus }}</span>
                                        @else
                                            <span class="badge badge-outline-secondary">Not started</span>
                                        @endif
                                    </td>


                                    <td style="min-width:180px;">

                                        {{-- PENDING --}}
                                        @if ($item->approvalStatus === 'Pending')
                                            {{-- ADMIN ONLY --}}
                                            @if (session('role_name') == 'Admin')
                                                <button type="button"
                                                    class="btn bg-primary-gradient btn-primary btn-effect"
                                                    onclick="openApproveModal(
                                                {{ $item->manpowerRequestId }},
                                                '{{ addslashes($item->department->DepartmentName ?? '') }}',
                                                '{{ addslashes($item->escalation->EscalationName ?? '') }}'
                                            )">

                                                    <i class="ti ti-circle-check"></i>
                                                    Update

                                                </button>
                                            @else
                                                <span style="color:#6b748a; font-size:13px;">
                                                    Awaiting admin review
                                                </span>
                                            @endif

                                            {{-- APPROVED --}}
                                        @elseif ($item->approvalStatus === 'Approved')
                                            {{-- HR SELF ASSIGN --}}
                                            @if (!$item->assigned_hr_id && $canSelfAssign)
                                                <button type="button"
                                                    class="btn bg-primary-gradient btn-primary btn-effect"
                                                    onclick="selfAssignModal(
                                                {{ $item->manpowerRequestId }},
                                            )">
                                                    <i class="ti ti-circle-check"></i>
                                                    Self Assign

                                                </button>
                                            @elseif ($item->assigned_hr_id)
                                                <button type="button"
                                                    class="btn bg-primary-gradient btn-primary btn-effect"
                                                    onclick="candidateModal(
                                                {{ $item->manpowerRequestId }},
                                            )">
                                                    <i class="ti ti-circle-check"></i>
                                                    Candidate

                                                </button>
                                            @endif
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10"
                                        style="text-align:center; padding:28px; color:#8890a6; font-size:13px;">
                                        No workflow history found.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                <br>
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

            </div>
            {{-- /td-card --}}
            <div class="td-footer">Powered by Vecura &nbsp;·&nbsp; All rights reserved</div>
        </div>
    </div>
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content"
                style="border-radius:6px; border:none; overflow:hidden; box-shadow:0 8px 32px rgba(55,65,176,.18);">

                {{-- Header --}}
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title" id="approveModalLabel">
                        <i class="ti ti-clipboard-check" style="font-size:17px;"></i>
                        Review Manpower Request
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <div class="ap-modal-body">

                    {{-- Info strip --}}
                    <div class="ap-info-strip">
                        <div class="ap-info-item">
                            Department:&nbsp;<strong id="ap_dept_display">—</strong>
                        </div>
                        <div class="ap-info-item">
                            Escalation:&nbsp;<strong id="ap_esc_display">—</strong>
                        </div>
                    </div>

                    <input type="hidden" id="ap_request_id">

                    {{-- Decision --}}
                    <div class="mb-3">
                        <label class="ap-field-label">
                            Decision <span class="req">*</span>
                        </label>
                        <div class="ap-status-row">

                            <label class="ap-status-opt" id="opt-approved" onclick="selectStatus('Approved')">
                                <input type="radio" name="ap_status" value="Approved" id="radio_approved">
                                <span class="ap-opt-dot"></span>
                                <i class="ti ti-circle-check ap-opt-icon"></i>
                                <span class="ap-opt-text">Approved</span>
                            </label>

                            <label class="ap-status-opt" id="opt-rejected" onclick="selectStatus('Rejected')">
                                <input type="radio" name="ap_status" value="Rejected" id="radio_rejected">
                                <span class="ap-opt-dot"></span>
                                <i class="ti ti-circle-x ap-opt-icon"></i>
                                <span class="ap-opt-text">Rejected</span>
                            </label>

                        </div>
                        <div class="ap-error" id="err_status">Please select a decision.</div>
                    </div>

                    {{-- Remarks --}}
                    <div class="mb-1">
                        <label class="ap-field-label" for="ap_remarks">
                            Remarks <span class="req">*</span>
                        </label>
                        <textarea id="ap_remarks" class="ap-textarea" rows="3" placeholder="Enter your remarks or reason…"></textarea>
                        <div class="ap-error" id="err_remarks">Remarks are required.</div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="ap-btn-submit" id="ap_submit_btn" onclick="submitApproval()">
                        <i class="ti ti-send" style="font-size:13px;"></i>
                        Submit
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="selfAssignModal" tabindex="-1" aria-labelledby="selfAssignModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content"
                style="border-radius:6px; border:none; overflow:hidden; box-shadow:0 8px 32px rgba(55,65,176,.18);">

                {{-- Header --}}
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title" id="selfAssignModalLabel">
                        <i class="ti ti-clipboard-check" style="font-size:17px;"></i>
                        Self Assign HR
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- Body --}}
                <div class="ap-modal-body">
                    <input type="hidden" id="self_request_id">

                    {{-- Decision --}}
                    <div class="mb-3">
                        <label class="ap-field-label">
                            Select Employee
                            <span class="req">*</span>
                        </label>
                        <select class="form-control" id="assign_employee">
                            <option value="">Select Employee</option>

                            @foreach ($employees as $emp)
                                <option value="{{ $emp->UserID }}">
                                    {{ $emp->FullName }} ({{ $emp->UserCode }})
                                </option>
                            @endforeach
                        </select>
                        <div class="ap-error" id="err_status">Please select a decision.</div>
                    </div>

                    {{-- Remarks --}}
                    <div class="mb-1">
                        <label class="ap-field-label" for="ap_remarks">
                            Remarks <span class="req">*</span>
                        </label>
                        <textarea id="assign_remarks" class="ap-textarea" rows="3" placeholder="Enter your remarks or reason…"></textarea>
                        <div class="ap-error" id="err_remarks">Remarks are required.</div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="button" class="ap-btn-submit" id="ap_submit_btn" onclick="submitSelfAssign()">
                        <i class="ti ti-send" style="font-size:13px;"></i>
                        Submit
                    </button>
                </div>

            </div>
        </div>
    </div>
    <!-- Modal -->

    <div class="modal fade" id="candidateModal" tabindex="-1" aria-labelledby="candidateModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">

            <div class="modal-content"
                style="border-radius:6px;
                   border:none;
                   overflow:hidden;
                   box-shadow:0 8px 32px rgba(55,65,176,.18);">

                {{-- Header --}}
                <div class="ap-modal-header">

                    <h5 class="ap-modal-title" id="candidateModalLabel">

                        <i class="ti ti-clipboard-check" style="font-size:17px;"></i>

                        Candidate

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                </div>

                {{-- Body --}}
                <div class="ap-modal-body">

                    {{-- Hidden Request ID --}}
                    <input type="hidden" id="request_id">


                    {{-- Employee ID --}}
                    <div class="mb-3">

                        <label class="ap-field-label">

                            Employee ID
                            {{-- <span class="req">*</span> --}}

                        </label>

                        <input type="text" id="employee_id" class="form-control" placeholder="Enter Employee ID">

                    </div>
                    {{-- STATUS --}}
                    <div class="mb-3">
                        <label class="ap-field-label">
                            Status
                            <span class="req">*</span>
                        </label>
                        <select id="candidate_status" class="form-control">
                            <option value="">
                                Select Status
                            </option>
                            {{-- <option value="In Progress">
                                In Progress
                            </option> --}}
                            <option value="Sourcing">
                                Sourcing
                            </option>
                            <option value="Interview Scheduled">
                                Interview Scheduled
                            </option>
                            <option value="Interview Date Fixed">
                                Interview Date Fixed
                            </option>
                            <option value="Interviewed">
                                Interviewed
                            </option>
                            <option value="Selected">
                                Selected
                            </option>
                            <option value="Joined">
                                Joined
                            </option>
                            <option value="Query">
                                Query
                            </option>
                            <option value="Wrong">
                                Wrong
                            </option>
                            <option value="Hold">
                                Hold
                            </option>
                        </select>

                    </div>

                    {{-- Remarks --}}
                    <div class="mb-1">

                        <label class="ap-field-label">

                            Remarks
                            <span class="req">*</span>

                        </label>

                        <textarea id="employee_assign_remarks" class="ap-textarea" rows="3"
                            placeholder="Enter your remarks or reason…"></textarea>

                    </div>

                </div>

                {{-- Footer --}}
                <div class="ap-modal-footer">

                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="button" class="ap-btn-submit" id="candidate_submit_btn" onclick="submitCandidate()">

                        <i class="ti ti-send" style="font-size:13px;"></i>

                        Submit

                    </button>

                </div>

            </div>

        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════
         SCRIPTS
    ═══════════════════════════════════════════════════ --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        let selectedStatus = '';

        function openApproveModal(requestId, dept, esc) {
            // Reset form
            selectedStatus = '';
            document.getElementById('ap_request_id').value = requestId;
            document.getElementById('ap_dept_display').textContent = dept || '—';
            document.getElementById('ap_esc_display').textContent = esc || '—';
            document.getElementById('ap_remarks').value = '';
            document.getElementById('radio_approved').checked = false;
            document.getElementById('radio_rejected').checked = false;
            document.getElementById('opt-approved').className = 'ap-status-opt';
            document.getElementById('opt-rejected').className = 'ap-status-opt';
            document.getElementById('err_status').style.display = 'none';
            document.getElementById('err_remarks').style.display = 'none';

            new bootstrap.Modal(document.getElementById('approveModal')).show();
        }

        function selectStatus(val) {
            selectedStatus = val;
            document.getElementById('err_status').style.display = 'none';

            if (val === 'Approved') {
                document.getElementById('radio_approved').checked = true;
                document.getElementById('radio_rejected').checked = false;
                document.getElementById('opt-approved').className = 'ap-status-opt sel-approved';
                document.getElementById('opt-rejected').className = 'ap-status-opt';
            } else {
                document.getElementById('radio_rejected').checked = true;
                document.getElementById('radio_approved').checked = false;
                document.getElementById('opt-rejected').className = 'ap-status-opt sel-rejected';
                document.getElementById('opt-approved').className = 'ap-status-opt';
            }
        }

        function submitApproval() {
            let valid = true;

            if (!selectedStatus) {
                document.getElementById('err_status').style.display = 'block';
                valid = false;
            }

            let remarks = document.getElementById('ap_remarks').value.trim();
            if (!remarks) {
                document.getElementById('err_remarks').style.display = 'block';
                valid = false;
            }

            if (!valid) return;

            let requestId = document.getElementById('ap_request_id').value;
            let btn = document.getElementById('ap_submit_btn');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader-2 ti-spin" style="font-size:13px;"></i> Submitting…';

            $.ajax({
                url: "{{ url('/approval/update') }}",

                type: "POST",
                data: {
                    request_id: requestId,

                    status: selectedStatus,
                    remarks: remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(res) {
                    bootstrap.Modal.getInstance(document.getElementById('approveModal')).hide();

                    Swal.fire({
                        icon: res.status ? 'success' : 'error',
                        title: res.status ? 'Done!' : 'Error',
                        text: res.message,
                        timer: 1800,
                        showConfirmButton: false
                    }).then(() => {
                        if (res.status) location.reload();
                    });
                },
                error: function() {
                    Swal.fire('Error', 'Server error. Please try again.', 'error');
                },
                complete: function() {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="ti ti-send" style="font-size:13px;"></i> Submit';
                }
            });
        }
    </script>
    <script>
        function selfAssignModal(requestId) {
            $('#self_request_id').val(requestId);
            $('#assign_employee').val('');
            $('#assign_remarks').val('');

            new bootstrap.Modal(
                document.getElementById('selfAssignModal')
            ).show();
        }

        function submitSelfAssign() {
            let requestId = $('#self_request_id').val();
            let employeeId = $('#assign_employee').val();
            let remarks = $('#assign_remarks').val();

            if (employeeId == '') {
                Swal.fire(
                    'Warning',
                    'Please select employee',
                    'warning'
                );

                return;
            }

            $.ajax({

                url: "/self-assign/" + requestId,

                type: "POST",

                data: {

                    employee_id: employeeId,
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

                error: function() {
                    Swal.fire(
                        'Error',
                        'Something went wrong',
                        'error'
                    );
                }

            });
        }
    </script>
    <script>
        let isSubmitting = false;

        // OPEN CANDIDATE MODAL
        function candidateModal(requestId) {
            $('#request_id').val(requestId);
            $('#employee_id').val('');
            $('#employee_assign_remarks').val('');
            new bootstrap.Modal(
                document.getElementById('candidateModal')
            ).show();
        }

        // SUBMIT CANDIDATE
        function submitCandidate() {
            if (isSubmitting) {
                return;
            }
            let requestId = $('#request_id').val();
            let employeeId = $('#employee_id').val();
            let remarks = $('#employee_assign_remarks').val();
            let status = $('#candidate_status').val();

            // VALIDATION
            // if (employeeId == '') {
            //     Swal.fire(
            //         'Warning',
            //         'Please enter Employee ID',
            //         'warning'
            //     );
            //     return;
            // }
            if (status == '') {

                Swal.fire(
                    'Warning',
                    'Please select status',
                    'warning'
                );

                return;
            }
            if (remarks == '') {
                Swal.fire(
                    'Warning',
                    'Please enter remarks',
                    'warning'
                );
                return;
            }
            isSubmitting = true;
            $('#candidate_submit_btn').prop('disabled', true);

            $.ajax({
                url: "/candidate/store/" + requestId,
                type: "POST",
                data: {
                    employee_id: employeeId,
                    remarks: remarks,
                    status: status,
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
                error: function() {
                    isSubmitting = false;

                    $('#candidate_submit_btn').prop('disabled', false);

                    Swal.fire(
                        'Error',
                        'Something went wrong',
                        'error'
                    );

                }
            });
        }
    </script>
@endsection
