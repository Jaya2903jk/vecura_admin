@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">

        <!-- Start Content -->
        <div class="content" id="patientDetailsPage">

            {{-- Page Header --}}
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-2 pb-2 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0 fs-18">Patient Details</h4>
                </div>
                <div class="text-end d-flex gap-2">
                    <a href="{{ route('patient.index') }}" class="btn btn-light border btn-sm">
                        <i class="ti ti-arrow-left me-1"></i>Back to List
                    </a>
                </div>
            </div>
            {{-- End Page Header --}}

            {{-- Print-only letterhead (hidden on screen, shown via @media print), spans full width above sidebar --}}
            <div class="print-letterhead">
                <div>
                    <p class="clinic-name">Ve<span>Cura</span></p>
                    <p class="clinic-sub">Wellness Clinic</p>
                </div>
                <p class="clinic-tagline">
                    Care tailored just for you. Our weight loss and body contouring programs.<br>
                    Printed on {{ now()->format('d/m/y, g:i A') }}
                </p>
            </div>

            <div class="card mb-0">
                <div class="card-body p-0">
                    <div class="settings-wrapper d-flex compact-patient-page">

                        {{-- Start Patient Sidebar (main menu + child menu, compact) --}}
                        <div class="sidebars settings-sidebar" id="sidebar2" style="width: 220px; min-width: 220px;">
                            <div class="sidebar-inner">
                                <div id="sidebar-menu6" class="sidebar-menu mt-0 p-0">
                                    <ul>

                                        <li>
                                            <a href="javascript:void(0);" class="tab-trigger active" data-tab="profile">
                                                <i class="ti ti-user-circle me-2"></i><span>Profile</span>
                                            </a>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="appointments">
                                                <i class="ti ti-calendar-check me-2"></i><span>Appointments</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <!-- <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="appointments-upcoming">Upcoming</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="appointments-history">History</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="appointments-new" data-bs-toggle="modal"
                                                        data-bs-target="#bookAppointmentModal">Book New</a></li> -->
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="email-templates">Email Templates</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="consultation">
                                                <i class="ti ti-stethoscope me-2"></i><span>Consultation</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="consultation">Consultation History</a></li>
                                                        <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="consultation">Add Consultation</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="medical-screening">
                                                <i class="ti ti-microscope me-2"></i><span>Medical Screening</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);">Screening Report</a></li>
                                                <li><a href="javascript:void(0);">Add Screening</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="bca">
                                                <i class="ti ti-body-scan me-2"></i><span>Body Composition Analysis</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);">BCA Report</a></li>
                                                <li><a href="javascript:void(0);">Add BCA</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="nutritional-review">
                                                <i class="ti ti-salad me-2"></i><span>Nutritional Review</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);">Review History</a></li>
                                                <li><a href="javascript:void(0);">Add Review</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger"
                                                data-tab="nutritional-screening">
                                                <i class="ti ti-apple me-2"></i><span>Nutritional Screening</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);">Screening History</a></li>
                                                <li><a href="javascript:void(0);">Add Screening</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="diet-chart">
                                                <i class="ti ti-clipboard-list me-2"></i><span>Diet Chart</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);">View Chart</a></li>
                                                <li><a href="javascript:void(0);">Create Chart</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="receipt">
                                                <i class="ti ti-receipt me-2"></i><span>Receipt</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);">All Receipts</a></li>
                                                <li><a href="javascript:void(0);">New Receipt</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="bill">
                                                <i class="ti ti-file-invoice me-2"></i><span>Bill</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger" data-tab="bill">All
                                                        Bills</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger" data-tab="bill">New
                                                        Bill</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="measurement">
                                                <i class="ti ti-ruler-2 me-2"></i><span>Full Body Measurement</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="measurement">Measurement History</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="measurement">Add Measurement</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="photos">
                                                <i class="ti ti-photo me-2"></i><span>Before After Photos</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="photos">Gallery</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="photos">Upload Photos</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="execution">
                                                <i class="ti ti-player-play me-2"></i><span>Execution</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="execution">Execution Log</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="execution">Add Execution</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger"
                                                data-tab="treatment-record">
                                                <i class="ti ti-file-description me-2"></i><span>Treatment Record
                                                    Sheet</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="treatment-record">View Sheet</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="treatment-record">Add Entry</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="hma">
                                                <i class="ti ti-heartbeat me-2"></i><span>HMA</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger" data-tab="hma">HMA
                                                        Report</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger" data-tab="hma">Add
                                                        HMA</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="ticket">
                                                <i class="ti ti-ticket me-2"></i><span>Ticket</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="ticket">All Tickets</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="ticket">Raise Ticket</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="feedback">
                                                <i class="ti ti-message-star me-2"></i><span>Treatment Feedback</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="feedback">Feedback History</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="feedback">Add Feedback</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="loan">
                                                <i class="ti ti-coin me-2"></i><span>Loan</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="loan">Loan Details</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="loan">Apply Loan</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="credit-note">
                                                <i class="ti ti-file-minus me-2"></i><span>Credit Note</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="credit-note">All Credit Notes</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="credit-note">New Credit Note</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="dtr">
                                                <i class="ti ti-report-money me-2"></i><span>DTR</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger" data-tab="dtr">DTR
                                                        Report</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger" data-tab="dtr">Add
                                                        Entry</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="support">
                                                <i class="ti ti-headset me-2"></i><span>V Support</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="support">Support History</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="support">Raise Request</a></li>
                                            </ul>
                                        </li>

                                        <li class="submenu">
                                            <a href="javascript:void(0);" class="tab-trigger" data-tab="followup">
                                                <i class="ti ti-phone-outgoing me-2"></i><span>Follow Up</span>
                                                <span class="menu-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="followup">Follow Up History</a></li>
                                                <li><a href="javascript:void(0);" class="tab-trigger"
                                                        data-tab="followup">Schedule Follow Up</a></li>
                                            </ul>
                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        {{-- End Patient Sidebar --}}

                        <div class="card flex-fill mb-0 border-0 bg-light-500 shadow-none">
                            <div class="card-header border-bottom px-0 mx-3 py-2">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <h6 class="fw-bold mb-0">
                                        {{ $patient->Salutation ?? 'MR.' }} {{ $patient->FirstName ?? 'Jayakanthan' }}
                                        {{ $patient->LastName ?? 'P' }}
                                        <span class="text-muted fs-13 fw-normal">
                                            ( {{ $patient->RegistrationNo ?? 'ANR26070001' }} )
                                        </span>
                                        <span class="badge badge-soft-primary border border-primary ms-2 fs-12">
                                            Total Amount (Billed - Refunded): {{ $patient->TotalAmount ?? '0' }}
                                        </span>
                                    </h6>

                                </div>
                            </div>

                            <div class="card-body px-0 mx-3 py-2">
                                <div class="tab-content" id="profile-tab" style="display: block;">

                                    {{-- ===================== REGISTRATION SUMMARY ===================== --}}
                                    <div class="card shadow-none border mb-3">
                                        <div class="card-header bg-light-subtle py-2 d-flex align-items-center gap-2">
                                            <i class="ti ti-id-badge-2 fs-16 text-primary"></i>
                                            <span class="fw-semibold fs-14">Registration Summary</span>
                                            <span class="ms-auto">
                                                @php $status = $patient->CustomerStatus ?? 'Active'; @endphp
                                                @if ($status === 'Active')
                                                    <span
                                                        class="badge badge-soft-success rounded text-success border border-success fs-12 fw-medium">Active</span>
                                                @else
                                                    <span
                                                        class="badge badge-soft-warning rounded text-warning border border-warning fs-12 fw-medium">{{ $status }}</span>
                                                @endif
                                            </span>
                                        </div>
                                        <div class="card-body py-2">
                                            <div class="row gy-2 gx-3 compact-details-grid">
                                                <div class="col-md-4 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Registration No.</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->RegistrationNo ?? 'ANR26070001' }}</span>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Registered Date</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->RegisteredDate ?? '14-Jul-2026 | 12:42:48' }}</span>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Consultant</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->ConsultantName ?? '25447' }}</span>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Known By</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->KnownBy ?? 'CONTACT FORM' }}</span>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Type</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Type ?? 'Central Call Center' }}</span>
                                                </div>
                                                <div class="col-md-4 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Ref. Patient</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->RefByPatient ?? '323' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ===================== PERSONAL DETAILS ===================== --}}
                                    <div class="card shadow-none border mb-3">
                                        <div class="card-header bg-light-subtle py-2 d-flex align-items-center gap-2">
                                            <i class="ti ti-user fs-16 text-primary"></i>
                                            <span class="fw-semibold fs-14">Personal Details</span>
                                        </div>
                                        <div class="card-body py-2">
                                            <div class="row gy-2 gx-3 compact-details-grid">
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Name</span>
                                                    <span class="fw-medium text-dark fs-13">
                                                        {{ $patient->Salutation ?? 'MR.' }}
                                                        {{ $patient->FirstName ?? 'Jayakanthan' }}
                                                        {{ $patient->LastName ?? 'P' }}
                                                    </span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Sex</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Sex ?? 'Male' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Date of Birth</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->DOB ?? '29-Mar-1999' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Age</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Age ?? '27' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Marital Status</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->MaritalStatus ?? 'SINGLE' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Wedding Day</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->WeddingDay ?? '—' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Occupation</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Occupation ?? 'ASST DIRECTION' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Occupation Details</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->OccupationDetails ?? 'None' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ===================== CONTACT DETAILS ===================== --}}
                                    <div class="card shadow-none border mb-3">
                                        <div class="card-header bg-light-subtle py-2 d-flex align-items-center gap-2">
                                            <i class="ti ti-phone fs-16 text-primary"></i>
                                            <span class="fw-semibold fs-14">Contact Details</span>
                                        </div>
                                        <div class="card-body py-2">
                                            <div class="row gy-2 gx-3 compact-details-grid">
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Mobile</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Mobile ?? '8825816433' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Email</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->EMail ?? 'pjayakanthan17@gmail.com' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Telephone (R)</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->TelephoneR ?? '—' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Telephone (O)</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->TelephoneO ?? '—' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">SMS Alert</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->SmsAlert ?? 'No' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">E-Mail Alert</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->EmailAlert ?? 'No' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ===================== ADDRESS ===================== --}}
                                    <div class="card shadow-none border mb-3">
                                        <div class="card-header bg-light-subtle py-2 d-flex align-items-center gap-2">
                                            <i class="ti ti-map-pin fs-16 text-primary"></i>
                                            <span class="fw-semibold fs-14">Address</span>
                                        </div>
                                        <div class="card-body py-2">
                                            <div class="row gy-2 gx-3 compact-details-grid">
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Street</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Street ?? 'Chennai' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Area</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Area ?? '—' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">City</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->City ?? '—' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">State</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->State ?? '—' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Pincode</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Pincode ?? '600041' }}</span>
                                                </div>
                                                <div class="col-md-6 d-flex justify-content-between">
                                                    <span class="text-muted fs-13">Country</span>
                                                    <span
                                                        class="fw-medium text-dark fs-13">{{ $patient->Country ?? 'INDIA' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- ===================== BILLING & ADMIN (collapsed by default) ===================== --}}
                                    <div class="card shadow-none border mb-0">
                                        <div class="card-header bg-light-subtle py-2 d-flex align-items-center gap-2"
                                            role="button" data-bs-toggle="collapse"
                                            data-bs-target="#adminDetailsCollapse" aria-expanded="false">
                                            <i class="ti ti-settings fs-16 text-primary"></i>
                                            <span class="fw-semibold fs-14">Billing &amp; Admin Details</span>
                                            <i class="ti ti-chevron-down ms-auto fs-14 text-muted"></i>
                                        </div>
                                        <div id="adminDetailsCollapse" class="collapse">
                                            <div class="card-body py-2">
                                                <div class="row gy-2 gx-3 compact-details-grid">
                                                    <div class="col-md-6 d-flex justify-content-between">
                                                        <span class="text-muted fs-13">Special Discount</span>
                                                        <span
                                                            class="fw-medium text-dark fs-13">{{ $patient->SpecialDiscount ?? 'No' }}</span>
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-between">
                                                        <span class="text-muted fs-13">Reg. Type</span>
                                                        <span
                                                            class="fw-medium text-dark fs-13">{{ $patient->RegType ?? '—' }}</span>
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-between">
                                                        <span class="text-muted fs-13">Product Special Discount (%)</span>
                                                        <span
                                                            class="fw-medium text-dark fs-13">{{ $patient->ProductSpecialDiscount ?? '0.00' }}</span>
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-between">
                                                        <span class="text-muted fs-13">Service Special Discount (%)</span>
                                                        <span
                                                            class="fw-medium text-dark fs-13">{{ $patient->ServiceSpecialDiscount ?? '0.00' }}</span>
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-between">
                                                        <span class="text-muted fs-13">PAN Number</span>
                                                        <span
                                                            class="fw-medium text-dark fs-13">{{ $patient->PanNumber ?? '-' }}</span>
                                                    </div>
                                                    <div class="col-md-6 d-flex justify-content-between">
                                                        <span class="text-muted fs-13">Aadhar Number</span>
                                                        <span
                                                            class="fw-medium text-dark fs-13">{{ $patient->AadharNumber ?? '-' }}</span>
                                                    </div>
                                                    <div class="col-12 d-flex justify-content-between">
                                                        <span class="text-muted fs-13">Customer Remarks</span>
                                                        <span
                                                            class="fw-medium text-dark fs-13">{{ $patient->CustomerRemarks ?? '—' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-3">
                                        <a href="javascript:window.print();" class="btn btn-primary border btn-sm px-4">
                                            <i class="ti ti-printer me-1"></i>Print
                                        </a>
                                    </div>
                                </div>

                                <div class="tab-content" id="appointments-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div
                                            class="card-header bg-light d-flex align-items-center justify-content-between">
                                            <h6 class="fw-bold mb-0">Appointments</h6>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#bookAppointmentModal">
                                                <i class="ti ti-calendar-plus me-1"></i>Book New Scheduling
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-sm align-middle">
                                                    <thead>
                                                        <tr class="bg-light">
                                                            <th>S.No.</th>
                                                            <th>Location</th>
                                                            <th>Date</th>
                                                            <th>Time</th>
                                                            <th>Type</th>
                                                            <th>Doctor</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($appointments ?? [] as $index => $appt)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td><span
                                                                        class="bg-light-primary">{{ $appt->LOCATION ?? '—' }}</span>
                                                                </td>
                                                                <td>{{ optional($appt->Sch_Datetime)->format('d-M-Y') ?? '—' }}
                                                                </td>
                                                                <td>{{ $appt->Sch_Time ?? (optional($appt->Sch_Datetime)->format('g:i A') ?? '—') }}
                                                                </td>
                                                                <td>{{ $appt->appointmentFor->AppointName ?? '—' }}
                                                                </td>
                                                                 <td>{{ $appt->doctor->userMaster->FullName ?? ($appt->doctor->DoctorName ?? ($appt->Sch_Doctname ?? '—')) }}</td>
                                                                <td>
                                                                    @php $apptStatus = strtoupper($appt->Sch_Status ?? 'SCHEDULED'); @endphp
                                                                    @if ($apptStatus === 'COMPLETED')
                                                                        <span
                                                                            class="badge bg-success">{{ $apptStatus }}</span>
                                                                    @elseif ($apptStatus === 'CANCELLED')
                                                                        <span
                                                                            class="badge bg-danger">{{ $apptStatus }}</span>
                                                                    @else
                                                                        <span
                                                                            class="badge bg-warning">{{ $apptStatus }}</span>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <button class="btn btn-sm btn-light border"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#emailTemplateModal">
                                                                        {{-- <i class="ti ti-mail me-1"></i>Send Email --}}
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="8" class="text-center text-muted py-3">No
                                                                    appointments found for this patient.</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="email-templates-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Email Templates for
                                                {{ $patient->FirstName ?? 'Patient' }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <div class="card shadow-none border">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-start">
                                                                <span
                                                                    class="avatar avatar-lg p-2 bg-primary-transparent rounded flex-shrink-0 me-2">
                                                                    <i class="ti ti-mail text-primary fs-24"></i>
                                                                </span>
                                                                <div class="flex-grow-1">
                                                                    <p class="fw-medium text-dark mb-1">Appointment
                                                                        Confirmation</p>
                                                                    <p class="mb-0 text-muted fs-13">Send appointment
                                                                        confirmation email</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer bg-light">
                                                            <button class="btn btn-sm btn-primary w-100"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#emailTemplateModal"
                                                                data-template="appointment">
                                                                <i class="ti ti-send me-1"></i>Send Email
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="card shadow-none border">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-start">
                                                                <span
                                                                    class="avatar avatar-lg p-2 bg-info-transparent rounded flex-shrink-0 me-2">
                                                                    <i class="ti ti-medical-cross text-info fs-24"></i>
                                                                </span>
                                                                <div class="flex-grow-1">
                                                                    <p class="fw-medium text-dark mb-1">Follow-up
                                                                        Consultation</p>
                                                                    <p class="mb-0 text-muted fs-13">Send follow-up email
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer bg-light">
                                                            <button class="btn btn-sm btn-primary w-100"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#emailTemplateModal"
                                                                data-template="followup">
                                                                <i class="ti ti-send me-1"></i>Send Email
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="card shadow-none border">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-start">
                                                                <span
                                                                    class="avatar avatar-lg p-2 bg-warning-transparent rounded flex-shrink-0 me-2">
                                                                    <i class="ti ti-bell text-warning fs-24"></i>
                                                                </span>
                                                                <div class="flex-grow-1">
                                                                    <p class="fw-medium text-dark mb-1">Appointment
                                                                        Reminder</p>
                                                                    <p class="mb-0 text-muted fs-13">Send reminder email
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer bg-light">
                                                            <button class="btn btn-sm btn-primary w-100"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#emailTemplateModal"
                                                                data-template="reminder">
                                                                <i class="ti ti-send me-1"></i>Send Email
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="card shadow-none border">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-start">
                                                                <span
                                                                    class="avatar avatar-lg p-2 bg-danger-transparent rounded flex-shrink-0 me-2">
                                                                    <i class="ti ti-report text-danger fs-24"></i>
                                                                </span>
                                                                <div class="flex-grow-1">
                                                                    <p class="fw-medium text-dark mb-1">Test Result
                                                                        Notification</p>
                                                                    <p class="mb-0 text-muted fs-13">Send test result email
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="card-footer bg-light">
                                                            <button class="btn btn-sm btn-primary w-100"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#emailTemplateModal"
                                                                data-template="result">
                                                                <i class="ti ti-send me-1"></i>Send Email
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="consultation-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Consultation</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Consultation content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="medical-screening-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Medical Screening</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Medical Screening content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="bca-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Body Composition Analysis</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">BCA content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="nutritional-review-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Nutritional Review</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Nutritional Review content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="nutritional-screening-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Nutritional Screening</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Nutritional Screening content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="diet-chart-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Diet Chart</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Diet Chart content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="receipt-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Receipt</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Receipt content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="bill-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Bill</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Bill content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="measurement-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Full Body Measurement</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Measurement content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="photos-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Before After Photos</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Photo gallery content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="execution-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Execution</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Execution content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="treatment-record-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Treatment Record Sheet</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Treatment Record content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="hma-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">HMA</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">HMA content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="ticket-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Ticket</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Ticket content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="feedback-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Treatment Feedback</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Feedback content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="loan-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Loan</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Loan content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="credit-note-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Credit Note</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Credit Note content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="dtr-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">DTR</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">DTR content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="support-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">V Support</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Support content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content" id="followup-tab" style="display: none;">
                                    <div class="card shadow-none border">
                                        <div class="card-header bg-light">
                                            <h6 class="fw-bold mb-0">Follow Up</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="text-muted">Follow Up content will be displayed here</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <!-- End Content -->

    </div>

    {{-- Email Template Modal --}}
    <div id="emailTemplateModal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-dark modal-title fw-bold">Send Email to {{ $patient->FirstName ?? 'Patient' }}</h5>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">To<span class="text-danger ms-1">*</span></label>
                            <input type="email" class="form-control" value="{{ $patient->EMail ?? '' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" id="emailSubject" placeholder="Email subject">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message<span class="text-danger ms-1">*</span></label>
                            <textarea class="form-control" id="emailBody" rows="6" placeholder="Email message"></textarea>
                        </div>
                        <div class="alert alert-info fs-13">
                            <strong>Available Variables:</strong> @{{ patient_name }}, @{{ doctor_name }},
                            @{{ appointment_date }}, @{{ appointment_time }}, @{{ clinic_name }}
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-white border" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Send Email</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Email Template Modal --}}

    {{-- Book Appointment Modal --}}
    <div id="bookAppointmentModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="text-dark modal-title fw-bold">Book Scheduling for {{ $patient->FirstName ?? 'Patient' }}
                    </h5>
                    <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
                <form id="bookAppointmentForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Scheduling For<span class="text-danger ms-1">*</span></label>
                            <select class="form-select" name="appointment_for" required>
                                <option value="" selected disabled>Select any one</option>
                                @foreach ($appointment_for_options as $option)
                                    <option value="{{ $option->AppointtCode }}">{{ $option->AppointName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Customer Name<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" value="{{ $patient->FirstName ?? '' }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Registration No<span class="text-danger ms-1">*</span></label>
                            <input type="text" class="form-control" value="{{ $patient->RegistrationNo ?? '' }}"
                                readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wellness Expert / Consultant<span
                                    class="text-danger ms-1">*</span></label>
                            <select class="form-select" name="consultant" required>
                                <option value="" selected disabled>Select any one</option>
                                @forelse ($consultants ?? [] as $consultant)
                                    <option value="{{ $consultant->UserID ?? $consultant->id }}">
                                        {{ $consultant->FullName ?? $consultant->name }}</option>
                                @empty
                                    <option value="V1864">V1864 - Rebeka | Wellness Expert</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Schedule Time<span class="text-danger ms-1">*</span></label>
                            <div class="d-flex align-items-center gap-2 position-relative" id="scheduleTimeRow">
                                <input type="text" class="form-control form-control-sm" id="scheduleTimeFrom"
                                    name="schedule_time_from" placeholder="From" readonly required
                                    style="max-width: 100px;">
                                <span class="text-muted fs-13">To</span>
                                <input type="text" class="form-control form-control-sm" id="scheduleTimeTo"
                                    name="schedule_time_to" placeholder="To" readonly required style="max-width: 100px;">
                                <a href="javascript:void(0);" id="getDateTimeLink"
                                    class="fs-13 text-decoration-underline">Get Date Time</a>

                                {{-- Clickable time-slot popover, 10-min intervals, 9:00 AM to 7:00 PM --}}
                                <div id="timeSlotPopover" class="time-slot-popover d-none">
                                    <div class="time-slot-popover-header">Select a time slot</div>
                                    <div class="time-slot-grid" id="timeSlotGrid"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                            <input type="date" class="form-control" name="schedule_date" id="scheduleDate" required>
                        </div>
                    </div>
                    <div class="modal-footer d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- End Book Appointment Modal --}}

    <style>
        /* Time slot popover for the Book Appointment modal */
        .time-slot-popover {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            z-index: 1060;
            width: 260px;
            max-height: 220px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #d9d9e3;
            border-radius: 6px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            padding: 8px;
        }

        .time-slot-popover-header {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 2px 4px 8px;
            border-bottom: 1px solid #eee;
            margin-bottom: 6px;
        }

        .time-slot-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 6px;
        }

        .time-slot-btn {
            border: 1px solid #d9d9e3;
            background: #fff;
            border-radius: 4px;
            font-size: 12px;
            padding: 5px 4px;
            cursor: pointer;
            color: #333;
        }

        .time-slot-btn:hover {
            border-color: var(--bs-primary, #4a4a68);
            color: var(--bs-primary, #4a4a68);
            background: var(--bs-primary-bg-subtle, #eef2ff);
        }

        .time-slot-btn.selected {
            background: var(--bs-primary, #4a4a68);
            border-color: var(--bs-primary, #4a4a68);
            color: #fff;
        }
    </style>

    <style>
        /* Compact patient sidebar: main + child menu, tighter spacing, no scroll needed */
        .compact-patient-page .sidebar-menu>ul>li>a {
            padding: 6px 12px;
            font-size: 13px;
            line-height: 1.3;
        }

        .compact-patient-page .sidebar-menu>ul>li>a i {
            font-size: 15px;
        }

        .compact-patient-page .sidebar-menu>ul>li>a.active {
            background-color: var(--bs-primary-bg-subtle, #eef2ff);
            color: var(--bs-primary);
            font-weight: 500;
            border-radius: 4px;
        }

        .compact-patient-page .sidebar-menu .submenu>ul {
            padding-left: 0;
        }

        .compact-patient-page .sidebar-menu .submenu>ul>li>a {
            padding: 4px 12px 4px 34px;
            font-size: 12.5px;
            color: #6c757d;
        }

        .compact-patient-page .sidebar-menu .submenu>ul {
            display: none;
        }

        .compact-patient-page .sidebar-menu .submenu.subdrop>ul {
            display: block;
        }

        /* Trim vertical rhythm on the content side so the whole page fits without scrolling */
        #patientDetailsPage .card-header,
        #patientDetailsPage .card-body {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .compact-details-grid>div {
            padding-top: 4px;
            padding-bottom: 4px;
        }

        /* Section card headers on the profile tab */
        #profile-tab .card-header[class*="bg-light-subtle"] {
            cursor: default;
        }

        #adminDetailsCollapse .card-header,
        [data-bs-target="#adminDetailsCollapse"] {
            cursor: pointer;
        }

        [data-bs-target="#adminDetailsCollapse"][aria-expanded="true"] .ti-chevron-down {
            transform: rotate(180deg);
            transition: transform 0.15s ease-in-out;
        }

        [data-bs-target="#adminDetailsCollapse"] .ti-chevron-down {
            transition: transform 0.15s ease-in-out;
        }

        /* Print-only letterhead, hidden on screen */
        .print-letterhead {
            display: none;
        }
    </style>

    {{-- ===================== PRINT STYLESHEET ===================== --}}
    {{-- Matches the legacy report format: clinic letterhead + sidebar menu stay visible, not stripped out --}}
    <style media="print">
        .print-letterhead {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 2px solid #1a2b6d;
        }

        .print-letterhead .clinic-name {
            font-size: 22px;
            font-weight: 800;
            color: #1a2b6d;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .print-letterhead .clinic-name span {
            color: #17a2b8;
        }

        .print-letterhead .clinic-sub {
            font-size: 10px;
            color: #666;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .print-letterhead .clinic-tagline {
            font-size: 11px;
            color: #444;
            font-style: italic;
            text-align: right;
            max-width: 320px;
        }

        /* Hide only the non-report chrome: page header actions, other hidden tabs, modals, print button itself */
        .page-wrapper .d-flex.border-bottom .btn,
        #profile-tab a.btn,
        .modal,
        .card-header.border-bottom {
            display: none !important;
        }

        /* Keep the sidebar visible like the legacy report, but flatten it: no icons, no active-state box, plain list */
        .sidebars.settings-sidebar {
            width: 150px !important;
            min-width: 150px !important;
        }

        .compact-patient-page .sidebar-menu>ul>li>a {
            padding: 3px 0 !important;
            font-size: 11px !important;
            color: #1a2b6d !important;
            background: none !important;
            font-weight: 600 !important;
        }

        .compact-patient-page .sidebar-menu>ul>li>a i,
        .compact-patient-page .sidebar-menu .menu-arrow {
            display: none !important;
        }

        .compact-patient-page .sidebar-menu .submenu>ul {
            display: none !important;
        }

        /* Force the collapsed Billing & Admin section open on print */
        #adminDetailsCollapse {
            display: block !important;
            height: auto !important;
        }

        /* Clean card styling for print */
        #profile-tab .card {
            border: 1px solid #999 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
            margin-bottom: 8px !important;
        }

        #profile-tab .card-header {
            background: #f0f0f0 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border-bottom: 1px solid #999 !important;
            font-weight: 700;
        }

        #profile-tab .badge {
            border: 1px solid #666 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #profile-tab .fs-13,
        #profile-tab .fs-14,
        #profile-tab .fs-16 {
            font-size: 11px !important;
        }

        @page {
            margin: 12mm 10mm;
        }
    </style>

    <script>
        // Lightweight accordion toggle for the submenu items — no external JS dependency
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.compact-patient-page .sidebar-menu > ul > li.submenu > a').forEach(function(
                link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var parentLi = link.closest('li.submenu');
                    var wasOpen = parentLi.classList.contains('subdrop');
                    // Close any other open submenu for a clean single-open accordion
                    document.querySelectorAll(
                            '.compact-patient-page .sidebar-menu > ul > li.submenu.subdrop')
                        .forEach(function(openLi) {
                            openLi.classList.remove('subdrop');
                        });
                    if (!wasOpen) {
                        parentLi.classList.add('subdrop');
                    }
                });
            });

            // Tab switching functionality
            document.querySelectorAll('.tab-trigger').forEach(function(trigger) {
                trigger.addEventListener('click', function(e) {
                    var tab = this.getAttribute('data-tab');
                    if (!tab) return;

                    e.preventDefault();

                    // Hide all tabs
                    document.querySelectorAll('.tab-content').forEach(function(tabContent) {
                        tabContent.style.display = 'none';
                    });

                    // Show selected tab
                    var selectedTab = document.getElementById(tab + '-tab');
                    if (selectedTab) {
                        selectedTab.style.display = 'block';
                    } else if (tab === 'appointments') {
                        selectedTab = document.getElementById('appointments-tab');
                        if (selectedTab) selectedTab.style.display = 'block';
                    } else if (tab === 'email-templates') {
                        selectedTab = document.getElementById('email-templates-tab');
                        if (selectedTab) selectedTab.style.display = 'block';
                    }

                    // Update active state
                    document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
                        link.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });

            // Set Profile as default active
            document.querySelector('.sidebar-menu > ul > li > a').classList.add('active');

            // Expand the collapsed Billing & Admin section before printing so it's not blank on paper
            window.addEventListener('beforeprint', function() {
                var adminSection = document.getElementById('adminDetailsCollapse');
                if (adminSection && !adminSection.classList.contains('show')) {
                    adminSection.classList.add('show');
                    adminSection.dataset.wasCollapsedBeforePrint = 'true';
                }
            });
            window.addEventListener('afterprint', function() {
                var adminSection = document.getElementById('adminDetailsCollapse');
                if (adminSection && adminSection.dataset.wasCollapsedBeforePrint === 'true') {
                    adminSection.classList.remove('show');
                    delete adminSection.dataset.wasCollapsedBeforePrint;
                }
            });

            // ===== Book Appointment: time-slot picker =====
            // Generates clickable 10-minute interval slots from 9:00 AM to 7:00 PM.
            // Clicking a slot fills "From" and sets "To" to From + 20 minutes (default appointment length).
            var SLOT_START_MIN = 9 * 60; // 9:00 AM in minutes-from-midnight
            var SLOT_END_MIN = 19 * 60; // 7:00 PM
            var SLOT_INTERVAL_MIN = 10; // 10-minute increments
            var SLOT_DURATION_MIN = 20; // default appointment length once a slot is picked

            function formatMinutesToAmPm(totalMinutes) {
                var h = Math.floor(totalMinutes / 60);
                var m = totalMinutes % 60;
                var period = h >= 12 ? 'PM' : 'AM';
                var h12 = h % 12;
                if (h12 === 0) h12 = 12;
                return h12 + ':' + String(m).padStart(2, '0') + ' ' + period;
            }

            function buildTimeSlotGrid() {
                var grid = document.getElementById('timeSlotGrid');
                if (!grid) return;
                grid.innerHTML = '';
                for (var t = SLOT_START_MIN; t <= SLOT_END_MIN; t += SLOT_INTERVAL_MIN) {
                    var btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'time-slot-btn';
                    btn.textContent = formatMinutesToAmPm(t);
                    btn.dataset.minutes = t;
                    grid.appendChild(btn);
                }
            }

            var timeSlotPopover = document.getElementById('timeSlotPopover');
            var getDateTimeLink = document.getElementById('getDateTimeLink');
            var scheduleTimeFrom = document.getElementById('scheduleTimeFrom');
            var scheduleTimeTo = document.getElementById('scheduleTimeTo');

            if (getDateTimeLink && timeSlotPopover) {
                buildTimeSlotGrid();

                getDateTimeLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    timeSlotPopover.classList.toggle('d-none');
                });

                timeSlotPopover.addEventListener('click', function(e) {
                    var btn = e.target.closest('.time-slot-btn');
                    if (!btn) return;

                    // Highlight selection
                    timeSlotPopover.querySelectorAll('.time-slot-btn.selected').forEach(function(el) {
                        el.classList.remove('selected');
                    });
                    btn.classList.add('selected');

                    var startMin = parseInt(btn.dataset.minutes, 10);
                    var endMin = Math.min(startMin + SLOT_DURATION_MIN, SLOT_END_MIN);

                    scheduleTimeFrom.value = formatMinutesToAmPm(startMin);
                    scheduleTimeTo.value = formatMinutesToAmPm(endMin);

                    timeSlotPopover.classList.add('d-none');
                });

                // Close the popover when clicking outside it
                document.addEventListener('click', function(e) {
                    if (!timeSlotPopover.classList.contains('d-none') &&
                        !timeSlotPopover.contains(e.target) &&
                        e.target !== getDateTimeLink) {
                        timeSlotPopover.classList.add('d-none');
                    }
                });
            }

            // Default the Date field to today when the modal opens, if not already set
            var bookAppointmentModalEl = document.getElementById('bookAppointmentModal');
            if (bookAppointmentModalEl) {
                bookAppointmentModalEl.addEventListener('show.bs.modal', function() {
                    var dateInput = document.getElementById('scheduleDate');
                    if (dateInput && !dateInput.value) {
                        var today = new Date();
                        var yyyy = today.getFullYear();
                        var mm = String(today.getMonth() + 1).padStart(2, '0');
                        var dd = String(today.getDate()).padStart(2, '0');
                        dateInput.value = yyyy + '-' + mm + '-' + dd;
                    }
                });
            }
        });
    </script>
@endsection
