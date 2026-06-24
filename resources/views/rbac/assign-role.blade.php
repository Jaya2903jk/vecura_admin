@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <h4 class="mb-4">Assign Role to Employee</h4>
        <form id="assignRoleForm" class="card p-4">
            @csrf
            <div class="mb-3">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->UserID }}">{{ $emp->FullName }} ({{ $emp->UserCode }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                <select name="role_id" class="form-control" required>
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }} (Level {{ $role->level }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Role</button>
        </form>
    </div>
</div>

<script>
document.getElementById('assignRoleForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/rbac/assign-role', {
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
            document.getElementById('assignRoleForm').reset();
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to assign role: ' + error.message);
    });
});
</script>
@endsection
