@extends('layout.mainlayout')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Module Management</h4>
                <h6 class="text-muted">Create modules (auto-generates 5 permissions: read, create, edit, delete, approve)</h6>
            </div>
        </div>

        {{-- Edit Module Modal --}}
        <div class="modal fade" id="editModuleModal" tabindex="-1" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Edit Module</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editModuleForm">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" id="editModuleId" name="id">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Module Name</label>
                                <input type="text" id="editModuleName" class="form-control" readonly>
                                <small class="text-muted">Cannot be changed</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Parent Category</label>
                                <input type="text" id="editParentName" class="form-control" placeholder="e.g., Masters, RBAC">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea id="editDescription" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Icon</label>
                                <input type="text" id="editIcon" class="form-control" placeholder="e.g., ti ti-users">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" id="editSortOrder" class="form-control">
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="editIsActive" class="form-check-input" style="width: 3.5em; height: 1.8em;">
                                    <label class="form-check-label ms-2" for="editIsActive">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Module</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Left Panel: Create Module Form --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="ti ti-plus me-2"></i>Create New Module
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form id="createModuleForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="mb-4">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    Parent Category (Menu Group)
                                    {{-- <span class="badge bg-info ms-2">Optional</span> --}}
                                </label>
                                <input type="text" id="parentName" name="parent" class="form-control"
                                    placeholder="e.g., Masters, RBAC, Accounts (leave empty for top-level)">
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Existing parents: Masters, RBAC, Tickets, Accounts
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold d-flex align-items-center">
                                    Module Name
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <input type="text" id="moduleName" name="name" class="form-control form-control-lg"
                                    placeholder="e.g., invoice, payroll, complaints" required>
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Lowercase, no spaces (e.g., invoice, purchase-order)
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Description </label>
                                <textarea id="moduleDesc" name="description" class="form-control" rows="3"
                                    placeholder="e.g., Manage employee payroll and salary disbursement"></textarea>
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Help identify what this module does
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Route Prefix </label>
                                <input type="text" id="routePrefix" name="route_prefix" class="form-control"
                                    placeholder="e.g., /payroll, /invoices">
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    URL path where this module lives
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Icon </label>
                                <input type="text" id="moduleIcon" name="icon" class="form-control"
                                    placeholder="e.g., ti ti-invoice, ti ti-wallet">
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Use Tabler Icons (ti-*)
                                </small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Sort Order (Optional)</label>
                                <input type="number" id="sortOrder" name="sort_order" class="form-control"
                                    placeholder="e.g., 1, 2, 3" value="0">
                                <small class="text-muted d-block mt-2">
                                    <i class="ti ti-info-circle"></i>
                                    Lower number = appears first in menu
                                </small>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="isActiveCheck" name="is_active"
                                        class="form-check-input" value="1" checked style="width: 3.5em; height: 1.8em;">
                                    <label class="form-check-label ms-2" for="isActiveCheck">
                                        <strong>Active</strong>
                                        <small class="text-muted d-block">Enable this module</small>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="ti ti-plus me-2"></i>Create Module & Permissions
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mt-3">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="ti ti-alert-circle me-2"></i>
                            <strong>Auto-Created:</strong> 5 permissions (read, create, edit, delete, approve) are automatically generated for each module
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Panel: Existing Modules List --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ti ti-list me-2"></i>All Modules
                                <span class="badge bg-secondary ms-2" id="moduleCount">0</span>
                            </h5>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadModules()">
                                <i class="ti ti-refresh"></i> Refresh
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%">Module Name</th>
                                        <th style="width: 35%">Description</th>
                                        <th style="width: 15%">Permissions</th>
                                        <th style="width: 15%" class="text-center">Status</th>
                                        <th style="width: 15%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="modulesList">
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
                                            <i class="ti ti-loader-2 me-2" style="animation: spin 1s linear infinite;"></i>
                                            Loading modules...
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
                                <h6 class="text-muted mb-2">Total Modules</h6>
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

