<?php $page = 'tickets'; ?>
@extends('layout.mainlayout')
@section('content')

    <style>
        .crumb-back {
            color: #22304d; text-decoration: none; display: inline-flex; align-items: center;
            gap: 6px; margin-bottom: 14px; font-weight: 600; font-size: 13px;
        }
        .crumb-back:hover { color: #3741b0; text-decoration: none; }

        .details-card {
            background: #fff; border: 1px solid #e7e8eb; border-radius: 8px;
            overflow: hidden; box-shadow: 0 1px 2px rgba(20,24,50,.04);
        }
        .details-card-header {
            padding: 13px 18px; border-bottom: 1px solid #e7e8eb; font-size: 15.5px;
            font-weight: 700; color: #132144; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        }
        .dch-count {
            font-size: 11.5px; font-weight: 600; color: #8890a6; background: #f1f2f5;
            border-radius: 20px; padding: 2px 9px;
        }

        /* ── Status flow ── */
        .status-flow { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; padding: 14px 18px 0; }
        .sf-item { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; border: 2px solid transparent; background: #f3f4f8; color: #888; }
        .sf-item.active { border-color: currentColor; }

        /* ── Detail item grid ── */
        .client-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; padding: 18px; }
        .client-item { display: flex; gap: 14px; align-items: flex-start; }
        .client-icon {
            width: 34px; height: 34px; border-radius: 50%; background: #f3f4f8; display: flex;
            align-items: center; justify-content: center; color: #6b738a; flex-shrink: 0;
        }
        .client-label { font-size: 11px; font-weight: 700; color: #8890a6; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 3px; }
        .client-value { font-size: 13.5px; color: #18284d; font-weight: 600; line-height: 1.4; }

        /* ── Amount summary strip ── */
        .amount-strip { display: grid; grid-template-columns: repeat(4, 1fr); border-top: 1px solid #e7e8eb; border-bottom: 1px solid #e7e8eb; }
        .amount-cell { padding: 15px 16px; border-right: 1px solid #e7e8eb; text-align: center; }
        .amount-cell:last-child { border-right: none; }
        .amount-label { font-size: 11px; font-weight: 600; color: #8890a6; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 5px; }
        .amount-val { font-size: 19px; font-weight: 700; color: #132144; font-family: 'JetBrains Mono','SF Mono',Consolas,monospace; }
        .amount-val.green  { color: #1a7a3c; }
        .amount-val.blue   { color: #125e80; }
        .amount-val.red    { color: #b52020; }
        .amount-val.purple { color: #5c35aa; }
        .amount-val.amber  { color: #8f5e00; }

        /* ── Grid tables ── */
        .grid-table-wrap { overflow-x: auto; }
        .grid-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 580px; }
        .grid-table thead th {
            position: sticky; top: 0; background: #3741b0; color: #fff; font-size: 11.5px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .3px; padding: 10px 14px; text-align: left;
            white-space: nowrap; border: none; z-index: 2;
        }
        .grid-table thead th.num, .grid-table tbody td.num { text-align: right; }
        .grid-table thead th.center, .grid-table tbody td.center { text-align: center; }
        .grid-table tbody tr { border-bottom: 1px solid #eceef3; transition: background .12s; }
        .grid-table tbody tr:nth-child(even) { background: #fafbfd; }
        .grid-table tbody tr:last-child { border-bottom: none; }
        .grid-table tbody tr:hover { background: #f1f3fb; }
        .grid-table tbody td { padding: 10.5px 14px; font-size: 13px; color: #4f5d7c; vertical-align: middle; }
        .grid-table tbody td.amount-cell {
            font-weight: 700; color: #18284d; font-family: 'JetBrains Mono','SF Mono',Consolas,monospace; text-align: right;
        }
        .grid-table tfoot td {
            background: #f3f4f8; font-weight: 700; padding: 10px 14px; font-size: 13px;
            color: #18284d; border-top: 2px solid #e7e8eb;
        }
        .grid-table .row-idx { color: #b0b6c5; font-weight: 600; font-size: 12px; }
        .grid-table .designation-tag {
            font-size: 11px; font-weight: 600; color: #5c6b8a; background: #eef0fb;
            border-radius: 4px; padding: 2px 8px; display: inline-block;
        }
        .grid-empty-row td { text-align: center; padding: 26px 14px; color: #9ba2b8; font-size: 13px; }
        .grid-icon-btn {
            display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px;
            border-radius: 6px; border: 1px solid #e7e8eb; background: #fff; color: #6b748a;
            cursor: pointer; transition: all .15s; font-size: 13px; text-decoration: none;
        }
        .grid-icon-btn:hover { background: #e4f3fb; color: #125e80; border-color: #9fd5ed; }

        /* ── Pills ── */
        .td-pill {
            display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 5px;
            font-size: 12px; font-weight: 700; white-space: nowrap; border: 1px solid transparent;
        }
        .pill-pending      { background:#fef6e4; color:#8f5e00; border-color:#f0d48a; }
        .pill-approved     { background:#e8f7ef; color:#1a7a3c; border-color:#a8dfc0; }
        .pill-rejected     { background:#fdeaea; color:#b52020; border-color:#f5c0c0; }
        .pill-received     { background:#d1fae5; color:#065f46; border-color:#6ee7b7; }
        .pill-closed       { background:#f1f2f5; color:#4b5673; border-color:#d1d5e0; }
        .pill-inprogress   { background:#fff3cd; color:#856404; border-color:#ffc107; }

        /* ── Right sidebar: balance card ── */
        .balance-hero {
            background: linear-gradient(135deg, #3741b0, #5761d0); border-radius: 10px; color: #fff;
            padding: 20px; text-align: center; margin-bottom: 14px; position: relative; overflow: hidden;
        }
        .balance-hero::after {
            content: ''; position: absolute; top: -30px; right: -30px; width: 110px; height: 110px;
            border-radius: 50%; background: rgba(255,255,255,.07);
        }
        .balance-hero-label { font-size: 11.5px; font-weight: 600; opacity: .85; text-transform: uppercase; letter-spacing: .4px; margin-bottom: 5px; }
        .balance-hero-val { font-size: 30px; font-weight: 800; font-family: 'JetBrains Mono','SF Mono',Consolas,monospace; position: relative; z-index: 1; }
        .balance-hero-sub { font-size: 11.5px; opacity: .8; margin-top: 4px; }

        .stat-mini-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px; }
        .stat-mini { border: 1px solid #e7e8eb; border-radius: 7px; padding: 10px 12px; text-align: center; background: #fafbfd; }
        .stat-mini-label { font-size: 10.5px; color: #8890a6; font-weight: 600; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 4px; }
        .stat-mini-val { font-size: 14px; font-weight: 700; font-family: 'JetBrains Mono','SF Mono',Consolas,monospace; }
        .stat-mini-val.c-primary { color: #3741b0; }
        .stat-mini-val.c-success { color: #1a7a3c; }
        .stat-mini-val.c-warning { color: #8f5e00; }
        .stat-mini-val.c-info    { color: #125e80; }

        /* ── Action card ── */
        .action-card-inner { padding: 16px; }
        .action-btn-row { display: flex; flex-direction: column; gap: 10px; }
        .ac-btn {
            display: flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 16px;
            border-radius: 8px; font-size: 14px; font-weight: 700; border: none; cursor: pointer;
            width: 100%; transition: opacity .15s, transform .1s;
        }
        .ac-btn:hover { opacity: .88; }
        .ac-btn:active { transform: translateY(1px); }
        .ac-btn-primary  { background: #3741b0; color: #fff; }
        .ac-btn-teal     { background: #065f46; color: #fff; }
        .ac-btn-close    { background: #4b5673; color: #fff; }

        .status-banner {
            display: flex; flex-direction: column; align-items: center; gap: 6px;
            padding: 22px 16px; border-radius: 8px; text-align: center;
        }
        .status-banner.is-closed { background: #e8f7ef; color: #1a7a3c; }
        .status-banner.is-rejected { background: #fdeaea; color: #b52020; }
        .status-banner i { font-size: 26px; }
        .status-banner strong { font-size: 14px; }

        /* ── Modal shared ── */
        .ap-modal-header {
            background: #3741b0; padding: 14px 20px; border-bottom: none; border-radius: 6px 6px 0 0;
            display: flex; align-items: center; justify-content: space-between;
        }
        .ap-modal-title { font-size: 15px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px; margin: 0; }
        .ap-modal-header .btn-close { filter: invert(1) brightness(2); opacity: .8; }
        .ap-modal-header.dark   { background: #4b5673; }
        .ap-modal-header.teal   { background: #065f46; }
        .ap-modal-body { padding: 20px 20px 10px; }
        .ap-info-strip { background: #f3f4f8; border-radius: 5px; padding: 10px 14px; margin-bottom: 16px; display: flex; gap: 20px; flex-wrap: wrap; }
        .ap-info-item { font-size: 13px; color: #6b748a; }
        .ap-info-item strong { color: #18284d; font-weight: 700; }
        .ap-field-label { font-size: 13px; font-weight: 700; color: #18284d; margin-bottom: 7px; display: block; }
        .ap-field-label .req { color: #e03535; margin-left: 2px; }
        .ap-textarea {
            width: 100%; font-size: 13px; color: #18284d; border: 1px solid #d0d3de; border-radius: 5px;
            padding: 8px 10px; outline: none; resize: vertical; transition: border-color .15s; font-family: inherit;
        }
        .ap-textarea:focus { border-color: #3741b0; box-shadow: 0 0 0 3px rgba(55,65,176,.10); }
        .ap-modal-footer { padding: 12px 20px 16px; display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #e7e8eb; }
        .ap-btn-cancel {
            padding: 7px 18px; font-size: 13px; font-weight: 600; border-radius: 5px; border: 1px solid #d0d3de;
            background: #fff; color: #4b5673; cursor: pointer;
        }
        .ap-btn-cancel:hover { background: #f3f4f8; }
        .ap-btn-submit {
            padding: 7px 22px; font-size: 13px; font-weight: 700; border-radius: 5px; border: none;
            background: #3741b0; color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: opacity .15s;
        }
        .ap-btn-submit:hover { opacity: .88; }
        .ap-btn-submit:disabled { opacity: .6; cursor: not-allowed; }
        .ap-btn-submit.teal   { background: #065f46; }

        .ap-choice-row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 4px; }
        .ap-choice-card {
            border: 1px solid #d0d3de; border-radius: 6px; padding: 11px 14px; display: flex; align-items: center;
            gap: 10px; cursor: pointer; font-size: 13px; font-weight: 600; color: #18284d; position: relative;
            user-select: none; transition: border-color .15s, background .15s;
        }
        .ap-choice-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
        .ap-choice-dot { width: 16px; height: 16px; border-radius: 50%; border: 2px solid #c0c6d4; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .ap-choice-dot::after { content: ''; width: 8px; height: 8px; border-radius: 50%; background: transparent; }
        .ap-choice-card.sel-approved { border-color: #1a7a3c; background: #f0fbf5; }
        .ap-choice-card.sel-approved .ap-choice-dot { border-color: #1a7a3c; }
        .ap-choice-card.sel-approved .ap-choice-dot::after { background: #1a7a3c; }
        .ap-choice-card.sel-rejected { border-color: #b52020; background: #fff5f5; }
        .ap-choice-card.sel-rejected .ap-choice-dot { border-color: #b52020; }
        .ap-choice-card.sel-rejected .ap-choice-dot::after { background: #b52020; }

        .settle-item-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .settle-item-table th { background: #5c35aa; color: #fff; padding: 7px 9px; font-weight: 600; text-align: left; white-space: nowrap; }
        .settle-item-table td { padding: 7px 9px; border-bottom: 1px solid #e7e8eb; color: #18284d; }
        .settle-item-table tr:last-child td { border-bottom: none; }

        .calc-hint { background: #f3f4f8; border-radius: 5px; padding: 9px 13px; font-size: 13px; font-weight: 600; color: #18284d; margin-bottom: 12px; }

        /* ── Direction dropdown cards (the unified action picker) ── */
        .direction-card {
            border: 1px solid #d0d3de; border-radius: 8px; padding: 12px 14px; margin-bottom: 10px;
            cursor: pointer; transition: border-color .15s, background .15s; position: relative;
        }
        .direction-card input[type="radio"] { position: absolute; opacity: 0; }
        .direction-card-top { display: flex; align-items: center; gap: 8px; font-weight: 700; font-size: 13.5px; color: #18284d; margin-bottom: 3px; }
        .direction-card-sub { font-size: 12px; color: #8890a6; margin-left: 26px; }
        .direction-card.sel-pay { border-color: #065f46; background: #f0fdf7; }
        .direction-card.sel-receive { border-color: #8f5e00; background: #fffaf0; }
        .direction-card.disabled { opacity: .45; cursor: not-allowed; }

        @media(max-width:991.98px) {
            .client-grid { grid-template-columns: 1fr 1fr; }
            .amount-strip { grid-template-columns: repeat(2,1fr); }
        }
        @media(max-width:575.98px) {
            .client-grid { grid-template-columns: 1fr; }
            .ap-choice-row { grid-template-columns: 1fr; }
            .stat-mini-grid { grid-template-columns: 1fr 1fr; }
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

                    {{-- Settlement Details card --}}
                    <div class="details-card mb-3">
                        <div class="details-card-header">
                            <i class="ti ti-file-invoice" style="color:#5c35aa;"></i>
                            Settlement — #{{ $ticket->ticketId ?? '—' }}
                            @php
                                $statusPill = [
                                    'PENDING'  => ['pill-pending',  'Pending'],
                                    'APPROVED' => ['pill-approved', 'Approved'],
                                    'REJECTED' => ['pill-rejected', 'Rejected'],
                                    'RECEIVED' => ['pill-received', 'Received'],
                                    'CLOSED'   => ['pill-closed',   'Closed'],
                                ];
                                $curStatus = $settlement->settlement_status ?? 'PENDING';
                                [$pillClass, $pillLabel] = $statusPill[$curStatus] ?? ['pill-pending', 'Pending'];
                            @endphp
                            <span class="td-pill {{ $pillClass }} ms-auto">{{ $pillLabel }}</span>
                        </div>

                        {{-- Status flow: PENDING -> APPROVED -> RECEIVED -> CLOSED --}}
                        <div class="status-flow">
                            @php
                                $flowOrder = ['PENDING', 'APPROVED', 'RECEIVED', 'CLOSED'];
                                $flowLabels = ['PENDING' => 'Pending', 'APPROVED' => 'Approved', 'RECEIVED' => 'Received', 'CLOSED' => 'Closed'];
                                $flowColors = ['PENDING' => '#8f5e00', 'APPROVED' => '#1a7a3c', 'RECEIVED' => '#065f46', 'CLOSED' => '#4b5673'];
                            @endphp
                            @foreach ($flowOrder as $step)
                                <span class="sf-item {{ $curStatus === $step ? 'active' : '' }}" style="color:{{ $flowColors[$step] }};">
                                    {{ $flowLabels[$step] }}
                                </span>
                                @if (!$loop->last)
                                    <span class="text-muted" style="font-size:16px;line-height:1.4;">→</span>
                                @endif
                            @endforeach
                            @if ($curStatus === 'REJECTED')
                                <span class="sf-item active" style="color:#b52020;border-color:#b52020;">Rejected</span>
                            @endif
                        </div>

                        {{-- Amount strip --}}
                        @php
                            $remainingClaim = max(0, ($settlement->employee_claim_amount ?? 0) - ($settlement->claim_transfer_amount ?? 0));
                            $remainingReturn = $settlement->remaining_balance ?? 0;
                        @endphp
                        <div class="amount-strip" style="margin-top:14px;">
                            <div class="amount-cell">
                                <div class="amount-label">Total Bill</div>
                                <div class="amount-val">₹{{ number_format($settlement->total_bill_amount ?? 0, 2) }}</div>
                            </div>
                            <div class="amount-cell">
                                <div class="amount-label">Company Settled</div>
                                <div class="amount-val green">₹{{ number_format($settlement->company_settlement_amount ?? 0, 2) }}</div>
                            </div>
                            <div class="amount-cell">
                                <div class="amount-label">Owed to Employee</div>
                                <div class="amount-val {{ $remainingClaim > 0 ? 'red' : 'green' }}">₹{{ number_format($remainingClaim, 2) }}</div>
                            </div>
                            <div class="amount-cell">
                                <div class="amount-label">Owed by Employee</div>
                                <div class="amount-val {{ $remainingReturn > 0 ? 'amber' : 'green' }}">₹{{ number_format($remainingReturn, 2) }}</div>
                            </div>
                        </div>

                        {{-- Detail grid --}}
                        <div class="client-grid">
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-user"></i></div>
                                <div>
                                    <div class="client-label">Employee</div>
                                    <div class="client-value">{{ $settlement->employee->FullName ?? '—' }}</div>
                                </div>
                            </div>
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-tag"></i></div>
                                <div>
                                    <div class="client-label">Settlement Type</div>
                                    <div class="client-value">{{ $settlement->settlement_type === 'BILL' ? 'Bill Submission' : 'Cash Return' }}</div>
                                </div>
                            </div>
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-calendar"></i></div>
                                <div>
                                    <div class="client-label">Submitted On</div>
                                    <div class="client-value">{{ $settlement->created_at ? date('d M Y h:i A', strtotime($settlement->created_at)) : '—' }}</div>
                                </div>
                            </div>
                            @if ($settlement->approved_by)
                                <div class="client-item">
                                    <div class="client-icon"><i class="ti ti-user-check"></i></div>
                                    <div>
                                        <div class="client-label">Reviewed By</div>
                                        <div class="client-value">{{ $settlement->approver->FullName ?? '—' }}</div>
                                    </div>
                                </div>
                                <div class="client-item">
                                    <div class="client-icon"><i class="ti ti-calendar-check"></i></div>
                                    <div>
                                        <div class="client-label">Reviewed At</div>
                                        <div class="client-value">{{ $settlement->approved_at ? date('d M Y h:i A', strtotime($settlement->approved_at)) : '—' }}</div>
                                    </div>
                                </div>
                            @endif
                            <div class="client-item" style="grid-column:1/-1;">
                                <div class="client-icon"><i class="ti ti-message"></i></div>
                                <div>
                                    <div class="client-label">Remarks</div>
                                    <div class="client-value">{{ $settlement->remarks ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bill Items --}}
                    @if ($settlement->settlement_type === 'BILL' && $settlement->items && $settlement->items->count())
                        <div class="details-card mb-3">
                            <div class="details-card-header">
                                <i class="ti ti-receipt" style="color:#5c35aa;"></i> Bill Submission Items
                                <span class="dch-count">{{ $settlement->items->count() }}</span>
                            </div>
                            <div class="grid-table-wrap">
                                <table class="grid-table">
                                    <thead>
                                        <tr>
                                            <th style="width:44px;">#</th>
                                            <th>Expense Type</th>
                                            <th>Bill Date</th>
                                            <th class="num">Bill Amount</th>
                                            <th class="num">Settlement Amt</th>
                                            <th class="num">Employee Extra</th>
                                            <th class="center">File</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($settlement->items as $k => $item)
                                            <tr>
                                                <td class="row-idx">{{ $k + 1 }}</td>
                                                <td>{{ $item->expense_type ?? '—' }}</td>
                                                <td>{{ $item->bill_date ? date('d M Y', strtotime($item->bill_date)) : '—' }}</td>
                                                <td class="amount-cell">₹{{ number_format($item->bill_amount, 2) }}</td>
                                                <td class="num" style="color:#1a7a3c;font-weight:700;">₹{{ number_format($item->settlement_amount, 2) }}</td>
                                                <td class="num" style="color:#b52020;font-weight:700;">₹{{ number_format($item->employee_claim_amount, 2) }}</td>
                                                <td class="center">
                                                    @if ($item->bill_file)
                                                        <a href="{{ asset('uploads/settlements/' . $item->bill_file) }}" target="_blank" class="grid-icon-btn" title="View attachment">
                                                            <i class="ti ti-eye"></i>
                                                        </a>
                                                    @else
                                                        <span style="color:#c4c9d8;font-size:12px;">—</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->remarks ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">Total</td>
                                            <td class="num" style="font-family:'JetBrains Mono',monospace;">₹{{ number_format($settlement->total_bill_amount, 2) }}</td>
                                            <td class="num" style="color:#1a7a3c;font-family:'JetBrains Mono',monospace;">₹{{ number_format($settlement->company_settlement_amount, 2) }}</td>
                                            <td class="num" style="color:#b52020;font-family:'JetBrains Mono',monospace;">₹{{ number_format($settlement->employee_claim_amount, 2) }}</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Payment Transactions (unified — covers both pay & receive) --}}
                    @if ($paymentTransactions && $paymentTransactions->count())
                        <div class="details-card mb-3">
                            <div class="details-card-header">
                                <i class="ti ti-send" style="color:#065f46;"></i> Payment Transactions
                                <span class="dch-count">{{ $paymentTransactions->count() }}</span>
                            </div>
                            <div class="grid-table-wrap">
                                <table class="grid-table">
                                    <thead>
                                        <tr>
                                            <th style="width:44px;">#</th>
                                            <th>Direction</th>
                                            <th class="num">Amount</th>
                                            <th>Mode</th>
                                            <th>Remarks</th>
                                            <th>Processed By</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($paymentTransactions as $k => $txn)
                                            <tr>
                                                <td class="row-idx">{{ $k + 1 }}</td>
                                                <td>
                                                    @if ($txn->type === 'claim_transfer')
                                                        <span class="td-pill pill-received"><i class="ti ti-arrow-up-right"></i> Paid to Employee</span>
                                                    @else
                                                        <span class="td-pill pill-inprogress"><i class="ti ti-arrow-down-left"></i> Received from Employee</span>
                                                    @endif
                                                </td>
                                                <td class="num" style="font-weight:700;font-family:'JetBrains Mono',monospace;color:{{ $txn->type === 'claim_transfer' ? '#065f46' : '#8f5e00' }};">₹{{ number_format($txn->amount, 2) }}</td>
                                                <td>{{ $txn->payment_mode ?? '—' }}</td>
                                                <td>{{ $txn->remarks ?? '—' }}</td>
                                                <td style="font-weight:600;color:#18284d;">{{ $txn->creator->FullName ?? '—' }}</td>
                                                <td>{{ $txn->created_at ? date('d M Y h:i A', strtotime($txn->created_at)) : '—' }}</td>
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
                            <i class="ti ti-history" style="color:#3741b0;"></i> Action History
                            <span class="dch-count">{{ $actionHistory->count() ?? 0 }}</span>
                        </div>
                        <div class="grid-table-wrap">
                            <table class="grid-table">
                                <thead>
                                    <tr>
                                        <th style="width:44px;">#</th>
                                        <th>Date</th>
                                        <th style="width:140px;">Status</th>
                                        <th>Remarks</th>
                                        <th>Changed By</th>
                                        <th>Designation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($actionHistory as $k => $h)
                                        <tr>
                                            <td class="row-idx">{{ $k + 1 }}</td>
                                            <td>{{ $h->changedAt ? date('d M Y h:i A', strtotime($h->changedAt)) : '—' }}</td>
                                            <td>
                                                @php
                                                    $statusKey = strtolower($h->status ?? 'pending');
                                                    $hp = match($statusKey) {
                                                        'approved' => 'pill-approved',
                                                        'rejected' => 'pill-rejected',
                                                        'closed' => 'pill-closed',
                                                        'received' => 'pill-received',
                                                        'paid_employee' => 'pill-received',
                                                        'received_from_employee' => 'pill-inprogress',
                                                        default => 'pill-pending',
                                                    };
                                                @endphp
                                                <span class="td-pill {{ $hp }}">{{ ucfirst(str_replace('_', ' ', $h->status)) }}</span>
                                            </td>
                                            <td>{{ $h->remarks ?? '—' }}</td>
                                            <td style="font-weight:600;color:#18284d;">{{ $h->changedBy }}</td>
                                            <td><span class="designation-tag">{{ $h->designation }}</span></td>
                                        </tr>
                                    @empty
                                        <tr class="grid-empty-row">
                                            <td colspan="6">
                                                <i class="ti ti-history" style="font-size:22px;display:block;margin-bottom:6px;color:#c4c9d8;"></i>
                                                No history found
                                            </td>
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
                                <i class="ti ti-user-dollar" style="color:#3741b0;"></i> Employee Balance
                            </div>
                            <div class="p-3">
                                <div class="balance-hero">
                                    <div class="balance-hero-label">Net Pending Balance</div>
                                    <div class="balance-hero-val">₹{{ number_format($balance->pending_balance, 2) }}</div>
                                    <div class="balance-hero-sub">
                                        {{ $balance->pending_balance > 0 ? 'Employee owes company' : 'Fully settled' }}
                                    </div>
                                </div>
                                <div class="stat-mini-grid">
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Total IOU</div>
                                        <div class="stat-mini-val c-primary">₹{{ number_format($balance->total_iou_amount, 2) }}</div>
                                    </div>
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Total Settled</div>
                                        <div class="stat-mini-val c-success">₹{{ number_format($balance->total_settlement_amount, 2) }}</div>
                                    </div>
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Total Claims</div>
                                        <div class="stat-mini-val c-warning">₹{{ number_format($balance->total_claim_amount, 2) }}</div>
                                    </div>
                                    <div class="stat-mini">
                                        <div class="stat-mini-label">Claim Pending</div>
                                        <div class="stat-mini-val c-info">₹{{ number_format($balance->pending_claim_amount ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Action Card — single unified Process Payment button --}}
                    @if (!in_array($curStatus, ['CLOSED', 'REJECTED']))
                        <div class="details-card">
                            <div class="details-card-header">
                                <i class="ti ti-settings" style="color:#3741b0;"></i> Actions
                            </div>
                            <div class="action-card-inner">
                                <div class="action-btn-row">
                                    @if ($curStatus === 'PENDING')
                                        <button class="ac-btn ac-btn-primary" onclick="openReviewModal()">
                                            <i class="ti ti-clipboard-check"></i> Review Settlement
                                        </button>
                                    @endif

                                    @if (in_array($curStatus, ['APPROVED', 'RECEIVED']) && ($remainingClaim > 0 || $remainingReturn > 0))
                                        <button class="ac-btn ac-btn-teal" onclick="openPaymentModal()">
                                            <i class="ti ti-arrows-exchange"></i> Process Payment
                                        </button>
                                    @endif

                                    @if (in_array($curStatus, ['APPROVED', 'RECEIVED']))
                                        <button class="ac-btn ac-btn-close" onclick="openCloseModal()">
                                            <i class="ti ti-lock"></i> Close Settlement
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="details-card">
                            <div class="status-banner {{ $curStatus === 'CLOSED' ? 'is-closed' : 'is-rejected' }}">
                                <i class="ti ti-{{ $curStatus === 'CLOSED' ? 'circle-check' : 'circle-x' }}"></i>
                                <strong>This Settlement is {{ $curStatus === 'CLOSED' ? 'Closed' : 'Rejected' }}</strong>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>

    {{-- ══ MODALS ══════════════════════════════════════════════════════ --}}

    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
            <div class="modal-content" style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(55,65,176,.18);">
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title"><i class="ti ti-clipboard-check" style="font-size:17px;"></i> Review Settlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $settlement->employee->FullName ?? '—' }}</div>
                        <div class="ap-info-item"><strong>Type:</strong> {{ $settlement->settlement_type === 'BILL' ? 'Bill Submission' : 'Cash Return' }}</div>
                        <div class="ap-info-item"><strong>Total Bill:</strong> ₹{{ number_format($settlement->total_bill_amount, 2) }}</div>
                        <div class="ap-info-item"><strong>Company Settles:</strong> ₹{{ number_format($settlement->company_settlement_amount, 2) }}</div>
                        @if (($settlement->employee_claim_amount ?? 0) > 0)
                            <div class="ap-info-item" style="color:#b52020;"><strong>Owed to Employee:</strong> ₹{{ number_format($settlement->employee_claim_amount, 2) }}</div>
                        @endif
                        @if (($settlement->remaining_balance ?? 0) > 0)
                            <div class="ap-info-item" style="color:#8f5e00;"><strong>Owed by Employee:</strong> ₹{{ number_format($settlement->remaining_balance, 2) }}</div>
                        @endif
                    </div>

                    @if ($settlement->settlement_type === 'BILL' && $settlement->items && $settlement->items->count())
                        <div class="mb-3">
                            <label class="ap-field-label">Bill Items</label>
                            <table class="settle-item-table">
                                <thead><tr><th>Expense Type</th><th>Bill Date</th><th>Bill Amt</th><th>Settle</th><th>Extra</th></tr></thead>
                                <tbody>
                                    @foreach ($settlement->items as $item)
                                        <tr>
                                            <td>{{ $item->expense_type }}</td>
                                            <td>{{ $item->bill_date ? date('d M Y', strtotime($item->bill_date)) : '—' }}</td>
                                            <td>₹{{ number_format($item->bill_amount, 2) }}</td>
                                            <td style="color:#1a7a3c;font-weight:600;">₹{{ number_format($item->settlement_amount, 2) }}</td>
                                            <td style="color:#b52020;font-weight:600;">₹{{ number_format($item->employee_claim_amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="ap-field-label">Decision <span class="req">*</span></label>
                        <div class="ap-choice-row">
                            <label class="ap-choice-card" id="choiceApprove" onclick="selectDecision('approved')">
                                <input type="radio" name="settle_decision" value="approved">
                                <div class="ap-choice-dot"></div>
                                <i class="ti ti-circle-check" style="font-size:18px;color:#1a7a3c;"></i>
                                <span>Approve</span>
                            </label>
                            <label class="ap-choice-card" id="choiceReject" onclick="selectDecision('rejected')">
                                <input type="radio" name="settle_decision" value="rejected">
                                <div class="ap-choice-dot"></div>
                                <i class="ti ti-circle-x" style="font-size:18px;color:#b52020;"></i>
                                <span>Reject</span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Remarks <span class="req">*</span></label>
                        <textarea id="review_remarks" class="ap-textarea" rows="3" placeholder="Enter review remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit" id="reviewSubmitBtn" onclick="submitReview()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- UNIFIED Process Payment Modal — direction dropdown decides everything --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
            <div class="modal-content" style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(6,95,70,.18);">
                <div class="ap-modal-header teal">
                    <h5 class="ap-modal-title"><i class="ti ti-arrows-exchange" style="font-size:17px;"></i> Process Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $settlement->employee->FullName ?? '—' }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="ap-field-label">Direction <span class="req">*</span></label>

                        <label class="direction-card {{ $remainingClaim <= 0 ? 'disabled' : '' }}" id="dirPayCard" onclick="selectDirection('pay_employee')">
                            <input type="radio" name="payment_direction" value="pay_employee" {{ $remainingClaim <= 0 ? 'disabled' : '' }}>
                            <div class="direction-card-top">
                                <i class="ti ti-arrow-up-right" style="color:#065f46;"></i> Pay to Employee
                            </div>
                            <div class="direction-card-sub">Company owes ₹{{ number_format($remainingClaim, 2) }} extra claim</div>
                        </label>

                        <label class="direction-card {{ $remainingReturn <= 0 ? 'disabled' : '' }}" id="dirReceiveCard" onclick="selectDirection('receive_employee')">
                            <input type="radio" name="payment_direction" value="receive_employee" {{ $remainingReturn <= 0 ? 'disabled' : '' }}>
                            <div class="direction-card-top">
                                <i class="ti ti-arrow-down-left" style="color:#8f5e00;"></i> Receive from Employee
                            </div>
                            <div class="direction-card-sub">Employee owes ₹{{ number_format($remainingReturn, 2) }} cash return</div>
                        </label>
                    </div>

                    <div class="calc-hint" id="paymentHint" style="display:none;">
                        <i class="ti ti-info-circle"></i> This will mark the settlement as <strong>Received</strong>. Close it manually once confirmed.
                    </div>

                    <div class="mb-3">
                        <label class="ap-field-label">Amount <span class="req">*</span></label>
                        <input type="number" id="payment_amount" class="form-control" min="0.01" step="0.01" placeholder="Enter amount">
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
                        <textarea id="payment_remarks" class="ap-textarea" rows="2" placeholder="Enter remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit teal" id="paymentSubmitBtn" onclick="submitPayment()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="closeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
            <div class="modal-content" style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.12);">
                <div class="ap-modal-header dark">
                    <h5 class="ap-modal-title"><i class="ti ti-lock" style="font-size:17px;"></i> Close Settlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip" style="background:#fff8e1;">
                        <div class="ap-info-item" style="color:#8f5e00;">
                            <i class="ti ti-alert-triangle" style="margin-right:4px;"></i>
                            Closing this settlement is <strong>irreversible</strong>. Ensure all payments are complete.
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Closing Remarks <span class="req">*</span></label>
                        <textarea id="close_remarks" class="ap-textarea" rows="3" placeholder="Enter closing remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit" style="background:#4b5673;" id="closeSubmitBtn" onclick="submitClose()">
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
        const SETTLEMENT_ID = {{ $settlement->settlement_id }};
        const REMAINING_CLAIM = {{ $remainingClaim }};
        const REMAINING_RETURN = {{ $remainingReturn }};

        function setSubmitting(btnId, state) { const btn = document.getElementById(btnId); if (btn) btn.disabled = state; }
        function showWarn(msg)  { Swal.fire('Warning', msg, 'warning'); }
        function showError(msg) { Swal.fire('Error', msg, 'error'); }
        function showSuccess(msg, cb) { Swal.fire({ icon:'success', title:'Success', text:msg, timer:1800, showConfirmButton:false }).then(cb); }
        function openModal(id)  { new bootstrap.Modal(document.getElementById(id)).show(); }
        function closeModal(id) { bootstrap.Modal.getInstance(document.getElementById(id))?.hide(); }

        function openReviewModal() { openModal('reviewModal'); }

        function selectDecision(val) {
            document.getElementById('choiceApprove').classList.toggle('sel-approved', val === 'approved');
            document.getElementById('choiceReject').classList.toggle('sel-rejected', val === 'rejected');
            document.getElementById('choiceApprove').querySelector('input').checked = (val === 'approved');
            document.getElementById('choiceReject').querySelector('input').checked = (val === 'rejected');
        }

        let submittingReview = false;
        function submitReview() {
            if (submittingReview) return;
            const decision = document.querySelector('input[name="settle_decision"]:checked')?.value;
            const remarks = document.getElementById('review_remarks').value.trim();
            if (!decision) return showWarn('Please select Approve or Reject.');
            if (!remarks) return showWarn('Please enter remarks.');
            submittingReview = true;
            setSubmitting('reviewSubmitBtn', true);
            $.ajax({
                url: `/settlement/${SETTLEMENT_ID}/review`, type: 'POST',
                data: { decision, remarks, _token: $('meta[name="csrf-token"]').attr('content') },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => { submittingReview = false; setSubmitting('reviewSubmitBtn', false); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        }

        // ── Unified Payment Modal ──────────────────────────────────────
        function openPaymentModal() {
            resetPaymentModal();
            openModal('paymentModal');
        }

        function resetPaymentModal() {
            document.getElementById('dirPayCard').classList.remove('sel-pay');
            document.getElementById('dirReceiveCard').classList.remove('sel-receive');
            document.querySelectorAll('input[name="payment_direction"]').forEach(el => el.checked = false);
            document.getElementById('payment_amount').value = '';
            document.getElementById('payment_mode').value = '';
            document.getElementById('payment_remarks').value = '';
            document.getElementById('paymentHint').style.display = 'none';

            // Auto-select the only available direction if just one applies.
            if (REMAINING_CLAIM > 0 && REMAINING_RETURN <= 0) {
                selectDirection('pay_employee');
            } else if (REMAINING_RETURN > 0 && REMAINING_CLAIM <= 0) {
                selectDirection('receive_employee');
            }
        }

        function selectDirection(direction) {
            const payCard = document.getElementById('dirPayCard');
            const receiveCard = document.getElementById('dirReceiveCard');

            if (direction === 'pay_employee' && REMAINING_CLAIM <= 0) return;
            if (direction === 'receive_employee' && REMAINING_RETURN <= 0) return;

            payCard.classList.toggle('sel-pay', direction === 'pay_employee');
            receiveCard.classList.toggle('sel-receive', direction === 'receive_employee');
            payCard.querySelector('input').checked = direction === 'pay_employee';
            receiveCard.querySelector('input').checked = direction === 'receive_employee';

            document.getElementById('paymentHint').style.display = 'block';
            document.getElementById('payment_amount').value = direction === 'pay_employee'
                ? REMAINING_CLAIM.toFixed(2)
                : REMAINING_RETURN.toFixed(2);
            document.getElementById('payment_amount').max = direction === 'pay_employee'
                ? REMAINING_CLAIM
                : REMAINING_RETURN;
        }

        let submittingPayment = false;
        function submitPayment() {
            if (submittingPayment) return;
            const direction = document.querySelector('input[name="payment_direction"]:checked')?.value;
            const amount = document.getElementById('payment_amount').value;
            const mode = document.getElementById('payment_mode').value;
            const remarks = document.getElementById('payment_remarks').value.trim();

            if (!direction) return showWarn('Please select a direction (Pay to Employee or Receive from Employee).');
            if (!amount || amount <= 0) return showWarn('Please enter a valid amount.');
            if (!mode) return showWarn('Please select payment mode.');
            if (!remarks) return showWarn('Please enter remarks.');

            submittingPayment = true;
            setSubmitting('paymentSubmitBtn', true);
            $.ajax({
                url: `/settlement/${SETTLEMENT_ID}/process-payment`, type: 'POST',
                data: { direction, amount, payment_mode: mode, remarks, _token: $('meta[name="csrf-token"]').attr('content') },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => { submittingPayment = false; setSubmitting('paymentSubmitBtn', false); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        }

        function openCloseModal() { openModal('closeModal'); }

        let submittingClose = false;
        function submitClose() {
            if (submittingClose) return;
            const remarks = document.getElementById('close_remarks').value.trim();
            if (!remarks) return showWarn('Please enter closing remarks.');
            submittingClose = true;
            setSubmitting('closeSubmitBtn', true);
            $.ajax({
                url: `/settlement/${SETTLEMENT_ID}/close`, type: 'POST',
                data: { remarks, _token: $('meta[name="csrf-token"]').attr('content') },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => { submittingClose = false; setSubmitting('closeSubmitBtn', false); showError(xhr.responseJSON?.message ?? 'Something went wrong.'); }
            });
        }
    </script>
@endsection
