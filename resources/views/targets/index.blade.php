@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content px-4 py-3">

            <!-- PAGE HEADER -->
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h4 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                        <i class="ti ti-target text-primary fs-24"></i>Sales Targets
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle fs-12 ms-2 px-2.5 py-1 rounded-pill">
                            Total Targets: {{ $targets->total() }}
                        </span>
                    </h4>
                    <p class="text-muted fs-13 mb-0">Manage daily and monthly employee and branch sales target allocations.</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#targetModal" onclick="resetForm()">
                        <i class="ti ti-plus me-1"></i>Add New Target
                    </button>
                </div>
            </div>

            <!-- SUCCESS ALERT -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-xs mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="ti ti-circle-check fs-20 me-2 text-success"></i>
                        <span class="fs-13 fw-semibold">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- FILTER BAR -->
            <div class="card border-0 shadow-xs mb-4 rounded-3">
                <div class="card-body p-3 bg-light-subtle rounded-3">
                    <form method="GET" action="{{ route('targets.index') }}" id="targetFilterForm">
                        <div class="row g-2 align-items-center">
                            {{-- Target Type --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Target Type</label>
                                <select name="target_type" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('targetFilterForm').submit()">
                                    <option value="">All Types</option>
                                    <option value="day" {{ request('target_type') == 'day' ? 'selected' : '' }}>Day Target</option>
                                    <option value="month" {{ request('target_type') == 'month' ? 'selected' : '' }}>Month Target</option>
                                </select>
                            </div>

                            {{-- Branch --}}
                            <div class="col-md-3">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Branch</label>
                                <select name="branch_id" class="form-select form-select-sm fs-13 text-dark" onchange="document.getElementById('targetFilterForm').submit()">
                                    <option value="">All Branches</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Employee Search --}}
                            <div class="col-md-4">
                                <label class="form-label fw-semibold fs-13 text-dark mb-1">Employee Search</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white border-end-0"><i class="ti ti-search text-muted"></i></span>
                                    <input type="text" name="search" class="form-control border-start-0 fs-13 text-dark"
                                        placeholder="Name or Code..." value="{{ request('search') }}">
                                </div>
                            </div>

                            {{-- Action Controls --}}
                            <div class="col-md-2 d-flex align-items-end gap-1 pt-3">
                                <button type="submit" class="btn btn-primary btn-sm fw-semibold fs-13 flex-fill">
                                    <i class="ti ti-filter me-1"></i>Filter
                                </button>
                                <a href="{{ route('targets.index') }}" class="btn btn-light border btn-sm fw-semibold fs-13 text-secondary" title="Reset Filters">
                                    <i class="ti ti-refresh"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TARGETS TABLE CARD -->
            <div class="card border-0 shadow-xs rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 ps-4">Employee Info</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Code</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Branch</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Target Type</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Target Amount</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3">Valid Period</th>
                                    <th class="fs-12 text-uppercase fw-bold text-slate-700 py-3 text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($targets as $target)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="patient-avatar-circle flex-shrink-0 text-decoration-none">
                                                    <i class="ti ti-target fs-16 text-primary"></i>
                                                </div>
                                                <div>
                                                    <span class="fw-bold text-dark fs-13">{{ $target->employee?->FullName ?? 'All Employees' }}</span>
                                                    <div class="fs-11 text-muted">ID: #{{ $target->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="patient-code-tag font-monospace">{{ $target->employee?->UserCode ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border px-2.5 py-1 fs-12 fw-semibold rounded-2">
                                                <i class="ti ti-building-store me-1 text-muted"></i>{{ $target->branch?->branch_name ?? 'All Branches' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($target->target_type == 'day')
                                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-calendar-event me-1"></i>Day Target
                                                </span>
                                            @else
                                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1 fs-11 fw-bold rounded-pill">
                                                    <i class="ti ti-calendar me-1"></i>Month Target
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark fs-13">₹{{ number_format($target->target_amount, 2) }}</span>
                                        </td>
                                        <td class="fs-12 text-muted">
                                            <i class="ti ti-clock me-1"></i>{{ $target->effective_from?->format('d M Y') ?? '-' }} to {{ $target->effective_to?->format('d M Y') ?? '-' }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex align-items-center justify-content-end gap-1">
                                                <button class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Edit Target" data-bs-toggle="modal"
                                                    data-bs-target="#targetModal" onclick="editTarget({{ $target->toJson() }})">
                                                    <i class="ti ti-edit fs-15 text-warning"></i>
                                                </button>
                                                <form action="{{ route('targets.destroy', $target->id) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this target?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-light border btn-sm px-2 py-1 shadow-2xs" title="Delete Target">
                                                        <i class="ti ti-trash fs-15 text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5 fs-13">
                                            <i class="ti ti-target-off fs-36 text-muted mb-2 d-block"></i>
                                            No targets found matching criteria.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TABLE FOOTER / PAGINATION BAR -->
            <div class="table-footer-bar d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 fs-13 bg-white p-3 rounded-3 shadow-xs border gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="text-muted">
                        Showing <span class="fw-semibold text-dark">{{ $targets->firstItem() ?? 0 }}</span> to <span class="fw-semibold text-dark">{{ $targets->lastItem() ?? 0 }}</span> of <span class="fw-semibold text-dark">{{ $targets->total() }}</span> entries
                    </div>
                </div>
                <div>
                    {{ $targets->appends(request()->query())->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- TARGET MODAL -->
    <div class="modal fade" id="targetModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-bottom px-4 py-3 bg-light">
                    <h5 class="modal-title fw-bold text-dark d-flex align-items-center gap-2" id="modalTitle">
                        <i class="ti ti-target text-primary fs-20"></i>Add Target
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="targetForm" method="POST" action="{{ route('targets.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <!-- Employee Selection -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label fw-semibold fs-13 text-dark">Employee (Optional)</label>
                            <select name="user_id" id="user_id" class="form-select fs-13">
                                <option value="">-- Select Employee --</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->UserID }}">
                                        {{ $employee->FullName }} ({{ $employee->UserCode }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted fs-12">Leave blank for branch/company-wide targets</small>
                        </div>

                        <!-- Branch Selection -->
                        <div class="mb-3">
                            <label for="branch_id" class="form-label fw-semibold fs-13 text-dark">Branch (Optional)</label>
                            <select name="branch_id" id="branch_id" class="form-select fs-13">
                                <option value="">-- Select Branch --</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->branch_id }}">
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted fs-12">Leave blank for company-wide targets</small>
                        </div>

                        <!-- Target Type -->
                        <div class="mb-3">
                            <label for="target_type" class="form-label fw-semibold fs-13 text-dark">Target Type <span class="text-danger">*</span></label>
                            <select name="target_type" id="target_type" class="form-select fs-13" required>
                                <option value="">-- Select Type --</option>
                                <option value="day">Day Target</option>
                                <option value="month">Month Target</option>
                            </select>
                        </div>

                        <!-- Target Amount -->
                        <div class="mb-3">
                            <label for="target_amount" class="form-label fw-semibold fs-13 text-dark">Target Amount (₹) <span class="text-danger">*</span></label>
                            <input type="number" name="target_amount" id="target_amount" class="form-control fs-13"
                                placeholder="0.00" step="0.01" required>
                        </div>

                        <!-- Effective From -->
                        <div class="mb-3">
                            <label for="effective_from" class="form-label fw-semibold fs-13 text-dark">Effective From <span class="text-danger">*</span></label>
                            <input type="date" name="effective_from" id="effective_from" class="form-control fs-13" required>
                        </div>

                        <!-- Effective To -->
                        <div class="mb-3">
                            <label for="effective_to" class="form-label fw-semibold fs-13 text-dark">Effective To <span class="text-danger">*</span></label>
                            <input type="date" name="effective_to" id="effective_to" class="form-control fs-13" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold fs-13 text-dark">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control fs-13" rows="2"
                                placeholder="Any notes about this target"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer px-4 py-3 bg-light border-top gap-2">
                        <button type="button" class="btn btn-light border btn-sm fw-semibold" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold px-3">
                            <i class="ti ti-check me-1"></i> <span id="submitBtnText">Create Target</span>
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
            document.getElementById('modalTitle').innerHTML = '<i class="ti ti-target text-primary fs-20"></i>Add Target';
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
            document.getElementById('effective_from').value = target.effective_from ? target.effective_from.split('T')[0] : '';
            document.getElementById('effective_to').value = target.effective_to ? target.effective_to.split('T')[0] : '';
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
            document.getElementById('modalTitle').innerHTML = '<i class="ti ti-edit text-warning fs-20"></i>Edit Target';
            document.getElementById('submitBtnText').textContent = 'Update Target';
        }
    </script>
@endsection
