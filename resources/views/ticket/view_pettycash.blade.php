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
        }

        .details-card {
            background: #fff;
            border: 1px solid #e7e8eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .details-card-header {
            padding: 12px 16px;
            border-bottom: 1px solid #e7e8eb;
            font-size: 17px;
            font-weight: 700;
            color: #132144;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .client-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            padding: 16px;
        }

        .client-item {
            display: flex;
            gap: 14px;
            align-items: flex-start;
        }

        .client-icon {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #f3f4f8;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b738a;
            flex-shrink: 0;
        }

        .client-label {
            font-size: 13px;
            font-weight: 700;
            color: #18284d;
            margin-bottom: 2px;
        }

        .client-value {
            font-size: 13px;
            color: #737b90;
        }

        .table-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin: 18px 0 10px;
        }

        .table-title {
            font-size: 15px;
            font-weight: 700;
            color: #132144;
            margin: 0;
        }

        .blue-table thead th {
            background: #3741b0;
            color: #fff;
            border-bottom: none;
            padding: 9px 12px;
            font-size: 13px;
        }

        .blue-table tbody td {
            padding: 10px 12px;
            font-size: 13px;
            color: #4f5d7c;
            border-color: #e7e8eb;
        }

        .wallet-box {
            background: linear-gradient(135deg, #1e7e34, #28a745);
            border-radius: 10px;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .wallet-amount {
            font-size: 32px;
            font-weight: 800;
        }

        .wallet-empty {
            text-align: center;
            padding: 24px 16px;
        }

        .wallet-empty .wi {
            font-size: 40px;
            margin-bottom: 8px;
        }

        .status-flow {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 16px;
        }

        .status-flow .sf-item {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid transparent;
            background: #f3f4f8;
            color: #666;
        }

        .status-flow .sf-item.active {
            border-color: currentColor;
        }

        @media(max-width:991.98px) {
            .client-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-wrapper">
        <div class="content">

            <a href="{{ route('tickets') }}" class="crumb-back">
                <i class="ti ti-chevron-left"></i> Back to Tickets
            </a>

            <div class="row g-3">

                {{-- ══ LEFT ══════════════════════════════════════════════════ --}}
                <div class="col-lg-8">

                    {{-- Request Details --}}
                    <div class="details-card mb-3">
                        <div class="details-card-header">
                            <i class="ti ti-receipt"></i>
                            Petty Cash Request — #{{ $ticket->ticketId ?? '-' }}

                            {{-- Current status pill --}}
                            @php
                                $sColors = [
                                    'open' => 'primary',
                                    'under_review' => 'warning',
                                    'approved' => 'info',
                                    'transferred' => 'success',
                                    'rejected' => 'danger',
                                    'on_hold' => 'secondary',
                                    'closed' => 'dark',
                                ];
                                $sLabels = [
                                    'open' => 'Open',
                                    'under_review' => 'In Progress',
                                    'approved' => 'Approved',
                                    'transferred' => 'Transferred',
                                    'rejected' => 'Rejected',
                                    'on_hold' => 'On Hold',
                                    'closed' => 'Closed',
                                ];
                                $curColor = $sColors[$pcRequest->accounts_status] ?? 'secondary';
                                $curLabel =
                                    $sLabels[$pcRequest->accounts_status] ?? ucfirst($pcRequest->accounts_status);
                            @endphp
                            <span class="badge bg-{{ $curColor }} ms-auto" style="font-size:13px;">
                                {{ $curLabel }}
                            </span>
                        </div>

                        {{-- Status flow bar --}}
                        <div class="px-3 pt-3">
                            <div class="status-flow">
                                @php
                                    $flowSteps = [
                                        'open' => ['Open', 'primary'],
                                        'under_review' => ['In Progress', 'warning'],
                                        'approved' => ['Approved', 'info'],
                                        'transferred' => ['Transferred', 'success'],
                                    ];
                                @endphp
                                @foreach ($flowSteps as $step => [$stepLabel, $stepColor])
                                    <span
                                        class="sf-item text-{{ $stepColor }}
                                    {{ $pcRequest->accounts_status === $step ? 'active' : '' }}">
                                        {{ $stepLabel }}
                                    </span>
                                    @if (!$loop->last)
                                        <span class="text-muted" style="font-size:18px; line-height:1.4;">→</span>
                                    @endif
                                @endforeach
                                <span
                                    class="sf-item text-danger
                                {{ $pcRequest->accounts_status === 'rejected' ? 'active' : '' }}">
                                    Rejected
                                </span>
                                <span
                                    class="sf-item text-secondary
                                {{ $pcRequest->accounts_status === 'on_hold' ? 'active' : '' }}">
                                    On Hold
                                </span>
                            </div>
                        </div>

                        <div class="client-grid">

                            {{-- Branch --}}
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-building"></i></div>
                                <div>
                                    <div class="client-label">Branch</div>
                                    <div class="client-value">{{ $pcRequest->branch->branch_name ?? '-' }}</div>
                                </div>
                            </div>

                            {{-- Raised By --}}
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-user"></i></div>
                                <div>
                                    <div class="client-label">Raised By</div>
                                    <div class="client-value">
                                        {{ $pcRequest->raisedBy->FullName ?? ($pcRequest->raisedBy->UserName ?? '-') }}
                                    </div>
                                    <div style="font-size:11px;color:#aaa;">
                                        {{ $pcRequest->raisedBy?->designation?->Designation ?? '' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Raised On --}}
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-calendar"></i></div>
                                <div>
                                    <div class="client-label">Raised On</div>
                                    <div class="client-value">
                                        {{ \Carbon\Carbon::parse($pcRequest->created_at)->format('d M Y h:i A') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Requested Amount --}}
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-currency-rupee"></i></div>
                                <div>
                                    <div class="client-label">Requested Amount</div>
                                    <div class="client-value fw-bold text-primary" style="font-size:16px;">
                                        ₹ {{ number_format($pcRequest->requested_amount, 2) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Approved Amount --}}
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-currency-rupee"></i></div>
                                <div>
                                    <div class="client-label">Approved Amount</div>
                                    <div class="client-value fw-bold text-success" style="font-size:16px;">
                                        ₹ {{ number_format($pcRequest->approved_amount ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Balance At Raise --}}
                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-wallet"></i></div>
                                <div>
                                    <div class="client-label">Balance At Raise</div>
                                    <div class="client-value">
                                        ₹ {{ number_format($pcRequest->wallet_balance_at_raise, 2) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Accounts Status --}}
                            {{-- <div class="client-item">
                                <div class="client-icon"><i class="ti ti-circle-dot"></i></div>
                                <div>
                                    <div class="client-label">Accounts Status</div>
                                    <div class="client-value">
                                        <span class="badge bg-{{ $curColor }}">{{ $curLabel }}</span>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- Ticket Status --}}
                            {{-- <div class="client-item">
                                <div class="client-icon"><i class="ti ti-ticket"></i></div>
                                <div>
                                    <div class="client-label">Ticket Status</div>
                                    <div class="client-value">
                                        <span
                                            class="badge bg-{{ $pcRequest->ticket_status === 'closed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($pcRequest->ticket_status) }}
                                        </span>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- Mgmt Status --}}
                            {{-- <div class="client-item">
                                <div class="client-icon"><i class="ti ti-briefcase"></i></div>
                                <div>
                                    <div class="client-label">Mgmt Status</div>
                                    <div class="client-value">
                                        @php
                                            $mColors = [
                                                'pending' => 'warning',
                                                'approved' => 'info',
                                                'transferred' => 'success',
                                                'rejected' => 'danger',
                                            ];
                                            $mColor = $mColors[$pcRequest->mgmt_status ?? ''] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $mColor }}">
                                            {{ ucfirst($pcRequest->mgmt_status ?? 'Pending') }}
                                        </span>
                                    </div>
                                </div>
                            </div> --}}

                            {{-- Transfer Reference --}}
                            @if ($pcRequest->transfer_ref)
                                <div class="client-item">
                                    <div class="client-icon"><i class="ti ti-transfer"></i></div>
                                    <div>
                                        <div class="client-label">Transfer Reference</div>
                                        <div class="client-value fw-semibold text-success">
                                            {{ $pcRequest->transfer_ref }}
                                        </div>
                                    </div>
                                </div>

                                <div class="client-item">
                                    <div class="client-icon"><i class="ti ti-clock"></i></div>
                                    <div>
                                        <div class="client-label">Transferred At</div>
                                        <div class="client-value">
                                            {{ \Carbon\Carbon::parse($pcRequest->transferred_at)->format('d M Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Closed At --}}
                            @if ($pcRequest->closed_at)
                                <div class="client-item">
                                    <div class="client-icon"><i class="ti ti-lock"></i></div>
                                    <div>
                                        <div class="client-label">Closed At</div>
                                        <div class="client-value">
                                            {{ \Carbon\Carbon::parse($pcRequest->closed_at)->format('d M Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Reason --}}
                            <div class="client-item" style="grid-column:1/-1;">
                                <div class="client-icon"><i class="ti ti-message"></i></div>
                                <div>
                                    <div class="client-label">Reason</div>
                                    <div class="client-value">{{ $pcRequest->reason ?? '-' }}</div>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- Status History --}}
                    <div class="details-card">
                        <div class="details-card-header">
                            <i class="ti ti-history"></i> Status History
                        </div>
                        <div class="table-responsive">
                            <table class="table blue-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-light">#</th>
                                        <th class="text-light">Date</th>
                                        <th class="text-light">Action</th>
                                        <th class="text-light">Remarks</th>
                                        <th class="text-light">Updated By</th>
                                        <th class="text-light">Designation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($actionHistory->reverse()->values() as $key => $h)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {{ $h->changedAt ? \Carbon\Carbon::parse($h->changedAt)->format('d M Y h:i A') : '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $aColors = [
                                                        'OPEN' => 'primary',
                                                        'UNDER_REVIEW' => 'warning',
                                                        'APPROVED' => 'info',
                                                        'TRANSFERRED' => 'success',
                                                        'REJECTED' => 'danger',
                                                        'ON_HOLD' => 'secondary',
                                                        'CLOSED' => 'dark',
                                                    ];
                                                    $aLabels = [
                                                        'OPEN' => 'Open',
                                                        'UNDER_REVIEW' => 'In Progress',
                                                        'APPROVED' => 'Approved',
                                                        'TRANSFERRED' => 'Transferred',
                                                        'REJECTED' => 'Rejected',
                                                        'ON_HOLD' => 'On Hold',
                                                        'CLOSED' => 'Closed',
                                                    ];
                                                    $aColor = $aColors[$h->action] ?? 'secondary';
                                                    $aLabel = $aLabels[$h->action] ?? ucfirst(strtolower($h->action));
                                                @endphp
                                                <span class="badge bg-{{ $aColor }}">{{ $aLabel }}</span>
                                            </td>
                                            <td><small>{{ $h->remarks ?? '-' }}</small></td>
                                            <td>{{ $h->changedBy ?? '-' }}</td>
                                            <td>{{ $h->designation ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                No history found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- ══ RIGHT ══════════════════════════════════════════════════ --}}
                <div class="col-lg-4">

                    {{-- Wallet Card --}}
                    <div class="details-card mb-3">
                        <div class="details-card-header">
                            <i class="ti ti-wallet"></i> Branch Wallet
                        </div>
                        <div class="p-3">
                            @if ($pcRequest->wallet)
                                <div class="wallet-box mb-3">
                                    <div style="font-size:13px;opacity:.85;">Current Balance</div>
                                    <div class="wallet-amount">
                                        ₹ {{ number_format($pcRequest->wallet->current_balance, 2) }}
                                    </div>
                                </div>
                                <div class="row g-2 text-center">
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Total Credited</div>
                                            <div class="fw-semibold text-success" style="font-size:14px;">
                                                ₹ {{ number_format($pcRequest->wallet->total_credited, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Total Debited</div>
                                            <div class="fw-semibold text-danger" style="font-size:14px;">
                                                ₹ {{ number_format($pcRequest->wallet->total_debited, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted text-center mt-2" style="font-size:11px;">
                                    Last updated:
                                    {{ $pcRequest->wallet->last_updated
                                        ? \Carbon\Carbon::parse($pcRequest->wallet->last_updated)->format('d M Y h:i A')
                                        : '-' }}
                                </div>
                            @else
                                <div class="wallet-empty">
                                    <div class="wi">💼</div>
                                    <div class="text-muted" style="font-size:13px;">
                                        No wallet yet for this branch.
                                    </div>
                                    <div class="text-muted mt-1" style="font-size:12px;">
                                        Wallet will be created automatically when transfer is done.
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Update Status Card --}}
                    @if ($pcRequest->ticket_status !== 'closed' && ($isAdmin || $isAccounts))
                        <div class="details-card">
                            <div class="details-card-header">
                                <i class="ti ti-settings"></i> Update Status
                            </div>
                            <div class="p-3">
                                <form id="statusForm">
                                    @csrf

                                    <div class="mb-2">
                                        <label class="form-label" style="font-size:13px;">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="accounts_status" id="accounts_status" class="form-control">
                                            <option value="">Select Status</option>

                                            @if ($pcRequest->accounts_status === 'open')
                                                <option value="under_review">In Progress</option>
                                                <option value="approved">Approved</option>
                                                <option value="on_hold">On Hold</option>
                                                <option value="rejected">Rejected</option>
                                            @elseif($pcRequest->accounts_status === 'under_review')
                                                <option value="approved">Approved</option>
                                                <option value="on_hold">On Hold</option>
                                                <option value="rejected">Rejected</option>
                                            @elseif($pcRequest->accounts_status === 'on_hold')
                                                <option value="under_review">In Progress</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            @elseif($pcRequest->accounts_status === 'approved')
                                                <option value="transferred">Transferred (credit wallet)</option>
                                                <option value="on_hold">On Hold</option>
                                                <option value="rejected">Rejected</option>
                                            @elseif($pcRequest->accounts_status === 'transferred')
                                                <option value="closed">Close Ticket</option>
                                            @else
                                                <option value="under_review">In Progress</option>
                                                <option value="approved">Approved</option>
                                                <option value="transferred">Transferred</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="on_hold">On Hold</option>
                                            @endif
                                        </select>
                                    </div>

                                    {{-- Transfer ref — show only when transferred --}}
                                    <div class="mb-2" id="transfer_ref_block" style="display:none;">
                                        <label class="form-label" style="font-size:13px;">
                                            Transfer Reference (UTR) <span class="text-danger">*</span>
                                            <small class="text-muted d-block">
                                                Enter UTR → wallet credited automatically
                                            </small>
                                        </label>
                                        <input type="text" name="transfer_ref" id="transfer_ref" class="form-control"
                                            placeholder="Enter UTR / Reference No">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="font-size:13px;">Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2" placeholder="Optional remarks"></textarea>
                                    </div>

                                    <button type="submit" id="statusBtn" class="btn btn-primary w-100">
                                        <i class="ti ti-check me-1"></i> Update Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($pcRequest->ticket_status === 'closed')
                        <div class="alert alert-success text-center mb-0">
                            <i class="ti ti-circle-check fs-4 d-block mb-1"></i>
                            This request is <strong>Closed</strong>
                            @if ($pcRequest->closed_at)
                                <div class="mt-1" style="font-size:12px;opacity:.8;">
                                    {{ \Carbon\Carbon::parse($pcRequest->closed_at)->format('d M Y h:i A') }}
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('build/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {

            // Show transfer ref only when transferred selected
            $('#accounts_status').on('change', function() {
                if ($(this).val() === 'transferred') {
                    $('#transfer_ref_block').slideDown();
                } else {
                    $('#transfer_ref_block').slideUp();
                    $('#transfer_ref').val('');
                }
            });

            // Submit
            $('#statusForm').on('submit', function(e) {
                e.preventDefault();

                let status = $('#accounts_status').val();
                if (!status) {
                    Swal.fire('Error', 'Please select a status', 'error');
                    return;
                }

                // Transfer ref required for transferred
                if (status === 'transferred' && !$('#transfer_ref').val().trim()) {
                    Swal.fire('Error', 'Transfer Reference is required', 'error');
                    return;
                }

                let btn = $('#statusBtn');
                btn.prop('disabled', true).html('<i class="ti ti-loader me-1"></i> Updating...');

                $.ajax({
                    url: '{{ route('pc.request.status', $pcRequest->request_id) }}',
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        setTimeout(() => location.reload(), 1600);
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(
                            '<i class="ti ti-check me-1"></i> Update Status');
                        Swal.fire('Error', xhr.responseJSON?.message ?? 'Something went wrong',
                            'error');
                    }
                });
            });
        });
    </script>

@endsection
