@extends('layout.mainlayout')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Edit Target</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('targets.update', $target->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Employee Selection -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Employee (Optional - Leave blank for branch-wide)</label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                                <option value="">-- Select Employee --</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->UserID }}" {{ $target->user_id == $employee->UserID ? 'selected' : '' }}>
                                        {{ $employee->FullName }} ({{ $employee->UserCode }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Branch Selection -->
                        <div class="mb-3">
                            <label for="branch_id" class="form-label">Branch (Optional)</label>
                            <select name="branch_id" id="branch_id" class="form-select @error('branch_id') is-invalid @enderror">
                                <option value="">-- Select Branch --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $target->branch_id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Target Type -->
                        <div class="mb-3">
                            <label for="target_type" class="form-label">Target Type</label>
                            <select name="target_type" id="target_type" class="form-select @error('target_type') is-invalid @enderror" required>
                                <option value="">-- Select Type --</option>
                                <option value="day" {{ $target->target_type == 'day' ? 'selected' : '' }}>Day Target</option>
                                <option value="month" {{ $target->target_type == 'month' ? 'selected' : '' }}>Month Target</option>
                            </select>
                            @error('target_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Target Amount -->
                        <div class="mb-3">
                            <label for="target_amount" class="form-label">Target Amount (₹)</label>
                            <input type="number" name="target_amount" id="target_amount" class="form-control @error('target_amount') is-invalid @enderror"
                                   placeholder="0.00" step="0.01" value="{{ $target->target_amount }}" required>
                            @error('target_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Effective From -->
                        <div class="mb-3">
                            <label for="effective_from" class="form-label">Effective From</label>
                            <input type="date" name="effective_from" id="effective_from" class="form-control @error('effective_from') is-invalid @enderror"
                                   value="{{ $target->effective_from?->format('Y-m-d') }}" required>
                            @error('effective_from')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Effective To -->
                        <div class="mb-3">
                            <label for="effective_to" class="form-label">Effective To</label>
                            <input type="date" name="effective_to" id="effective_to" class="form-control @error('effective_to') is-invalid @enderror"
                                   value="{{ $target->effective_to?->format('Y-m-d') }}" required>
                            @error('effective_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">Description (Optional)</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="3" placeholder="Any notes about this target">{{ $target->description }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Update Target
                            </button>
                            <a href="{{ route('targets.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
