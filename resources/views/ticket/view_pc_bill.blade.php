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

        .blue-table tfoot td {
            padding: 10px 12px;
            border-color: #e7e8eb;
            background: #f8f9fb;
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

                {{-- ══ LEFT ══ --}}
                <div class="col-lg-8">

                    {{-- Details Card --}}
                    <div class="details-card mb-3">
                        <div class="details-card-header">
                            <i class="ti ti-receipt"></i>
                            Bill Submission — #{{ $ticket->ticketId ?? '-' }}
                            @php
                                $sColors = [
                                    'open' => 'primary',
                                    'under_review' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger',
                                    'on_hold' => 'secondary',
                                ];
                                $sLabels = [
                                    'open' => 'Open',
                                    'under_review' => 'In Progress',
                                    'approved' => 'Approved',
                                    'rejected' => 'Rejected',
                                    'on_hold' => 'On Hold',
                                ];
                                $sColor = $sColors[$submission->accounts_status] ?? 'secondary';
                                $sLabel =
                                    $sLabels[$submission->accounts_status] ?? ucfirst($submission->accounts_status);
                            @endphp
                            <span class="badge bg-{{ $sColor }} ms-auto"
                                style="font-size:13px;">{{ $sLabel }}</span>
                        </div>

                        <div class="client-grid">

                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-building"></i></div>
                                <div>
                                    <div class="client-label">Branch</div>
                                    <div class="client-value">{{ $submission->branch->branch_name ?? '-' }}</div>
                                </div>
                            </div>

                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-user"></i></div>
                                <div>
                                    <div class="client-label">Raised By</div>
                                    <div class="client-value">
                                        {{ $submission->raisedBy->FullName ?? ($submission->raisedBy->UserName ?? '-') }}
                                    </div>
                                    <div style="font-size:11px;color:#aaa;">
                                        {{ $submission->raisedBy?->designation?->Designation ?? '' }}</div>
                                </div>
                            </div>

                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-calendar"></i></div>
                                <div>
                                    <div class="client-label">Raised On</div>
                                    <div class="client-value">
                                        {{ \Carbon\Carbon::parse($submission->created_at)->format('d M Y h:i A') }}</div>
                                </div>
                            </div>

                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-currency-rupee"></i></div>
                                <div>
                                    <div class="client-label">Total Bill Amount</div>
                                    <div class="client-value fw-bold text-primary" style="font-size:16px;">
                                        ₹ {{ number_format($submission->total_bill_amount, 2) }}
                                    </div>
                                </div>
                            </div>

                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-currency-rupee"></i></div>
                                <div>
                                    <div class="client-label">Approved Amount</div>
                                    <div class="client-value fw-bold text-success" style="font-size:16px;">
                                        ₹ {{ number_format($submission->approved_amount ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>

                            <div class="client-item">
                                <div class="client-icon"><i class="ti ti-wallet"></i></div>
                                <div>
                                    <div class="client-label">Balance At Raise</div>
                                    <div class="client-value">₹
                                        {{ number_format($submission->wallet_balance_at_raise, 2) }}</div>
                                </div>
                            </div>

                            {{-- <div class="client-item">
                            <div class="client-icon"><i class="ti ti-circle-dot"></i></div>
                            <div>
                                <div class="client-label">Accounts Status</div>
                                <div class="client-value">
                                    <span class="badge bg-{{ $sColor }}">{{ $sLabel }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="client-item">
                            <div class="client-icon"><i class="ti ti-ticket"></i></div>
                            <div>
                                <div class="client-label">Ticket Status</div>
                                <div class="client-value">
                                    <span class="badge bg-{{ $submission->ticket_status === 'closed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($submission->ticket_status) }}
                                    </span>
                                </div>
                            </div>
                        </div> --}}

                            @if ($submission->reviewed_by)
                                <div class="client-item">
                                    <div class="client-icon"><i class="ti ti-user-check"></i></div>
                                    <div>
                                        <div class="client-label">Reviewed By</div>
                                        @php $reviewer = \App\Models\UserMaster::find($submission->reviewed_by); @endphp
                                        <div class="client-value">{{ $reviewer->FullName ?? ($reviewer->UserName ?? '-') }}
                                        </div>
                                        <div style="font-size:11px;color:#aaa;">
                                            {{ \Carbon\Carbon::parse($submission->reviewed_at)->format('d M Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($submission->rejection_reason)
                                <div class="client-item" style="grid-column:1/-1;">
                                    <div class="client-icon"><i class="ti ti-x"></i></div>
                                    <div>
                                        <div class="client-label text-danger">Rejection Reason</div>
                                        <div class="client-value">{{ $submission->rejection_reason }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($submission->description)
                                <div class="client-item" style="grid-column:1/-1;">
                                    <div class="client-icon"><i class="ti ti-message"></i></div>
                                    <div>
                                        <div class="client-label">Description</div>
                                        <div class="client-value">{{ $submission->description }}</div>
                                    </div>
                                </div>
                            @endif

                            @if ($submission->closed_at)
                                <div class="client-item">
                                    <div class="client-icon"><i class="ti ti-lock"></i></div>
                                    <div>
                                        <div class="client-label">Closed At</div>
                                        <div class="client-value">
                                            {{ \Carbon\Carbon::parse($submission->closed_at)->format('d M Y h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    {{-- Bill Items --}}
                    <div class="details-card mb-3">
                        <div class="details-card-header">
                            <i class="ti ti-list"></i> Bill Items
                            <span class="badge bg-primary ms-auto">{{ $submission->items->count() }} items</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table blue-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-light">#</th>
                                        <th class="text-light">Expense Category</th>
                                        <th class="text-light">Bill No</th>
                                        <th class="text-light">Amount</th>
                                        <th class="text-light">Attachment</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $grandTotal = 0; @endphp
                                    @forelse($submission->items as $key => $item)
                                        @php $grandTotal += $item->amount; @endphp
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    {{ $item->expense->ExpenseName ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $item->bill_number ?? '-' }}</td>
                                            <td class="fw-semibold text-primary">₹ {{ number_format($item->amount, 2) }}
                                            </td>
                                            <td>
                                                @if ($item->attachment_path)
                                                    @php $ext = strtolower(pathinfo($item->attachment_path, PATHINFO_EXTENSION)); @endphp
                                                    @if (in_array($ext, ['jpg', 'jpeg', 'png']))
                                                        <a href="{{ asset($item->attachment_path) }}" target="_blank"
                                                            title="View Image">
                                                            <img src="{{ asset($item->attachment_path) }}"
                                                                style="width:40px;height:40px;object-fit:cover;border-radius:4px;border:1px solid #ddd;">
                                                        </a>
                                                    @elseif($ext === 'pdf')
                                                        <a href="{{ asset($item->attachment_path) }}" target="_blank"
                                                            class="btn btn-sm btn-outline-danger py-1 px-2">
                                                            <i class="ti ti-file-type-pdf me-1"></i> PDF
                                                        </a>
                                                    @endif
                                                @else
                                                    <span class="text-muted small">No file</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">No bill items found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                @if ($submission->items->count() > 0)
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                                            <td class="fw-bold text-primary" style="font-size:15px;">
                                                ₹ {{ number_format($grandTotal, 2) }}
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
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
                                            <td>{{ $h->changedAt ? \Carbon\Carbon::parse($h->changedAt)->format('d M Y h:i A') : '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $aColors = [
                                                        'OPEN' => 'primary',
                                                        'UNDER_REVIEW' => 'warning',
                                                        'APPROVED' => 'success',
                                                        'REJECTED' => 'danger',
                                                        'ON_HOLD' => 'secondary',
                                                        'CREATED' => 'dark',
                                                    ];
                                                    $aLabels = [
                                                        'OPEN' => 'Open',
                                                        'UNDER_REVIEW' => 'In Progress',
                                                        'APPROVED' => 'Approved',
                                                        'REJECTED' => 'Rejected',
                                                        'ON_HOLD' => 'On Hold',
                                                        'CREATED' => 'Created',
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
                                            <td colspan="6" class="text-center text-muted py-3">No history found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- ══ RIGHT ══ --}}
                <div class="col-lg-4">

                    {{-- Wallet --}}
                    <div class="details-card mb-3">
                        <div class="details-card-header">
                            <i class="ti ti-wallet"></i> Branch Wallet
                        </div>
                        <div class="p-3">
                            @if ($submission->wallet)
                                <div class="wallet-box mb-3">
                                    <div style="font-size:13px;opacity:.85;">Current Balance</div>
                                    <div class="wallet-amount">₹
                                        {{ number_format($submission->wallet->current_balance, 2) }}</div>
                                </div>
                                <div class="row g-2 text-center">
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Total Credited</div>
                                            <div class="fw-semibold text-success" style="font-size:14px;">
                                                ₹ {{ number_format($submission->wallet->total_credited, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="border rounded p-2">
                                            <div class="text-muted" style="font-size:11px;">Total Debited</div>
                                            <div class="fw-semibold text-danger" style="font-size:14px;">
                                                ₹ {{ number_format($submission->wallet->total_debited, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-muted text-center mt-2" style="font-size:11px;">
                                    Last updated:
                                    {{ $submission->wallet->last_updated
                                        ? \Carbon\Carbon::parse($submission->wallet->last_updated)->format('d M Y h:i A')
                                        : '-' }}
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <div style="font-size:40px;">💼</div>
                                    <div class="text-muted mt-2" style="font-size:13px;">No wallet found.</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Update Status --}}
                    @if ($submission->ticket_status !== 'closed' && ($isAdmin || $isAccounts))
                        <div class="details-card">
                            <div class="details-card-header">
                                <i class="ti ti-settings"></i> Update Status
                            </div>
                            <div class="p-3">
                                <form id="billStatusForm">
                                    @csrf
                                    <div class="mb-2">
                                        <label class="form-label" style="font-size:13px;">
                                            Status <span class="text-danger">*</span>
                                        </label>
                                        <select name="accounts_status" id="bill_accounts_status" class="form-control">
                                            <option value="">Select Status</option>
                                            @if ($submission->accounts_status === 'open')
                                                <option value="under_review">In Progress</option>
                                                <option value="on_hold">On Hold</option>
                                                <option value="rejected">Rejected</option>
                                            @elseif($submission->accounts_status === 'under_review')
                                                <option value="approved">Approved (wallet debited)</option>
                                                <option value="on_hold">On Hold</option>
                                                <option value="rejected">Rejected</option>
                                            @elseif($submission->accounts_status === 'on_hold')
                                                <option value="under_review">In Progress</option>
                                                <option value="approved">Approved (wallet debited)</option>
                                                <option value="rejected">Rejected</option>
                                            @else
                                                <option value="under_review">In Progress</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="on_hold">On Hold</option>
                                            @endif
                                        </select>
                                    </div>

                                    {{-- Debit warning --}}
                                    <div id="debit_info_block" style="display:none;">
                                        <div class="alert alert-warning py-2 mb-2" style="font-size:12px;">
                                            <i class="ti ti-alert-triangle me-1"></i>
                                            <strong>₹ {{ number_format($submission->total_bill_amount, 2) }}</strong>
                                            will be debited from wallet.
                                            @if ($submission->wallet)
                                                Current balance:
                                                <strong>₹
                                                    {{ number_format($submission->wallet->current_balance, 2) }}</strong>
                                                @if ($submission->wallet->current_balance < $submission->total_bill_amount)
                                                    <br><span class="text-danger fw-semibold">⚠ Insufficient
                                                        balance!</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" style="font-size:13px;">Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="2" placeholder="Optional remarks"></textarea>
                                    </div>

                                    <button type="submit" id="billStatusBtn" class="btn btn-primary w-100">
                                        <i class="ti ti-check me-1"></i> Update Status
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($submission->ticket_status === 'closed')
                        <div class="alert alert-success text-center mb-0">
                            <i class="ti ti-circle-check fs-4 d-block mb-1"></i>
                            This submission is <strong>Closed</strong>
                            @if ($submission->closed_at)
                                <div class="mt-1" style="font-size:12px;opacity:.8;">
                                    {{ \Carbon\Carbon::parse($submission->closed_at)->format('d M Y h:i A') }}
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

            $('#bill_accounts_status').on('change', function() {
                if ($(this).val() === 'approved') {
                    $('#debit_info_block').slideDown();
                } else {
                    $('#debit_info_block').slideUp();
                }
            });

            $('#billStatusForm').on('submit', function(e) {
                e.preventDefault();

                let status = $('#bill_accounts_status').val();
                if (!status) {
                    Swal.fire('Error', 'Please select a status', 'error');
                    return;
                }

                let btn = $('#billStatusBtn');
                btn.prop('disabled', true).html('<i class="ti ti-loader me-1"></i> Updating...');

                $.ajax({
                    url: '{{ route('pc.bill.status', $submission->submission_id) }}',
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
