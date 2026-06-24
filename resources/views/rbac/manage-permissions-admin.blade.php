@extends('layout.mainlayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Permission Management</h4>
                <h6 class="text-muted">Create permissions for modules (Admin Only)</h6>
            </div>
        </div>

        <div class="row">
            {{-- Left Panel: Create Permission Form --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-plus me-2"></i>Create New Permission
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="createPermissionForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="mb-4">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    Module Name
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <select id="moduleSelect" name="module" class="form-select form-control-lg" required>
                                    <option value="">-- Select Module --</option>
                                    @foreach($modules as $module)
                                        <option value="{{ $module->name }}">
                                            {{ ucfirst($module->name) }} ({{ $module->description }})
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Select the module this permission belongs to
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    Action Type
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <select id="actionSelect" name="name" class="form-select form-control-lg" required>
                                    <option value="">-- Select Action --</option>
                                    <option value="read">📖 Read (View records)</option>
                                    <option value="create">➕ Create (Add new records)</option>
                                    <option value="edit">✏️ Edit (Modify records)</option>
                                    <option value="delete">🗑️ Delete (Remove records)</option>
                                    <option value="approve">✅ Approve (Approve workflow)</option>
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Choose what action users can perform
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description (Optional)</label>
                                <textarea id="descriptionField" name="description" class="form-control" rows="2"
                                    placeholder="e.g., Allow users to view staff records"></textarea>
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Leave blank to auto-generate
                                </small>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="isActiveCheck" name="is_active"
                                        class="form-check-input" value="1" checked style="width: 3.5em; height: 1.8em;">
                                    <label class="form-check-label ms-2" for="isActiveCheck">
                                        <strong>Active</strong>
                                        <small class="text-muted d-block">Enable this permission</small>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="ti ti-plus me-2"></i>Create Permission
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="ti ti-alert-circle me-2"></i>
                            <strong>Tip:</strong> After creating permissions, assign them to roles in "Manage Roles & Permissions"
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Panel: Existing Permissions List --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ti ti-list me-2"></i>All Permissions
                                <span class="badge bg-secondary ms-2" id="permissionCount">0</span>
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadPermissions()">
                                <i class="ti ti-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%">Action</th>
                                        <th style="width: 20%">Module</th>
                                        <th style="width: 40%">Description</th>
                                        <th style="width: 15%" class="text-center">Status</th>
                                        <th style="width: 10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="permissionsList">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="ti ti-loader-2 me-2" style="animation: spin 1s linear infinite;"></i>
                                            Loading permissions...
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Stats Card --}}
                <div class="row mt-3 g-3">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Total Permissions</h6>
                                <h3 class="mb-0" id="totalCount">0</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Active</h6>
                                <h3 class="mb-0 text-success" id="activeCount">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes spin {
    to { transform: rotate(360deg); }
}

.form-control-lg, .form-select.form-control-lg {
    min-height: 2.5rem;
    font-size: 0.95rem;
}

.card {
    border-radius: 8px;
}

.card-header {
    border-radius: 8px 8px 0 0;
}

.badge {
    padding: 0.35rem 0.6rem;
    font-size: 0.75rem;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
}

.form-check-input {
    cursor: pointer;
    border: 2px solid #ddd;
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}
</style>

<script>
// Load permissions on page load
document.addEventListener('DOMContentLoaded', () => {
    loadPermissions();
    setupFormListeners();
});

function setupFormListeners() {
    const moduleSelect = document.getElementById('moduleSelect');
    const actionSelect = document.getElementById('actionSelect');
    const descriptionField = document.getElementById('descriptionField');

    actionSelect.addEventListener('change', () => {
        if (!descriptionField.value) {
            const module = moduleSelect.value;
            const action = actionSelect.value;
            if (module && action) {
                descriptionField.value = `${action} on ${module}`;
            }
        }
    });

    moduleSelect.addEventListener('change', () => {
        if (!descriptionField.value) {
            const module = moduleSelect.value;
            const action = actionSelect.value;
            if (module && action) {
                descriptionField.value = `${action} on ${module}`;
            }
        }
    });
}

function loadPermissions() {
    fetch('/api/permissions')
        .then(r => r.json())
        .then(permissions => {
            let html = '';
            let totalCount = permissions.length;
            let activeCount = 0;

            if (permissions.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted py-4">No permissions created yet</td></tr>';
            } else {
                // Group by module
                const grouped = {};
                permissions.forEach(p => {
                    if (!grouped[p.module]) grouped[p.module] = [];
                    grouped[p.module].push(p);
                });

                // Render grouped
                Object.keys(grouped).sort().forEach(module => {
                    grouped[module].forEach((perm, idx) => {
                        if (perm.is_active) activeCount++;

                        const icons = {
                            'read': '📖',
                            'create': '➕',
                            'edit': '✏️',
                            'delete': '🗑️',
                            'approve': '✅'
                        };
                        const icon = icons[perm.name] || '';

                        html += `<tr>
                            <td>
                                <span class="badge bg-primary">${icon} ${perm.name.toUpperCase()}</span>
                            </td>
                            <td>
                                <span class="badge bg-info">${perm.module}</span>
                            </td>
                            <td>${perm.description || '-'}</td>
                            <td class="text-center">
                                ${perm.is_active ?
                                    '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Active</span>' :
                                    '<span class="badge bg-secondary"><i class="ti ti-x me-1"></i>Inactive</span>'}
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                    onclick="deletePermission(${perm.id})">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                });
            }

            document.getElementById('permissionsList').innerHTML = html;
            document.getElementById('permissionCount').textContent = totalCount;
            document.getElementById('totalCount').textContent = totalCount;
            document.getElementById('activeCount').textContent = activeCount;
        })
        .catch(e => {
            console.error('Error loading permissions:', e);
            document.getElementById('permissionsList').innerHTML =
                '<tr><td colspan="5" class="text-center text-danger py-4">Error loading permissions</td></tr>';
        });
}

function deletePermission(id) {
    Swal.fire({
        title: 'Delete Permission?',
        text: 'This permission will be removed. Roles using it will lose this permission.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/api/permissions/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    Swal.fire('Deleted!', 'Permission deleted successfully', 'success');
                    loadPermissions();
                } else {
                    Swal.fire('Error', data.message || 'Failed to delete', 'error');
                }
            });
        }
    });
}

// Create permission form
document.getElementById('createPermissionForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const module = document.getElementById('moduleSelect').value;
    const name = document.getElementById('actionSelect').value;
    const description = document.getElementById('descriptionField').value;
    const is_active = document.getElementById('isActiveCheck').checked ? 1 : 0;

    if (!module || !name) {
        Swal.fire('Error', 'Please select both Module and Action', 'error');
        return;
    }

    try {
        const response = await fetch('/api/permissions', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                module,
                name,
                description: description || `${name} on ${module}`,
                is_active
            })
        });

        const data = await response.json();

        if (data.status) {
            Swal.fire({
                icon: 'success',
                title: 'Created!',
                text: `Permission "${name}:${module}" created successfully`,
                timer: 2000
            });

            e.target.reset();
            loadPermissions();
        } else {
            Swal.fire('Error', data.message || 'Failed to create permission', 'error');
        }
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
});
</script>
@endsection
