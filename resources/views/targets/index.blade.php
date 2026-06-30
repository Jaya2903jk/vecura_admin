@extends('layout.mainlayout')

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Sales Targets</h1>
        <a href="{{ route('targets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Target
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Target Type</label>
                    <select name="target_type" class="form-select">
                        <option value="">All Types</option>
                        <option value="day" {{ request('target_type') == 'day' ? 'selected' : '' }}>Day Target</option>
                        <option value="month" {{ request('target_type') == 'month' ? 'selected' : '' }}>Month Target</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Employee</label>
                    <input type="text" name="search" class="form-control" placeholder="Name or Code" value="{{ request('search') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-secondary w-100">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Targets Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 15%;">Employee</th>
                        <th style="width: 15%;">Code</th>
                        <th style="width: 12%;">Branch</th>
                        <th style="width: 12%;">Target Type</th>
                        <th style="width: 15%;">Amount</th>
                        <th style="width: 15%;">Valid Period</th>
                        <th style="width: 16%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($targets as $target)
                        <tr>
                            <td>
                                <strong>{{ $target->employee?->FullName ?? 'All Employees' }}</strong>
                            </td>
                            <td>{{ $target->employee?->UserCode ?? '-' }}</td>
                            <td>{{ $target->branch?->branch_name ?? 'All Branches' }}</td>
                            <td>
                                <span class="badge {{ $target->target_type == 'day' ? 'bg-info' : 'bg-warning' }}">
                                    {{ ucfirst($target->target_type) }}
                                </span>
                            </td>
                            <td>
                                <strong>₹ {{ number_format($target->target_amount, 2) }}</strong>
                            </td>
                            <td>
                                <small>
                                    {{ $target->effective_from?->format('d M Y') ?? '-' }} to
                                    {{ $target->effective_to?->format('d M Y') ?? '-' }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('targets.edit', $target->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('targets.destroy', $target->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <p class="text-muted">No targets found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $targets->links() }}
    </div>
</div>
@endsection
