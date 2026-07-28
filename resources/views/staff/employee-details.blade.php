@extends('layout.mainlayout')

@section('content')
<style>
    .profile-hero-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }

    .profile-avatar-lg {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #e2e8f0;
        color: #1e293b;
        font-weight: 700;
        font-size: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-transform: uppercase;
        border: 2px solid #cbd5e1;
        flex-shrink: 0;
    }

    .info-card-erp {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        height: 100%;
        transition: all 0.2s ease;
    }

    .info-card-erp:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
    }

    .info-card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 12px 18px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .info-label {
        font-size: 11.5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #64748b;
        margin-bottom: 3px;
    }

    .info-value {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0;
    }

    .emp-code-badge {
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        color: #2563eb;
        background-color: #eff6ff;
        padding: 3px 9px;
        border-radius: 4px;
        border: 1px solid #bfdbfe;
    }

    .role-pill-badge {
        background-color: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        font-size: 11.5px;
        font-weight: 600;
        padding: 3px 9px;
        border-radius: 12px;
    }
</style>

<div class="page-wrapper">
    <div class="content px-4 py-3">

        <!-- BREADCRUMB & BACK BUTTON -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <a href="{{ route('staff.index') }}" class="btn btn-light border btn-sm fw-semibold text-secondary">
                <i class="ti ti-arrow-left me-1"></i>Back to Staff Directory
            </a>
            <div class="d-flex align-items-center gap-2">
                @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                    <button type="button" class="btn btn-primary btn-sm fw-bold px-3 shadow-xs" data-bs-toggle="modal" data-bs-target="#editEmployeeModal">
                        <i class="ti ti-pencil me-1"></i>Edit Profile
                    </button>
                @endif
            </div>
        </div>

        @php
            $nameParts = explode(' ', trim($employee->FullName));
            $initials = strtoupper(substr($nameParts[0] ?? 'E', 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
            $userCode = $employee->UserCode ?? $employee->employee_code ?? '—';
            $desigName = $employee->designation?->Designation ?? $employee->Designation ?? '—';
            $deptName = $employee->department?->DepartmentName ?? $employee->departments?->first()?->DepartmentName ?? '—';
            $branchName = $employee->branch?->branch_name ?? $employee->branch?->Branchname ?? $employee->branch?->BranchName ?? '—';
        @endphp

        <!-- HERO PROFILE CARD -->
        <div class="profile-hero-card p-4 mb-4">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="profile-avatar-lg">{{ $initials }}</div>
                    <div>
                        <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                            <h3 class="fw-bold text-dark mb-0 fs-20">{{ $employee->FullName }}</h3>
                            <span class="emp-code-badge">{{ $userCode }}</span>
                            <span class="badge {{ $employee->UserStatus == 'Active' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-3 py-1 rounded-pill fs-12 fw-bold">
                                {{ $employee->UserStatus == 'Active' ? 'Active' : 'InActive' }}
                            </span>
                        </div>
                        <p class="text-secondary fs-13 mb-1">
                            <span class="fw-semibold text-dark">{{ $desigName }}</span>
                            <span class="mx-1 text-muted">•</span>
                            <span>{{ $deptName }}</span>
                            <span class="mx-1 text-muted">•</span>
                            <i class="ti ti-map-pin text-primary me-0.5"></i>{{ $branchName }}
                            @if($employee->office_type)
                                <span class="badge bg-light text-dark border ms-1 fs-11">{{ $employee->office_type }}</span>
                            @endif
                        </p>
                        <div class="d-flex align-items-center flex-wrap gap-1 mt-2">
                            <span class="text-muted fs-12 me-1">Assigned Roles:</span>
                            @forelse($employee->roles as $r)
                                <span class="role-pill-badge">{{ $r->name }}</span>
                            @empty
                                <span class="text-muted fs-12">—</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NAVIGATION TABS -->
        <ul class="nav nav-tabs mb-4" role="tablist">
            <li class="nav-item">
                <a class="nav-link active fw-semibold fs-13" href="#overview" data-bs-toggle="tab">
                    <i class="ti ti-info-circle me-1.5"></i>Overview Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold fs-13" href="#education-documents" data-bs-toggle="tab">
                    <i class="ti ti-file-certificate me-1.5"></i>Education Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold fs-13" href="#official-documents" data-bs-toggle="tab">
                    <i class="ti ti-file me-1.5"></i>Official Documents
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold fs-13" href="#bond" data-bs-toggle="tab">
                    <i class="ti ti-lock me-1.5"></i>Bond Agreements
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link fw-semibold fs-13" href="#relieving" data-bs-toggle="tab">
                    <i class="ti ti-logout me-1.5"></i>Relieving / Exit
                </a>
            </li>
        </ul>

        <!-- TAB CONTENT -->
        <div class="tab-content">
            <!-- OVERVIEW TAB -->
            <div class="tab-pane fade show active" id="overview">
                <div class="row g-3">

                    <!-- CARD 1: PERSONAL INFORMATION -->
                    <div class="col-lg-6">
                        <div class="info-card-erp">
                            <div class="info-card-header d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold text-dark mb-0 fs-14">
                                    <i class="ti ti-user me-1.5 text-primary"></i>Personal &amp; Contact Information
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="info-label">Employee Code</div>
                                        <div class="info-value text-primary font-monospace">{{ $userCode }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Full Name</div>
                                        <div class="info-value">{{ $employee->FullName }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Primary Email Address</div>
                                        <div class="info-value">
                                            @if($employee->EmailId)
                                                <i class="ti ti-mail text-muted me-1"></i>{{ $employee->EmailId }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Phone Number</div>
                                        <div class="info-value">
                                            @if($employee->profile?->phone_number)
                                                <i class="ti ti-phone text-muted me-1"></i>{{ $employee->profile->phone_number }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Alternate Phone</div>
                                        <div class="info-value">{{ $employee->profile?->alternate_phone ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Date of Birth</div>
                                        <div class="info-value">
                                            @if($employee->date_of_birth)
                                                {{ \Carbon\Carbon::parse($employee->date_of_birth)->format('d M Y') }}
                                            @elseif($employee->profile?->date_of_birth)
                                                {{ \Carbon\Carbon::parse($employee->profile->date_of_birth)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Gender</div>
                                        <div class="info-value">{{ $employee->profile?->gender ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="info-label">Full Residential Address</div>
                                        <div class="info-value">
                                            @if($employee->profile?->address)
                                                <i class="ti ti-map-pin text-muted me-1"></i>{{ $employee->profile->address }}
                                                @if($employee->profile->city), {{ $employee->profile->city }} @endif
                                                @if($employee->profile->state), {{ $employee->profile->state }} @endif
                                                @if($employee->profile->postal_code) - {{ $employee->profile->postal_code }} @endif
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 2: EMPLOYMENT INFORMATION -->
                    <div class="col-lg-6">
                        <div class="info-card-erp">
                            <div class="info-card-header d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold text-dark mb-0 fs-14">
                                    <i class="ti ti-briefcase me-1.5 text-primary"></i>Employment &amp; Work Assignment
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="info-label">Department</div>
                                        <div class="info-value">
                                            <span class="badge bg-light text-dark border px-2 py-0.5 fs-12 fw-semibold">
                                                {{ $deptName }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Designation</div>
                                        <div class="info-value">{{ $desigName }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Branch Location</div>
                                        <div class="info-value">
                                            <i class="ti ti-building me-1 text-primary"></i>{{ $branchName }}
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Office Type</div>
                                        <div class="info-value">{{ $employee->office_type ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Reporting Manager</div>
                                        <div class="info-value">
                                            @if($employee->manager?->FullName)
                                                <span class="text-primary fw-semibold">{{ $employee->manager->FullName }}</span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Employee Type</div>
                                        <div class="info-value">{{ $employee->profile?->employee_type ?? 'Permanent' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Date of Joining</div>
                                        <div class="info-value">
                                            @if($employee->profile?->date_of_joining)
                                                {{ \Carbon\Carbon::parse($employee->profile->date_of_joining)->format('d M Y') }}
                                            @elseif($employee->CreatedDate)
                                                {{ \Carbon\Carbon::parse($employee->CreatedDate)->format('d M Y') }}
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Employment Status</div>
                                        <div class="info-value">
                                            <span class="badge {{ $employee->UserStatus == 'Active' ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle' }} px-2.5 py-0.5 rounded-pill fs-12 fw-bold">
                                                {{ $employee->employee_status ?? $employee->UserStatus }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 3: FINANCIAL & GOVERNMENT IDENTIFIERS -->
                    <div class="col-lg-6">
                        <div class="info-card-erp">
                            <div class="info-card-header">
                                <h6 class="fw-bold text-dark mb-0 fs-14">
                                    <i class="ti ti-credit-card me-1.5 text-primary"></i>Financial &amp; Government Identifiers
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="info-label">Aadhar Number</div>
                                        <div class="info-value">{{ $employee->profile?->aadhar_number ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">PAN Number</div>
                                        <div class="info-value">{{ $employee->profile?->pan_number ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Bank Account Number</div>
                                        <div class="info-value">{{ $employee->profile?->bank_account_number ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">IFSC Code</div>
                                        <div class="info-value">{{ $employee->profile?->ifsc_code ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CARD 4: HEALTH & EMERGENCY CONTACT -->
                    <div class="col-lg-6">
                        <div class="info-card-erp">
                            <div class="info-card-header">
                                <h6 class="fw-bold text-dark mb-0 fs-14">
                                    <i class="ti ti-heart-handshake me-1.5 text-primary"></i>Emergency Contact &amp; Health Record
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="info-label">Emergency Contact Name</div>
                                        <div class="info-value">{{ $employee->profile?->emergency_contact_name ?? $employee->medical?->emergency_contact_name ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Emergency Contact Phone</div>
                                        <div class="info-value">{{ $employee->profile?->emergency_contact_phone ?? $employee->medical?->emergency_contact_phone ?? '—' }}</div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Blood Group</div>
                                        <div class="info-value">
                                            @if($employee->profile?->blood_group || $employee->medical?->blood_group)
                                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle fw-bold">
                                                    {{ $employee->profile?->blood_group ?? $employee->medical?->blood_group }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="info-label">Medical Conditions</div>
                                        <div class="info-value">{{ $employee->medical?->medical_conditions ?? 'None Reported' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- EDUCATION DOCUMENTS TAB -->
            <div class="tab-pane fade" id="education-documents">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0 fs-14">Educational Documents (Degree, 10th, 12th)</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addEducationDocumentModal">
                                <i class="ti ti-plus me-1"></i>Upload Document
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
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0 fs-14">Official Documents (Aadhar, PAN, Passport, etc.)</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addOfficialDocumentModal">
                                <i class="ti ti-plus me-1"></i>Upload Document
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
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0 fs-14">Bond Information</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#addBondModal">
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
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold text-dark mb-0 fs-14">Relieving / Exit (2-Month Notice Period)</h6>
                        @if(session('is_admin') || \App\Helpers\RbacHelper::canPerformAction('edit', 'staff'))
                            <button type="button" class="btn btn-sm btn-danger fw-bold" data-bs-toggle="modal" data-bs-target="#initiateRelievingModal">
                                <i class="ti ti-logout me-1"></i>Initiate Relieving
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div id="relievingContent">
                            <p class="text-muted text-center py-4 fs-13">No relieving record yet</p>
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
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold fs-16"><i class="ti ti-file-certificate me-2"></i>Upload Educational Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addEducationDocumentForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select form-select-sm fs-13" required>
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
                        <label class="form-label fw-semibold fs-13">Document Number</label>
                        <input type="text" name="document_number" class="form-control form-control-sm fs-13">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control form-control-sm fs-13">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Document File <span class="text-danger">*</span></label>
                        <input type="file" name="file_path" class="form-control form-control-sm fs-13" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted fs-11">PDF, JPG, or PNG (Max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Description</label>
                        <textarea name="description" class="form-control form-control-sm fs-13" rows="2" placeholder="Any additional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fs-13 fw-bold"><i class="ti ti-check me-1"></i>Upload Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Official Document Modal -->
<div class="modal fade" id="addOfficialDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold fs-16"><i class="ti ti-file me-2"></i>Upload Official Document</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addOfficialDocumentForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Document Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-select form-select-sm fs-13" required>
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
                        <label class="form-label fw-semibold fs-13">Document Number</label>
                        <input type="text" name="document_number" class="form-control form-control-sm fs-13">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control form-control-sm fs-13">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control form-control-sm fs-13">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Document File <span class="text-danger">*</span></label>
                        <input type="file" name="file_path" class="form-control form-control-sm fs-13" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted fs-11">PDF, JPG, or PNG (Max 5MB)</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Description</label>
                        <textarea name="description" class="form-control form-control-sm fs-13" rows="2" placeholder="Any additional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fs-13 fw-bold"><i class="ti ti-check me-1"></i>Upload Document</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Bond Modal -->
<div class="modal fade" id="addBondModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-info text-white py-3">
                <h5 class="modal-title fw-bold fs-16"><i class="ti ti-lock me-2"></i>Create Bond</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addBondForm">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Bond Duration (Years) <span class="text-danger">*</span></label>
                        <input type="number" name="bond_duration_years" class="form-control form-control-sm fs-13" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Bond Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="bond_start_date" class="form-control form-control-sm fs-13" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Bond Amount</label>
                        <input type="number" name="bond_amount" class="form-control form-control-sm fs-13" step="0.01" placeholder="Amount in currency">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Bond Conditions</label>
                        <textarea name="bond_conditions" class="form-control form-control-sm fs-13" rows="3" placeholder="Terms and conditions"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Bond Document (PDF)</label>
                        <input type="file" name="bond_document_file" class="form-control form-control-sm fs-13" accept=".pdf">
                        <small class="text-muted fs-11">Optional: PDF only (Max 5MB)</small>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info fs-13 fw-bold"><i class="ti ti-check me-1"></i>Create Bond</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Initiate Relieving Modal -->
<div class="modal fade" id="initiateRelievingModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white py-3">
                <h5 class="modal-title fw-bold fs-16"><i class="ti ti-logout me-2"></i>Initiate Relieving (2-Month Notice)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="initiateRelievingForm">
                <div class="modal-body p-4">
                    <div class="alert alert-info py-2 fs-13">
                        <strong>Notice Period:</strong> 2 months from resignation date
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Resignation Date <span class="text-danger">*</span></label>
                        <input type="date" name="resignation_date" class="form-control form-control-sm fs-13" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold fs-13">Reason for Leaving <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control form-control-sm fs-13" rows="3" required placeholder="Detailed reason for leaving"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger fs-13 fw-bold"><i class="ti ti-check me-1"></i>Initiate Relieving</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold fs-16"><i class="ti ti-pencil me-2"></i>Edit Employee Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editEmployeeForm">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control form-control-sm fs-13" value="{{ $employee->FullName }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Email Address</label>
                            <input type="email" name="email" class="form-control form-control-sm fs-13" value="{{ $employee->EmailId }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Designation <span class="text-danger">*</span></label>
                            <select name="designation" class="form-select form-select-sm fs-13" required>
                                <option value="">Select Designation</option>
                                @foreach($designations as $des)
                                    <option value="{{ $des->DesignationCode }}" {{ ($employee->designation_code == $des->DesignationCode || $employee->Designation == $des->DesignationCode) ? 'selected' : '' }}>
                                        {{ $des->Designation }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-select form-select-sm fs-13" required>
                                <option value="">Select Branch</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->branch_id ?? $b->BranchID }}" {{ $employee->branch_id == ($b->branch_id ?? $b->BranchID) ? 'selected' : '' }}>
                                        {{ $b->branch_name ?? $b->Branchname }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">User Status <span class="text-danger">*</span></label>
                            <select name="user_status" class="form-select form-select-sm fs-13" required>
                                <option value="Active" {{ $employee->UserStatus == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="InActive" {{ $employee->UserStatus == 'InActive' ? 'selected' : '' }}>InActive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary fs-13" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fs-13 fw-bold"><i class="ti ti-check me-1"></i>Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JAVASCRIPT LOGIC -->
<script>
const employeeId = {{ $employee->UserID }};

function loadWorkflowData() {
    loadEducationalDocuments();
    loadOfficialDocuments();
    loadBonds();
    loadRelieving();
}

function loadEducationalDocuments() {
    fetch(`/employee/${employeeId}/educational-document/list`)
        .then(r => r.json())
        .then(res => {
            if (res.status) {
                renderDocumentsTable(res.data, 'educationDocumentContent', 'education');
            }
        })
        .catch(err => console.error(err));
}

function loadOfficialDocuments() {
    fetch(`/employee/${employeeId}/document/list`)
        .then(r => r.json())
        .then(res => {
            if (res.status) {
                renderDocumentsTable(res.data, 'officialDocumentContent', 'official');
            }
        })
        .catch(err => console.error(err));
}

function renderDocumentsTable(documents, containerId, type) {
    const container = document.getElementById(containerId);
    if (!documents || documents.length === 0) {
        container.innerHTML = `<p class="text-muted text-center py-4 fs-13">No ${type} documents uploaded yet</p>`;
        return;
    }

    let html = `
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 fs-13">
                <thead>
                    <tr class="table-light">
                        <th>Type</th>
                        <th>Number</th>
                        <th>Issue Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    `;

    documents.forEach(doc => {
        html += `
            <tr>
                <td><span class="badge bg-light text-dark border fw-semibold">${doc.document_type}</span></td>
                <td class="fw-semibold">${doc.document_number || '—'}</td>
                <td>${doc.issue_date || '—'}</td>
                <td>
                    <a href="/storage/${doc.file_path}" target="_blank" class="btn btn-sm btn-light border me-1">
                        <i class="ti ti-eye me-1"></i>View File
                    </a>
                </td>
            </tr>
        `;
    });

    html += `</tbody></table></div>`;
    container.innerHTML = html;
}

function loadBonds() {
    fetch(`/employee/${employeeId}/bond/list`)
        .then(r => r.json())
        .then(res => {
            if (res.status) {
                const container = document.getElementById('bondContent');
                const bonds = res.data;
                if (!bonds || bonds.length === 0) {
                    container.innerHTML = `<p class="text-muted text-center py-4 fs-13">No bond records found</p>`;
                    return;
                }

                let html = `
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 fs-13">
                            <thead>
                                <tr class="table-light">
                                    <th>Duration</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                `;

                bonds.forEach(bond => {
                    html += `
                        <tr>
                            <td class="fw-bold">${bond.bond_duration_years} Years</td>
                            <td>${bond.bond_start_date}</td>
                            <td>${bond.bond_end_date}</td>
                            <td class="fw-semibold">${bond.bond_amount ? '₹' + bond.bond_amount : '—'}</td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle">${bond.bond_status || 'Active'}</span></td>
                        </tr>
                    `;
                });

                html += `</tbody></table></div>`;
                container.innerHTML = html;
            }
        })
        .catch(err => console.error(err));
}

function loadRelieving() {
    fetch(`/employee/${employeeId}/workflow/status`)
        .then(r => r.json())
        .then(res => {
            const container = document.getElementById('relievingContent');
            if (res.status && res.relieving) {
                const r = res.relieving;
                container.innerHTML = `
                    <div class="alert alert-warning border-warning-subtle">
                        <h6 class="fw-bold"><i class="ti ti-alert-triangle me-1"></i>Relieving Status: ${r.status}</h6>
                        <p class="mb-1"><strong>Resignation Date:</strong> ${r.resignation_date}</p>
                        <p class="mb-1"><strong>Expected Relieving Date:</strong> ${r.expected_relieving_date}</p>
                        <p class="mb-0"><strong>Reason:</strong> ${r.reason || 'N/A'}</p>
                    </div>
                `;
            } else {
                container.innerHTML = `<p class="text-muted text-center py-4 fs-13">No relieving process initiated for this employee.</p>`;
            }
        })
        .catch(err => console.error(err));
}

// Submissions
let isEduDocSubmitting = false;
document.getElementById('addEducationDocumentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (isEduDocSubmitting) return false;
    isEduDocSubmitting = true;

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

    const formData = new FormData(this);
    fetch(`/employee/${employeeId}/educational-document/add`, {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire('Success', 'Educational document uploaded', 'success');
            this.reset();
            loadEducationalDocuments();
            bootstrap.Modal.getInstance(document.getElementById('addEducationDocumentModal')).hide();
        } else {
            Swal.fire('Error', data.message, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            isEduDocSubmitting = false;
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Upload failed: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        isEduDocSubmitting = false;
    });
});

let isOfficialDocSubmitting = false;
document.getElementById('addOfficialDocumentForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (isOfficialDocSubmitting) return false;
    isOfficialDocSubmitting = true;

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...';

    const formData = new FormData(this);
    fetch(`/employee/${employeeId}/document/add`, {
        method: 'POST',
        body: formData,
        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
    })
    .then(r => r.json())
    .then(data => {
        if (data.status) {
            Swal.fire('Success', 'Official document uploaded', 'success');
            this.reset();
            loadOfficialDocuments();
            bootstrap.Modal.getInstance(document.getElementById('addOfficialDocumentModal')).hide();
        } else {
            Swal.fire('Error', data.message, 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            isOfficialDocSubmitting = false;
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Upload failed: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        isOfficialDocSubmitting = false;
    });
});

let isBondSubmitting = false;
document.getElementById('addBondForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (isBondSubmitting) return false;
    isBondSubmitting = true;

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Creating...';

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
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            isBondSubmitting = false;
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Failed: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        isBondSubmitting = false;
    });
});

let isRelievingSubmitting = false;
document.getElementById('initiateRelievingForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (isRelievingSubmitting) return false;
    isRelievingSubmitting = true;

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Initiating...';

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
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            isRelievingSubmitting = false;
        }
    })
    .catch(error => {
        Swal.fire('Error', 'Failed: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        isRelievingSubmitting = false;
    });
});

let isEditSubmitting = false;
document.getElementById('editEmployeeForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    if (isEditSubmitting) return false;
    isEditSubmitting = true;

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    const formData = new FormData(this);
    const jsonData = Object.fromEntries(formData);

    fetch(`/staff/${employeeId}`, {
        method: 'PUT',
        body: JSON.stringify(jsonData),
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.status || data.success) {
            Swal.fire('Success', 'Employee updated successfully', 'success');
            setTimeout(() => window.location.reload(), 1200);
        } else {
            Swal.fire('Error', data.message || 'Failed to update employee', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
            isEditSubmitting = false;
        }
    })
    .catch(error => {
        Swal.fire('Error', 'An error occurred while updating: ' + error.message, 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
        isEditSubmitting = false;
    });
});

loadWorkflowData();
</script>
@endsection
