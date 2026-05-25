<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')
    <style>
        /* ── Base ── */
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

        /* ── Card ── */
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

        /* ── Workflow Stepper ── */
        .iou-workflow {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: #f8f9fc;
            border-bottom: 1px solid #e7e8eb;
            overflow-x: auto;
        }

        .iou-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            min-width: 90px;
            position: relative;
        }

        .iou-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 16px;
            left: calc(50% + 18px);
            width: calc(100% - 36px);
            height: 2px;
            background: #d1d5e0;
            z-index: 0;
        }

        .iou-step.done:not(:last-child)::after {
            background: #3741b0;
        }

        .iou-step.active:not(:last-child)::after {
            background: #d1d5e0;
        }

        .iou-step-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: 2px solid #d1d5e0;
            background: #fff;
            color: #8890a6;
            z-index: 1;
            position: relative;
        }

        .iou-step.done .iou-step-dot {
            background: #3741b0;
            border-color: #3741b0;
            color: #fff;
        }

        .iou-step.active .iou-step-dot {
            background: #fff;
            border-color: #3741b0;
            color: #3741b0;
            box-shadow: 0 0 0 4px rgba(55, 65, 176, .12);
        }

        .iou-step.rejected .iou-step-dot {
            background: #fdeaea;
            border-color: #b52020;
            color: #b52020;
        }

        .iou-step-label {
            font-size: 11px;
            font-weight: 600;
            color: #8890a6;
            white-space: nowrap;
        }

        .iou-step.done .iou-step-label {
            color: #3741b0;
        }

        .iou-step.active .iou-step-label {
            color: #132144;
        }

        .iou-step.rejected .iou-step-label {
            color: #b52020;
        }

        /* ── Amount Summary Strip ── */
        .iou-amount-strip {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
            border-bottom: 1px solid #e7e8eb;
        }

        .iou-amount-cell {
            padding: 14px 16px;
            border-right: 1px solid #e7e8eb;
            text-align: center;
        }

        .iou-amount-cell:last-child {
            border-right: none;
        }

        .iou-amount-label {
            font-size: 11px;
            font-weight: 600;
            color: #8890a6;
            text-transform: uppercase;
            letter-spacing: .4px;
            margin-bottom: 4px;
        }

        .iou-amount-val {
            font-size: 18px;
            font-weight: 700;
            color: #132144;
        }

        .iou-amount-val.positive {
            color: #1a7a3c;
        }

        .iou-amount-val.negative {
            color: #b52020;
        }

        .iou-amount-val.warning {
            color: #8f5e00;
        }

        /* ── Section row ── */
        .td-section-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            padding: 14px 16px 8px;
            border-top: 1px solid #e7e8eb;
        }

        .td-section-title {
            font-size: 15px;
            font-weight: 700;
            color: #132144;
            margin: 0;
        }

        /* ── Detail grid ── */
        .td-detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            border-top: 1px solid #e7e8eb;
        }

        .td-detail-cell {
            padding: 12px 16px;
            border-right: 1px solid #e7e8eb;
            border-bottom: 1px solid #e7e8eb;
        }

        .td-detail-cell:last-child {
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

        /* ── Table ── */
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
        }

        /* ── Pills ── */
        .td-pill {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .pill-pending {
            background: #fef6e4;
            color: #8f5e00;
            border-color: #f0d48a;
        }

        .pill-approved {
            background: #e8f7ef;
            color: #1a7a3c;
            border-color: #a8dfc0;
        }

        .pill-rejected {
            background: #fdeaea;
            color: #b52020;
            border-color: #f5c0c0;
        }

        .pill-paid {
            background: #e4f3fb;
            color: #125e80;
            border-color: #9fd5ed;
        }

        .pill-settled {
            background: #f0ebfb;
            color: #5c35aa;
            border-color: #c9b8ef;
        }

        .pill-closed {
            background: #f1f2f5;
            color: #4b5673;
            border-color: #d1d5e0;
        }

        .pill-inprogress {
            background: #fff3cd;
            color: #856404;
            border-color: #ffc107;
        }

        /* ── Action buttons ── */
        .td-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 14px;
            border-radius: 5px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
            transition: opacity .15s;
        }

        .td-btn:hover {
            opacity: .82;
        }

        .td-btn-approve {
            background: #e8f7ef;
            color: #1a7a3c;
            border-color: #a8dfc0;
        }

        .td-btn-reject {
            background: #fdeaea;
            color: #b52020;
            border-color: #f5c0c0;
        }

        .td-btn-pay {
            background: #e4f3fb;
            color: #125e80;
            border-color: #9fd5ed;
        }

        .td-btn-settle {
            background: #f0ebfb;
            color: #5c35aa;
            border-color: #c9b8ef;
        }

        .td-btn-close {
            background: #f1f2f5;
            color: #4b5673;
            border-color: #d1d5e0;
        }

        .td-btn-primary {
            background: #3741b0;
            color: #fff;
            border-color: #3741b0;
        }

        /* ── Settlements sub-table ── */
        .settle-row td {
            background: #fafbff;
        }

        /* ── Modal shared ── */
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

        .ap-modal-header.danger {
            background: #b52020;
        }

        .ap-modal-header.success {
            background: #1a7a3c;
        }

        .ap-modal-header.info {
            background: #125e80;
        }

        .ap-modal-header.purple {
            background: #5c35aa;
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
            gap: 20px;
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

        .ap-btn-submit.danger {
            background: #b52020;
        }

        .ap-btn-submit.success {
            background: #1a7a3c;
        }

        .ap-btn-submit.info {
            background: #125e80;
        }

        .ap-btn-submit.purple {
            background: #5c35aa;
        }

        /* ── Confirm cards (approve/reject choice) ── */
        .ap-choice-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 4px;
        }

        .ap-choice-card {
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

        .ap-choice-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .ap-choice-dot {
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

        .ap-choice-dot::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: transparent;
            transition: background .15s;
        }

        .ap-choice-card.sel-approved {
            border-color: #1a7a3c;
            background: #f0fbf5;
        }

        .ap-choice-card.sel-approved .ap-choice-dot {
            border-color: #1a7a3c;
        }

        .ap-choice-card.sel-approved .ap-choice-dot::after {
            background: #1a7a3c;
        }

        .ap-choice-card.sel-rejected {
            border-color: #b52020;
            background: #fff5f5;
        }

        .ap-choice-card.sel-rejected .ap-choice-dot {
            border-color: #b52020;
        }

        .ap-choice-card.sel-rejected .ap-choice-dot::after {
            background: #b52020;
        }

        .td-footer {
            text-align: center;
            font-size: 12px;
            color: #6f7790;
            padding: 20px 0 10px;
        }

        @media(max-width:767px) {
            .iou-amount-strip {
                grid-template-columns: repeat(2, 1fr);
            }

            .ap-choice-row {
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

            <a href="{{ route('tickets') }}" class="td-crumb">
                <i class="ti ti-chevron-left"></i> Back to Tickets
            </a>

            <div class="td-card">

                <div class="td-card-header">
                    <div class="td-card-header-left">
                        <div class="td-header-icon"><i class="ti ti-wallet"></i></div>
                        <div>
                            <div class="td-header-title">IOU Request Details</div>
                            <div class="td-header-sub">
                                #TKT{{ $ticket->ticketId ?? '—' }}
                                &nbsp;·&nbsp;
                                Raised {{ $ticket->CreatedDate ? date('d M Y', strtotime($ticket->CreatedDate)) : '—' }}
                                &nbsp;·&nbsp;
                                <span
                                    class="td-pill
                            @if ($iou->status === 'pending') pill-pending
                            @elseif($iou->status === 'approved') pill-approved
                            @elseif($iou->status === 'rejected') pill-rejected
                            @elseif($iou->status === 'paid')     pill-paid
                            @elseif($iou->status === 'settled')  pill-settled
                            @elseif($iou->status === 'Closed')   pill-closed
                            @else                                pill-pending @endif">
                                    {{ $iou->status ?? 'pending' }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if ($iou->status === 'pending')
                        <button class="td-btn td-btn-primary" onclick="openApprovalModal()">
                            <i class="ti ti-clipboard-check"></i> Review Request
                        </button>
                    @elseif($iou->status === 'approved')
                        <button class="td-btn td-btn-pay" onclick="openPaymentModal()">
                            <i class="ti ti-cash"></i> Mark as paid
                        </button>
                    @elseif($iou->status === 'paid')
                        <button class="td-btn td-btn-settle" onclick="openSettlementModal()">
                            <i class="ti ti-file-invoice"></i> Add Settlement
                        </button>
                    @elseif($iou->status === 'settled')
                        <button class="td-btn td-btn-close" onclick="openCloseModal()">
                            <i class="ti ti-lock"></i> Close Ticket
                        </button>
                    @endif
                </div>
                <div class="iou-amount-strip">
                    <div class="iou-amount-cell">
                        <div class="iou-amount-label">Requested</div>
                        <div class="iou-amount-val">₹{{ number_format($iou->requested_amount, 2) }}</div>
                    </div>
                    <div class="iou-amount-cell">
                        <div class="iou-amount-label">approved</div>
                        <div class="iou-amount-val positive">₹{{ number_format($iou->approved_amount ?? 0, 2) }}</div>
                    </div>
                    <div class="iou-amount-cell">
                        <div class="iou-amount-label">paid</div>
                        <div class="iou-amount-val info" style="color:#125e80;">
                            ₹{{ number_format($iou->paid_amount ?? 0, 2) }}</div>
                    </div>

                    <div class="iou-amount-cell">

                        <div class="iou-amount-label">Net pending Balance</div>
                        <div class="iou-amount-val {{ ($balance?->pending_balance ?? 0) > 0 ? 'negative' : 'positive' }}">
                            ₹{{ number_format($balance?->pending_balance ?? 0, 2) }}
                        </div>

                    </div>
                </div>

                {{-- ── IOU Details ── --}}
                <div class="td-section-row">
                    <div class="td-section-title"><i class="ti ti-info-circle"
                            style="margin-right:6px;color:#3741b0;"></i>IOU Details</div>
                </div>
                <div class="td-detail-grid">
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Employee</div>
                        <div class="td-detail-val">{{ $iou->employee->FullName ?? '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Department</div>
                        <div class="td-detail-val">{{ $iou->department->DepartmentName ?? '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Category</div>
                        <div class="td-detail-val">{{ $iou->category->category_name ?? '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Type of Escalation</div>
                        <div class="td-detail-val">{{ $iou->issue->IssueName ?? '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Request Date</div>
                        <div class="td-detail-val">
                            {{ $iou->request_date ? date('d M Y', strtotime($iou->request_date)) : '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Purpose</div>
                        <div class="td-detail-val">{{ $iou->purpose ?? '—' }}</div>
                    </div>

                </div>
                @if ($balance)
                    <div class="td-section-row">
                        <div class="td-section-title"><i class="ti ti-user-dollar"
                                style="margin-right:6px;color:#3741b0;"></i>Employee Balance</div>
                    </div>
                    <div class="td-detail-grid">
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Total IOU Amount</div>
                            <div class="td-detail-val">₹{{ number_format($balance->total_iou_amount, 2) }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Total settled</div>
                            <div class="td-detail-val positive">₹{{ number_format($balance->total_settlement_amount, 2) }}
                            </div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Total Claims</div>
                            <div class="td-detail-val warning">₹{{ number_format($balance->total_claim_amount, 2) }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Net pending Balance</div>
                            <div class="td-detail-val {{ $balance->pending_balance > 0 ? 'negative' : 'positive' }}">
                                ₹{{ number_format($balance->pending_balance, 2) }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── Transactions ── --}}
                @if ($iou->transactions && $iou->transactions->count())
                    <div class="td-section-row">
                        <div class="td-section-title"><i class="ti ti-transfer"
                                style="margin-right:6px;color:#3741b0;"></i>Transactions</div>
                    </div>
                    <div class="table-responsive">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($iou->transactions as $k => $txn)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td><span
                                                class="td-pill pill-inprogress">{{ ucfirst(str_replace('_', ' ', $txn->type)) }}</span>
                                        </td>
                                        <td style="font-weight:700;color:#18284d;">₹{{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td>{{ $txn->remarks ?? '—' }}</td>
                                        <td>{{ $txn->created_at ? date('d M Y h:i A', strtotime($txn->created_at)) : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                <div class="td-section-row">
                    <div class="td-section-title">
                        <i class="ti ti-history" style="margin-right:6px;color:#3741b0;"></i>
                        Action History
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="td-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Changed By</th>
                                <th>Designation</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($actionHistory as $k => $history)
                                <tr>

                                    <td>{{ $k + 1 }}</td>

                                    <td>
                                        <span
                                            class="td-pill
                            {{ $history->status == 'approved' ? 'pill-success' : '' }}
                            {{ $history->status == 'rejected' ? 'pill-danger' : '' }}
                            {{ $history->status == 'pending' ? 'pill-inprogress' : '' }}">

                                            {{ ucfirst($history->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $history->remarks ?? '—' }}
                                    </td>

                                    <td>
                                        {{ $history->changedBy }}
                                    </td>

                                    <td>
                                        {{ $history->designation }}
                                    </td>

                                    <td>
                                        {{ $history->changedAt ? date('d M Y h:i A', strtotime($history->changedAt)) : '—' }}
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        No history found
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

                {{-- ── Settlements ── --}}
                @if ($iou->settlements && $iou->settlements->count())
                    <div class="td-section-row">
                        <div class="td-section-title"><i class="ti ti-file-invoice"
                                style="margin-right:6px;color:#5c35aa;"></i>Settlements</div>
                    </div>
                    <div class="table-responsive">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Settlement Date</th>
                                    <th>Actual Expense</th>
                                    <th>Returned Amount</th>
                                    <th>Extra Claim</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($iou->settlements as $k => $s)
                                    <tr class="settle-row">
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $s->settlement_date ? date('d M Y', strtotime($s->settlement_date)) : '—' }}
                                        </td>
                                        <td style="font-weight:700;color:#18284d;">
                                            ₹{{ number_format($s->actual_expense, 2) }}</td>
                                        <td style="color:#1a7a3c;font-weight:600;">
                                            ₹{{ number_format($s->returned_amount, 2) }}</td>
                                        <td style="color:#b52020;font-weight:600;">
                                            ₹{{ number_format($s->extra_claim_amount, 2) }}</td>
                                        <td>{{ $s->remarks ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- ── Claims ── --}}
                @if ($iou->claims && $iou->claims->count())
                    <div class="td-section-row">
                        <div class="td-section-title"><i class="ti ti-receipt"
                                style="margin-right:6px;color:#125e80;"></i>Claims</div>
                    </div>
                    <div class="table-responsive">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Expense Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($iou->claims as $k => $c)
                                    <tr>
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $c->expense_date ? date('d M Y', strtotime($c->expense_date)) : '—' }}</td>
                                        <td>{{ $c->expense_type ?? '—' }}</td>
                                        <td style="font-weight:700;color:#18284d;">
                                            ₹{{ number_format($c->expense_amount, 2) }}</td>
                                        <td>{{ $c->remarks ?? '—' }}</td>
                                        <td>
                                            <span
                                                class="td-pill
                                @if ($c->status === 'pending') pill-pending
                                @elseif($c->status === 'approved') pill-approved
                                @elseif($c->status === 'rejected') pill-rejected
                                @else pill-pending @endif">{{ $c->status }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <div style="height:8px;"></div>
            </div>{{-- /td-card --}}

            <div class="td-footer">Powered by Vecura &nbsp;·&nbsp; All rights reserved</div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
     MODAL 1: Approval / Rejection
     ════════════════════════════════════════ --}}
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(55,65,176,.18);">
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title"><i class="ti ti-clipboard-check" style="font-size:17px;"></i> Review IOU
                        Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $iou->employee->FullName ?? '—' }}</div>
                        <div class="ap-info-item"><strong>Requested:</strong>
                            ₹{{ number_format($iou->requested_amount, 2) }}</div>
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
                        <label class="ap-field-label">approved Amount <span class="req">*</span></label>
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

    {{-- ════════════════════════════════════════
     MODAL 2: Mark as paid
     ════════════════════════════════════════ --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(18,94,128,.18);">
                <div class="ap-modal-header info">
                    <h5 class="ap-modal-title"><i class="ti ti-cash" style="font-size:17px;"></i> Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>approved Amount:</strong>
                            ₹{{ number_format($iou->approved_amount ?? 0, 2) }}</div>
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $iou->employee->FullName ?? '—' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">paid Amount <span class="req">*</span></label>
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

    {{-- ════════════════════════════════════════
     MODAL 3: Settlement
     ════════════════════════════════════════ --}}
    <div class="modal fade" id="settlementModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(92,53,170,.18);">
                <div class="ap-modal-header purple">
                    <h5 class="ap-modal-title"><i class="ti ti-file-invoice" style="font-size:17px;"></i> Add Settlement
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>paid:</strong> ₹{{ number_format($iou->paid_amount ?? 0, 2) }}
                        </div>
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $iou->employee->FullName ?? '—' }}</div>
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Settlement Date <span class="req">*</span></label>
                        <input type="date" id="settlement_date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Actual Expense <span class="req">*</span></label>
                        <input type="number" id="actual_expense" class="form-control" min="0" step="0.01"
                            placeholder="Total actual expense incurred">
                    </div>
                    <div id="settlementCalcRow"
                        style="display:none;background:#f3f4f8;border-radius:5px;padding:10px 14px;margin-bottom:12px;font-size:13px;">
                        <div id="settlementCalcMsg" style="color:#18284d;font-weight:600;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Returned Amount</label>
                        <input type="number" id="returned_amount" class="form-control" min="0" step="0.01"
                            value="0" placeholder="Amount returned by employee (if any)">
                        <small class="text-muted">Fill if actual expense &lt; paid amount</small>
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Extra Claim Amount</label>
                        <input type="number" id="extra_claim_amount" class="form-control" min="0"
                            step="0.01" value="0" placeholder="Extra claim by employee (if any)">
                        <small class="text-muted">Fill if actual expense &gt; paid amount</small>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Remarks <span class="req">*</span></label>
                        <textarea id="settlement_remarks" class="ap-textarea" rows="2" placeholder="Enter settlement remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit purple" id="settlementSubmitBtn"
                        onclick="submitSettlement()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Submit Settlement
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
     MODAL 4: Close Ticket
     ════════════════════════════════════════ --}}
    <div class="modal fade" id="closeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.12);">
                <div class="ap-modal-header" style="background:#4b5673;">
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
                    <button type="button" class="ap-btn-submit" style="background:#4b5673;" id="closeSubmitBtn"
                        onclick="submitClose()">
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

        // ── Helpers ──────────────────────────────────────────────────
        function setSubmitting(btnId, state) {
            const btn = document.getElementById(btnId);
            if (btn) btn.disabled = state;
        }

        function showWarn(msg) {
            Swal.fire('Warning', msg, 'warning');
        }

        function showError(msg) {
            Swal.fire('Error', msg, 'error');
        }

        function showSuccess(msg, cb) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: msg,
                timer: 1600,
                showConfirmButton: false
            }).then(cb);
        }

        function openModal(id) {
            new bootstrap.Modal(document.getElementById(id)).show();
        }

        function closeModal(id) {
            bootstrap.Modal.getInstance(document.getElementById(id))?.hide();
        }

        // ── MODAL 1: Approval ────────────────────────────────────────
        function openApprovalModal() {
            openModal('approvalModal');
        }

        function selectDecision(val) {
            document.getElementById('choiceApprove').classList.toggle('sel-approved', val === 'approved');
            document.getElementById('choiceReject').classList.toggle('sel-rejected', val === 'rejected');
            document.getElementById('approvedAmountRow').style.display = val === 'approved' ? 'block' : 'none';
            // store selection
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
            if (decision === 'approved' && (!amount || amount <= 0)) return showWarn(
                'Please enter a valid approved amount.');
            if (!remarks) return showWarn('Please enter remarks.');

            submittingApproval = true;
            setSubmitting('approvalSubmitBtn', true);

            $.ajax({
                url: `/iou/${IOU_ID}/approve`,
                type: 'POST',
                data: {
                    decision,
                    approved_amount: amount,
                    remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => {
                    submittingApproval = false;
                    setSubmitting('approvalSubmitBtn', false);
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.');
                }
            });
        }

        // ── MODAL 2: Payment ─────────────────────────────────────────
        function openPaymentModal() {
            openModal('paymentModal');
        }

        let submittingPayment = false;

        function submitPayment() {
            if (submittingPayment) return;
            const amount = document.getElementById('paid_amount').value;
            const mode = document.getElementById('payment_mode').value;
            const remarks = document.getElementById('payment_remarks').value.trim();

            if (!amount || amount <= 0) return showWarn('Please enter a valid paid amount.');
            if (!mode) return showWarn('Please select payment mode.');
            if (!remarks) return showWarn('Please enter remarks.');

            submittingPayment = true;
            setSubmitting('paymentSubmitBtn', true);

            $.ajax({
                url: `/iou/${IOU_ID}/pay`,
                type: 'POST',
                data: {
                    paid_amount: amount,
                    payment_mode: mode,
                    remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => {
                    submittingPayment = false;
                    setSubmitting('paymentSubmitBtn', false);
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.');
                }
            });
        }

        // ── MODAL 3: Settlement ──────────────────────────────────────
        function openSettlementModal() {
            openModal('settlementModal');
        }

        // Auto-calculate returned/extra when actual_expense changes
        document.addEventListener('DOMContentLoaded', function() {
            const expInput = document.getElementById('actual_expense');
            if (expInput) {
                expInput.addEventListener('input', function() {
                    const actual = parseFloat(this.value) || 0;
                    const paid = paid_AMOUNT;
                    const diff = paid - actual;
                    const calcRow = document.getElementById('settlementCalcRow');
                    const calcMsg = document.getElementById('settlementCalcMsg');
                    if (actual > 0) {
                        calcRow.style.display = 'block';
                        if (diff > 0) {
                            calcMsg.innerHTML =
                                `<span style="color:#1a7a3c;">Employee should return ₹${diff.toFixed(2)}</span>`;
                            document.getElementById('returned_amount').value = diff.toFixed(2);
                            document.getElementById('extra_claim_amount').value = '0';
                        } else if (diff < 0) {
                            calcMsg.innerHTML =
                                `<span style="color:#b52020;">Employee should claim ₹${Math.abs(diff).toFixed(2)} extra</span>`;
                            document.getElementById('extra_claim_amount').value = Math.abs(diff).toFixed(2);
                            document.getElementById('returned_amount').value = '0';
                        } else {
                            calcMsg.innerHTML =
                                `<span style="color:#18284d;">Amounts match perfectly</span>`;
                            document.getElementById('returned_amount').value = '0';
                            document.getElementById('extra_claim_amount').value = '0';
                        }
                    } else {
                        calcRow.style.display = 'none';
                    }
                });
            }
        });

        let submittingSettlement = false;

        function submitSettlement() {
            if (submittingSettlement) return;
            const date = document.getElementById('settlement_date').value;
            const actual = document.getElementById('actual_expense').value;
            const returned = document.getElementById('returned_amount').value || 0;
            const extra = document.getElementById('extra_claim_amount').value || 0;
            const remarks = document.getElementById('settlement_remarks').value.trim();

            if (!date) return showWarn('Please select settlement date.');
            if (!actual || actual <= 0) return showWarn('Please enter actual expense amount.');
            if (!remarks) return showWarn('Please enter remarks.');

            submittingSettlement = true;
            setSubmitting('settlementSubmitBtn', true);

            $.ajax({
                url: `/iou/${IOU_ID}/settle`,
                type: 'POST',
                data: {
                    settlement_date: date,
                    actual_expense: actual,
                    returned_amount: returned,
                    extra_claim_amount: extra,
                    remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => {
                    submittingSettlement = false;
                    setSubmitting('settlementSubmitBtn', false);
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.');
                }
            });
        }

        // ── MODAL 4: Close ───────────────────────────────────────────
        function openCloseModal() {
            openModal('closeModal');
        }

        let submittingClose = false;

        function submitClose() {
            if (submittingClose) return;
            const remarks = document.getElementById('close_remarks').value.trim();
            if (!remarks) return showWarn('Please enter closing remarks.');

            submittingClose = true;
            setSubmitting('closeSubmitBtn', true);

            $.ajax({
                url: `/iou/${IOU_ID}/close`,
                type: 'POST',
                data: {
                    remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => {
                    submittingClose = false;
                    setSubmitting('closeSubmitBtn', false);
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.');
                }
            });
        }
    </script>
@endsection
