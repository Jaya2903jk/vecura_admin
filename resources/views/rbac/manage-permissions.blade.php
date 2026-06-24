@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex align-items-center">
                <h1>Manage Role Permissions</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Select Role</h5>
                    </div>
                    <div class="card-body">
                        <form id="roleSelectForm">
                            <div class="mb-3">
                                <label class="form-label">Choose Role</label>
                                <select id="roleSelect" class="form-control" required>
                                    <option value="">-- Select Role --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">
                                            {{ $role->name }} (Level {{ $role->level }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Permissions for <span id="selectedRole">-</span></h5>
                    </div>
                    <div class="card-body">
                        <div id="permissionsContainer">
                            <p class="text-muted">Select a role to view its permissions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Legend -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Permission Guide</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>read</strong>
                                <p class="text-muted small">View records</p>
                            </div>
                            <div class="col-md-3">
                                <strong>create</strong>
                                <p class="text-muted small">Add new records</p>
                            </div>
                            <div class="col-md-3">
                                <strong>edit</strong>
                                <p class="text-muted small">Modify existing records</p>
                            </div>
                            <div class="col-md-3">
                                <strong>delete</strong>
                                <p class="text-muted small">Remove records</p>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <strong>approve</strong>
                                <p class="text-muted small">Approve changes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('roleSelect').addEventListener('change', function() {
    const roleId = this.value;
    if (!roleId) {
        document.getElementById('permissionsContainer').innerHTML = '<p class="text-muted">Select a role to view its permissions</p>';
        document.getElementById('selectedRole').textContent = '-';
        return;
    }

    // Get role name for display
    const roleText = this.options[this.selectedIndex].text;
    document.getElementById('selectedRole').textContent = roleText;

    // Fetch permissions for this role
    fetch(`/api/roles/${roleId}/permissions`)
        .then(response => response.json())
        .then(data => {
            if (!data.permissions || data.permissions.length === 0) {
                document.getElementById('permissionsContainer').innerHTML = '<p class="text-warning">No permissions assigned to this role</p>';
                return;
            }

            // Group by module
            const grouped = {};
            data.permissions.forEach(perm => {
                if (!grouped[perm.module]) {
                    grouped[perm.module] = [];
                }
                grouped[perm.module].push(perm);
            });

            // Render HTML
            let html = '';
            Object.keys(grouped).sort().forEach(module => {
                html += `<div class="mb-4">
                    <h6 class="text-uppercase text-primary mb-2">${module}</h6>
                    <div class="row">`;

                grouped[module].forEach(perm => {
                    html += `<div class="col-md-2 mb-2">
                        <span class="badge bg-success">${perm.name}</span>
                    </div>`;
                });

                html += `</div></div>`;
            });

            document.getElementById('permissionsContainer').innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('permissionsContainer').innerHTML = '<p class="text-danger">Error loading permissions</p>';
        });
});
</script>
@endsection
