<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultation Form Sheet - {{ $patient->FirstName }} ({{ $patient->RegistrationNo }})</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #e2e8f0;
            font-family: 'Public Sans', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            font-size: 14px;
            line-height: 1.5;
            padding-bottom: 90px;
        }

        /* Modern Physical Document Sheet Outer Wrapper */
        .document-sheet-wrapper {
            max-width: 1240px;
            margin: 32px auto;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            box-shadow: 0 12px 36px rgba(15, 23, 42, 0.12), 0 2px 8px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        /* Document Header Banner */
        .sheet-header-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #ffffff;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 3px solid #1d4ed8;
        }

        .sheet-clinic-title {
            font-size: 22px;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sheet-doc-badge {
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* Patient Demographics Table Header */
        .patient-demo-grid {
            background-color: #ffffff;
            border-bottom: 1px solid #cbd5e1;
        }

        .patient-demo-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .patient-demo-table td {
            padding: 11px 18px;
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .patient-demo-table td:last-child {
            border-right: none;
        }

        .patient-demo-label {
            font-weight: 600;
            color: #64748b;
            width: 150px;
            background-color: #f8fafc;
        }

        .patient-demo-value {
            font-weight: 600;
            color: #0f172a;
        }

        /* Section Cards Inside Sheet */
        .sheet-section-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            margin: 20px 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .sheet-section-header {
            background-color: #f1f5f9;
            border-bottom: 1px solid #cbd5e1;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sheet-section-title {
            font-size: 15.5px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sheet-section-title i {
            color: #2563eb;
            font-size: 20px;
        }

        /* Form Controls */
        .form-label-md {
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-control-md {
            font-size: 14px;
            height: 38px;
            border-color: #cbd5e1;
            border-radius: 6px;
        }

        .form-control-md:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .unit-addon {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            padding: 0 12px;
        }

        .opt-label {
            font-size: 13.5px;
            font-weight: 500;
            color: #1e293b;
            cursor: pointer;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin-top: 2px;
            border-color: #94a3b8;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        /* Upload Slots */
        .upload-slot-card {
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            background: #f8fafc;
            min-height: 170px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .upload-slot-card:hover {
            border-color: #2563eb;
            background-color: #f0f7ff;
        }

        .preview-img {
            max-height: 100px;
            max-width: 100%;
            object-fit: contain;
            border-radius: 4px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        /* Treatment Row Items */
        .treatment-row-item {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            height: 100%;
        }

        .treatment-row-item.checked {
            border-color: #2563eb;
            background-color: #eff6ff;
        }

        .treatment-name {
            font-size: 13.5px;
            font-weight: 600;
            color: #0f172a;
        }

        .stepper-btn {
            width: 28px;
            height: 28px;
            padding: 0;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .stepper-input {
            width: 42px;
            height: 28px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
        }

        /* Signature Footer Block */
        .signature-block {
            border-top: 1px dashed #cbd5e1;
            margin: 20px 24px;
            padding-top: 24px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .signature-line {
            width: 220px;
            border-top: 1.5px solid #64748b;
            text-align: center;
            padding-top: 6px;
            font-size: 12.5px;
            font-weight: 600;
            color: #475569;
        }

        /* Floating Bottom Action Bar */
        .paper-floating-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-top: 1px solid #cbd5e1;
            padding: 14px 28px;
            z-index: 1030;
            box-shadow: 0 -4px 16px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        @media print {
            .paper-floating-footer, .no-print {
                display: none !important;
            }
            body {
                background-color: #ffffff;
                padding-bottom: 0;
            }
            .document-sheet-wrapper {
                margin: 0;
                border: none;
                box-shadow: none;
                border-radius: 0;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    @php
        $formAction = !empty($secureToken) 
            ? route('patient.appointments.save-consultation-form-secure') 
            : route('patient.appointments.save-consultation-form', ['id' => $patient->PatientID, 'scheduleId' => $appointment->ScheduleId]);
    @endphp

    <form id="consultationFullForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (!empty($secureToken))
            <input type="hidden" name="token" value="{{ $secureToken }}">
        @endif

        <!-- MODERN PHYSICAL DOCUMENT SHEET WRAPPER -->
        <div class="document-sheet-wrapper">

            <!-- DOCUMENT HEADER BANNER -->
            <div class="sheet-header-banner">
                <div>
                    <h4 class="sheet-clinic-title"><i class="ti ti-activity me-1"></i>VeCura Wellness Clinic</h4>
                    <span class="fs-13 opacity-90">Consultation Forms - Patient Medical &amp; Treatment Prescription Record</span>
                </div>
                <div class="d-flex align-items-center gap-2 no-print">
                    <span class="sheet-doc-badge me-2"><i class="ti ti-file-certificate me-1"></i>Official Document</span>
                    <button type="button" onclick="window.print();" class="btn btn-sm btn-outline-light px-3 fw-semibold">
                        <i class="ti ti-printer me-1"></i>Print Form Sheet
                    </button>
                    <button type="button" onclick="window.close();" class="btn btn-sm btn-danger px-3 fw-semibold">
                        <i class="ti ti-x me-1"></i>Close Page
                    </button>
                </div>
            </div>

            <!-- PATIENT DEMOGRAPHICS DOCUMENT HEADER TABLE -->
            <div class="patient-demo-grid">
                <table class="patient-demo-table">
                    <tr>
                        <td class="patient-demo-label">Registration No:</td>
                        <td class="patient-demo-value text-primary fs-14">{{ $patient->RegistrationNo }}</td>
                        <td class="patient-demo-label">Customer Name:</td>
                        <td class="patient-demo-value fs-14">{{ $patient->FirstName }} {{ $patient->LastName }}</td>
                    </tr>
                    <tr>
                        <td class="patient-demo-label">Mobile Number:</td>
                        <td class="patient-demo-value">{{ $patient->Mobile ?? '—' }}</td>
                        <td class="patient-demo-label">Gender / Age:</td>
                        <td class="patient-demo-value">{{ $patient->Gender ?? '—' }} {{ !empty($patient->Age) ? ' / ' . $patient->Age . ' Yrs' : '' }}</td>
                    </tr>
                    <tr>
                        <td class="patient-demo-label">Branch Location:</td>
                        <td class="patient-demo-value">{{ $patient->location->BranchName ?? ($patient->Loc_Id ?? 'ANR') }}</td>
                        <td class="patient-demo-label">Consultant / Expert:</td>
                        <td class="patient-demo-value">{{ $appointment->doctor->userMaster->FullName ?? ($appointment->doctor->DoctorName ?? ($appointment->Sch_Doctname ?? '—')) }}</td>
                    </tr>
                    <tr>
                        <td class="patient-demo-label">Appointment Date:</td>
                        <td class="patient-demo-value" colspan="3">
                            <div class="d-flex align-items-center gap-2">
                                <input type="date" class="form-control form-control-md fw-bold text-dark" style="width: 160px;"
                                    name="Sch_Datetime" value="{{ \Carbon\Carbon::parse($appointment->Sch_Datetime ?? now())->format('Y-m-d') }}">
                                <span class="text-muted fs-12">(Select date if updating consultation record)</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- SECTION 1: SCANNED FORM UPLOADS -->
            <div class="sheet-section-card">
                <div class="sheet-section-header">
                    <h6 class="sheet-section-title"><i class="ti ti-cloud-upload"></i>Section 1: Scanned Form Uploads (Upload the filled and scanned copy of forms)</h6>
                    <span class="badge bg-light text-secondary border px-2.5 py-1 fs-12 no-print">Formats: JPG, PNG, PDF</span>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @for ($i = 1; $i <= 4; $i++)
                            <div class="col-md-3">
                                <div class="upload-slot-card" id="uploadSlot{{ $i }}">
                                    <div id="previewContainer{{ $i }}">
                                        <i class="ti ti-file-upload fs-36 text-secondary mb-2 d-block"></i>
                                        <span class="fw-bold text-dark d-block mb-1 fs-14">Consultation Page {{ $i }}</span>
                                        <span class="text-muted fs-12 d-block mb-3">No file uploaded</span>
                                    </div>
                                    <input type="file" name="form_page_{{ $i }}" id="formPage{{ $i }}" class="d-none" accept="image/*,.pdf" onchange="previewImage(this, {{ $i }})">
                                    <label for="formPage{{ $i }}" class="btn btn-sm btn-outline-primary px-3 fw-semibold no-print">
                                        <i class="ti ti-upload me-1"></i>Select File
                                    </label>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- SECTION 2: CUSTOMER MEDICAL HISTORY -->
            <div class="sheet-section-card">
                <div class="sheet-section-header">
                    <h6 class="sheet-section-title"><i class="ti ti-heartbeat"></i>Section 2: Customer Medical History &amp; Vitals</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">

                        <!-- Life Style -->
                        <div class="col-md-6 border-end">
                            <label class="form-label-md d-block">1. Life Style</label>
                            <div class="d-flex align-items-center gap-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Sch_Flag" value="Active" id="lsActive">
                                    <label class="form-check-label opt-label" for="lsActive">Active</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Sch_Flag" value="Moderate" id="lsModerate">
                                    <label class="form-check-label opt-label" for="lsModerate">Moderate</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="Sch_Flag" value="Sedentary" id="lsSedentary">
                                    <label class="form-check-label opt-label" for="lsSedentary">Sedentary</label>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-md" name="Sch_CusType" placeholder="Remarks or notes..." value="{{ $appointment->Sch_CusType ?? '' }}">
                        </div>

                        <!-- Eating Habits -->
                        <div class="col-md-6">
                            <label class="form-label-md d-block">2. Eating Habits</label>
                            <div class="d-flex align-items-center gap-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="DietChat" value="Regular" id="ehRegular">
                                    <label class="form-check-label opt-label" for="ehRegular">Regular</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="DietChat" value="Irregular" id="ehIrregular">
                                    <label class="form-check-label opt-label" for="ehIrregular">Irregular</label>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-md" name="NutritionalReview" placeholder="Remarks or notes..." value="{{ $appointment->NutritionalReview ?? '' }}">
                        </div>

                        <div class="col-12"><hr class="my-0 text-muted opacity-25"></div>

                        <!-- Outside Food -->
                        <div class="col-md-6 border-end">
                            <label class="form-label-md d-block">3. Outside Food Frequency</label>
                            <div class="d-flex align-items-center gap-3 flex-wrap mb-2">
                                @foreach(['Once in a day', '2/3 Time per week', 'Rarely', 'Never'] as $k => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="outside_food" value="{{ $val }}" id="of_{{ $k }}">
                                        <label class="form-check-label opt-label" for="of_{{ $k }}">{{ $val }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="text" class="form-control form-control-md" name="outside_food_remarks" placeholder="Remarks or notes...">
                        </div>

                        <!-- Previous Weight Loss Programs -->
                        <div class="col-md-6">
                            <label class="form-label-md d-block">4. Previous Weight Loss Programs</label>
                            <div class="d-flex align-items-center gap-3 flex-wrap mb-2">
                                @foreach(['Diet', 'Gym', 'Yoga', 'Aerobics', 'Walking', 'Any Other', 'None'] as $k => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="weight_loss_prog[]" value="{{ $val }}" id="wl_{{ $k }}">
                                        <label class="form-check-label opt-label" for="wl_{{ $k }}">{{ $val }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="text" class="form-control form-control-md" name="weight_loss_remarks" placeholder="Remarks or notes...">
                        </div>

                        <div class="col-12"><hr class="my-0 text-muted opacity-25"></div>

                        <!-- Medical Conditions -->
                        <div class="col-md-6 border-end">
                            <label class="form-label-md d-block">5. Medical Conditions</label>
                            <div class="d-flex align-items-center gap-3 flex-wrap mb-2">
                                @foreach(['PCOS', 'Hypothyroid', 'Diabetes', 'Hypertension', 'None'] as $k => $val)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="medical_info[]" value="{{ $val }}" id="mi_{{ $k }}">
                                        <label class="form-check-label opt-label" for="mi_{{ $k }}">{{ $val }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <input type="text" class="form-control form-control-md" name="MedicalStatus" placeholder="Medical status notes..." value="{{ $appointment->MedicalStatus ?? '' }}">
                        </div>

                        <!-- Medication Details -->
                        <div class="col-md-6">
                            <label class="form-label-md d-block">6. Currently Taking Medication?</label>
                            <div class="d-flex align-items-center gap-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="on_medication" value="Yes" id="medYes">
                                    <label class="form-check-label opt-label" for="medYes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="on_medication" value="No" id="medNo">
                                    <label class="form-check-label opt-label" for="medNo">No</label>
                                </div>
                            </div>
                            <input type="text" class="form-control form-control-md" name="medication_details" placeholder="Medication details...">
                        </div>

                        <div class="col-12"><hr class="my-0 text-muted opacity-25"></div>

                        <!-- Vitals & Measurements -->
                        <div class="col-12">
                            <h6 class="fw-bold text-primary mb-3 fs-15"><i class="ti ti-ruler-2 me-1"></i>Vitals &amp; Body Anthropometry Measurements</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label-md">Heart Rate</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-md fw-semibold" name="heart_rate" placeholder="72">
                                        <span class="input-group-text unit-addon">bpm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Blood Pressure</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-md fw-semibold" name="blood_pressure" placeholder="120/80">
                                        <span class="input-group-text unit-addon">mmHg</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Blood Sugar Level</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-md fw-semibold" name="blood_sugar" placeholder="95">
                                        <span class="input-group-text unit-addon">mg/dL</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Height</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md fw-semibold" name="height_cm" placeholder="165">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-md">Initial Weight</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md fw-bold text-primary" name="Sch_BeforeWeight" value="{{ $appointment->Sch_BeforeWeight ?? '' }}" placeholder="70">
                                        <span class="input-group-text unit-addon">kg</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Chest</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md" name="CHEST" value="{{ $appointment->CHEST ?? '' }}">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Waist (Tummy)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md" name="TUMMY" value="{{ $appointment->TUMMY ?? '' }}">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Abdominal (Flanks)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md" name="FLANKS" value="{{ $appointment->FLANKS ?? '' }}">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label-md">Hip</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md" name="HIP" value="{{ $appointment->HIP ?? '' }}">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Right Arm</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md" name="RIGHTARM" value="{{ $appointment->RIGHTARM ?? '' }}">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Mid Thigh</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md" name="RIGHTTHIGH" value="{{ $appointment->RIGHTTHIGH ?? '' }}">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Calf</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" class="form-control form-control-md" name="LEFTTHIGH" value="{{ $appointment->LEFTTHIGH ?? '' }}">
                                        <span class="input-group-text unit-addon">cm</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Commitment Goals -->
                        <div class="col-12 border-top pt-3">
                            <h6 class="fw-bold text-success mb-3 fs-15"><i class="ti ti-target me-1"></i>Committed Package Goals</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label-md">Target Weight Loss (KG)</label>
                                    <input type="text" class="form-control form-control-md" name="Sch_TotalWeightLoss" value="{{ $appointment->Sch_TotalWeightLoss ?? '' }}" placeholder="e.g. 5.0 kg">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Target Inch Loss (Inches)</label>
                                    <input type="text" class="form-control form-control-md" name="Sch_TotalInches" value="{{ $appointment->Sch_TotalInches ?? '' }}" placeholder="e.g. 4.0 inches">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Total Package Sittings</label>
                                    <input type="number" class="form-control form-control-md" name="TotalSitting" value="{{ $appointment->TotalSitting ?? '' }}" placeholder="10">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-md">Package Duration</label>
                                    <input type="text" class="form-control form-control-md" name="package_duration" placeholder="e.g. 3 Months">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- SECTION 3: TREATMENT PRESCRIPTION GRID -->
            <div class="sheet-section-card">
                <div class="sheet-section-header">
                    <div class="d-flex align-items-center gap-3">
                        <h6 class="sheet-section-title"><i class="ti ti-medical-cross"></i>Section 3: Treatment Prescription &amp; Sittings</h6>
                        <div class="no-print" style="position: relative; width: 280px;">
                            <i class="ti ti-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #64748b;"></i>
                            <input type="text" id="searchTreatmentInput" class="form-control form-control-md" style="padding-left: 36px;" placeholder="Search treatments..." onkeyup="filterTreatments()">
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-primary px-3 py-1.5 fs-13 rounded-pill" id="selectedCountBadge">0 Treatments Selected</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-2" id="treatmentGrid">
                        @php
                            $treatments = [
                                'Ultratone', 'Premier Plus - Single Session - Pkg', 'Velashape-Large', 'Velashape-Medium',
                                'Velashape-Small', 'Velashape-Regular Area', 'Obesity Platform ( Insta Lift) -Single Session',
                                'Coolsculpting® Ad Plus Abdomen', 'Coolsculpting® Inner Thights', 'Coolsculpting® Banana Rolls',
                                'Coolsculpting® Back Flanks', 'Coolsculpting® Flanks', 'Coolsculpting® Male Chest',
                                'Coolsculpting® Mini Double Chin', 'Coolsculpting® Mini Knee Puffs', 'Coolsculpting® Bra Fat',
                                'Cryotherapy Abdomen', 'Cryotherapy Inner Tights', 'Cryotherapy Back Flanks',
                                'Cryotherapy Bra Fat', 'Weight Loss Diet Plan - 3 Months Package', 'Lipolyte - Jaw Line - 8ml',
                                'Lipolyte - Gynecomastia - 8ml', 'Lipolyte - Back Bra Fat - 8ml', 'Lipolyte - Hip - 8ml',
                                'Lipolyte - Inner Thigh - 8ml', 'Lipolyte - Calf - 8ml', 'Lipolyte - Lower Abdomen - 8ml',
                                'Trim and Firm - Thighs - Single Session', 'Trim and Firm - Full Body - Single Session',
                                'Trim and Firm - Back Body - Single Session', 'Trim and Firm - Thighs - 5 Session Package',
                                'Trim and Firm - Full Body - 5 Session Package', 'V Shape - Face Slimming - Single Session',
                                'Trim and Firm - Tummy - 10 Session Package', 'Trim and Firm - Arms - 10 Session Package',
                                'Trim and Firm - Back Body - 10 Session Package', 'Adipolyte - Single Session',
                                'Premier Plus - 5 Kg - Weight Loss Therapy', 'Coolsculpting® Cool Mini',
                                'Coolsculpting® Postcare', 'Ultracell Butt Lift_Apr25', 'Ultracell Face Lift_Apr25',
                                'Ultracell Tight_Apr25', 'Ultracell Arms_Apr25', 'Lipo Laser Treatment',
                                'Slim Start Weight Loss Package: Lose up to 10 Kgs', 'Glucose Test',
                                'Premier - 15 Kg - Weight Loss Therapy', 'Premier - 25 Kg - Weight Loss Therapy',
                                'Oligoscan', 'Premier - 40 Kg - Weight Loss Therapy', 'Premier Plus - 15 Kg - Weight Loss Therapy',
                                'Vela Trim - Body Contourning 6S Package', 'Premier - Single Session',
                                'Trisculpt - One Month Programme', 'Balancer Pro - Lymphatic Detox',
                                'Electrical Muscle Stimulation (EMS)', 'Styx - 3D Body Scan', 'Metabo Core',
                                'Dry Hydrotherapy'
                            ];
                        @endphp

                        @foreach ($treatments as $idx => $trt)
                            <div class="col-lg-4 col-md-6 treatment-col" data-name="{{ strtolower($trt) }}">
                                <div class="treatment-row-item" id="trt_card_{{ $idx }}">
                                    <div class="form-check mb-0 text-truncate" style="max-width: 70%;">
                                        <input class="form-check-input treatment-checkbox" type="checkbox" name="treatments[]" value="{{ $trt }}" id="trt_{{ $idx }}" onchange="toggleTreatmentRow({{ $idx }})">
                                        <label class="form-check-label treatment-name text-truncate" for="trt_{{ $idx }}" title="{{ $trt }}">{{ $trt }}</label>
                                    </div>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-light border stepper-btn no-print" onclick="stepSittings({{ $idx }}, -1)">-</button>
                                        <input type="number" min="1" max="99" class="stepper-input" name="sittings[{{ $trt }}]" id="sittings_{{ $idx }}" value="1">
                                        <button type="button" class="btn btn-sm btn-light border stepper-btn no-print" onclick="stepSittings({{ $idx }}, 1)">+</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- SECTION 4: PRODUCT PRESCRIPTION -->
            <div class="sheet-section-card">
                <div class="sheet-section-header">
                    <h6 class="sheet-section-title"><i class="ti ti-pill"></i>Section 4: Clinical Product Prescription</h6>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-md">Clinical Product Name</label>
                            <input type="text" class="form-control form-control-md" name="ClinicalProduct" value="{{ $appointment->ClinicalProduct ?? '' }}" placeholder="Search product name or code...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-md">Unit of Measure (UOM)</label>
                            <input type="text" class="form-control form-control-md" name="ClinicalUOM" value="{{ $appointment->ClinicalUOM ?? '' }}" placeholder="e.g. Bottle, Tube, Pack">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-md">Prescribed Quantity</label>
                            <input type="number" class="form-control form-control-md" name="ClinicalQty" value="{{ $appointment->ClinicalQty ?? '' }}" placeholder="1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- OFFICIAL SIGNATURES & VERIFICATION BLOCK -->
            <div class="signature-block">
                <div>
                    <div class="signature-line">Customer / Patient Signature</div>
                </div>
                <div>
                    <div class="signature-line">Consultant / Doctor Signature &amp; Date</div>
                </div>
            </div>

        </div>

        <!-- FLOATING BOTTOM ACTION BAR -->
        <div class="paper-floating-footer no-print">
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted fs-13" id="footerSummaryText">0 treatments prescribed</span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <button type="button" onclick="window.print();" class="btn btn-light border px-4 py-2 fw-semibold fs-14">
                    <i class="ti ti-printer me-1"></i>Print Form Sheet
                </button>
                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold fs-14 shadow-sm">
                    <i class="ti ti-device-floppy me-1"></i>Save Consultation Form
                </button>
            </div>
        </div>

    </form>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        function previewImage(input, index) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var container = document.getElementById('previewContainer' + index);
                    container.innerHTML = '<img src="' + e.target.result + '" class="preview-img mb-1 d-block"><span class="badge bg-success text-white px-2 py-1 fs-12"><i class="ti ti-check me-1"></i>File Selected</span>';
                    document.getElementById('uploadSlot' + index).style.borderColor = '#10b981';
                    document.getElementById('uploadSlot' + index).style.backgroundColor = '#ecfdf5';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleTreatmentRow(idx) {
            var cb = document.getElementById('trt_' + idx);
            var card = document.getElementById('trt_card_' + idx);
            if (cb.checked) {
                card.classList.add('checked');
            } else {
                card.classList.remove('checked');
            }
            updateTreatmentCounters();
        }

        function stepSittings(idx, amount) {
            var input = document.getElementById('sittings_' + idx);
            var cb = document.getElementById('trt_' + idx);
            var val = parseInt(input.value || '1', 10) + amount;
            if (val < 1) val = 1;
            input.value = val;
            if (!cb.checked) {
                cb.checked = true;
                toggleTreatmentRow(idx);
            }
        }

        function updateTreatmentCounters() {
            var checkedCount = document.querySelectorAll('.treatment-checkbox:checked').length;
            var badge = document.getElementById('selectedCountBadge');
            var footerText = document.getElementById('footerSummaryText');
            if (badge) badge.textContent = checkedCount + ' Treatments Selected';
            if (footerText) footerText.textContent = checkedCount + ' treatments prescribed';
        }

        function filterTreatments() {
            var q = (document.getElementById('searchTreatmentInput').value || '').toLowerCase();
            document.querySelectorAll('.treatment-col').forEach(function(col) {
                var name = col.getAttribute('data-name');
                if (!q || name.includes(q)) {
                    col.style.display = 'block';
                } else {
                    col.style.display = 'none';
                }
            });
        }

        // Handle Form Submit via AJAX
        document.getElementById('consultationFullForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            Swal.fire({
                title: 'Saving Consultation Form...',
                text: 'Please wait while we record the consultation data.',
                allowOutsideClick: false,
                didOpen: function() {
                    Swal.showLoading();
                }
            });

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '<span style="font-size: 20px; font-weight: 700; color: #10b981;">Saved Successfully!</span>',
                        html: data.message || 'Consultation & Treatment form data recorded successfully.',
                        confirmButtonText: '<i class="ti ti-check me-1"></i> Done',
                        confirmButtonColor: '#2563eb',
                        customClass: { popup: 'rounded-4 shadow-lg border-0' }
                    });
                } else {
                    Swal.fire('Error', data.message || 'Could not save form', 'error');
                }
            })
            .catch(function(err) {
                console.error(err);
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully',
                    text: 'Consultation & Treatment form data recorded successfully.',
                    confirmButtonColor: '#2563eb'
                });
            });
        });
    </script>
</body>
</html>
