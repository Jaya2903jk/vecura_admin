@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">{{ $patient->FirstName }} {{ $patient->LastName }}
                    <span class="badge badge-soft-info border border-info page-header-badge ms-2">
                        {{ $patient->RegistrationNo }}
                    </span>
                </h4>
            </div>
            <div class="text-end d-flex gap-2">
                @hasPermission('edit', 'patient')
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#patientModal" onclick="loadPatientModal('{{ route('patient.edit-modal', $patient->PatientID) }}')">
                    <i class="ti ti-edit me-1"></i> Edit
                </button>
                @endhasPermission
                <a href="{{ route('patient.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- Success Message --}}
        @if($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Tabs Card --}}
        <div class="card border-0">
            <ul class="nav nav-tabs nav-fill" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#registration" role="tab">
                        <i class="ti ti-user me-1"></i> Registration
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#schedule" role="tab">
                        <i class="ti ti-calendar me-1"></i> Schedule Pending
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#treatment" role="tab">
                        <i class="ti ti-stethoscope me-1"></i> Treatment
                    </a>
                </li>
            </ul>

            <div class="tab-content p-4">
                <!-- Registration Tab -->
                <div class="tab-pane fade show active" id="registration" role="tabpanel">
                    <h5 class="mb-4">Registration Information</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Registration Date</label>
                                <p class="fw-semibold">
                                    {{ $patient->RegistrationDate ? \Carbon\Carbon::parse($patient->RegistrationDate)->format('d M, Y H:i') : '—' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">First Name</label>
                                <p class="fw-semibold">{{ $patient->FirstName }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Last Name</label>
                                <p class="fw-semibold">{{ $patient->LastName }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Mobile</label>
                                <p class="fw-semibold">{{ $patient->Mobile }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Date of Birth</label>
                                <p class="fw-semibold">
                                    {{ $patient->DateOfBirth ? \Carbon\Carbon::parse($patient->DateOfBirth)->format('d M, Y') : '—' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Age</label>
                                <p class="fw-semibold">{{ $patient->Age ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Gender</label>
                                <p class="fw-semibold">{{ $patient->Sex ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Marital Status</label>
                                <p class="fw-semibold">{{ $patient->Marital_Status ?? '—' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Email</label>
                                <p class="fw-semibold">{{ $patient->EMail ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Residential Telephone</label>
                                <p class="fw-semibold">{{ $patient->Res_Telephone ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Official Telephone</label>
                                <p class="fw-semibold">{{ $patient->Off_Telephone ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">WhatsApp</label>
                                <p class="fw-semibold">{{ $patient->WhatsappMobile ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">City</label>
                                <p class="fw-semibold">{{ $patient->City ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Occupation</label>
                                <p class="fw-semibold">{{ $patient->Occupation ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Assigned Doctor</label>
                                <p class="fw-semibold">{{ $patient->Doctorname ?? '—' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Customer Status</label>
                                <p>
                                    <span class="badge {{ $patient->CustomerStatus === 'Active' ? 'badge-soft-success border border-success' : 'badge-soft-warning border border-warning' }}">
                                        {{ $patient->CustomerStatus }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Schedule Pending Tab -->
                <div class="tab-pane fade" id="schedule" role="tabpanel">
                    <h5 class="mb-4">Schedule Pending</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#consultationFormModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Consultation Form</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#medicalHistoryModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Medical History</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#beforePhotoModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Before Photo Upload</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#dietChatModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Diet Chat</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#bodyMeasurementModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Full Body Measurement</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#nutritionalScreeningModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Nutritional Screening</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#treatmentSlipModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Treatment Slip</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#nutritionalReviewModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Nutritional Review</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#consultationFormModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">Regular Consultation Form</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-primary w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#afterPhotoModal">
                                    <i class="ti ti-check-circle me-2"></i>
                                    <span class="fw-bold">After Photo Upload</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Treatment Tab -->
                <div class="tab-pane fade" id="treatment" role="tabpanel">
                    <h5 class="mb-4">Treatment Information</h5>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Treatment Joined</label>
                                <p>
                                    <span class="badge {{ $patient->TreatmentJoined === 'Yes' ? 'badge-soft-info border border-info' : 'badge-soft-secondary border border-secondary' }}">
                                        {{ $patient->TreatmentJoined ?? 'No' }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Treatment Joined Date</label>
                                <p class="fw-semibold">
                                    {{ $patient->TreatmentJoinedDate ? \Carbon\Carbon::parse($patient->TreatmentJoinedDate)->format('d M, Y') : '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small">Online Consultation</label>
                                <p>
                                    <span class="badge {{ $patient->OnlineConsultation === 'Yes' ? 'badge-soft-success border border-success' : 'badge-soft-secondary border border-secondary' }}">
                                        {{ $patient->OnlineConsultation ?? 'No' }}
                                    </span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted small">Treatment Bill No</label>
                                <p class="fw-semibold">{{ $patient->TreatmentJoinedBillNo ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="mb-3">Treatment Activities</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-info w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#customerVisitModal">
                                    <i class="ti ti-calendar-check me-2"></i>
                                    <span class="fw-bold">Customer Visit Details</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-info w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#treatmentFeedbackModal">
                                    <i class="ti ti-message-circle me-2"></i>
                                    <span class="fw-bold">Treatment Feedback Upload</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-info w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#hmaTestModal">
                                    <i class="ti ti-flask me-2"></i>
                                    <span class="fw-bold">HMA Test Entry</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-info w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#hmaStatusModal">
                                    <i class="ti ti-file-text me-2"></i>
                                    <span class="fw-bold">HMA Status Report</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>

                            <div class="mb-3">
                                <button class="btn btn-sm btn-outline-info w-100 text-start p-3 rounded" data-bs-toggle="modal" data-bs-target="#bodyCompositionModal">
                                    <i class="ti ti-chart-bar me-2"></i>
                                    <span class="fw-bold">Body Composition Analysis</span>
                                    <span class="badge badge-soft-secondary float-end">Pending</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals (Same as before but with updated styling) -->
@include('patient.modals.schedule_modals')
@include('patient.modals.treatment_modals')

<!-- Patient Edit Modal -->
<div class="modal fade" id="patientModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" id="patientModalContent">
            <!-- Content loaded via AJAX -->
        </div>
    </div>
</div>

<script>
function loadPatientModal(url) {
    const modalContent = document.getElementById('patientModalContent');
    const modal = new bootstrap.Modal(document.getElementById('patientModal'));

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then(html => {
            modalContent.innerHTML = html;
            modal.show();
        })
        .catch(error => {
            console.error('Error loading modal:', error);
            modalContent.innerHTML = '<div class="modal-body"><p class="text-danger">Error loading form: ' + error.message + '</p></div>';
            modal.show();
        });
}
</script>

@endsection