.form-control-lg {
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
// Load modules on page load
document.addEventListener('DOMContentLoaded', () => {
    loadModules();
});

function loadModules() {
    fetch('/api/modules')
        .then(r => r.json())
        .then(modules => {
            let html = '';
            let totalCount = modules.length;
            let activeCount = 0;

            if (modules.length === 0) {
                html = '<tr><td colspan="5" class="text-center text-muted py-4">No modules created yet</td></tr>';
            } else {
                modules.forEach(mod => {
                    if (mod.is_active) activeCount++;

                    html += `<tr>
                        <td>
                            <strong>${mod.name}</strong>
                        </td>
                        <td>${mod.description || '-'}</td>
                        <td>
                            <span class="badge bg-info">5</span>
                        </td>
                        <td class="text-center">
                            ${mod.is_active ?
                                '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Active</span>' :
                                '<span class="badge bg-secondary"><i class="ti ti-x me-1"></i>Inactive</span>'}
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary me-2"
                                onclick="editModule(${mod.id}, '${mod.name}', '${mod.parent || ''}', '${mod.description || ''}', '${mod.icon || ''}', ${mod.sort_order}, ${mod.is_active})">
                                <i class="ti ti-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                onclick="deleteModule(${mod.id})">
                                <i class="ti ti-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                });
            }

            document.getElementById('modulesList').innerHTML = html;
            document.getElementById('moduleCount').textContent = totalCount;
            document.getElementById('totalCount').textContent = totalCount;
            document.getElementById('activeCount').textContent = activeCount;
        })
        .catch(e => {
            console.error('Error loading modules:', e);
            document.getElementById('modulesList').innerHTML =
                '<tr><td colspan="5" class="text-center text-danger py-4">Error loading modules</td></tr>';
        });
}

function editModule(id, name, parent, description, icon, sortOrder, isActive) {
    document.getElementById('editModuleId').value = id;
    document.getElementById('editModuleName').value = name;
    document.getElementById('editParentName').value = parent;
    document.getElementById('editDescription').value = description;
    document.getElementById('editIcon').value = icon;
    document.getElementById('editSortOrder').value = sortOrder;
    document.getElementById('editIsActive').checked = isActive == 1;

    new bootstrap.Modal(document.getElementById('editModuleModal')).show();
}

// Handle edit form submission
document.getElementById('editModuleForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const id = document.getElementById('editModuleId').value;
    const parent = document.getElementById('editParentName').value;
    const description = document.getElementById('editDescription').value;
    const icon = document.getElementById('editIcon').value;
    const sortOrder = document.getElementById('editSortOrder').value;
    const isActive = document.getElementById('editIsActive').checked ? 1 : 0;

    try {
        const response = await fetch(`/modules/${id}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                parent: parent || null,
                description,
                icon,
                sort_order: parseInt(sortOrder) || 0,
                is_active: isActive
            })
        });

        const data = await response.json();

        if (data.status) {
            Swal.fire({
                icon: 'success',
                title: 'Updated!',
                text: 'Module updated successfully',
                timer: 2000
            });

            bootstrap.Modal.getInstance(document.getElementById('editModuleModal')).hide();
            loadModules();
        } else {
            Swal.fire('Error', data.message || 'Failed to update module', 'error');
        }
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
});

function deleteModule(id) {
    Swal.fire({
        title: 'Delete Module?',
        text: 'This will delete the module and ALL its 5 permissions. Roles using these permissions will lose access.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch(`/modules/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value }
            })
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    Swal.fire('Deleted!', 'Module deleted successfully', 'success');
                    loadModules();
                } else {
                    Swal.fire('Error', data.message || 'Failed to delete', 'error');
                }
            });
        }
    });
}

// Create module form
document.getElementById('createModuleForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const name = document.getElementById('moduleName').value;
    const parent = document.getElementById('parentName').value;
    const description = document.getElementById('moduleDesc').value;
    const route_prefix = document.getElementById('routePrefix').value;
    const icon = document.getElementById('moduleIcon').value;
    const sort_order = document.getElementById('sortOrder').value;
    const is_active = document.getElementById('isActiveCheck').checked ? 1 : 0;

    if (!name) {
        Swal.fire('Error', 'Please enter module name', 'error');
        return;
    }

    try {
        const response = await fetch('/modules', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('[name="_token"]').value,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name.toLowerCase().trim(),
                parent: parent || null,
                description,
                route_prefix,
                icon,
                sort_order: parseInt(sort_order) || 0,
                is_active
            })
        });

        const data = await response.json();

        if (data.status) {
            Swal.fire({
                icon: 'success',
                title: 'Created!',
                text: `Module "${name}" created with 5 permissions`,
                timer: 2000
            });

            e.target.reset();
            document.getElementById('sortOrder').value = '0';
            loadModules();
        } else {
            Swal.fire('Error', data.message || 'Failed to create module', 'error');
        }
    } catch (error) {
        Swal.fire('Error', error.message, 'error');
    }
});
</script>
@endsection
