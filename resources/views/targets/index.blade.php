@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">

        <div class="content">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Sales Targets</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#targetModal" onclick="resetForm()">
                    <i class="fas fa-plus"></i> Add Target
                </button>
            </div>

            @if (session('success'))
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
                                <option value="day" {{ request('target_type') == 'day' ? 'selected' : '' }}>Day Target
                                </option>
                                <option value="month" {{ request('target_type') == 'month' ? 'selected' : '' }}>Month
                                    Target</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Branch</label>
                            <select name="branch_id" class="form-select">
                                <option value="">All Branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Employee</label>
                            <input type="text" name="search" class="form-control" placeholder="Name or Code"
                                value="{{ request('search') }}">
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
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#targetModal" onclick="editTarget({{ $target->toJson() }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ route('targets.destroy', $target->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Delete?')">
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
    </div>

    <!-- Target Modal -->
    <div class="modal fade" id="targetModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle">Add Target</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="targetForm" method="POST" action="{{ route('targets.store') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Employee Selection -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Employee (Optional)</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->UserID }}">
                                        {{ $employee->FullName }} ({{ $employee->UserCode }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Leave blank for branch/company-wide targets</small>
                        </div>

                        <!-- Branch Selection -->
                        <div class="mb-3">
                            <label for="branch_id" class="form-label">Branch (Optional)</label>
                            <select name="branch_id" id="branch_id" class="form-select">
                                <option value="">-- Select Branch --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->branch_id }}">
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Leave blank for company-wide targets</small>
                        </div>

                        <!-- Target Type -->
                        <div class="mb-3">
                            <label for="target_type" class="form-label">Target Type <span class="text-danger">*</span></label>
                            <select name="target_type" id="target_type" class="form-select" required>
                                <option value="">-- Select Type --</option>
                                <option value="day">Day Target</option>
                                <option value="month">Month Target</option>
                            </select>
                        </div>

                        <!-- Target Amount -->
                        <div class="mb-3">
                            <label for="target_amount" class="form-label">Target Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="target_amount" id="target_amount" class="form-control"
                                placeholder="0.00" step="0.01" required>
                        </div>

                        <!-- Effective From -->
                        <div class="mb-3">
                            <label for="effective_from" class="form-label">Effective From <span class="text-danger">*</span></label>
                            <input type="date" name="effective_from" id="effective_from" class="form-control" required>
                        </div>

                        <!-- Effective To -->
                        <div class="mb-3">
                            <label for="effective_to" class="form-label">Effective To <span class="text-danger">*</span></label>
                            <input type="date" name="effective_to" id="effective_to" class="form-control" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control" rows="2"
                                placeholder="Any notes about this target"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> <span id="submitBtnText">Create Target</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function resetForm() {
            document.getElementById('targetForm').reset();
            document.getElementById('targetForm').action = "{{ route('targets.store') }}";
            document.getElementById('modalTitle').textContent = 'Add Target';
            document.getElementById('submitBtnText').textContent = 'Create Target';

            // Remove method field if exists
            const methodField = document.getElementById('targetForm').querySelector('input[name="_method"]');
            if (methodField) methodField.remove();
        }

        function editTarget(target) {
            // Fill form with target data
            document.getElementById('user_id').value = target.user_id || '';
            document.getElementById('branch_id').value = target.branch_id || '';
            document.getElementById('target_type').value = target.target_type;
            document.getElementById('target_amount').value = target.target_amount;
            document.getElementById('effective_from').value = target.effective_from;
            document.getElementById('effective_to').value = target.effective_to;
            document.getElementById('description').value = target.description || '';

            // Update form action to update endpoint
            const form = document.getElementById('targetForm');
            form.action = `/targets/${target.id}`;

            // Add method spoofing for PUT request
            let methodField = form.querySelector('input[name="_method"]');
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                form.appendChild(methodField);
            }
            methodField.value = 'PUT';

            // Update modal title and button
            document.getElementById('modalTitle').textContent = 'Edit Target';
            document.getElementById('submitBtnText').textContent = 'Update Target';
        }
    </script>
@endsection
