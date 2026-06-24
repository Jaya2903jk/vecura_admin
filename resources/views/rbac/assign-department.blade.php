@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <h4 class="mb-4">Assign Department to Employee</h4>
        <form id="assignDeptForm" class="card p-4">
            @csrf
            <div class="mb-3">
                <label class="form-label">Employee <span class="text-danger">*</span></label>
                <select name="employee_id" class="form-control" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->UserID }}">{{ $emp->FullName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" class="form-control" required>
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->Departmentid }}">{{ $dept->DepartmentName }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Assign Department</button>
        </form>
    </div>
</div>

<script>
document.getElementById('assignDeptForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/rbac/assign-department', {
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
            document.getElementById('assignDeptForm').reset();
            setTimeout(() => location.reload(), 1000);
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to assign department: ' + error.message);
    });
});
</script>
@endsection
