{{-- Action buttons component with permission checking --}}
@props(['module', 'recordId', 'editRoute' => null, 'deleteRoute' => null, 'viewRoute' => null])

@php
    $allowedActions = \App\Helpers\RbacHelper::getAllowedActions($module);
    $isAdmin = session('is_admin', false);
@endphp

<div class="btn-group btn-group-sm" role="group">
    @if($isAdmin || in_array('read', $allowedActions))
        @if($viewRoute)
            <a href="{{ route($viewRoute, $recordId) }}" class="btn btn-info" title="View">
                <i class="ti ti-eye"></i>
            </a>
        @endif
    @endif

    @if($isAdmin || in_array('edit', $allowedActions))
        @if($editRoute)
            <a href="{{ route($editRoute, $recordId) }}" class="btn btn-warning" title="Edit">
                <i class="ti ti-edit"></i>
            </a>
        @endif
    @endif

    @if($isAdmin || in_array('delete', $allowedActions))
        @if($deleteRoute)
            <button type="button" class="btn btn-danger" title="Delete"
                onclick="confirmDelete('{{ route($deleteRoute, $recordId) }}')">
                <i class="ti ti-trash"></i>
            </button>
        @endif
    @endif

    @if($isAdmin || in_array('approve', $allowedActions))
        <slot name="approve-button" />
    @endif
</div>

<script>
function confirmDelete(url) {
    if (!confirm('Are you sure you want to delete this record?')) return;

    fetch(url, {
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
    })
    .catch(e => alert('Error: ' + e.message));
}
</script>
