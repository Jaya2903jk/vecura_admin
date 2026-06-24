@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <h4 class="mb-4">Employee: {{ $employee->FullName }}</h4>

        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Roles ({{ count($roles) }})</div>
                    <div class="card-body">
                        @forelse($roles as $role)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $role->role->name }}</span>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="removeRole({{ $employee->UserID }}, {{ $role->role_id }})">Remove</button>
                            </div>
                        @empty
                            <p class="text-muted">No roles assigned</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Departments ({{ count($departments) }})</div>
                    <div class="card-body">
                        @forelse($departments as $dept)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span>{{ $dept->department->DepartmentName }}</span>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="removeDept({{ $employee->UserID }}, {{ $dept->department_id }})">Remove</button>
                            </div>
                        @empty
                            <p class="text-muted">No departments assigned</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Hierarchy Access ({{ count($hierarchy) }})</div>
                    <div class="card-body">
                        @forelse($hierarchy as $h)
                            <div class="mb-2 p-2 border rounded">
                                <strong>{{ $h->role->name }}</strong><br>
                                @if($h->zone_id) Zone: {{ $h->zone->zone_name }}<br> @endif
                                @if($h->branch_id) Branch: {{ $h->branch->branch_name }}<br> @endif
                                @if($h->location_id) Location: {{ $h->location->location_name }}<br> @endif
                            </div>
                        @empty
                            <p class="text-muted">No hierarchy access</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function removeRole(empId, roleId) {
    if (confirm('Remove this role?')) {
        $.post(`/rbac/remove-role/${empId}/${roleId}`, { _token: '{{ csrf_token() }}' }, function(res) {
            if (res.status) location.reload();
        });
    }
}

function removeDept(empId, deptId) {
    if (confirm('Remove this department?')) {
        $.post(`/rbac/remove-department/${empId}/${deptId}`, { _token: '{{ csrf_token() }}' }, function(res) {
            if (res.status) location.reload();
        });
    }
}
</script>
@endsection
