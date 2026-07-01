@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">⚙️ Admin: Modules & Cache Management</h1>
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Cache Status Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">📦 Cache Status</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Sidebar Modules Cache:</strong></td>
                                <td>
                                    @if($cacheStatus['sidebar_modules'])
                                        <span class="badge bg-success">✅ Active</span>
                                    @else
                                        <span class="badge bg-danger">❌ Empty</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Cache TTL:</strong></td>
                                <td>1 hour (3600 seconds)</td>
                            </tr>
                            <tr>
                                <td><strong>Last Updated:</strong></td>
                                <td>
                                    @if($cacheStatus['cached_at'])
                                        {{ $cacheStatus['cached_at']->format('d M Y H:i:s') }}
                                    @else
                                        Never
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">🔧 Cache Actions</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" class="space-y-2">
                            @csrf

                            <button type="submit" formaction="{{ route('admin.cache.clear-sidebar') }}"
                                class="btn btn-warning w-100 mb-2"
                                onclick="return confirm('Clear sidebar cache?')">
                                <i class="fas fa-trash"></i> Clear Sidebar Cache
                            </button>

                            <button type="submit" formaction="{{ route('admin.cache.rebuild') }}"
                                class="btn btn-info w-100 mb-2"
                                onclick="return confirm('Rebuild module cache?')">
                                <i class="fas fa-sync"></i> Rebuild Module Cache
                            </button>

                            <button type="submit" formaction="{{ route('admin.cache.clear-all') }}"
                                class="btn btn-danger w-100"
                                onclick="return confirm('Clear ALL application cache? This includes all cached data!')">
                                <i class="fas fa-fire"></i> Clear All Cache
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modules Management -->
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">📋 Active Modules ({{ $modules->total() }} total)</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Module Name</th>
                            <th>Description</th>
                            <th>Parent</th>
                            <th>Route Prefix</th>
                            <th>Icon</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($modules as $module)
                        <tr>
                            <td><strong>{{ $module->name }}</strong></td>
                            <td>{{ $module->description ?? '—' }}</td>
                            <td>{{ $module->parent ?? '—' }}</td>
                            <td><code>{{ $module->route_prefix ?? '—' }}</code></td>
                            <td><i class="{{ $module->icon ?? 'ti ti-app' }}"></i></td>
                            <td>
                                @if($module->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.module.toggle', $module) }}" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $module->is_active ? 'btn-danger' : 'btn-success' }}"
                                        onclick="return confirm('Toggle module status?')">
                                        {{ $module->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No modules found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($modules->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        Showing {{ $modules->firstItem() }} to {{ $modules->lastItem() }} of {{ $modules->total() }} modules
                    </div>
                    <div>
                        {{ $modules->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Info Box -->
        <div class="alert alert-info mt-4" role="alert">
            <h5>ℹ️ How Cache Works</h5>
            <ul class="mb-0">
                <li><strong>Sidebar Cache:</strong> Modules list is cached for 1 hour to improve performance</li>
                <li><strong>Auto Refresh:</strong> Cache automatically expires after 1 hour</li>
                <li><strong>Manual Clear:</strong> Use buttons above to clear cache manually when modules change</li>
                <li><strong>Toggle Module:</strong> When you toggle a module status, sidebar cache is automatically cleared</li>
            </ul>
        </div>
    </div>
</div>
@endsection
