@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Header --}}
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">
                    Tickets
                    @if($typeLabel !== 'All')
                        <span class="text-muted fw-normal">— {{ $typeLabel }}</span>
                    @endif
                    @if($statusLabel !== 'All')
                        <span class="badge
                            @if($statusLabel === 'Pending') badge-soft-warning border border-warning
                            @elseif($statusLabel === 'InProgress') badge-soft-info border border-info
                            @elseif($statusLabel === 'Resolved') badge-soft-success border border-success
                            @else badge-soft-danger border border-danger
                            @endif ms-2">
                            {{ $statusLabel }}
                        </span>
                    @endif
                    <span class="badge badge-soft-primary border border-primary page-header-badge ms-2">
                        {{ $tickets->total() }} records
                    </span>
                </h4>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('ticket.summary') }}" class="btn btn-outline-secondary btn-md fs-13">
                    <i class="ti ti-arrow-left me-1"></i> Back to Summary
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card border-0 mb-3">
            <div class="card-body py-2">
                <form method="GET" action="{{ route('ticket.summary.listing') }}" class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label mb-1 small fw-semibold">Department</label>
                        <select name="type" class="form-control form-control-sm">
                            <option value="">All Departments</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ $type == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label mb-1 small fw-semibold">Status</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">All Status</option>
                            @foreach($statuses as $code => $label)
                                <option value="{{ $code }}" {{ $statusCode == $code ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label mb-1 small fw-semibold">Per Page</label>
                        <select name="per_page" class="form-control form-control-sm">
                            @foreach([15,25,50,100] as $n)
                                <option value="{{ $n }}" {{ $perPage == $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                    </div>
                    @if($type || $statusCode !== null)
                    <div class="col-md-2">
                        <a href="{{ route('ticket.summary.listing') }}" class="btn btn-light btn-sm w-100">
                            <i class="ti ti-x me-1"></i> Clear
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- Table --}}
        <div class="card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table datatable mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ticket ID</th>
                                <th>Department</th>
                                <th>Type</th>
                                <th>Branch</th>
                                <th>Reg No / Employee</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Created Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $i => $t)
                            <tr>
                                <td>{{ $tickets->firstItem() + $i }}</td>
                                <td><strong>#TKT{{ str_pad($t->ticketId, 3, '0', STR_PAD_LEFT) }}</strong></td>
                                <td>{{ $t->department->DepartmentName ?? '—' }}</td>
                                <td><span class="badge badge-soft-secondary border border-secondary">{{ strtoupper($t->type ?? '—') }}</span></td>
                                <td>{{ $t->location->LocationName ?? 'Coop' }}</td>
                                <td>{{ $t->customer->RegistrationNo ?? ($t->get_employee->UserCode ?? '—') }}</td>
                                <td class="text-truncate" style="max-width:180px;" title="{{ $t->Subject }}">
                                    {{ $t->Subject ?? '—' }}
                                </td>
                                <td>
                                    <span class="status-badge
                                        @if($t->Status == 0) status-pending
                                        @elseif($t->Status == 1) status-progress
                                        @elseif($t->Status == 2) status-resolved
                                        @elseif($t->Status == 4) status-rejected
                                        @else status-closed
                                        @endif">
                                        @if($t->Status == 0) Pending
                                        @elseif($t->Status == 1) In Progress
                                        @elseif($t->Status == 2) Resolved
                                        @elseif($t->Status == 3) Closed
                                        @elseif($t->Status == 4) Rejected
                                        @else Unknown
                                        @endif
                                    </span>
                                </td>
                                <td>{{ $t->CreatedDate ?? '—' }}</td>
                                <td>
                                    <a href="{{ route('ticket.view', $t->ticketId) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No tickets found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="table-footer-bar d-flex justify-content-between align-items-center mt-2">
            <div class="d-flex align-items-center gap-3">
                @if($tickets->total())
                <div>Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} entries</div>
                @endif
            </div>
        </div>
        <div class="pagination-box">
            {{ $tickets->appends(request()->all())->links('pagination::bootstrap-5') }}
        </div>

    </div>

    <div class="footer text-center bg-white p-2 border-top mt-3">
        <p class="text-dark mb-0">Copyright &copy; 2026 - Vecura.</p>
    </div>
</div>
@endsection
