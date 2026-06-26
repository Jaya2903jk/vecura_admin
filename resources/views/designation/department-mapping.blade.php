@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <div>
                <h3 class="fw-bold mb-1">Designation Department Mapping</h3>
                <p class="text-muted mb-0">Map designations to departments for better organization</p>
            </div>
        </div>

        {{-- Designations List --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 12%">Code</th>
                                <th style="width: 25%">Designation</th>
                                <th style="width: 45%">Mapped Departments</th>
                                <th style="width: 18%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($designations as $designation)
                                <tr>
                                    <td>
                                        <span class="badge badge-soft-info">{{ $designation->DesignationCode }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ $designation->Designation }}</strong>
                                    </td>
                                    <td>
                                        @if($designation->departmentMappings->count() > 0)
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($designation->departmentMappings as $mapping)
                                                    <span class="badge bg-primary">
                                                        {{ $mapping->department->DepartmentName }}
                                                        <a href="javascript:void(0);" onclick="removeMapping({{ $mapping->id }})" class="ms-1 text-white" style="cursor: pointer; font-weight: bold;">×</a>
                                                    </span>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">No departments mapped</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="openMapModal({{ $designation->id }}, '{{ $designation->Designation }}')">
                                            <i class="ti ti-link me-1"></i>Map Department
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No designations found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Pagination --}}
        <x-pagination :paginator="$designations" />
    </div>
</div>

{{-- ======== MAP DEPARTMENT MODAL ======== --}}
<div class="modal fade" id="mapDepartmentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ti ti-link me-2"></i>Map Departments to <span id="designationName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="mapDepartmentForm">
                @csrf
                <input type="hidden" id="designationId" name="designation_id">
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Select departments:</strong> Choose one or more departments to map this designation to
                    </div>

                    <label class="form-label fw-semibold mb-3">Select Departments <span class="text-danger">*</span></label>
                    <div id="departmentCheckboxes" class="border rounded p-4 bg-light" style="max-height: 400px; overflow-y: auto;">
                        <div class="row g-3">
                            @foreach($departments as $dept)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input dept-checkbox" type="checkbox" name="department_ids[]" value="{{ $dept->Departmentid }}" id="dept_{{ $dept->Departmentid }}">
                                        <label class="form-check-label" for="dept_{{ $dept->Departmentid }}">
                                            <i class="ti ti-building me-1"></i>{{ $dept->DepartmentName }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Mapping</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    let currentDesignationId = null;

    function openMapModal(designationId, designationName) {
        currentDesignationId = designationId;
        document.getElementById('designationId').value = designationId;
        document.getElementById('designationName').textContent = designationName;

        // Clear all checkboxes
        document.querySelectorAll('.dept-checkbox').forEach(cb => cb.checked = false);

        // Load existing mappings
        fetch(`/designation-department/${designationId}`)
            .then(r => r.json())
            .then(data => {
                if (data.status) {
                    data.mapped_departments.forEach(deptId => {
                        const checkbox = document.getElementById(`dept_${deptId}`);
                        if (checkbox) checkbox.checked = true;
                    });
                }
                const modal = new bootstrap.Modal(document.getElementById('mapDepartmentModal'));
                modal.show();
            })
            .catch(e => {
                console.error('Error loading mappings:', e);
                const modal = new bootstrap.Modal(document.getElementById('mapDepartmentModal'));
                modal.show();
            });
    }

    document.getElementById('mapDepartmentForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const checkedDepts = document.querySelectorAll('.dept-checkbox:checked');
        if (checkedDepts.length === 0) {
            Swal.fire('Required', 'Please select at least one department', 'warning');
            return;
        }

        const formData = new FormData(this);

        fetch('/designation-department', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                Swal.fire('Success', data.message, 'success').then(() => {
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message, 'error');
            }
        })
        .catch(e => Swal.fire('Error', 'Something went wrong', 'error'));
    });

    function removeMapping(mappingId) {
        Swal.fire({
            title: 'Remove Department?',
            text: 'Are you sure you want to remove this department mapping?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Remove'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/designation-department/${mappingId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        Swal.fire('Removed', data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                });
            }
        });
    }
</script>
@endsection
