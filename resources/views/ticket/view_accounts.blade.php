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
            font-weight: 600;
            font-size: 13px;
        }
        .crumb-back:hover { color: #3741b0; text-decoration: none; }

        .details-card {
            background: #fff;
            border: 1px solid #e7e8eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .details-card-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e7e8eb;
            font-size: 16px;
            font-weight: 700;
            color: #132144;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        /* ── Status flow ── */
        .status-flow {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            padding: 12px 16px 0;
        }
        .sf-item {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid transparent;
            background: #f3f4f8;
            color: #888;
        }
        .sf-item.active { border-color: currentColor; }

        /* ── Client grid ── */
        .client-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            padding: 16px;
        }
        .client-item { display: flex; gap: 14px; align-items: flex-start; }
        .client-icon {
            width: 34px; height: 34px; border-radius: 50%;
            background: #f3f4f8; display: flex; align-items: center;
            justify-content: center; color: #6b738a; flex-shrink: 0;
        }
        .client-label { font-size: 12px; font-weight: 700; color: #18284d; margin-bottom: 2px; }
        .client-value { font-size: 13px; color: #737b90; }

        /* ── Amount summary row ── */
        .amount-strip {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-top: 1px solid #e7e8eb;
            border-bottom: 1px solid #e7e8eb;
        }
        .amount-cell {
            padding: 14px 16px;
            border-right: 1px solid #e7e8eb;
            text-align: center;
        }
        .amount-cell:last-child { border-right: none; }
        .amount-label {
            font-size: 11px; font-weight: 600; color: #8890a6;
            text-transform: uppercase; letter-spacing: .4px; margin-bottom: 4px;
        }
        .amount-val { font-size: 18px; font-weight: 700; color: #132144; }
        .amount-val.green  { color: #1a7a3c; }
        .amount-val.blue   { color: #125e80; }
        .amount-val.red    { color: #b52020; }
        .amount-val.purple { color: #5c35aa; }

        /* ── Blue table ── */
        .blue-table thead th {
            background: #3741b0; color: #fff; border-bottom: none;
            padding: 9px 12px; font-size: 13px;
        }
        .blue-table tbody td {
            padding: 10px 12px; font-size: 13px;
            color: #4f5d7c; border-color: #e7e8eb;
        }

        /* ── Pills ── */
        .td-pill {
            display: inline-block; padding: 3px 9px; border-radius: 5px;
            font-size: 12px; font-weight: 700; white-space: nowrap; border: 1px solid transparent;
        }
        .pill-pending  { background:#fef6e4; color:#8f5e00; border-color:#f0d48a; }
        .pill-approved { background:#e8f7ef; color:#1a7a3c; border-color:#a8dfc0; }
        .pill-rejected { background:#fdeaea; color:#b52020; border-color:#f5c0c0; }
        .pill-paid     { background:#e4f3fb; color:#125e80; border-color:#9fd5ed; }
        .pill-settled  { background:#f0ebfb; color:#5c35aa; border-color:#c9b8ef; }
        .pill-closed   { background:#f1f2f5; color:#4b5673; border-color:#d1d5e0; }
        .pill-inprogress { background:#fff3cd; color:#856404; border-color:#ffc107; }

        /* ── Action card ── */
        .action-card-inner { padding: 16px; }
        .action-btn-row { display: flex; flex-direction: column; gap: 10px; }
        .ac-btn {
            display: flex; align-items: center; justify-content: center; gap: 7px;
            padding: 10px 16px; border-radius: 6px; font-size: 14px; font-weight: 700;
            border: none; cursor: pointer; width: 100%; transition: opacity .15s;
        }
        .ac-btn:hover { opacity: .87; }
        .ac-btn-primary { background: #3741b0; color: #fff; }
        .ac-btn-pay     { background: #125e80; color: #fff; }
        .ac-btn-settle  { background: #5c35aa; color: #fff; }
        .ac-btn-close   { background: #4b5673; color: #fff; }

        /* ── Modal shared ── */
        .ap-modal-header {
            background: #3741b0; padding: 14px 20px; border-bottom: none;
            border-radius: 6px 6px 0 0; display: flex; align-items: center; justify-content: space-between;
        }
        .ap-modal-title { font-size: 15px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; margin: 0; }
        .ap-modal-header .btn-close { filter: invert(1) brightness(2); opacity: .8; }
        .ap-modal-header.info   { background: #125e80; }
        .ap-modal-header.purple { background: #5c35aa; }
        .ap-modal-header.dark   { background: #4b5673; }
        .ap-modal-body { padding: 20px 20px 10px; }
        .ap-info-strip {
            background: #f3f4f8; border-radius: 5px; padding: 10px 14px;
            margin-bottom: 16px; display: flex; gap: 20px; flex-wrap: wrap;
        }
        .ap-info-item { font-size: 13px; color: #6b748a; }
        .ap-info-item strong { color: #18284d; font-weight: 700; }
        .ap-field-label { font-size: 13px; font-weight: 700; color: #18284d; margin-bottom: 7px; display: block; }
        .ap-field-label .req { color: #e03535; margin-left: 2px; }
        .ap-textarea {
            width: 100%; font-size: 13px; color: #18284d; border: 1px solid #d0d3de;
            border-radius: 5px; padding: 8px 10px; outline: none; resize: vertical;
            transition: border-color .15s; font-family: inherit;
        }
        .ap-textarea:focus { border-color: #3741b0; box-shadow: 0 0 0 3px rgba(55,65,176,.10); }
        .ap-modal-footer {
            padding: 12px 20px 16px; display: flex; justify-content: flex-end;
            gap: 8px; border-top: 1px solid #e7e8eb;
        }
        .ap-btn-cancel {
            padding: 7px 18px; font-size: 13px; font-weight: 600; border-radius: 5px;
            border: 1px solid #d0d3de; background: #fff; color: #4b5673; cursor: pointer;
        }
        .ap-btn-cancel:hover { background: #f3f4f8; }
        .ap-btn-submit {
            padding: 7px 22px; font-size: 13px; font-weight: 700; border-radius: 5px;
            border: none; background: #3741b0; color: #fff; cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px; transition: opacity .15s;
        }
        .ap-btn-submit:hover { opacity: .88; }
        .ap-btn-submit:disabled { opacity: .6; cursor: not-allowed; }
        .ap-btn-submit.info   { background: #125e80; }
        .ap-btn-submit.purple { background: #5c35aa; }
        .ap-btn-submit.dark   { background: #4b5673; }

        /* ── Choice cards ── */
        .ap-choice-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 4px; }
        .ap-choice-card {
            border: 1px solid #d0d3de; border-radius: 6px; padding: 11px 14px;
            display: flex; align-items: center; gap: 10px; cursor: pointer;
            font-size: 13px; font-weight: 600; color: #18284d; position: relative; user-select: none;
        }
        .ap-choice-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .ap-choice-dot {
            width: 16px; height: 16px; border-radius: 50%; border: 2px solid #c0c6d4;
            flex-shrink: 0; display: flex; align-items: center; justify-content: center;
        }
        .ap-choice-dot::after { content: ''; width: 8px; height: 8px; border-radius: 50%; background: transparent; }
        .ap-choice-card.sel-approved { border-color: #1a7a3c; background: #f0fbf5; }
        .ap-choice-card.sel-approved .ap-choice-dot { border-color: #1a7a3c; }
        .ap-choice-card.sel-approved .ap-choice-dot::after { background: #1a7a3c; }
        .ap-choice-card.sel-rejected { border-color: #b52020; background: #fff5f5; }
        .ap-choice-card.sel-rejected .ap-choice-dot { border-color: #b52020; }
        .ap-choice-card.sel-rejected .ap-choice-dot::after { background: #b52020; }

        @media(max-width:991.98px) {
            .client-grid { grid-template-columns: 1fr 1fr; }
            .amount-strip { grid-template-columns: repeat(2,1fr); }
        }
        @media(max-width:575.98px) {
            .client-grid { grid-template-columns: 1fr; }
            .ap-choice-row { grid-template-columns: 1fr; }
        }
    </style>

    <div class="page-wrapper">
        <div class="content">

            <a href="{{ route('tickets') }}" class="crumb-back">
                <i class="ti ti-chevron-left"></i> Back to Tickets
            </a>

            <div class="row g-3">

                {{-- ══ LEFT COL ══════════════════════════════════════════════ --}}
                <div class="col-lg-8">

                    {{-- IOU Request Details card --}}
                    <div class="details-card mb-3">
                        <div class="details-card-header">
                            <i class="ti ti-wallet"></i>
                            IOU Request — #{{ $ticket->ticketId ?? '—' }}
                            @php
                                $statusPill = [
                                    'pending'  => ['pill-pending',  'Pending'],
                                    'approved' => ['pill-approved', 'Approved'],
                                    'rejected' => ['pill-rejected', 'Rejected'],
                                    'paid'     => ['pill-paid',     'Paid'],
                                    'settled'  => ['pill-settled',  'Settled'],
                                    'closed'   => ['pill-closed',   'Closed'],
                                ];
                                [$pillClass, $pillLabel] = $statusPill[$iou->status] ?? ['pill-pending','Pending'];
                            @endphp
                            <span class="td-pill {{ $pillClass }} ms-auto">{{ $pillLabel }}</span>
                        </div>

                        {{-- Status flow --}}
                        <div class="status-flow">
                            @php
                                $flowOrder = ['pending','approved','paid','settled','Closed'];
                                $flowLabels = ['pending'=>'Pending','approved'=>'Approved','paid'=>'Paid','settled'=>'Settled','Closed'=>'Closed'];
                                $flowColors = ['pending'=>'#8f5e00','approved'=>'#1a7a3c','paid'=>'#125e80','settled'=>'#5c35aa','Closed'=>'#4b5673'];
                                $curIdx = array_search($iou->status, $flowOrder);
                            @endphp
                            @foreach ($flowOrder as $idx => $step)
                                <span class="sf-item {{ $iou->status === $step ? 'active' : '' }}"
                                    style="color:{{ $flowColors[$step] }};">
                                    {{ $flowLabels[$step] }}
                                </span>
                                @if (!$loop->last)
                                    <span class="text-muted" style="font-size:16px;line-height:1.4;">→</span>
                                @endif
                            @endforeach
                            @if ($iou->status === 'rejected')
                                <span class="sf-item active" style="color:#b52020;border-color:#b52020;">Rejected</span>
                            @endif
                        </div>

                        {{-- Amount strip --}}
                        <div class="amount-strip" style="margin-top:12px;">
                            <div class="amount-cell">
                                <div class="amount-label">Requested</div>
                                <div class="amount-val">₹{{ number_format($iou->requested_amount, 2) }}</div>
                            </div>
                            <div class="amount-cell">
                                <div class="amount-label">Approved</div>
                                <div class="amount-val green">₹{{ number_format($iou->approved_amount ?? 0, 2) }}</div>
                            </div>
                            <div class="amount-cell">
                                <div class="amount-label">Paid</div>
                                <div class="amount-val blue">₹{{ number_format($iou->paid_amount ?? 0, 2) }}</div>
                            </div>
                            <div class="amount-cell">
                                <div class="amount-label">Pending Balance</div>
                                <div class="amount-val {{ ($balance?->pending_balance ?? 0) > 0 ? 'red' : 'green' }}">
                                    ₹{{ number_format($balance?->pending_balance ?? 0, 2) }}
                                </div>
                            </div>
                        </div>

                        {{-- Detail grid --}}
                        <div class="client-grid">
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-user"></i></div>
                                <div>
                                    <div class="client-label">Employee</div>
                                    <div class="client-value">{{ $iou->employee->FullName ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-building"></i></div>
                                <div>
                                    <div class="client-label">Department</div>
                                    <div class="client-value">{{ $iou->department->DepartmentName ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-tag"></i></div>
                                <div>
                                    <div class="client-label">Category</div>
                                    <div class="client-value">{{ $iou->category->category_name ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-list"></i></div>
                                <div>
                                    <div class="client-label">Type of Escalation</div>
                                    <div class="client-value">{{ $iou->issue->IssueName ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-calendar"></i></div>
                                <div>
                                    <div class="client-label">Request Date</div>
                                    <div class="client-value">
                                        {{ $iou->request_date ? date('d M Y', strtotime($iou->request_date)) : '—' }}
                                    </div>
                                </div>
                            </div>
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-calendar-check"></i></div>
                                <div>
                                    <div class="client-label">Raised On</div>
                                    <div class="client-value">
                                        {{ $ticket->CreatedDate ? date('d M Y', strtotime($ticket->CreatedDate)) : '—' }}
                                    </div>
                                </div>
                            </div>
                            <div class="client-item" style="grid-column:1/-1;">
                                <div class="client-icon"><i class="ti ti-message"></i></div>
                                <div>
                                    <div class="client-label">Purpose</div>
                                    <div class="client-value">{{ $iou->purpose ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Transactions --}}
                    @if ($iou->transactions && $iou->transactions->count())
                        <div class="details-card mb-3">
                            <div class="details-card-header">
                                <i class="ti ti-transfer"></i> Transactions
                            </div>
                            <div class="table-responsive">
                                <table class="table blue-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-light">#</th>
                                            <th class="text-light">Type</th>
                                            <th class="text-light">Amount</th>
                                            <th class="text-light">Remarks</th>
                                            <th class="text-light">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($iou->transactions as $k => $txn)
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td><span class="td-pill pill-inprogress">{{ ucfirst(str_replace('_', ' ', $txn->type)) }}</span></td>
                                                <td style="font-weight:700;color:#18284d;">₹{{ number_format($txn->amount, 2) }}</td>
                                                <td>{{ $txn->remarks ?? '—' }}</td>
                                                <td>{{ $txn->created_at ? date('d M Y h:i A', strtotime($txn->created_at)) : '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Settlements --}}
                    @if ($iou->settlements && $iou->settlements->count())
                        <div class="details-card mb-3">
                            <div class="details-card-header">
                                <i class="ti ti-file-invoice" style="color:#5c35aa;"></i> Settlements
                            </div>
                            <div class="table-responsive">
                                <table class="table blue-table mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-light">#</th>
                                            <th class="text-light">Type</th>
                                            <th class="text-light">Settlement Amt</th>
                                            <th class="text-light">Employee Extra</th>
                                            <th class="text-light">Remaining Bal</th>
                                            <th class="text-light">Remarks</th>
                                            <th class="text-light">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($iou->settlements as $k => $s)
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td><span class="td-pill pill-settled">{{ $s->settlement_type ?? '—' }}</span></td>
                                                <td style="color:#1a7a3c;font-weight:600;">₹{{ number_format($s->company_settlement_amount ?? $s->actual_expense ?? 0, 2) }}</td>
                                                <td style="color:#b52020;font-weight:600;">₹{{ number_format($s->employee_claim_amount ?? $s->extra_claim_amount ?? 0, 2) }}</td>
                                                <td style="color:#125e80;font-weight:600;">₹{{ number_format($s->remaining_balance ?? $s->returned_amount ?? 0, 2) }}</td>
                                                <td>{{ $s->remarks ?? '—' }}</td>
                                                <td>{{ $s->created_at ? date('d M Y', strtotime($s->created_at)) : '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Action History --}}
                    <div class="details-card">
                        <div class="details-card-header">
                            <i class="ti ti-history"></i> Action History
                        </div>
                        <div class="table-responsive">
                            <table class="table blue-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-light">#</th>
                                        <th class="text-light">Date</th>
                                        <th class="text-light">Status</th>
                                        <th class="text-light">Remarks</th>
                                        <th class="text-light">Changed By</th>
                                        <th class="text-light">Designation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($actionHistory as $k => $history)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $history->changedAt ? date('d M Y h:i A', strtotime($history->changedAt)) : '—' }}</td>
                                            <td>
                                                @php
                                                    $hPill = [
                                                        'pending'  => 'pill-pending',
                                                        'approved' => 'pill-approved',
                                                        'rejected' => 'pill-rejected',
                                                        'paid'     => 'pill-paid',
                                                        'settled'  => 'pill-settled',
                                                        'Closed'   => 'pill-closed',
                                                    ];
                                                    $hp = $hPill[strtolower($history->status)] ?? $hPill[$history->status] ?? 'pill-inprogress';
                                                @endphp
                                                <span class="td-pill {{ $hp }}">{{ ucfirst($history->status) }}</span>
                                            </td>
                                            <td><small>{{ $history->remarks ?? '—' }}</small></td>
                                            <td>{{ $history->changedBy }}</td>
                                            <td>{{ $history->designation }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">No history found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- ══ RIGHT COL ══════════════════════════════════════════════ --}}
                <div class="col-lg-4">

                    {{-- Employee Balance Card --}}
                    @if ($balance)
                        <div class="details-card mb-3">
                            <div class="details-card-header">
                                <i class="ti ti-user-dollar"></i> Employee Balance
                            </div>
                            <div class="p-3">
                                <div style="background:linear-gradient(135deg,#3741b0,#5761d0);border-radius:10px;color:#fff;padding:18px;text-align:center;margin-bottom:14px;">
                                    <div style="font-size:12px;opacity:.85;margin-bottom:4px;">Net Pending Balance</div>
                                    <div style="font-size:28px;font-weight:800;">₹{{ number_format($balance->pending_balance, 2) }}</div>
                                </div>
                                <div class="row g-2 text-center">
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Total IOU</div>
                                            <div class="fw-semibold" style="font-size:13px;color:#3741b0;">₹{{ number_format($balance->total_iou_amount, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Total Settled</div>
                                            <div class="fw-semibold text-success" style="font-size:13px;">₹{{ number_format($balance->total_settlement_amount, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Total Claims</div>
                                            <div class="fw-semibold text-warning" style="font-size:13px;">₹{{ number_format($balance->total_claim_amount, 2) }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Paid Amount</div>
                                            <div class="fw-semibold text-info" style="font-size:13px;">₹{{ number_format($iou->paid_amount ?? 0, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- Action Card --}}
                    @if ($iou->status !== 'Closed' && $iou->status !== 'rejected')
                        <div class="details-card">
                            <div class="details-card-header">
                                <i class="ti ti-settings"></i> Actions
                            </div>
                            <div class="action-card-inner">
                                <div class="action-btn-row">
                                    @if ($iou->status === 'pending')
                                        <button class="ac-btn ac-btn-primary" onclick="openApprovalModal()">
                                            <i class="ti ti-clipboard-check"></i> Review IOU Request
                                        </button>
                                    @elseif ($iou->status === 'approved')
                                        <button class="ac-btn ac-btn-pay" onclick="openPaymentModal()">
                                            <i class="ti ti-cash"></i> Mark as Paid
                                        </button>
                                    @elseif ($iou->status === 'paid')
                                        <button class="ac-btn ac-btn-settle" onclick="openSettlementModal()">
                                            <i class="ti ti-file-invoice"></i> Add Settlement
                                        </button>
                                    @elseif ($iou->status === 'settled')
                                        <button class="ac-btn ac-btn-close" onclick="openCloseModal()">
                                            <i class="ti ti-lock"></i> Close Ticket
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-{{ $iou->status === 'Closed' ? 'success' : 'danger' }} text-center mb-0">
                            <i class="ti ti-{{ $iou->status === 'Closed' ? 'circle-check' : 'circle-x' }} fs-4 d-block mb-1"></i>
                            This IOU is <strong>{{ $iou->status === 'Closed' ? 'Closed' : 'Rejected' }}</strong>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    {{-- ══ MODALS ══════════════════════════════════════════════════════ --}}

    {{-- Approval Modal --}}
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content" style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(55,65,176,.18);">
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title"><i class="ti ti-clipboard-check" style="font-size:17px;"></i> Review IOU Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $iou->employee->FullName ?? '—' }}</div>
                        <div class="ap-info-item"><strong>Requested:</strong> ₹{{ number_format($iou->requested_amount, 2) }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Decision <span class="req">*</span></label>
                        <div class="ap-choice-row">
                            <label class="ap-choice-card" id="choiceApprove" onclick="selectDecision('approved')">
                                <input type="radio" name="decision" value="approved">
                                <div class="ap-choice-dot"></div>
                                <i class="ti ti-circle-check" style="font-size:18px;color:#1a7a3c;"></i>
                                <span>Approve</span>
                            </label>
                            <label class="ap-choice-card" id="choiceReject" onclick="selectDecision('rejected')">
                                <input type="radio" name="decision" value="rejected">
                                <div class="ap-choice-dot"></div>
                                <i class="ti ti-circle-x" style="font-size:18px;color:#b52020;"></i>
                                <span>Reject</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="approvedAmountRow" style="display:none;">
                        <label class="ap-field-label">Approved Amount <span class="req">*</span></label>
                        <input type="number" id="approved_amount" class="form-control" min="1"
                            placeholder="Enter approved amount" value="{{ $iou->requested_amount }}">
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Remarks <span class="req">*</span></label>
                        <textarea id="approval_remarks" class="ap-textarea" rows="3" placeholder="Enter remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit" id="approvalSubmitBtn" onclick="submitApproval()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Modal --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content" style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(18,94,128,.18);">
                <div class="ap-modal-header info">
                    <h5 class="ap-modal-title"><i class="ti ti-cash" style="font-size:17px;"></i> Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Approved Amount:</strong> ₹{{ number_format($iou->approved_amount ?? 0, 2) }}</div>
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $iou->employee->FullName ?? '—' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Paid Amount <span class="req">*</span></label>
                        <input type="number" id="paid_amount" class="form-control" min="1"
                            placeholder="Enter paid amount" value="{{ $iou->approved_amount ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Payment Mode <span class="req">*</span></label>
                        <select id="payment_mode" class="form-control">
                            <option value="">Select Mode</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Remarks <span class="req">*</span></label>
                        <textarea id="payment_remarks" class="ap-textarea" rows="2" placeholder="Enter payment remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit info" id="paymentSubmitBtn" onclick="submitPayment()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Confirm Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Settlement Modal (Petty Cash UI) --}}
    <div class="modal fade" id="settlementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(92,53,170,.18);">
                <div class="ap-modal-header purple">
                    <h5 class="ap-modal-title"><i class="ti ti-file-invoice" style="font-size:17px;"></i> Add Settlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $iou->employee->FullName ?? '—' }}</div>
                        <div class="ap-info-item"><strong>Paid Amount:</strong> ₹{{ number_format($iou->paid_amount ?? 0, 2) }}</div>
                    </div>

                    <div id="settlement_request_block" class="row mt-2">
                        <div class="col-lg-4">
                            <label class="form-label">Current Balance</label>
                            <input type="text" name="settlement_current_balance" class="form-control"
                                value="{{ number_format($iou->paid_amount ?? 0, 2) }}" readonly>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Remaining Balance</label>
                            <input type="text" name="remaining_balance" class="form-control"
                                value="{{ number_format($iou->paid_amount ?? 0, 2) }}" readonly>
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Settlement Type <span class="text-danger">*</span></label>
                            <select name="settlement_type" id="settlement_type" class="form-control">
                                <option value="">Select Type</option>
                                <option value="BILL">BILL SUBMISSION</option>
                                <option value="RETURN">CASH RETURN</option>
                            </select>
                        </div>

                        <div class="col-lg-12" id="bill_section" style="display:none;">
                            <div class="d-flex justify-content-between mb-3 mt-3">
                                <h5>Bill Details</h5>
                                <button type="button" class="btn btn-primary btn-sm" id="add_bill_row">+ Add Row</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Expense Type</th>
                                            <th>Bill Date</th>
                                            <th>Bill Amount</th>
                                            <th>Settlement Amount</th>
                                            <th>Employee Extra</th>
                                            <th>File</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bill_table_body">
                                        <tr class="bill-row">
                                            <td>
                                                <select name="expense_type[]" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="Travel">Travel</option>
                                                    <option value="Food">Food</option>
                                                    <option value="Hotel">Hotel</option>
                                                    <option value="Fuel">Fuel</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </td>
                                            <td><input type="date" name="bill_date[]" class="form-control"></td>
                                            <td><input type="number" step="0.01" name="bill_amount[]" class="form-control bill-amount" placeholder="0.00"></td>
                                            <td><input type="text" name="settlement_amount[]" class="form-control settlement-amount bg-light" value="0.00" readonly></td>
                                            <td><input type="text" name="employee_extra_amount[]" class="form-control employee-extra bg-danger text-white" value="0.00" readonly></td>
                                            <td><input type="file" name="settlement_files[]" class="form-control"></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3" id="return_section" style="display:none;">
                            <div class="mb-3">
                                <label class="form-label">Return Amount <span class="text-danger">*</span></label>
                                <input type="number" id="return_amount" name="return_amount" class="form-control"
                                    min="0.01" step="0.01" placeholder="Amount returned by employee">
                                <small class="text-muted">Employee returns unused cash to company</small>
                            </div>
                            <div id="return_hint" style="display:none;background:#f3f4f8;border-radius:5px;padding:9px 13px;font-size:13px;font-weight:600;"></div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <label class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea id="settlement_remarks" name="remarks" class="form-control" rows="2" placeholder="Enter settlement remarks…"></textarea>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-3">
                                <label>Total Bill</label>
                                <input type="text" id="total_bill_amount" class="form-control" readonly value="0.00">
                            </div>
                            <div class="col-lg-3">
                                <label>Company Settlement</label>
                                <input type="text" id="total_settlement_amount" name="total_settlement_amount"
                                    class="form-control bg-success text-white" readonly value="0.00">
                            </div>
                            <div class="col-lg-3">
                                <label>Employee Extra</label>
                                <input type="text" id="total_employee_extra" name="employee_extra_amount"
                                    class="form-control bg-danger text-white" readonly value="0.00">
                            </div>
                            <div class="col-lg-3">
                                <label>Remaining Balance</label>
                                <input type="text" id="remaining_balance" name="remaining_balance"
                                    class="form-control bg-primary text-white" readonly value="0.00">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit purple" id="settlementSubmitBtn" onclick="submitSettlement()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Submit Settlement
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Close Modal --}}
    <div class="modal fade" id="closeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
            <div class="modal-content" style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.12);">
                <div class="ap-modal-header dark">
                    <h5 class="ap-modal-title"><i class="ti ti-lock" style="font-size:17px;"></i> Close Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip" style="background:#fff8e1;">
                        <div class="ap-info-item" style="color:#8f5e00;">
                            <i class="ti ti-alert-triangle" style="margin-right:4px;"></i>
                            Closing this ticket is <strong>irreversible</strong>. Ensure all settlements are complete.
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Closing Remarks <span class="req">*</span></label>
                        <textarea id="close_remarks" class="ap-textarea" rows="3" placeholder="Enter closing remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit dark" id="closeSubmitBtn" onclick="submitClose()">
                        <i class="ti ti-lock" style="font-size:13px;"></i> Confirm Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        const IOU_ID = {{ $iou->iou_id }};
        const paid_AMOUNT = {{ $iou->paid_amount ?? 0 }};

        function setSubmitting(btnId, state) {
            const btn = document.getElementById(btnId);
            if (btn) btn.disabled = state;
        }
        function showWarn(msg)  { Swal.fire('Warning', msg, 'warning'); }
        function showError(msg) { Swal.fire('Error', msg, 'error'); }
        function showSuccess(msg, cb) {
            Swal.fire({ icon:'success', title:'Success', text:msg, timer:1600, showConfirmButton:false }).then(cb);
        }
        function openModal(id)  { new bootstrap.Modal(document.getElementById(id)).show(); }
        function closeModal(id) { bootstrap.Modal.getInstance(document.getElementById(id))?.hide(); }

        // ── Approval ────────────────────────────────────────────────
        function openApprovalModal() { openModal('approvalModal'); }

        function selectDecision(val) {
            document.getElementById('choiceApprove').classList.toggle('sel-approved', val === 'approved');
            document.getElementById('choiceReject').classList.toggle('sel-rejected', val === 'rejected');
            document.getElementById('approvedAmountRow').style.display = val === 'approved' ? 'block' : 'none';
            document.getElementById('choiceApprove').querySelector('input').checked = val === 'approved';
            document.getElementById('choiceReject').querySelector('input').checked = val === 'rejected';
        }

        let submittingApproval = false;
        function submitApproval() {
            if (submittingApproval) return;
            const decision = document.querySelector('input[name="decision"]:checked')?.value;
            const remarks = document.getElementById('approval_remarks').value.trim();
            const amount = document.getElementById('approved_amount').value;
            if (!decision) return showWarn('Please select Approve or Reject.');
            if (decision === 'approved' && (!amount || amount <= 0)) return showWarn('Please enter a valid approved amount.');
            if (!remarks) return showWarn('Please enter remarks.');
            submittingApproval = true;
            setSubmitting('approvalSubmitBtn', true);
            $.ajax({
                url: `/iou/${IOU_ID}/approve`, type: 'POST',
                data: { decision, approved_amount: amount, remarks, _token: $('meta[name="csrf-token"]').attr('content') },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => { submittingApproval = false; setSubmitting('approvalSubmitBtn', false); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        }

        // ── Payment ─────────────────────────────────────────────────
        function openPaymentModal() { openModal('paymentModal'); }

        let submittingPayment = false;
        function submitPayment() {
            if (submittingPayment) return;
            const amount = document.getElementById('paid_amount').value;
            const mode   = document.getElementById('payment_mode').value;
            const remarks = document.getElementById('payment_remarks').value.trim();
            if (!amount || amount <= 0) return showWarn('Please enter a valid paid amount.');
            if (!mode) return showWarn('Please select payment mode.');
            if (!remarks) return showWarn('Please enter remarks.');
            submittingPayment = true;
            setSubmitting('paymentSubmitBtn', true);
            $.ajax({
                url: `/iou/${IOU_ID}/pay`, type: 'POST',
                data: { paid_amount: amount, payment_mode: mode, remarks, _token: $('meta[name="csrf-token"]').attr('content') },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => { submittingPayment = false; setSubmitting('paymentSubmitBtn', false); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        }

        // ── Settlement ──────────────────────────────────────────────
        function openSettlementModal() { openModal('settlementModal'); }

        // Settlement type change
        $('#settlement_type').on('change', function() {
            const type = $(this).val();
            $('#bill_section').toggle(type === 'BILL');
            $('#return_section').toggle(type === 'RETURN');
            recalcBills();
        });

        // Add bill row
        $('#add_bill_row').on('click', function() {
            const tbody = document.getElementById('bill_table_body');
            const row = tbody.querySelector('.bill-row').cloneNode(true);
            row.querySelectorAll('input:not([type="file"])').forEach(el => {
                el.value = (el.classList.contains('settlement-amount') || el.classList.contains('employee-extra')) ? '0.00' : '';
            });
            row.querySelectorAll('input[type="file"]').forEach(el => el.value = '');
            row.querySelectorAll('select').forEach(el => el.selectedIndex = 0);
            tbody.appendChild(row);
        });

        // Remove bill row (delegated)
        $(document).on('click', '.remove-row', function() {
            const tbody = document.getElementById('bill_table_body');
            if (tbody.querySelectorAll('.bill-row').length > 1) {
                $(this).closest('.bill-row').remove();
                recalcBills();
            }
        });

        // Recalc on bill amount input (delegated)
        $(document).on('input', '.bill-amount', function() { recalcBills(); });
        $(document).on('input', '#return_amount', function() { recalcReturn(); });

        function recalcBills() {
            const paidAmt = paid_AMOUNT;
            let running = paidAmt, totalBill = 0, totalSettle = 0, totalExtra = 0;
            document.querySelectorAll('#bill_table_body .bill-row').forEach(row => {
                const billAmt   = parseFloat(row.querySelector('.bill-amount')?.value) || 0;
                const settleAmt = Math.min(billAmt, running);
                const extra     = Math.max(0, billAmt - running);
                running = Math.max(0, running - billAmt);
                totalBill += billAmt; totalSettle += settleAmt; totalExtra += extra;
                row.querySelector('.settlement-amount').value = settleAmt.toFixed(2);
                row.querySelector('.employee-extra').value    = extra.toFixed(2);
            });
            const balAfter = Math.max(0, paidAmt - totalSettle);
            document.getElementById('total_bill_amount').value       = totalBill.toFixed(2);
            document.getElementById('total_settlement_amount').value = totalSettle.toFixed(2);
            document.getElementById('total_employee_extra').value    = totalExtra.toFixed(2);
            document.getElementById('remaining_balance').value       = balAfter.toFixed(2);
        }

        function recalcReturn() {
            const paidAmt  = paid_AMOUNT;
            const retAmt   = parseFloat(document.getElementById('return_amount').value) || 0;
            const balAfter = Math.max(0, paidAmt - retAmt);
            const hint     = document.getElementById('return_hint');
            hint.style.display = retAmt > 0 ? 'block' : 'none';
            hint.innerHTML = retAmt <= paidAmt
                ? `<span style="color:#1a7a3c;"><i class="ti ti-circle-check"></i> Employee returns ₹${retAmt.toFixed(2)}. Remaining: ₹${balAfter.toFixed(2)}</span>`
                : `<span style="color:#b52020;"><i class="ti ti-alert-triangle"></i> Return amount exceeds paid amount.</span>`;
            document.getElementById('remaining_balance').value = balAfter.toFixed(2);
        }

        let submittingSettlement = false;
        function submitSettlement() {
            if (submittingSettlement) return;
            const type    = document.getElementById('settlement_type').value;
            const remarks = document.getElementById('settlement_remarks').value.trim();
            if (!type)    return showWarn('Please select a settlement type.');
            if (!remarks) return showWarn('Please enter remarks.');
            if (type === 'BILL') {
                let valid = true;
                document.querySelectorAll('#bill_table_body .bill-row').forEach(row => {
                    if (!row.querySelector('select').value || !(parseFloat(row.querySelector('.bill-amount').value) > 0)) valid = false;
                });
                if (!valid) return showWarn('Please fill expense type and bill amount for all rows.');
            }
            if (type === 'RETURN') {
                const retAmt = parseFloat(document.getElementById('return_amount').value) || 0;
                if (retAmt <= 0)          return showWarn('Please enter a valid return amount.');
                if (retAmt > paid_AMOUNT) return showWarn('Return amount cannot exceed paid amount.');
            }
            submittingSettlement = true;
            setSubmitting('settlementSubmitBtn', true);
            const formData = new FormData();
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
            formData.append('settlement_type', type);
            formData.append('remarks', remarks);
            if (type === 'BILL') {
                document.querySelectorAll('#bill_table_body .bill-row').forEach(row => {
                    formData.append('expense_type[]',          row.querySelector('select').value);
                    formData.append('bill_date[]',             row.querySelector('input[name="bill_date[]"]').value);
                    formData.append('bill_amount[]',           row.querySelector('.bill-amount').value);
                    formData.append('settlement_amount[]',     row.querySelector('.settlement-amount').value);
                    formData.append('employee_extra_amount[]', row.querySelector('.employee-extra').value);
                    const file = row.querySelector('input[type="file"]').files[0];
                    if (file) formData.append('settlement_files[]', file);
                });
                formData.append('total_bill_amount',       document.getElementById('total_bill_amount').value);
                formData.append('total_settlement_amount', document.getElementById('total_settlement_amount').value);
                formData.append('employee_extra_amount',   document.getElementById('total_employee_extra').value);
                formData.append('remaining_balance',       document.getElementById('remaining_balance').value);
            } else {
                formData.append('return_amount', document.getElementById('return_amount').value);
            }
            $.ajax({
                url: `/iou/${IOU_ID}/settle`, type: 'POST',
                data: formData, processData: false, contentType: false,
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => { submittingSettlement = false; setSubmitting('settlementSubmitBtn', false); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        }

        // ── Close ───────────────────────────────────────────────────
        function openCloseModal() { openModal('closeModal'); }

        let submittingClose = false;
        function submitClose() {
            if (submittingClose) return;
            const remarks = document.getElementById('close_remarks').value.trim();
            if (!remarks) return showWarn('Please enter closing remarks.');
            submittingClose = true;
            setSubmitting('closeSubmitBtn', true);
            $.ajax({
                url: `/iou/${IOU_ID}/close`, type: 'POST',
                data: { remarks, _token: $('meta[name="csrf-token"]').attr('content') },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => { submittingClose = false; setSubmitting('closeSubmitBtn', false); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        }
    </script>
@endsection
