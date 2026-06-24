@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <h4 class="mb-4">Assign Hierarchy Access</h4>
        <form id="assignHierForm" class="card p-4">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">Select Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->UserID }}">{{ $emp->FullName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role <span class="text-danger">*</span></label>
                    <select name="role_id" class="form-control" required>
                        <option value="">Select Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Zone</label>
                    <select name="zone_id" class="form-control">
                        <option value="">Select Zone</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->zone_id }}">{{ $zone->zone_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Branch</label>
                    <select name="branch_id" class="form-control">
                        <option value="">Select Branch</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->branch_id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Location</label>
                <select name="location_id" class="form-control">
                    <option value="">Select Location</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->location_id }}">{{ $loc->location_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Access</button>
        </form>
    </div>
</div>

<script>
document.getElementById('assignHierForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/rbac/assign-hierarchy', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
    })
    .then(data => {
        if (data.status) {
            alert(data.message);
            document.getElementById('assignHierForm').reset();
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to assign hierarchy: ' + error.message);
    });
});
</script>
@endsection
