@extends('layout.mainlayout')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <h1>Manage Roles & Permissions</h1>
                <p class="text-muted">Create roles and assign permissions to control employee access</p>
            </div>

            <div class="row">
                {{-- Left: Create New Role --}}
                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0"><i class="ti ti-plus me-2"></i>Create New Role</h5>
                        </div>
                        <div class="card-body">
                            <form id="createRoleForm">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Role Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                        placeholder="e.g., SalesManager" required>
                                    <small class="text-muted">Unique name for this role</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Level <span class="text-danger">*</span></label>
                                    <input type="number" name="level" class="form-control" placeholder="e.g., 2"
                                        min="1" required>
                                    <small class="text-muted">1=High (Admin), 5=Low (Employee)</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="3" placeholder="What is this role for?"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-plus me-1"></i>Create Role
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right: Existing Roles --}}
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h5 class="mb-0"><i class="ti ti-list me-2"></i>All Roles ({{ $roles->count() }})</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 25%">Role Name</th>
                                            <th style="width: 10%" class="text-center">Level</th>
                                            <th style="width: 20%" class="text-center">Permissions</th>
                                            <th style="width: 45%" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($roles as $role)
                                            <tr>
                                                <td>
                                                    <strong>{{ $role->name }}</strong>
                                                    @if ($role->description)
                                                        <br><small class="text-muted">{{ $role->description }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">{{ $role->level }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-secondary">{{ $role->permissions->count() }}/60</span>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-primary"
                                                        onclick="showPermissionModal({{ $role->id }}, '{{ $role->name }}')"
                                                        title="Manage permissions for this role">
                                                        <i class="ti ti-lock me-1"></i>Permissions
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-4">
                                                    No roles found. Create one to get started.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================================================================== --}}
    {{-- Permission Management Modal — markup restructured, no custom styles --}}
    {{-- Uses only existing Bootstrap classes already used in your app --}}
    {{-- ===================================================================== --}}
    <div class="modal fade" id="permissionModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="ti ti-lock me-2"></i>Manage Permissions - <span id="roleName" class="fw-bold"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Current Permissions Section --}}
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">
                                <i class="ti ti-check me-2 text-success"></i>Assigned Permissions
                            </h6>
                            <span id="assignedCount" class="badge bg-success">0</span>
                        </div>

                        <div id="permLoadingState" class="text-center py-4">
                            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                            <span class="text-muted">Loading permissions...</span>
                        </div>

                        <div id="permissionList" style="display:none;"></div>

                        <div id="permEmptyState" class="text-center text-muted py-4 border rounded" style="display:none;">
                            No permissions assigned yet
                        </div>
                    </div>

                    <hr>

                    {{-- Add Permission Section --}}
                    <div>
                        <h6 class="mb-3">
                            <i class="ti ti-plus me-2 text-primary"></i>Add Permission to Role
                        </h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Select Module</label>
                                <select id="moduleSelect" class="form-select">
                                    <option value="">-- Loading modules --</option>
                                </select>
                                <small class="text-muted">Only active modules appear here</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Select Action</label>
                                <select id="actionSelect" class="form-select">
                                    <option value="">-- Choose Action --</option>
                                </select>
                                <small class="text-muted">Actions from selected module</small>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success w-100 mt-3" onclick="addPermission()">
                            <i class="ti ti-plus me-1"></i>Add Permission
                        </button>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentRoleId = null;
        let availableModules = [];
        let allPermissions = [];
        // Maps action/module names to Tabler icon classes only — no colors, inherits your theme
        const ACTION_ICONS = {
            read: 'ti-eye',
            create: 'ti-plus',
            edit: 'ti-pencil',
            delete: 'ti-trash',
            approve: 'ti-check'
        };

        const MODULE_ICONS = {
            department: 'ti-briefcase',
            designation: 'ti-user-check',
            branch: 'ti-building-skyscraper',
            location: 'ti-map-pin',
            country: 'ti-world',
            zone: 'ti-map-2',
            state: 'ti-map',
            city: 'ti-building',
        };

        document.addEventListener('DOMContentLoaded', () => {
            loadModules();
        });

        function loadModules() {
            fetch('/api/modules')
                .then(r => r.json())
                .then(modules => {
                    availableModules = modules;
                    const select = document.getElementById('moduleSelect');
                    select.innerHTML = '<option value="">-- Choose Module --</option>';

                    modules.forEach(mod => {
                        const option = document.createElement('option');
                        option.value = mod.name;
                        option.textContent = mod.name.charAt(0).toUpperCase() + mod.name.slice(1);
                        select.appendChild(option);
                    });

                    select.addEventListener('change', loadActionsForModule);
                })
                .catch(e => {
                    console.error('Error loading modules:', e);
                    document.getElementById('moduleSelect').innerHTML =
                        '<option value="">Error loading modules</option>';
                });
        }

        function loadActionsForModule() {
            const moduleName = document.getElementById('moduleSelect').value;
            const actionSelect = document.getElementById('actionSelect');

            if (!moduleName) {
                actionSelect.innerHTML = '<option value="">-- Choose Action --</option>';
                return;
            }

            fetch('/api/permissions')
                .then(r => r.json())
                .then(allPerms => {
                    const modulePerms = allPerms.filter(p => p.module === moduleName && p.is_active);

                    actionSelect.innerHTML = '<option value="">-- Choose Action --</option>';

                    if (modulePerms.length === 0) {
                        actionSelect.innerHTML = '<option value="">No actions available</option>';
                        return;
                    }

                    const actionOrder = ['read', 'create', 'edit', 'delete', 'approve'];
                    const uniqueActions = new Map();
                    modulePerms.forEach(perm => {
                        if (!uniqueActions.has(perm.name)) uniqueActions.set(perm.name, perm);
                    });

                    const sortedActions = Array.from(uniqueActions.values()).sort((a, b) => {
                        return actionOrder.indexOf(a.name) - actionOrder.indexOf(b.name);
                    });

                    sortedActions.forEach(action => {
                        const option = document.createElement('option');
                        option.value = action.name;
                        const label = action.name.charAt(0).toUpperCase() + action.name.slice(1);
                        option.textContent = label;
                        actionSelect.appendChild(option);
                    });
                })
                .catch(e => {
                    console.error('Error loading actions:', e);
                    actionSelect.innerHTML = '<option value="">Error loading actions</option>';
                });
        }

        function loadPermissionsForRole(roleId) {
            const loadingState = document.getElementById('permLoadingState');
            const permissionList = document.getElementById('permissionList');
            const emptyState = document.getElementById('permEmptyState');

            loadingState.style.display = 'block';
            permissionList.style.display = 'none';
            emptyState.style.display = 'none';

            fetch(`/api/roles/${roleId}/permissions`)
                .then(r => r.json())
                .then(data => {
                    if (data.permissions && data.permissions.length > 0) {
                        let html = '';

                        // Group by module
                        const grouped = {};
                        data.permissions.forEach(perm => {
                            if (!grouped[perm.module]) grouped[perm.module] = [];
                            grouped[perm.module].push(perm);
                        });

                        // Each module gets a card; uses your app's existing
                        // .card / .border / .bg-light classes only
                        Object.keys(grouped).sort().forEach(module => {
                            const perms = grouped[module];
                            const moduleIcon = MODULE_ICONS[module] || 'ti-box';

                            html += `<div class="card mb-2">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                <span>
                                    <i class="ti ${moduleIcon} me-2"></i>
                                    <strong>${module.toUpperCase()}</strong>
                                </span>
                                <span class="badge bg-secondary">${perms.length}</span>
                            </div>
                            <div class="card-body p-0">`;

                            perms.forEach(perm => {
                                const icon = ACTION_ICONS[perm.name] || 'ti-circle';
                                const label = perm.name.charAt(0).toUpperCase() + perm.name.slice(1);

                                html += `<div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                                <span><i class="ti ${icon} me-2 text-muted"></i>${label}</span>
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="removePermission(${roleId}, ${perm.id})">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>`;
                            });

                            html += `</div></div>`;
                        });

                        permissionList.innerHTML = html;
                        document.getElementById('assignedCount').textContent = data.permissions.length;

                        loadingState.style.display = 'none';
                        permissionList.style.display = 'block';
                        emptyState.style.display = 'none';
                    } else {
                        document.getElementById('assignedCount').textContent = '0';
                        loadingState.style.display = 'none';
                        permissionList.style.display = 'none';
                        emptyState.style.display = 'block';
                    }
                })
                .catch(e => {
                    console.error('Error loading permissions:', e);
                    loadingState.innerHTML =
                        '<span class="text-danger"><i class="ti ti-alert-circle me-1"></i>Error loading permissions</span>';
                });
        }

        function showPermissionModal(roleId, roleName) {
            currentRoleId = roleId;
            document.getElementById('roleName').textContent = roleName;
            loadPermissionsForRole(roleId);
            new bootstrap.Modal(document.getElementById('permissionModal')).show();
        }

        function addPermission() {
            const module = document.getElementById('moduleSelect').value;
            const action = document.getElementById('actionSelect').value;

            if (!module || !action) {
                Swal.fire('Missing Selection', 'Please select both Module and Action', 'warning');
                return;
            }

            fetch('/api/permissions')
                .then(r => r.json())
                .then(allPerms => {
                    const perm = allPerms.find(p => p.module === module && p.name === action);

                    if (!perm) {
                        Swal.fire('Error', 'Permission not found', 'error');
                        return;
                    }

                    fetch(`/rbac/role/${currentRoleId}/permission`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                permission_id: perm.id
                            })
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.status) {
                                document.getElementById('moduleSelect').value = '';
                                document.getElementById('actionSelect').innerHTML =
                                    '<option value="">-- Choose Action --</option>';

                                loadPermissionsForRole(currentRoleId);

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Permission Added!',
                                    text: `${action.charAt(0).toUpperCase() + action.slice(1)} permission on ${module} module added successfully`,
                                    timer: 2500,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Failed to add permission', 'error');
                            }
                        })
                        .catch(e => {
                            Swal.fire('Error', 'Network error: ' + e.message, 'error');
                        });
                });
        }

        function removePermission(roleId, permissionId) {
            Swal.fire({
                title: 'Remove Permission?',
                text: 'This permission will be revoked from the role',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Remove'
            }).then(result => {
                if (!result.isConfirmed) return;

                fetch(`/rbac/role/${roleId}/permission/${permissionId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value
                        }
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status) {
                            loadPermissionsForRole(roleId);

                            Swal.fire({
                                icon: 'success',
                                title: 'Removed!',
                                text: 'Permission removed successfully',
                                timer: 2000
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Failed to remove permission', 'error');
                        }
                    })
                    .catch(e => {
                        Swal.fire('Error', 'Network error: ' + e.message, 'error');
                    });
            });
        }

        document.getElementById('createRoleForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);

            try {
                const response = await fetch('/rbac/roles', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.status) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Role Created!',
                        text: result.message,
                        timer: 2000,
                        didClose: () => location.reload()
                    });
                } else {
                    Swal.fire('Error', result.message || 'Failed to create role', 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    </script>
@endsection
