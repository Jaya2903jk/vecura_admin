@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex align-items-center justify-content-between">
                <h1>Manage Modules</h1>
                <a href="{{ route('modules.create') }}" class="btn btn-primary">
                    <i class="ti ti-plus me-2"></i>Add Module
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Module Name</th>
                                <th>Description</th>
                                <th>Route Prefix</th>
                                <th>Icon</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($modules as $module)
                                <tr>
                                    <td>
                                        <strong>{{ $module->name }}</strong>
                                    </td>
                                    <td>{{ $module->description }}</td>
                                    <td><code>{{ $module->route_prefix }}</code></td>
                                    <td><i class="ti {{ $module->icon }}"></i> {{ $module->icon }}</td>
                                    <td>
                                        @if($module->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('modules.edit', $module->id) }}" class="btn btn-warning">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button class="btn btn-info" onclick="toggleModule({{ $module->id }})">
                                                <i class="ti ti-{{ $module->is_active ? 'eye-off' : 'eye' }}"></i>
                                            </button>
                                            <button class="btn btn-danger" onclick="deleteModule({{ $module->id }})">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No modules found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($modules->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $modules->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function toggleModule(moduleId) {
    if (!confirm('Toggle module status?')) return;

    fetch(`/rbac/modules/${moduleId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
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

function deleteModule(moduleId) {
    if (!confirm('Delete this module? This cannot be undone.')) return;

    fetch(`/rbac/modules/${moduleId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}',
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
</script>
@endsection
