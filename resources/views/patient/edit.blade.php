@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Page Header --}}
        <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
            <div class="flex-grow-1">
                <h4 class="fw-bold mb-0">Edit Patient - <span class="text-primary">{{ $patient->FirstName }} {{ $patient->LastName }}</span></h4>
            </div>
            <div class="text-end d-flex gap-2">
                <a href="{{ route('patient.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back
                </a>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="card border-0">
            <div class="card-header bg-light">
                <h5 class="mb-0">Patient Information</h5>
            </div>
            <form id="patientForm">
                @csrf

                <div class="card-body">
                    <!-- SECTION 1: Registration & Basic Details -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-user me-2"></i>Registration & Basic Details</h6>
                        </div>
                        <!-- Branch Selection -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label mb-1 text-dark fs-14 fw-medium">Branch</label>
                                <select class="form-select" id="branch_id"
                                    name="branch_id">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->branch_id }}" {{ old('branch_id', $patient->branch_id ?? '') == $branch->branch_id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }} ({{ $branch->branch_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Registration Number -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="RegistrationNo" class="form-label mb-1 text-dark fs-14 fw-medium">Registration Number</label>
                                <input type="text" class="form-control" id="RegistrationNoDisplay" readonly value="{{ $patient->RegistrationNo }}" style="background-color: #f8f9fa;">
                                <input type="hidden" id="RegistrationNo" name="RegistrationNo" value="{{ $patient->RegistrationNo }}">
                            </div>
                        </div>
                        <!-- Patient ID -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="PatientID" class="form-label mb-1 text-dark fs-14 fw-medium">Patient ID</label>
                                <input type="text" class="form-control" id="PatientID" disabled value="{{ $patient->PatientID }}" style="background-color: #f8f9fa;">
                            </div>
                        </div>
                        <!-- Title + First Name Row -->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="Title" class="form-label mb-1 text-dark fs-14 fw-medium">Title <span class="text-danger">*</span></label>
                                <select class="form-select" id="Title" name="Title" required>
                                    <option value="">Select</option>
                                    <option value="Mr" {{ old('Title', $patient->Title) === 'Mr' ? 'selected' : '' }}>Mr</option>
                                    <option value="Mrs" {{ old('Title', $patient->Title) === 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                    <option value="Ms" {{ old('Title', $patient->Title) === 'Ms' ? 'selected' : '' }}>Ms</option>
                                    <option value="Dr" {{ old('Title', $patient->Title) === 'Dr' ? 'selected' : '' }}>Dr</option>
                                </select>
                            </div>
                        </div>
                        <!-- First Name -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="FirstName" class="form-label mb-1 text-dark fs-14 fw-medium">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                       id="FirstName" name="FirstName" value="{{ old('FirstName', $patient->FirstName) }}" required>
                            </div>
                        </div>
                        <!-- Gender -->
                        <div class="col-lg-2">
                            <div class="mb-3">
                                <label for="Sex" class="form-label mb-1 text-dark fs-14 fw-medium">Gender <span class="text-danger">*</span></label>
                                <select class="form-select" id="Sex" name="Sex" required>
                                    <option value="">Select</option>
                                    <option value="M" {{ old('Sex', $patient->Sex) === 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('Sex', $patient->Sex) === 'F' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('Sex', $patient->Sex) === 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                        <!-- Last Name -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="LastName" class="form-label mb-1 text-dark fs-14 fw-medium">Last Name</label>
                                <input type="text" class="form-control"
                                       id="LastName" name="LastName" value="{{ old('LastName', $patient->LastName) }}">
                            </div>
                        </div>
                        <!-- Date of Birth -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="DateOfBirth" class="form-label mb-1 text-dark fs-14 fw-medium">Date of Birth <span class="text-danger">*</span></label>
                                <input type="date" class="form-control"
                                       id="DateOfBirth" name="DateOfBirth" value="{{ old('DateOfBirth', $patient->DateOfBirth) }}" required>
                            </div>
                        </div>
                        <!-- Age -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Age" class="form-label mb-1 text-dark fs-14 fw-medium">Age <span class="text-danger">*</span></label>
                                <input type="number" class="form-control"
                                       id="Age" name="Age" value="{{ old('Age', $patient->Age) }}" readonly style="background-color: #f8f9fa;" required>
                                <small class="text-muted d-block mt-1">Auto-calculated from DOB</small>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 2: Contact Information -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-phone me-2"></i>Contact Information</h6>
                        </div>
                        <!-- Mobile -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Mobile" class="form-label mb-1 text-dark fs-14 fw-medium">Mobile <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                       id="Mobile" name="Mobile" value="{{ old('Mobile', $patient->Mobile) }}" required>
                            </div>
                        </div>
                        <!-- Whatsapp Mobile -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="WhatsappMobile" class="form-label mb-0 text-dark fs-14 fw-medium">Whatsapp Mobile <span class="text-danger">*</span></label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="sameAsMobile" name="sameAsMobile">
                                        <label class="form-check-label fs-12" for="sameAsMobile">Same as Mobile</label>
                                    </div>
                                </div>
                                <input type="text" class="form-control"
                                       id="WhatsappMobile" name="WhatsappMobile" value="{{ old('WhatsappMobile', $patient->WhatsappMobile) }}" required>
                            </div>
                        </div>
                        <!-- Email -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="EMail" class="form-label mb-1 text-dark fs-14 fw-medium">E-Mail</label>
                                <input type="email" class="form-control"
                                       id="EMail" name="EMail" value="{{ old('EMail', $patient->EMail) }}">
                            </div>
                        </div>
                        <!-- Telephone (R) -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="Res_Telephone" class="form-label mb-1 text-dark fs-14 fw-medium">Telephone (R)</label>
                                <input type="text" class="form-control"
                                       id="Res_Telephone" name="Res_Telephone" value="{{ old('Res_Telephone', $patient->Res_Telephone) }}">
                            </div>
                        </div>
                        <!-- Telephone (O) -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="Off_Telephone" class="form-label mb-1 text-dark fs-14 fw-medium">Telephone (O)</label>
                                <input type="text" class="form-control"
                                       id="Off_Telephone" name="Off_Telephone" value="{{ old('Off_Telephone', $patient->Off_Telephone) }}">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 3: Address Information -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-map-pin me-2"></i>Address Information</h6>
                        </div>
                        <!-- Country -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="Country" class="form-label mb-1 text-dark fs-14 fw-medium">Country <span class="text-danger">*</span></label>
                                <select class="form-select" id="Country" name="Country" required>
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->country_name }}" data-country-id="{{ $country->country_id }}" {{ old('Country', $patient->Country) === $country->country_name ? 'selected' : '' }}>
                                            {{ $country->country_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Zone -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="Zone" class="form-label mb-1 text-dark fs-14 fw-medium">Zone</label>
                                <select class="form-select" id="Zone" name="Zone">
                                    <option value="">-- Select Zone --</option>
                                </select>
                            </div>
                        </div>
                        <!-- State -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="State" class="form-label mb-1 text-dark fs-14 fw-medium">State <span class="text-danger">*</span></label>
                                <select class="form-select" id="State" name="State" required>
                                    <option value="">-- Select State --</option>
                                </select>
                            </div>
                        </div>
                        <!-- City -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="City" class="form-label mb-1 text-dark fs-14 fw-medium">City <span class="text-danger">*</span></label>
                                <select class="form-select" id="City" name="City" required>
                                    <option value="">-- Select City --</option>
                                </select>
                            </div>
                        </div>
                        <!-- Branch -->
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label for="branch_id" class="form-label mb-1 text-dark fs-14 fw-medium">Branch <span class="text-danger">*</span></label>
                                <select class="form-select" id="branch_id" name="branch_id" required>
                                    <option value="">-- Select Branch --</option>
                                </select>
                            </div>
                        </div>
                        <!-- Area -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="Area" class="form-label mb-1 text-dark fs-14 fw-medium">Area <span class="text-danger">*</span></label>
                                <select class="form-select" id="Area" name="Area" required>
                                    <option value="">-- Select Area --</option>
                                    <option value="Downtown" {{ old('Area', $patient->Area) === 'Downtown' ? 'selected' : '' }}>Downtown</option>
                                    <option value="Uptown" {{ old('Area', $patient->Area) === 'Uptown' ? 'selected' : '' }}>Uptown</option>
                                </select>
                            </div>
                        </div>
                        <!-- Street -->
                        <div class="col-lg-8">
                            <div class="mb-3">
                                <label for="Street" class="form-label mb-1 text-dark fs-14 fw-medium">Street / Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                       id="Street" name="Street" value="{{ old('Street', $patient->Street) }}" required>
                            </div>
                        </div>
                        <!-- Pin Code -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="PinCode" class="form-label mb-1 text-dark fs-14 fw-medium">Pin Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                       id="PinCode" name="PinCode" value="{{ old('PinCode', $patient->PinCode) }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 4: Medical & Registration Details -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-stethoscope me-2"></i>Medical & Registration Details</h6>
                        </div>
                        <!-- Consultant -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Doctorname" class="form-label mb-1 text-dark fs-14 fw-medium">Consultant Name <span class="text-danger">*</span></label>
                                <select class="form-select" id="Doctorname" name="Doctorname" required>
                                    <option value="">-- Select Consultant --</option>
                                    @foreach($consultants as $consultant)
                                        <option value="{{ $consultant->FullName }}" data-consultant-id="{{ $consultant->UserID }}" data-role="{{ $consultant->role_name }}" {{ old('Doctorname', $patient->Doctorname) === $consultant->FullName ? 'selected' : '' }}>
                                            {{ $consultant->FullName }} - {{ $consultant->role_name }} ({{ $consultant->EmailId ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Reg. Type -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Type" class="form-label mb-1 text-dark fs-14 fw-medium">Reg. Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="Type" name="Type" required>
                                    <option value="">-- Select Type --</option>
                                    @foreach ($registrationTypes as $type)
                                        <option value="{{ $type->RegType }}" {{ old('Type', $patient->Type) === $type->RegType ? 'selected' : '' }}>
                                            {{ $type->RegType }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Ref. By Customer -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Ref_By_Patient" class="form-label mb-1 text-dark fs-14 fw-medium">Ref. By Customer <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                           id="Ref_By_Patient" name="Ref_By_Patient" value="{{ old('Ref_By_Patient', $patient->Ref_By_Patient) }}" required>
                                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal" data-bs-target="#refCustomerModal">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- Marital Status -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Marital_Status" class="form-label mb-1 text-dark fs-14 fw-medium">Marital Status</label>
                                <select class="form-select" id="Marital_Status" name="Marital_Status">
                                    <option value="">-- Select --</option>
                                    <option value="Single" {{ old('Marital_Status', $patient->Marital_Status) === 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ old('Marital_Status', $patient->Marital_Status) === 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ old('Marital_Status', $patient->Marital_Status) === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ old('Marital_Status', $patient->Marital_Status) === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                        </div>
                        <!-- Occupation -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Occupation" class="form-label mb-1 text-dark fs-14 fw-medium">Occupation <span class="text-danger">*</span></label>
                                <select class="form-select" id="Occupation" name="Occupation" required>
                                    <option value="">-- Select Occupation --</option>
                                    @foreach ($occupations as $occupation)
                                        <option value="{{ $occupation->occupation_name }}" {{ old('Occupation', $patient->Occupation) === $occupation->occupation_name ? 'selected' : '' }}>
                                            {{ $occupation->occupation_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Known By -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Knownby" class="form-label mb-1 text-dark fs-14 fw-medium">Known By <span class="text-danger">*</span></label>
                                <select class="form-select" id="Knownby" name="Knownby" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($knownBys as $knownby)
                                        <option value="{{ $knownby->KwnBy }}" {{ old('Knownby', $patient->Knownby) === $knownby->KwnBy ? 'selected' : '' }}>
                                            {{ $knownby->KwnBy }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Complaints -->
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="Complaints" class="form-label mb-1 text-dark fs-14 fw-medium">Chief Complaints / Medical Notes</label>
                                <textarea class="form-control"
                                          id="Complaints" name="Complaints" rows="3">{{ old('Complaints', $patient->Complaints) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 5: Preferences & Notifications -->
                    <div class="row mb-4 pb-3 border-bottom">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-bell me-2"></i>Preferences & Notifications</h6>
                        </div>
                        <!-- SMS Alert -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="smsAlert" class="form-label mb-1 text-dark fs-14 fw-medium">SMS / Whatsapp Notification <span class="text-danger">*</span></label>
                                <select class="form-select" id="smsAlert" name="smsAlert" required>
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('smsAlert', $patient->smsAlert) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('smsAlert', $patient->smsAlert) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Email Alert -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="emailAlert" class="form-label mb-1 text-dark fs-14 fw-medium">Email Alert</label>
                                <select class="form-select" id="emailAlert" name="emailAlert">
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('emailAlert', $patient->emailAlert) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('emailAlert', $patient->emailAlert) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Whatsapp Available -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="Whatsapp" class="form-label mb-1 text-dark fs-14 fw-medium">Whatsapp Available</label>
                                <select class="form-select" id="Whatsapp" name="Whatsapp">
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('Whatsapp', $patient->Whatsapp) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('Whatsapp', $patient->Whatsapp) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Online Consultation -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="OnlineConsultation" class="form-label mb-1 text-dark fs-14 fw-medium">Online Consultation</label>
                                <select class="form-select" id="OnlineConsultation" name="OnlineConsultation">
                                    <option value="">Select</option>
                                    <option value="Yes" {{ old('OnlineConsultation', $patient->OnlineConsultation) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('OnlineConsultation', $patient->OnlineConsultation) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Mobile Checkbox -->
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="Mobile_Checkbox" name="Mobile_Checkbox" {{ old('Mobile_Checkbox', $patient->Mobile_Checkbox) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="Mobile_Checkbox">
                                        Mobile (Primary Contact)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SECTION 6: Additional Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-file-document me-2"></i>Additional Information</h6>
                        </div>
                        <!-- Special Discount -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="SpecialDiscount" class="form-label mb-1 text-dark fs-14 fw-medium">Special Discount</label>
                                <select class="form-select" id="SpecialDiscount" name="SpecialDiscount">
                                    <option value="">-- Select --</option>
                                    <option value="Yes" {{ old('SpecialDiscount', $patient->SpecialDiscount) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('SpecialDiscount', $patient->SpecialDiscount) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Google Review -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="GoogleReview" class="form-label mb-1 text-dark fs-14 fw-medium">Google Review</label>
                                <select class="form-select" id="GoogleReview" name="GoogleReview">
                                    <option value="">-- Select --</option>
                                    <option value="Yes" {{ old('GoogleReview', $patient->GoogleReview) === 'Yes' ? 'selected' : '' }}>Yes</option>
                                    <option value="No" {{ old('GoogleReview', $patient->GoogleReview) === 'No' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>
                        </div>
                        <!-- Google Review Link -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="GoogleReviewLink" class="form-label mb-1 text-dark fs-14 fw-medium">Google Review Link</label>
                                <input type="url" class="form-control"
                                       id="GoogleReviewLink" name="GoogleReviewLink" value="{{ old('GoogleReviewLink', $patient->GoogleReviewLink) }}">
                            </div>
                        </div>
                        <!-- PAN Number -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="PANNumber" class="form-label mb-1 text-dark fs-14 fw-medium">PAN Number</label>
                                <input type="text" class="form-control"
                                       id="PANNumber" name="PANNumber" value="{{ old('PANNumber', $patient->PANNumber) }}">
                            </div>
                        </div>
                        <!-- Aadhar Number -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="AadharNumber" class="form-label mb-1 text-dark fs-14 fw-medium">Aadhar Number</label>
                                <input type="text" class="form-control"
                                       id="AadharNumber" name="AadharNumber" value="{{ old('AadharNumber', $patient->AadharNumber) }}">
                            </div>
                        </div>
                        <!-- Date of Anniversary -->
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="Wedding_Day" class="form-label mb-1 text-dark fs-14 fw-medium">Date of Anniversary</label>
                                <input type="date" class="form-control"
                                       id="Wedding_Day" name="Wedding_Day" value="{{ old('Wedding_Day', $patient->Wedding_Day) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex gap-2">
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <i class="ti ti-save me-1"></i> Update
                    </button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#appointmentModal">
                        <i class="ti ti-calendar me-1"></i> Get Appointment
                    </button>
                    <a href="{{ route('patient.index') }}" class="btn btn-secondary ms-auto">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modals -->
{{-- @include('patient.modals.forms_modals') --}}

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Auto-calculate age from date of birth
document.getElementById('DateOfBirth')?.addEventListener('change', function() {
    const dob = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const monthDiff = today.getMonth() - dob.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
        age--;
    }

    if (age > 0 && age < 150) {
        document.getElementById('Age').value = age;
    }
});

// Generate Registration Number based on branch
function generateRegistrationNumber(branchId = null) {
    const branch = branchId || document.getElementById('branch_id')?.value;
    if (!branch) {
        document.getElementById('RegistrationNo').value = '';
        return;
    }
    fetch('{{ route('patient.generate-registration-number') }}?branch_id=' + branch, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.registration_number) {
                document.getElementById('RegistrationNoDisplay').value = data.registration_number;
                document.getElementById('RegistrationNo').value = data.registration_number;
            }
        })
        .catch(error => console.error('Error generating registration number:', error));
}

// Branch change listener
document.getElementById('branch_id')?.addEventListener('change', function() {
    generateRegistrationNumber(this.value);
});

// Auto-copy Mobile to Whatsapp Mobile
document.getElementById('sameAsMobile')?.addEventListener('change', function() {
    if (this.checked) {
        const mobileValue = document.getElementById('Mobile').value;
        document.getElementById('WhatsappMobile').value = mobileValue;
    } else {
        document.getElementById('WhatsappMobile').value = '';
    }
});

// Update Whatsapp Mobile when Mobile field changes (if checkbox is checked)
document.getElementById('Mobile')?.addEventListener('change', function() {
    if (document.getElementById('sameAsMobile').checked) {
        document.getElementById('WhatsappMobile').value = this.value;
    }
});

// Load states and zones when country changes
document.getElementById('Country')?.addEventListener('change', function() {
    const countryId = this.options[this.selectedIndex]?.dataset.countryId;
    const stateSelect = document.getElementById('State');
    const zoneSelect = document.getElementById('Zone');
    const citySelect = document.getElementById('City');

    stateSelect.innerHTML = '<option value="">-- Select State --</option>';
    zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
    citySelect.innerHTML = '<option value="">-- Select City --</option>';

    if (!countryId) return;

    // Load States
    fetch('{{ route('patient.get-states') }}?country_id=' + countryId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.states) {
            data.states.forEach(state => {
                const opt = document.createElement('option');
                opt.value = state.state_name;
                opt.dataset.stateId = state.state_id;
                opt.textContent = state.state_name;
                stateSelect.appendChild(opt);
            });
        }
    })
    .catch(err => console.error('Error loading states:', err));

    // Load Zones
    fetch('{{ route('patient.get-zones') }}?country_id=' + countryId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.zones) {
            data.zones.forEach(zone => {
                const opt = document.createElement('option');
                opt.value = zone.zone_name;
                opt.textContent = zone.zone_name;
                zoneSelect.appendChild(opt);
            });
        }
    })
    .catch(err => console.error('Error loading zones:', err));
});

// Load cities when state changes
document.getElementById('State')?.addEventListener('change', function() {
    const stateId = this.options[this.selectedIndex]?.dataset.stateId;
    const citySelect = document.getElementById('City');
    const branchSelect = document.getElementById('branch_id');

    citySelect.innerHTML = '<option value="">-- Select City --</option>';
    branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';

    if (!stateId) return;

    fetch('{{ route('patient.get-cities') }}?state_id=' + stateId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.cities) {
            data.cities.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city.city_name;
                opt.dataset.cityId = city.city_id;
                opt.textContent = city.city_name;
                citySelect.appendChild(opt);
            });
        }
    })
    .catch(err => console.error('Error loading cities:', err));
});

// Load branches when city changes
document.getElementById('City')?.addEventListener('change', function() {
    const cityId = this.options[this.selectedIndex]?.dataset.cityId;
    const branchSelect = document.getElementById('branch_id');

    branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';

    if (!cityId) return;

    fetch('{{ route('patient.get-branches') }}?city_id=' + cityId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.branches && data.branches.length > 0) {
            data.branches.forEach(branch => {
                const opt = document.createElement('option');
                opt.value = branch.branch_id;
                opt.textContent = branch.branch_name + ' (' + branch.branch_code + ')';
                branchSelect.appendChild(opt);
            });
        } else {
            branchSelect.innerHTML += '<option disabled>No branches available for this city</option>';
        }
    })
    .catch(err => console.error('Error loading branches:', err));
});

// Loading overlay HTML
const loadingHTML = `
<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 align-items-center justify-content-center d-none" style="z-index: 9999; backdrop-filter: blur(3px);">
    <div class="text-center text-white">
        <div class="spinner-border mb-3" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h5>Processing...</h5>
        <p class="small mb-0">Please wait while we update your information</p>
    </div>
</div>`;

// AJAX Form Submission with Loading, Validation & Double Submit Prevention
$(document).ready(function() {
    $('body').append(loadingHTML);

    let isSubmitting = false;
    const $form = $('#patientForm');
    const $submitBtn = $('#submitBtn');
    const $overlay = $('#loadingOverlay');
    const patientID = '{{ $patient->PatientID }}';

    function showLoading() {
        $overlay.removeClass('d-none').addClass('d-flex');
        $submitBtn.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...');
    }

    function hideLoading() {
        $overlay.removeClass('d-flex').addClass('d-none');
        $submitBtn.prop('disabled', false)
            .html('<i class="ti ti-save me-1"></i> Update');
        $form.find(':input').prop('disabled', false);
    }

    function showAlert(type, icon, message) {
        $form.find('.alert').remove();
        $form.prepend(
            '<div class="alert alert-' + type + ' alert-dismissible fade show m-3" role="alert">' +
            '<i class="ti ti-' + icon + ' me-2"></i>' + message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>'
        );
        $('html, body').animate({ scrollTop: 0 }, 300);
    }

    $form.on('submit', function(e) {
        e.preventDefault();

        // Prevent double submission
        if (isSubmitting) return false;

        isSubmitting = true;

        // IMPORTANT: serialize BEFORE disabling inputs
        const formData = $form.serialize() + '&_method=PUT';

        showLoading();
        $form.find(':input').not($submitBtn).prop('disabled', true);

        $.ajax({
            url: '{{ route('patient.update', $patient->PatientID) }}',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (response.success) {
                    $overlay.removeClass('d-flex').addClass('d-none');
                    toast('success', response.message);
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1600);
                } else {
                    isSubmitting = false;
                    hideLoading();
                    toast('error', response.message || 'Something went wrong. Please try again.');
                }
            },
            error: function(xhr) {
                isSubmitting = false;
                hideLoading();

                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    showErrors('patient', xhr.responseJSON.errors);
                } else {
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message
                        ? xhr.responseJSON.message
                        : 'Something went wrong! Please try again.';
                    toast('error', errorMessage);
                }
            }
        });
    });

    // Clear field error on change
    $form.on('change input', ':input', function() {
        $(this).removeClass('is-invalid');
        $(this).closest('.mb-3').find('.invalid-feedback').html('').removeClass('d-block');
    });
});
</script>
@endsection
