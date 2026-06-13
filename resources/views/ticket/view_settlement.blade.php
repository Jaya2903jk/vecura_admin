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

        /* ── Stepper ── */
        .iou-workflow {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: #f8f9fc;
            border-bottom: 1px solid #e7e8eb;
            overflow-x: auto;
            gap: 0;
        }

        .iou-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            min-width: 100px;
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

        /* ── Amount Strip ── */
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

        .iou-amount-val.info-clr {
            color: #125e80;
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

        .settle-row td {
            background: #fafbff;
        }

        .claim-row td {
            background: #f8fffe;
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

        .pill-transferred {
            background: #d1fae5;
            color: #065f46;
            border-color: #6ee7b7;
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

        .td-btn-transfer {
            background: #d1fae5;
            color: #065f46;
            border-color: #6ee7b7;
        }

        .td-btn-claim {
            background: #fff3cd;
            color: #856404;
            border-color: #ffc107;
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

        .ap-modal-header.dark {
            background: #4b5673;
        }

        .ap-modal-header.teal {
            background: #065f46;
        }

        .ap-modal-header.amber {
            background: #92400e;
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

        .ap-btn-submit.teal {
            background: #065f46;
        }

        .ap-btn-submit.amber {
            background: #92400e;
        }

        /* ── Approve/Reject choice cards ── */
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

        /* ── Settlement item table inside modal ── */
        .settle-item-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .settle-item-table th {
            background: #5c35aa;
            color: #fff;
            padding: 7px 9px;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
        }

        .settle-item-table td {
            padding: 7px 9px;
            border-bottom: 1px solid #e7e8eb;
            color: #18284d;
        }

        .settle-item-table tr:last-child td {
            border-bottom: none;
        }

        .settle-item-table tfoot td {
            background: #f3f4f8;
            font-weight: 700;
        }

        /* ── Calc hint box ── */
        .calc-hint {
            background: #f3f4f8;
            border-radius: 5px;
            padding: 9px 13px;
            font-size: 13px;
            font-weight: 600;
            color: #18284d;
            margin-bottom: 12px;
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
    <style>
        .bd-wrap {
            font-family: var(--font-sans);
            padding: 0 0 16px;
        }

        .bd-section-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            padding: 14px 16px 8px;
            border-top: 1px solid var(--color-border-tertiary);
        }

        .bd-section-title {
            font-size: 15px;
            font-weight: 500;
            color: var(--color-text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .bd-section-icon {
            color: #5c35aa;
            font-size: 17px;
        }

        .bd-summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            border-top: 1px solid var(--color-border-tertiary);
            border-bottom: 1px solid var(--color-border-tertiary);
        }

        .bd-summary-cell {
            padding: 13px 16px;
            border-right: 1px solid var(--color-border-tertiary);
            text-align: center;
        }

        .bd-summary-cell:last-child {
            border-right: none;
        }

        .bd-summary-label {
            font-size: 11px;
            font-weight: 500;
            color: var(--color-text-secondary);
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 5px;
        }

        .bd-summary-val {
            font-size: 17px;
            font-weight: 500;
            color: var(--color-text-primary);
        }

        .bd-summary-val.green {
            color: #1a7a3c;
        }

        .bd-summary-val.red {
            color: #b52020;
        }

        .bd-summary-val.blue {
            color: #125e80;
        }

        .bd-summary-val.purple {
            color: #5c35aa;
        }

        .bd-table-wrap {
            overflow-x: auto;
        }

        .bd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .bd-table thead th {
            background: #5c35aa;
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            padding: 9px 12px;
            text-align: left;
            white-space: nowrap;
            border: none;
        }

        .bd-table thead th:first-child {
            border-radius: 0;
        }

        .bd-table tbody tr {
            border-bottom: 1px solid var(--color-border-tertiary);
        }

        .bd-table tbody tr:last-child {
            border-bottom: none;
        }

        .bd-table tbody tr:hover {
            background: var(--color-background-secondary);
        }

        .bd-table tbody td {
            padding: 10px 12px;
            color: var(--color-text-secondary);
            vertical-align: middle;
        }

        .bd-table tfoot td {
            background: var(--color-background-secondary);
            font-weight: 500;
            padding: 10px 12px;
            font-size: 13px;
            color: var(--color-text-primary);
            border-top: 1px solid var(--color-border-tertiary);
        }

        .bd-pill {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .pill-travel {
            background: #eef0fb;
            color: #3741b0;
            border-color: #c5c9ef;
        }

        .pill-food {
            background: #fef6e4;
            color: #8f5e00;
            border-color: #f0d48a;
        }

        .pill-accom {
            background: #e8f7ef;
            color: #1a7a3c;
            border-color: #a8dfc0;
        }

        .pill-misc {
            background: #f1f2f5;
            color: #4b5673;
            border-color: #d1d5e0;
        }

        .bd-file-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 9px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
            background: #e4f3fb;
            color: #125e80;
            border: 1px solid #9fd5ed;
            text-decoration: none;
            cursor: pointer;
        }

        .bd-nofile {
            font-size: 11px;
            color: var(--color-text-secondary);
        }

        .bd-remark-cell {
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            color: var(--color-text-secondary);
        }

        .bd-amount-strong {
            font-weight: 500;
            color: var(--color-text-primary);
        }

        .bd-amount-green {
            font-weight: 500;
            color: #1a7a3c;
        }

        .bd-amount-red {
            font-weight: 500;
            color: #b52020;
        }

        .bd-status-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: var(--color-background-secondary);
            border-top: 1px solid var(--color-border-tertiary);
            border-bottom: 1px solid var(--color-border-tertiary);
            font-size: 12px;
            color: var(--color-text-secondary);
            flex-wrap: wrap;
        }

        .bd-status-bar strong {
            color: var(--color-text-primary);
        }

        .bd-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #d1d5e0;
            flex-shrink: 0;
        }

        .bd-dot.green {
            background: #1a7a3c;
        }

        .bd-dot.red {
            background: #b52020;
        }

        .bd-dot.blue {
            background: #125e80;
        }

        @media(max-width:600px) {
            .bd-summary-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .bd-remark-cell {
                max-width: 90px;
            }
        }
    </style>
    <div class="page-wrapper">
        <div class="content td-page">

            <a href="{{ route('tickets') }}" class="td-crumb">
                <i class="ti ti-chevron-left"></i> Back to Tickets
            </a>

            <div class="td-card">

                {{-- ── Header ── --}}
                <div class="td-card-header">
                    <div class="td-card-header-left">
                        <div class="td-header-icon"><i class="ti ti-file-invoice"></i></div>
                        <div>
                            <div class="td-header-title">Settlement Details</div>
                            <div class="td-header-sub">
                                #TKT{{ $ticket->ticketId ?? '—' }}
                                &nbsp;·&nbsp;
                                {{ $ticket->CreatedDate ? date('d M Y', strtotime($ticket->CreatedDate)) : '—' }}
                                &nbsp;·&nbsp;
                                <span
                                    class="td-pill
                            @if ($settlement->settlement_status === 'PENDING') pill-pending
                            @elseif($settlement->settlement_status === 'APPROVED') pill-approved
                            @elseif($settlement->settlement_status === 'REJECTED') pill-rejected
                            @elseif($settlement->settlement_status === 'CLOSED')   pill-closed
                            @else pill-pending @endif">
                                    {{ ucfirst(strtolower($settlement->settlement_status ?? 'pending')) }}
                                </span>
                                &nbsp;·&nbsp;
                                <span
                                    class="td-pill pill-inprogress">{{ $settlement->settlement_type === 'BILL' ? 'Bill Submission' : 'Cash Return' }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- ── Action button based on status ── --}}
                    <div class="d-flex gap-2 flex-wrap">
                        @if ($settlement->settlement_status === 'PENDING')
                            <button class="td-btn td-btn-primary" onclick="openReviewModal()">
                                <i class="ti ti-clipboard-check"></i> Review Settlement
                            </button>
                        @endif

                        @if ($settlement->settlement_status === 'APPROVED' && ($settlement->employee_claim_amount ?? 0) > 0)
                            <button class="td-btn td-btn-transfer" onclick="openTransferModal()">
                                <i class="ti ti-send"></i> Transfer Claim to Employee
                            </button>
                        @endif

                        @if (in_array($settlement->settlement_status, ['APPROVED']) && $settlement->remaining_balance > 0)
                            <button class="td-btn td-btn-pay" onclick="openReturnModal()">
                                <i class="ti ti-arrow-back-up"></i> Record Return
                            </button>
                        @endif

                        @if ($settlement->settlement_status === 'APPROVED')
                            <button class="td-btn td-btn-close" onclick="openCloseModal()">
                                <i class="ti ti-lock"></i> Close
                            </button>
                        @endif
                    </div>
                </div>

                {{-- ── Stepper ── --}}
                @php
                    $steps = [
                        ['label' => 'Submitted', 'statuses' => ['PENDING', 'APPROVED', 'REJECTED', 'CLOSED']],
                        ['label' => 'Under Review', 'statuses' => ['PENDING']],
                        ['label' => 'Approved', 'statuses' => ['APPROVED', 'CLOSED']],
                        ['label' => 'Transferred', 'statuses' => ['CLOSED']],
                        ['label' => 'Closed', 'statuses' => ['CLOSED']],
                    ];
                    $currentStatus = $settlement->settlement_status ?? 'PENDING';
                    $stepIcons = ['ti-upload', 'ti-eye', 'ti-circle-check', 'ti-send', 'ti-lock'];
                    $stepDone = ['PENDING', 'APPROVED', 'REJECTED', 'CLOSED'];
                @endphp
                <div class="iou-workflow">
                    @foreach ($steps as $i => $step)
                        @php
                            if ($currentStatus === 'REJECTED' && $i === 2) {
                                $cls = 'rejected';
                            } elseif (in_array($currentStatus, $step['statuses']) && $currentStatus !== 'PENDING') {
                                $cls = 'done';
                            } elseif ($i === 0) {
                                $cls = 'done';
                            } elseif ($currentStatus === 'PENDING' && $i === 1) {
                                $cls = 'active';
                            } elseif ($currentStatus === 'APPROVED' && $i === 2) {
                                $cls = 'active';
                            } elseif ($currentStatus === 'CLOSED' && $i === 4) {
                                $cls = 'active';
                            } else {
                                $cls = '';
                            }
                        @endphp
                        <div class="iou-step {{ $cls }}">
                            <div class="iou-step-dot">
                                @if ($cls === 'done')
                                    <i class="ti ti-check" style="font-size:13px;"></i>
                                @elseif($cls === 'rejected')
                                    <i class="ti ti-x" style="font-size:13px;"></i>
                                @else<i class="ti {{ $stepIcons[$i] }}" style="font-size:13px;"></i>
                                @endif
                            </div>
                            <div class="iou-step-label">{{ $step['label'] }}</div>
                        </div>
                    @endforeach
                </div>



                {{-- ── Settlement Details ── --}}
                <div class="td-section-row">
                    <div class="td-section-title"><i class="ti ti-info-circle"
                            style="margin-right:6px;color:#3741b0;"></i>Settlement Details</div>
                </div>
                <div class="td-detail-grid">
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Employee</div>
                        <div class="td-detail-val">{{ $settlement->employee->FullName ?? '—' }}</div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Settlement Type</div>
                        <div class="td-detail-val">
                            {{ $settlement->settlement_type === 'BILL' ? 'Bill Submission' : 'Cash Return' }}</div>
                    </div>

                    <div class="td-detail-cell">
                        <div class="td-detail-key">Submitted On</div>
                        <div class="td-detail-val">
                            {{ $settlement->created_at ? date('d M Y h:i A', strtotime($settlement->created_at)) : '—' }}
                        </div>
                    </div>
                    <div class="td-detail-cell">
                        <div class="td-detail-key">Remarks</div>
                        <div class="td-detail-val">{{ $settlement->remarks ?? '—' }}</div>
                    </div>
                    @if ($settlement->approved_by)
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Reviewed By</div>
                            <div class="td-detail-val">{{ $settlement->approver->FullName ?? '—' }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Reviewed At</div>
                            <div class="td-detail-val">
                                {{ $settlement->approved_at ? date('d M Y h:i A', strtotime($settlement->approved_at)) : '—' }}
                            </div>
                        </div>
                    @endif
                </div>


                {{-- ── Employee Balance ── --}}
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
                            <div class="td-detail-key">Total Settled</div>
                            <div class="td-detail-val positive">₹{{ number_format($balance->total_settlement_amount, 2) }}
                            </div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Total Claims</div>
                            <div class="td-detail-val warning">₹{{ number_format($balance->total_claim_amount, 2) }}</div>
                        </div>
                        <div class="td-detail-cell">
                            <div class="td-detail-key">Net Pending Balance</div>
                            <div class="td-detail-val {{ $balance->pending_balance > 0 ? 'negative' : 'positive' }}">
                                ₹{{ number_format($balance->pending_balance, 2) }}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── Bill Items (if BILL type) ── --}}
                @if ($settlement->settlement_type === 'BILL' && $settlement->items && $settlement->items->count())
                    <div class="td-section-row">
                        <div class="td-section-title"><i class="ti ti-receipt"
                                style="margin-right:6px;color:#5c35aa;"></i>Bill Submission Items</div>
                    </div>
                    <div class="table-responsive">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Expense Type</th>
                                    <th>Bill Date</th>
                                    <th>Bill Amount</th>
                                    <th>Settlement Amount</th>
                                    <th>Employee Extra</th>
                                    <th>File</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($settlement->items as $k => $item)
                                    <tr class="settle-row">
                                        <td>{{ $k + 1 }}</td>
                                        <td>{{ $item->expense_type ?? '—' }}</td>
                                        <td>{{ $item->bill_date ? date('d M Y', strtotime($item->bill_date)) : '—' }}</td>
                                        <td style="font-weight:700;color:#18284d;">
                                            ₹{{ number_format($item->bill_amount, 2) }}</td>
                                        <td style="color:#1a7a3c;font-weight:600;">
                                            ₹{{ number_format($item->settlement_amount, 2) }}</td>
                                        <td style="color:#b52020;font-weight:600;">
                                            ₹{{ number_format($item->employee_claim_amount, 2) }}</td>
                                        <td>
                                            @if ($item->bill_file)
                                                <a href="{{ asset('uploads/settlements/' . $item->bill_file) }}"
                                                    target="_blank" class="td-btn td-btn-pay"
                                                    style="padding:3px 9px;font-size:11px;">
                                                    <i class="ti ti-download"></i> View
                                                </a>
                                            @else
                                                <span class="text-muted" style="font-size:12px;">No file</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->remarks ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr style="background:#f3f4f8;font-weight:700;">
                                    <td colspan="3" style="padding:9px 12px;font-size:13px;color:#18284d;">Total</td>
                                    <td style="padding:9px 12px;font-size:13px;color:#18284d;">
                                        ₹{{ number_format($settlement->total_bill_amount, 2) }}</td>
                                    <td style="padding:9px 12px;font-size:13px;color:#1a7a3c;">
                                        ₹{{ number_format($settlement->company_settlement_amount, 2) }}</td>
                                    <td style="padding:9px 12px;font-size:13px;color:#b52020;">
                                        ₹{{ number_format($settlement->employee_claim_amount, 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif

                {{-- ── Claim Transfers ── --}}
                @if ($claimTransfers && $claimTransfers->count())
                    <div class="td-section-row">
                        <div class="td-section-title"><i class="ti ti-send"
                                style="margin-right:6px;color:#065f46;"></i>Claim Transfers to Employee</div>
                    </div>
                    <div class="table-responsive">
                        <table class="td-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Amount</th>
                                    <th>Mode</th>
                                    <th>Remarks</th>
                                    <th>Transferred By</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($claimTransfers as $k => $txn)
                                    <tr class="claim-row">
                                        <td>{{ $k + 1 }}</td>
                                        <td style="font-weight:700;color:#065f46;">₹{{ number_format($txn->amount, 2) }}
                                        </td>
                                        <td>{{ $txn->payment_mode ?? '—' }}</td>
                                        <td>{{ $txn->remarks ?? '—' }}</td>
                                        <td>{{ $txn->creator->FullName ?? '—' }}</td>
                                        <td>{{ $txn->created_at ? date('d M Y h:i A', strtotime($txn->created_at)) : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- ── Money Transactions ── --}}
                @if ($transactions && $transactions->count())
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
                                @foreach ($transactions as $k => $txn)
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

                {{-- ── Action History ── --}}
                <div class="td-section-row">
                    <div class="td-section-title"><i class="ti ti-history"
                            style="margin-right:6px;color:#3741b0;"></i>Action History</div>
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
                            @forelse($actionHistory as $k => $h)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>
                                        <span
                                            class="td-pill
                                @if (strtolower($h->status) === 'approved') pill-approved
                                @elseif(strtolower($h->status) === 'rejected') pill-rejected
                                @elseif(strtolower($h->status) === 'closed') pill-closed
                                @elseif(strtolower($h->status) === 'transferred') pill-transferred
                                @else pill-pending @endif">
                                            {{ ucfirst($h->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $h->remarks ?? '—' }}</td>
                                    <td>{{ $h->changedBy }}</td>
                                    <td>{{ $h->designation }}</td>
                                    <td>{{ $h->changedAt ? date('d M Y h:i A', strtotime($h->changedAt)) : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted" style="padding:20px;">No history
                                        found</td>
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


    <div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(55,65,176,.18);">
                <div class="ap-modal-header">
                    <h5 class="ap-modal-title"><i class="ti ti-clipboard-check" style="font-size:17px;"></i> Review
                        Settlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $settlement->employee->FullName ?? '—' }}
                        </div>
                        <div class="ap-info-item"><strong>Type:</strong>
                            {{ $settlement->settlement_type === 'BILL' ? 'Bill Submission' : 'Cash Return' }}</div>
                        <div class="ap-info-item"><strong>Total Bill:</strong>
                            ₹{{ number_format($settlement->total_bill_amount, 2) }}</div>
                        <div class="ap-info-item"><strong>Company Settles:</strong>
                            ₹{{ number_format($settlement->company_settlement_amount, 2) }}</div>
                        @if (($settlement->employee_claim_amount ?? 0) > 0)
                            <div class="ap-info-item" style="color:#b52020;"><strong>Employee Extra Claim:</strong>
                                ₹{{ number_format($settlement->employee_claim_amount, 2) }}</div>
                        @endif
                        @if (($settlement->remaining_balance ?? 0) > 0)
                            <div class="ap-info-item" style="color:#8f5e00;"><strong>Employee Returns:</strong>
                                ₹{{ number_format($settlement->remaining_balance, 2) }}</div>
                        @endif
                    </div>

                    {{-- Bill items preview --}}
                    @if ($settlement->settlement_type === 'BILL' && $settlement->items && $settlement->items->count())
                        <div class="mb-3">
                            <label class="ap-field-label">Bill Items</label>
                            <table class="settle-item-table">
                                <thead>
                                    <tr>
                                        <th>Expense Type</th>
                                        <th>Bill Date</th>
                                        <th>Bill Amt</th>
                                        <th>Settle</th>
                                        <th>Extra</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($settlement->items as $item)
                                        <tr>
                                            <td>{{ $item->expense_type }}</td>
                                            <td>{{ $item->bill_date ? date('d M Y', strtotime($item->bill_date)) : '—' }}
                                            </td>
                                            <td>₹{{ number_format($item->bill_amount, 2) }}</td>
                                            <td style="color:#1a7a3c;font-weight:600;">
                                                ₹{{ number_format($item->settlement_amount, 2) }}</td>
                                            <td style="color:#b52020;font-weight:600;">
                                                ₹{{ number_format($item->employee_claim_amount, 2) }}</td>
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


    <div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(6,95,70,.18);">
                <div class="ap-modal-header teal">
                    <h5 class="ap-modal-title"><i class="ti ti-send" style="font-size:17px;"></i> Transfer Claim to
                        Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $settlement->employee->FullName ?? '—' }}
                        </div>
                        <div class="ap-info-item" style="color:#b52020;"><strong>Extra Claim Amount:</strong>
                            ₹{{ number_format($settlement->employee_claim_amount ?? 0, 2) }}</div>
                    </div>
                    <div class="calc-hint" style="background:#d1fae5;color:#065f46;">
                        <i class="ti ti-info-circle"></i>
                        Employee spent ₹{{ number_format($settlement->employee_claim_amount ?? 0, 2) }} more than the IOU
                        balance. Company will pay this extra amount.
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Transfer Amount <span class="req">*</span></label>
                        <input type="number" id="transfer_amount" class="form-control" min="0.01" step="0.01"
                            value="{{ number_format($settlement->employee_claim_amount ?? 0, 2, '.', '') }}"
                            placeholder="Amount to transfer">
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Payment Mode <span class="req">*</span></label>
                        <select id="transfer_mode" class="form-control">
                            <option value="">Select Mode</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Remarks <span class="req">*</span></label>
                        <textarea id="transfer_remarks" class="ap-textarea" rows="2" placeholder="Transfer remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit teal" id="transferSubmitBtn" onclick="submitTransfer()">
                        <i class="ti ti-send" style="font-size:13px;"></i> Confirm Transfer
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(18,94,128,.18);">
                <div class="ap-modal-header info">
                    <h5 class="ap-modal-title"><i class="ti ti-arrow-back-up" style="font-size:17px;"></i> Record Cash
                        Return</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip">
                        <div class="ap-info-item"><strong>Employee:</strong> {{ $settlement->employee->FullName ?? '—' }}
                        </div>
                        <div class="ap-info-item" style="color:#8f5e00;"><strong>Expected Return:</strong>
                            ₹{{ number_format($settlement->remaining_balance ?? 0, 2) }}</div>
                    </div>
                    <div class="calc-hint" style="background:#fef6e4;color:#8f5e00;">
                        <i class="ti ti-info-circle"></i>
                        Employee has a remaining balance of ₹{{ number_format($settlement->remaining_balance ?? 0, 2) }} to
                        return to the company.
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Returned Amount <span class="req">*</span></label>
                        <input type="number" id="return_amount" class="form-control" min="0.01" step="0.01"
                            value="{{ number_format($settlement->remaining_balance ?? 0, 2, '.', '') }}"
                            placeholder="Amount returned by employee">
                    </div>
                    <div class="mb-3">
                        <label class="ap-field-label">Return Mode <span class="req">*</span></label>
                        <select id="return_mode" class="form-control">
                            <option value="">Select Mode</option>
                            <option value="Cash">Cash</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Cheque">Cheque</option>
                            <option value="UPI">UPI</option>
                        </select>
                    </div>
                    <div class="mb-1">
                        <label class="ap-field-label">Remarks <span class="req">*</span></label>
                        <textarea id="return_remarks" class="ap-textarea" rows="2" placeholder="Return remarks…"></textarea>
                    </div>
                </div>
                <div class="ap-modal-footer">
                    <button type="button" class="ap-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="ap-btn-submit info" id="returnSubmitBtn" onclick="submitReturn()">
                        <i class="ti ti-check" style="font-size:13px;"></i> Confirm Return
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="closeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
            <div class="modal-content"
                style="border-radius:6px;border:none;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.12);">
                <div class="ap-modal-header dark">
                    <h5 class="ap-modal-title"><i class="ti ti-lock" style="font-size:17px;"></i> Close Settlement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="ap-modal-body">
                    <div class="ap-info-strip" style="background:#fff8e1;">
                        <div class="ap-info-item" style="color:#8f5e00;">
                            <i class="ti ti-alert-triangle" style="margin-right:4px;"></i>
                            Closing this settlement is <strong>irreversible</strong>. Ensure all transfers and returns are
                            complete.
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
        const SETTLEMENT_ID = {{ $settlement->settlement_id }};

        // ── Helpers ────────────────────────────────────────────────
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

        // ── MODAL 1: Review ────────────────────────────────────────
        function openReviewModal() {
            openModal('reviewModal');
        }

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
                url: `/settlement/${SETTLEMENT_ID}/review`,
                type: 'POST',
                data: {
                    decision,
                    remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => {
                    submittingReview = false;
                    setSubmitting('reviewSubmitBtn', false);
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.');
                }
            });
        }

        // ── MODAL 2: Transfer ──────────────────────────────────────
        function openTransferModal() {
            openModal('transferModal');
        }

        let submittingTransfer = false;

        function submitTransfer() {
            if (submittingTransfer) return;
            const amount = document.getElementById('transfer_amount').value;
            const mode = document.getElementById('transfer_mode').value;
            const remarks = document.getElementById('transfer_remarks').value.trim();
            if (!amount || amount <= 0) return showWarn('Please enter a valid transfer amount.');
            if (!mode) return showWarn('Please select payment mode.');
            if (!remarks) return showWarn('Please enter remarks.');

            submittingTransfer = true;
            setSubmitting('transferSubmitBtn', true);
            $.ajax({
                url: `/settlement/${SETTLEMENT_ID}/transfer-claim`,
                type: 'POST',
                data: {
                    amount,
                    payment_mode: mode,
                    remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => {
                    submittingTransfer = false;
                    setSubmitting('transferSubmitBtn', false);
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.');
                }
            });
        }

        // ── MODAL 3: Return ────────────────────────────────────────
        function openReturnModal() {
            openModal('returnModal');
        }

        let submittingReturn = false;

        function submitReturn() {
            if (submittingReturn) return;
            const amount = document.getElementById('return_amount').value;
            const mode = document.getElementById('return_mode').value;
            const remarks = document.getElementById('return_remarks').value.trim();
            if (!amount || amount <= 0) return showWarn('Please enter a valid return amount.');
            if (!mode) return showWarn('Please select return mode.');
            if (!remarks) return showWarn('Please enter remarks.');

            submittingReturn = true;
            setSubmitting('returnSubmitBtn', true);
            $.ajax({
                url: `/settlement/${SETTLEMENT_ID}/record-return`,
                type: 'POST',
                data: {
                    amount,
                    payment_mode: mode,
                    remarks,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: res => showSuccess(res.message, () => location.reload()),
                error: xhr => {
                    submittingReturn = false;
                    setSubmitting('returnSubmitBtn', false);
                    showError(xhr.responseJSON?.message ?? 'Something went wrong.');
                }
            });
        }

        // ── MODAL 4: Close ─────────────────────────────────────────
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
                url: `/settlement/${SETTLEMENT_ID}/close`,
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
