@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <div>
                <h3 class="fw-bold mb-1">{{ $employee->FullName }} ({{ $employee->employee_code }})</h3>
                <p class="text-muted mb-0">
                    <span class="badge bg-{{ $employee->employee_status === 'Active' ? 'success' : 'danger' }}">
                        {{ $employee->employee_status }}
                    </span>
                    {{ $employee->designation?->Designation ?? '-' }} | {{ $employee->office_type ?? 'N/A' }}
                </p>
            </div>
            <div>
                @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                        <i class="ti ti-pencil me-1"></i>Edit
                    </button>
                @endif
            </div>
        </div>

        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#overview" data-bs-toggle="tab">
                    <i class="ti ti-info-circle me-2"></i>Overview
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#education-documents" data-bs-toggle="tab">
                    <i class="ti ti-file-certificate me-2"></i>Education Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#official-documents" data-bs-toggle="tab">
                    <i class="ti ti-file me-2"></i>Official Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#bond" data-bs-toggle="tab">
                    <i class="ti ti-lock me-2"></i>Bond
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#relieving" data-bs-toggle="tab">
                    <i class="ti ti-logout me-2"></i>Relieving
                </a>
            </li>
            {{-- <li class="nav-item">
                <a class="nav-link" href="#roles" data-bs-toggle="tab">
                    <i class="ti ti-shield me-2"></i>Roles & Permissions
                </a>
            </li> --}}
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- OVERVIEW TAB -->
            <div class="tab-pane fade show active" id="overview">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Personal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small">Employee Code</label>
                                    <p class="fw-bold">{{ $employee->employee_code }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Full Name</label>
                                    <p class="fw-bold">{{ $employee->FullName }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Email</label>
                                    <p class="fw-bold">{{ $employee->EmailId ?? '-' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Date of Birth</label>
                                    <p class="fw-bold">{{ $employee->date_of_birth ? \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') : '-' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Phone</label>
                                    <p class="fw-bold">{{ $employee->profile?->phone_number ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="fw-bold mb-0">Employment Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="text-muted small">Department</label>
                                    <p class="fw-bold">{{ $employee->departments->first()?->DepartmentName ?? '-' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Designation</label>
                                    <p class="fw-bold">{{ $employee->designation?->Designation ?? '-' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Manager</label>
                                    <p class="fw-bold">{{ $employee->manager?->FullName ?? 'N/A' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Office Type</label>
                                    <p class="fw-bold">{{ $employee->office_type ?? '-' }}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Status</label>
                                    <p>
                                        @php
                                            $statusBg = ($employee->employee_status === 'Active') ? 'success' : (($employee->employee_status === 'Terminated') ? 'danger' : 'warning');
                                            $status = $employee->employee_status ?? 'Active';
                                        @endphp
                                        <span class="badge bg-{{ $statusBg }}">{{ $status }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- EDUCATION DOCUMENTS TAB -->
            <div class="tab-pane fade" id="education-documents">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Educational Documents (Degree, 10th, 12th)</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addEducationDocumentModal">
                                <i class="ti ti-plus me-1"></i>Upload
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="educationDocumentContent">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OFFICIAL DOCUMENTS TAB -->
            <div class="tab-pane fade" id="official-documents">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Official Documents (Aadhar, PAN, Passport, etc.)</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addOfficialDocumentModal">
                                <i class="ti ti-plus me-1"></i>Upload
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="officialDocumentContent">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOND TAB -->
            <div class="tab-pane fade" id="bond">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Bond Information</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBondModal">
                                <i class="ti ti-plus me-1"></i>Create Bond
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="bondContent">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RELIEVING TAB -->
            <div class="tab-pane fade" id="relieving">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">Relieving / Exit (2-Month Notice Period)</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#initiateRelievingModal">
                                <i class="ti ti-logout me-1"></i>Initiate Relieving
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="relievingContent">
                            <p class="text-muted text-center py-4">No relieving record yet</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== MODALS ========== -->

<!-- Add Educational Document Modal -->
<div class="modal fade" id="addEducationDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Upload Educational Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addEducationDocumentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="Degree">Degree</option>
                            <option value="10th Certificate">10th Certificate</option>
                            <option value="12th Certificate">12th Certificate</option>
                            <option value="Diploma">Diploma</option>
                            <option value="Certificate Course">Certificate Course</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document Number</label>
                        <input type="text" name="document_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document File <span class="text-danger">*</span></label>
                        <input type="file" name="file_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">PDF, JPG, or PNG (Max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Any additional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Official Document Modal -->
<div class="modal fade" id="addOfficialDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Upload Official Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addOfficialDocumentForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="Aadhar">Aadhar</option>
                            <option value="PAN">PAN</option>
                            <option value="Passport">Passport</option>
                            <option value="Driving License">Driving License</option>
                            <option value="Voter ID">Voter ID</option>
                            <option value="Birth Certificate">Birth Certificate</option>
                            <option value="Medical Report">Medical Report</option>
                            <option value="Police Clearance">Police Clearance</option>
                            <option value="Experience Letter">Experience Letter</option>
                            <option value="Relieving Letter">Relieving Letter</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document Number</label>
                        <input type="text" name="document_number" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Document File <span class="text-danger">*</span></label>
                        <input type="file" name="file_path" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">PDF, JPG, or PNG (Max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Any additional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Bond Modal -->
<div class="modal fade" id="addBondModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Create Bond</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBondForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bond Duration (Years) <span class="text-danger">*</span></label>
                        <input type="number" name="bond_duration_years" class="form-control" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bond Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="bond_start_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bond Amount</label>
                        <input type="number" name="bond_amount" class="form-control" step="0.01" placeholder="Amount in currency">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bond Conditions</label>
                        <textarea name="bond_conditions" class="form-control" rows="3" placeholder="Terms and conditions"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bond Document (PDF)</label>
                        <input type="file" name="bond_document_file" class="form-control" accept=".pdf">
                        <small class="text-muted">Optional: PDF only (Max 5MB)</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Create Bond</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Initiate Relieving Modal -->
<div class="modal fade" id="initiateRelievingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Initiate Relieving (2-Month Notice)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="initiateRelievingForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Notice Period:</strong> 2 months from resignation date
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Resignation Date <span class="text-danger">*</span></label>
                        <input type="date" name="resignation_date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Reason for Resignation</label>
                        <textarea name="reason_for_resignation" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Initiate Relieving</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">Edit Employee</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEmployeeForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" value="{{ $employee->FullName }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $employee->EmailId }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="user_status" class="form-select">
                            <option value="Active" {{ $employee->UserStatus === 'Active' ? 'selected' : '' }}>Active</option>
                            <option value="InActive" {{ $employee->UserStatus === 'InActive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
const employeeId = {{ $employee->UserID }};

function loadWorkflowData() {
    loadEducationDocuments();
    loadOfficialDocuments();
    loadBonds();
    loadRelieving();
    loadRoles();
    loadAvailableRoles();
}

function loadEducationDocuments() {
    fetch(`/employee/${employeeId}/educational-document/list`)
        .then(r => r.json())
        .then(data => {
            if (data.data && data.data.length > 0) {
                let html = '<table class="table table-sm"><thead class="table-light"><tr><th>Document Type</th><th>Number</th><th>Date</th><th>File</th></tr></thead><tbody>';
                data.data.forEach(doc => {
                    const fileLink = doc.file_path ? `<a href="/storage/${doc.file_path}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ti ti-download"></i>View</a>` : '-';
                    html += `
                        <tr>
                            <td><strong>${doc.document_type}</strong></td>
                            <td>${doc.document_number || '-'}</td>
                            <td>${doc.issue_date || '-'}</td>
                            <td>${fileLink}</td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
                document.getElementById('educationDocumentContent').innerHTML = html;
            } else {
                document.getElementById('educationDocumentContent').innerHTML = '<p class="text-muted text-center py-4">No educational documents uploaded</p>';
            }
        });
}

function loadOfficialDocuments() {
    fetch(`/employee/${employeeId}/document/list`)
        .then(r => r.json())
        .then(data => {
            if (data.data && data.data.length > 0) {
                let html = '<table class="table table-sm"><thead class="table-light"><tr><th>Document Type</th><th>Number</th><th>Date</th><th>Expiry</th><th>File</th></tr></thead><tbody>';
                data.data.forEach(doc => {
                    const fileLink = doc.file_path ? `<a href="/storage/${doc.file_path}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="ti ti-download"></i>View</a>` : '-';
                    html += `
                        <tr>
                            <td><strong>${doc.document_type}</strong></td>
                            <td>${doc.document_number || '-'}</td>
                            <td>${doc.issue_date || '-'}</td>
                            <td>${doc.expiry_date || '-'}</td>
                            <td>${fileLink}</td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
                document.getElementById('officialDocumentContent').innerHTML = html;
            } else {
                document.getElementById('officialDocumentContent').innerHTML = '<p class="text-muted text-center py-4">No official documents uploaded</p>';
            }
        });
}

function loadBonds() {
    fetch(`/employee/${employeeId}/bond/list`)
        .then(r => r.json())
        .then(data => {
            if (data.data && data.data.length > 0) {
                let html = '<table class="table table-sm table-hover"><thead class="table-light"><tr><th>Duration</th><th>Start Date</th><th>End Date</th><th>Amount</th><th>Status</th><th>Action</th></tr></thead><tbody>';
                data.data.forEach(bond => {
                    const statusBg = bond.bond_status === 'Active' ? 'success' : (bond.bond_status === 'Completed' ? 'info' : 'danger');
                    const actionBtn = bond.bond_status === 'Active' ? `<button class="btn btn-sm btn-success" onclick="completeBond(${bond.id})">Complete</button>` : '-';
                    html += `
                        <tr>
                            <td><strong>${bond.bond_duration_years} year${bond.bond_duration_years > 1 ? 's' : ''}</strong></td>
                            <td>${new Date(bond.bond_start_date).toLocaleDateString()}</td>
                            <td>${bond.bond_end_date ? new Date(bond.bond_end_date).toLocaleDateString() : '-'}</td>
                            <td>${bond.bond_amount ? '₹ ' + parseFloat(bond.bond_amount).toFixed(2) : '-'}</td>
                            <td><span class="badge bg-${statusBg}">${bond.bond_status}</span></td>
                            <td>${actionBtn}</td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
                document.getElementById('bondContent').innerHTML = html;
            } else {
                document.getElementById('bondContent').innerHTML = '<p class="text-muted text-center py-4">No bonds created</p>';
            }
        }).catch(e => {
            console.log('Bond load error:', e);
            document.getElementById('bondContent').innerHTML = '<p class="text-muted text-center py-4">No bonds created</p>';
        });
}

function loadRelieving() {
    fetch(`/employee/${employeeId}/workflow/status`)
        .then(r => r.json())
        .then(data => {
            const relievingData = data.data?.relieving;
            if (relievingData && relievingData.id) {
                const statusBg = relievingData.relieving_status === 'Pending' ? 'warning' : (relievingData.relieving_status === 'Completed' ? 'success' : 'info');
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="fw-bold">Relieving Details</h6>
                                    <div class="mb-2">
                                        <label class="text-muted small">Resignation Date</label>
                                        <p class="fw-bold">${relievingData.resignation_date || '-'}</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small">Notice Completion Date (2 Months)</label>
                                        <p class="fw-bold">${relievingData.notice_completion_date || '-'}</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small">Relieving Date</label>
                                        <p class="fw-bold">${relievingData.relieving_date || '-'}</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small">Status</label>
                                        <p><span class="badge bg-${statusBg}">${relievingData.relieving_status}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="fw-bold">Exit Checklist</h6>
                                    <div class="mb-2">
                                        <label class="text-muted small">All Dues Cleared</label>
                                        <p><span class="badge bg-${relievingData.all_dues_cleared ? 'success' : 'danger'}">${relievingData.all_dues_cleared ? '✓ Yes' : '✗ No'}</span></p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small">Equipment Returned</label>
                                        <p><span class="badge bg-${relievingData.equipment_returned ? 'success' : 'danger'}">${relievingData.equipment_returned ? '✓ Yes' : '✗ No'}</span></p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small">Final Remarks</label>
                                        <p class="fw-bold">${relievingData.final_remarks || '-'}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('relievingContent').innerHTML = html;
            } else {
                document.getElementById('relievingContent').innerHTML = '<p class="text-muted text-center py-4">No relieving record yet</p>';
            }
        }).catch(e => {
            console.log('Relieving load error:', e);
            document.getElementById('relievingContent').innerHTML = '<p class="text-muted text-center py-4">No relieving record yet</p>';
        });
}

function completeBond(bondId) {
    if (!confirm('Complete this bond?')) return;
    fetch(`/employee/${employeeId}/bond/${bondId}/complete`, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire('Success', 'Bond completed', 'success');
            loadBonds();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
}

document.getElementById('addEducationDocumentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`/employee/${employeeId}/educational-document/add`, {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire('Success', 'Document uploaded', 'success');
            this.reset();
            loadEducationDocuments();
            bootstrap.Modal.getInstance(document.getElementById('addEducationDocumentModal')).hide();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
});

document.getElementById('addOfficialDocumentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`/employee/${employeeId}/document/add`, {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire('Success', 'Document uploaded', 'success');
            this.reset();
            loadOfficialDocuments();
            bootstrap.Modal.getInstance(document.getElementById('addOfficialDocumentModal')).hide();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
});

document.getElementById('addBondForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`/employee/${employeeId}/bond/create`, {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire('Success', 'Bond created', 'success');
            this.reset();
            loadBonds();
            bootstrap.Modal.getInstance(document.getElementById('addBondModal')).hide();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
});

document.getElementById('initiateRelievingForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch(`/employee/${employeeId}/relieving/initiate`, {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire('Success', 'Relieving initiated (2-month notice)', 'success');
            this.reset();
            loadRelieving();
            bootstrap.Modal.getInstance(document.getElementById('initiateRelievingModal')).hide();
        } else {
            Swal.fire('Error', data.message, 'error');
        }
    });
});

// ============ ROLE MANAGEMENT ============



loadWorkflowData();
</script>

@endsection
