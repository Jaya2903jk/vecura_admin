@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <h1>Manage Role Permissions</h1>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Select Role</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($roles as $role)
                                <a href="?role_id={{ $role->id }}"
                                    class="list-group-item list-group-item-action {{ $selectedRole && $selectedRole->id === $role->id ? 'active' : '' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $role->name }}</strong>
                                            <small class="d-block text-muted">Level {{ $role->level }}</small>
                                        </div>
                                        <span class="badge bg-secondary">{{ $role->permissions->count() }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                @if($selectedRole)
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ $selectedRole->name }} - Permissions Management</h5>
                        </div>
                        <div class="card-body">
                            <!-- Current Permissions -->
                            <div class="mb-4">
                                <h6>Current Permissions ({{ $selectedRole->permissions->count() }})</h6>
                                @if($selectedRole->permissions->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Module</th>
                                                    <th>Action</th>
                                                    <th>Description</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($selectedRole->permissions()->orderBy('module')->get() as $permission)
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-info">{{ ucfirst($permission->module) }}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary">{{ ucfirst($permission->name) }}</span>
                                                        </td>
                                                        <td>{{ $permission->description }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger"
                                                                onclick="removePermission({{ $selectedRole->id }}, {{ $permission->id }})">
                                                                <i class="ti ti-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No permissions assigned yet.</p>
                                @endif
                            </div>

                            <!-- Add New Permission -->
                            <div class="border-top pt-4">
                                <h6>Add Permission</h6>
                                <form id="addPermissionForm">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label class="form-label">Module</label>
                                            <select id="moduleSelect" class="form-control" required>
                                                <option value="">-- Select Module --</option>
                                                @foreach($modules as $module)
                                                    <option value="{{ $module->name }}">{{ ucfirst($module->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Action</label>
                                            <select id="actionSelect" class="form-control" required>
                                                <option value="">-- Select Action --</option>
                                                <option value="read">Read</option>
                                                <option value="create">Create</option>
                                                <option value="edit">Edit</option>
                                                <option value="delete">Delete</option>
                                                <option value="approve">Approve</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-success w-100" onclick="addPermission({{ $selectedRole->id }})">
                                                <i class="ti ti-plus"></i> Add
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- Available Permissions Table -->
                            <div class="border-top pt-4">
                                <h6>Available Permissions (Not Assigned)</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Module</th>
                                                <th>Action</th>
                                                <th>Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $assignedIds = $selectedRole->permissions->pluck('id')->toArray();
                                                $available = \App\Models\Permission::where('is_active', 1)
                                                    ->whereNotIn('id', $assignedIds)
                                                    ->orderBy('module')
                                                    ->orderBy('name')
                                                    ->get();
                                            @endphp
                                            @forelse($available as $permission)
                                                <tr>
                                                    <td><span class="badge bg-info">{{ ucfirst($permission->module) }}</span></td>
                                                    <td><span class="badge bg-primary">{{ ucfirst($permission->name) }}</span></td>
                                                    <td>{{ $permission->description }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-success"
                                                            onclick="assignPermission({{ $selectedRole->id }}, {{ $permission->id }})">
                                                            <i class="ti ti-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">All permissions assigned to this role</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card-body text-center text-muted">
                            <p>Select a role from the left to manage its permissions</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function assignPermission(roleId, permissionId) {
    fetch(`/rbac/role/${roleId}/permission`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ permission_id: permissionId })
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed'));
        }
    });
}

function removePermission(roleId, permissionId) {
    if (!confirm('Remove this permission?')) return;

    fetch(`/rbac/role/${roleId}/permission/${permissionId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed'));
        }
    });
}

function addPermission(roleId) {
    const module = document.getElementById('moduleSelect').value;
    const action = document.getElementById('actionSelect').value;

    if (!module || !action) {
        alert('Please select both module and action');
        return;
    }

    fetch(`/api/permissions`, {
        method: 'GET'
    })
    .then(r => r.json())
    .then(permissions => {
        const perm = permissions.find(p => p.name === action && p.module === module);
        if (!perm) {
            alert('Permission not found. Create it first in permission management.');
            return;
        }
        assignPermission(roleId, perm.id);
    });
}
</script>
@endsection
