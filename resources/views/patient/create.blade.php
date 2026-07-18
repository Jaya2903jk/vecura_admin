@extends('layout.mainlayout')

@section('content')
    <div class="page-wrapper">
        <div class="content">

            {{-- Page Header --}}
            <div class="d-flex align-items-sm-center flex-sm-row flex-column gap-2 mb-3 pb-3 border-bottom">
                <div class="flex-grow-1">
                    <h4 class="fw-bold mb-0">New Patient Registration</h4>
                </div>
                <div class="text-end d-flex gap-2">
                    <a href="{{ route('patient.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            {{-- Form Card --}}
            <div class="card border-0">
                <form id="patientForm">
                    @csrf
                    <div class="card-body">
                        <!-- SECTION 1: Registration & Basic Details -->
                        <div class="row mb-4 pb-3 border-bottom">
                            <div class="col-12">
                                <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-user me-2"></i>Registration & Basic
                                    Details</h6>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="RegistrationNo"
                                        class="form-label mb-1 text-dark fs-14 fw-medium">Registration Number <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="RegistrationNoDisplay" readonly
                                        value="Auto Generated" style="background-color: #f8f9fa;">
                                    <input type="hidden" id="RegistrationNo" name="RegistrationNo">
                                    <small class="text-muted d-block mt-1">Auto-generated</small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="Title" class="form-label mb-1 text-dark fs-14 fw-medium">Title <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="Title" name="Title">
                                        <option value="">Select</option>
                                        <option value="Mr" {{ old('Title') === 'Mr' ? 'selected' : '' }}>Mr</option>
                                        <option value="Mrs" {{ old('Title') === 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                        <option value="Ms" {{ old('Title') === 'Ms' ? 'selected' : '' }}>Ms</option>
                                        <option value="Dr" {{ old('Title') === 'Dr' ? 'selected' : '' }}>Dr</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="FirstName" class="form-label mb-1 text-dark fs-14 fw-medium">First Name
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="FirstName" name="FirstName"
                                        value="{{ old('FirstName') }}">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="LastName" class="form-label mb-1 text-dark fs-14 fw-medium">Last
                                        Name</label>
                                    <input type="text" class="form-control" id="LastName" name="LastName"
                                        value="{{ old('LastName') }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="Sex" class="form-label mb-1 text-dark fs-14 fw-medium">Gender <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="Sex" name="Sex">
                                        <option value="">Select</option>
                                        <option value="M" {{ old('Sex') === 'M' ? 'selected' : '' }}>Male</option>
                                        <option value="F" {{ old('Sex') === 'F' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('Sex') === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="DateOfBirth" class="form-label mb-1 text-dark fs-14 fw-medium">Date of
                                        Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="DateOfBirth" name="DateOfBirth"
                                        value="{{ old('DateOfBirth') }}">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="Age" class="form-label mb-1 text-dark fs-14 fw-medium">
                                        Age <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" class="form-control" id="Age" name="Age" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Address Information -->
                        <div class="row mb-4 pb-3 border-bottom">
                            <div class="col-12">
                                <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-map-pin me-2"></i>Address
                                    Information</h6>
                            </div>
                            <!-- Country -->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="Country" class="form-label mb-1 text-dark fs-14 fw-medium">Country <span
                                            class="text-danger">*</span></label>

                                    <select class="form-select" id="Country" name="Country">
                                        <option value="">-- Select Country --</option>
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->country_id }}"
                                                data-country-id="{{ $country->country_id }}"
                                                {{ old('Country') == $country->country_id ? 'selected' : '' }}>
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
                                    <label for="State" class="form-label mb-1 text-dark fs-14 fw-medium">State <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="State" name="State">
                                        <option value="">-- Select State --</option>
                                    </select>
                                </div>
                            </div>
                            <!-- City -->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="City" class="form-label mb-1 text-dark fs-14 fw-medium">City <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="City" name="City">
                                        <option value="">-- Select City --</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Branch -->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="branch_id" class="form-label mb-1 text-dark fs-14 fw-medium">Branch <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="branch_id" name="branch_id">
                                        <option value="">-- Select Branch --</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Area -->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="Area" class="form-label mb-1 text-dark fs-14 fw-medium">Area <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="Area" name="Area">
                                        <option value="">-- Select Area --</option>
                                        <option value="Downtown" {{ old('Area') === 'Downtown' ? 'selected' : '' }}>
                                            Downtown</option>
                                        <option value="Uptown" {{ old('Area') === 'Uptown' ? 'selected' : '' }}>Uptown
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Street -->
                            <div class="col-lg-5">
                                <div class="mb-3">
                                    <label for="Street" class="form-label mb-1 text-dark fs-14 fw-medium">Street /
                                        Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Street" name="Street"
                                        value="{{ old('Street') }}">
                                </div>
                            </div>
                            <!-- Pin Code -->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="PinCode" class="form-label mb-1 text-dark fs-14 fw-medium">Pin Code <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="PinCode" name="PinCode"
                                            value="{{ old('PinCode') }}">
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#pincodeModal">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Contact Information -->
                        <div class="row mb-4 pb-3 border-bottom">
                            <div class="col-12">
                                <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-phone me-2"></i>Contact Information
                                </h6>
                            </div>
                            <!-- Mobile -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Mobile" class="form-label mb-1 text-dark fs-14 fw-medium">Mobile <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="Mobile" name="Mobile"
                                        maxlength="10" inputmode="numeric" value="{{ old('Mobile') }}">
                                </div>
                            </div>
                            <!-- Whatsapp Mobile -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center gap-2">
                                        <label for="WhatsappMobile"
                                            class="form-label mb-0 text-dark fs-14 fw-medium">Whatsapp Mobile <span
                                                class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="sameAsMobile"
                                                name="sameAsMobile">
                                            <label class="form-check-label fs-12" for="sameAsMobile">Same as
                                                Mobile</label>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="WhatsappMobile" name="WhatsappMobile"
                                        maxlength="10" inputmode="numeric" value="{{ old('WhatsappMobile') }}">
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="EMail" class="form-label mb-1 text-dark fs-14 fw-medium">E-Mail</label>
                                    <input type="email" class="form-control" id="EMail" name="EMail"
                                        value="{{ old('EMail') }}">
                                </div>
                            </div>
                            <!-- Telephone (R) -->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="Res_Telephone" class="form-label mb-1 text-dark fs-14 fw-medium">Telephone
                                        (R)</label>
                                    <input type="text" class="form-control" id="Res_Telephone" name="Res_Telephone"
                                        value="{{ old('Res_Telephone') }}">
                                </div>
                            </div>
                            <!-- Telephone (O) -->
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="Off_Telephone" class="form-label mb-1 text-dark fs-14 fw-medium">Telephone
                                        (O)</label>
                                    <input type="text" class="form-control" id="Off_Telephone" name="Off_Telephone"
                                        value="{{ old('Off_Telephone') }}">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: Medical & Registration Details -->
                        <div class="row mb-4 pb-3 border-bottom">
                            <div class="col-12">
                                <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-stethoscope me-2"></i>Medical &
                                    Registration Details</h6>
                            </div>
                            <!-- Consultant -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Doctorname" class="form-label mb-1 text-dark fs-14 fw-medium">Consultant
                                        Name <span class="text-danger">*</span></label>
                                    <select class="form-select" id="Doctorname" name="Doctorname">
                                        <option value="">-- Select Consultant --</option>
                                        @foreach ($consultants as $consultant)
                                            {{-- UserCode empty-aana legacy records-ku UserID fallback —
                                                 illainaa option value empty aagi 'required' fail aagum --}}
                                            <option value="{{ $consultant->UserCode ?: $consultant->UserID }}"
                                                data-consultant-id="{{ $consultant->UserID }}"
                                                data-role="{{ $consultant->role_name }}">
                                                {{ $consultant->FullName }} - {{ $consultant->role_name }}
                                                ({{ $consultant->EmailId ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Reg. Type -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Type" class="form-label mb-1 text-dark fs-14 fw-medium">Reg. Type
                                        <span class="text-danger">*</span></label>
                                    <select class="form-select" id="Type" name="Type">
                                        <option value="">-- Select Type --</option>
                                        @foreach ($registrationTypes as $type)
                                            <option value="{{ $type->RegTypeId }}" {{ old('Type') === $type->RegType ? 'selected' : '' }}>
                                                {{ $type->RegType }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Registration Date -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="RegistrationDate"
                                        class="form-label mb-1 text-dark fs-14 fw-medium">Registration Date & Time</label>
                                    <input type="datetime-local" class="form-control" id="RegistrationDate"
                                        name="RegistrationDate" value="{{ old('RegistrationDate') }}">
                                </div>
                            </div>
                            <!-- Ref. By Customer -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Ref_By_Patient" class="form-label mb-1 text-dark fs-14 fw-medium">Ref. By
                                        Customer <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="Ref_By_Patient"
                                            name="Ref_By_Patient" value="{{ old('Ref_By_Patient') }}">
                                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="modal"
                                            data-bs-target="#refCustomerModal">
                                            <i class="ti ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Marital Status -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Marital_Status" class="form-label mb-1 text-dark fs-14 fw-medium">Marital
                                        Status</label>
                                    <select class="form-select" id="Marital_Status" name="Marital_Status">
                                        <option value="">-- Select --</option>
                                        <option value="Single" {{ old('Marital_Status') === 'Single' ? 'selected' : '' }}>
                                            Single</option>
                                        <option value="Married"
                                            {{ old('Marital_Status') === 'Married' ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced"
                                            {{ old('Marital_Status') === 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed"
                                            {{ old('Marital_Status') === 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Occupation -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Occupation" class="form-label mb-1 text-dark fs-14 fw-medium">Occupation
                                        <span class="text-danger">*</span></label>
                                    <select class="form-select" id="Occupation" name="Occupation">
                                        <option value="">-- Select Occupation --</option>
                                        @foreach ($occupations as $occupation)
                                            <option value="{{ $occupation->occupation_name }}" {{ old('Occupation') === $occupation->occupation_name ? 'selected' : '' }}>
                                                {{ $occupation->occupation_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Known By -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Knownby" class="form-label mb-1 text-dark fs-14 fw-medium">Known By <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="Knownby" name="Knownby">
                                        <option value="">-- Select --</option>
                                        @foreach ($knownBys as $knownby)
                                            <option value="{{ $knownby->Knwid }}" {{ old('Knownby') === $knownby->KwnBy ? 'selected' : '' }}>
                                                {{ $knownby->KwnBy }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <!-- Complaints -->
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label for="Complaints" class="form-label mb-1 text-dark fs-14 fw-medium">Chief
                                        Complaints / Medical Notes</label>
                                    <textarea class="form-control" id="Complaints" name="Complaints" rows="3">{{ old('Complaints') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: Preferences & Notifications -->
                        <div class="row mb-4 pb-3 border-bottom">
                            <div class="col-12">
                                <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-bell me-2"></i>Preferences &
                                    Notifications</h6>
                            </div>
                            <!-- SMS Alert -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="smsAlert" class="form-label mb-1 text-dark fs-14 fw-medium">SMS / Whatsapp
                                        Notification <span class="text-danger">*</span></label>
                                    <select class="form-select" id="smsAlert" name="smsAlert">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('smsAlert') === 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="No" {{ old('smsAlert') === 'No' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Email Alert -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="emailAlert" class="form-label mb-1 text-dark fs-14 fw-medium">Email
                                        Alert</label>
                                    <select class="form-select" id="emailAlert" name="emailAlert">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('emailAlert') === 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="No" {{ old('emailAlert') === 'No' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Whatsapp Available -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="Whatsapp" class="form-label mb-1 text-dark fs-14 fw-medium">Whatsapp
                                        Available</label>
                                    <select class="form-select" id="Whatsapp" name="Whatsapp">
                                        <option value="">Select</option>
                                        <option value="Yes" {{ old('Whatsapp') === 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="No" {{ old('Whatsapp') === 'No' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Online Consultation -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="OnlineConsultation"
                                        class="form-label mb-1 text-dark fs-14 fw-medium">Online Consultation</label>
                                    <select class="form-select" id="OnlineConsultation" name="OnlineConsultation">
                                        <option value="">Select</option>
                                        <option value="Yes"
                                            {{ old('OnlineConsultation') === 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ old('OnlineConsultation') === 'No' ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Mobile Checkbox -->
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="Mobile_Checkbox"
                                            name="Mobile_Checkbox" value="1"
                                            {{ old('Mobile_Checkbox') ? 'checked' : '' }}>
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
                                <h6 class="mb-3 text-primary fw-bold"><i class="ti ti-file-document me-2"></i>Additional
                                    Information</h6>
                            </div>
                            <!-- Special Discount -->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="SpecialDiscount" class="form-label mb-1 text-dark fs-14 fw-medium">Special
                                        Discount</label>
                                    <select class="form-select" id="SpecialDiscount" name="SpecialDiscount">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ old('SpecialDiscount') === 'Yes' ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="No" {{ old('SpecialDiscount') === 'No' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Google Review -->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="GoogleReview" class="form-label mb-1 text-dark fs-14 fw-medium">Google
                                        Review</label>
                                    <select class="form-select" id="GoogleReview" name="GoogleReview">
                                        <option value="">-- Select --</option>
                                        <option value="Yes" {{ old('GoogleReview') === 'Yes' ? 'selected' : '' }}>Yes
                                        </option>
                                        <option value="No" {{ old('GoogleReview') === 'No' ? 'selected' : '' }}>No
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <!-- Google Review Link -->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="GoogleReviewLink" class="form-label mb-1 text-dark fs-14 fw-medium">Google
                                        Review Link</label>
                                    <input type="url" class="form-control" id="GoogleReviewLink"
                                        name="GoogleReviewLink" value="{{ old('GoogleReviewLink') }}">
                                </div>
                            </div>
                            <!-- PAN Number -->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="PANNumber" class="form-label mb-1 text-dark fs-14 fw-medium">PAN
                                        Number</label>
                                    <input type="text" class="form-control" id="PANNumber" name="PANNumber"
                                        value="{{ old('PANNumber') }}">
                                </div>
                            </div>
                            <!-- Aadhar Number -->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="AadharNumber" class="form-label mb-1 text-dark fs-14 fw-medium">Aadhar
                                        Number</label>
                                    <input type="text" class="form-control" id="AadharNumber" name="AadharNumber"
                                        value="{{ old('AadharNumber') }}">
                                </div>
                            </div>
                            <!-- Date of Anniversary -->
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="Wedding_Day" class="form-label mb-1 text-dark fs-14 fw-medium">Date of
                                        Anniversary</label>
                                    <input type="date" class="form-control" id="Wedding_Day" name="Wedding_Day"
                                        value="{{ old('Wedding_Day') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light d-flex gap-2">
                        <button type="submit" id="submitBtn" class="btn btn-primary">
                            <i class="ti ti-save me-1"></i> Save
                        </button>
                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#appointmentModal">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script>
        /* ============================================================
         * SweetAlert2 Toast Helpers
         * ============================================================ */

        // Base toast mixin (top-right, auto close)
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (t) => {
                t.addEventListener('mouseenter', Swal.stopTimer);
                t.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });

        // Simple toast: toast('success', 'Saved!') / toast('error', 'Failed')
        function toast(icon, message) {
            Toast.fire({
                icon: icon,
                title: message
            });
        }

        /*
         * Show EACH Laravel 422 validation error as its OWN stacked toast (top-right).
         * Bootstrap 5 toasts — stack natively, individually dismissible.
         * Fields also get red highlight + inline message + scroll to first error.
         */
        function showErrors(formPrefix, errors) {
            let firstField = null;

            // Create/reuse stacked toast container (top-right)
            let $container = $('#validationToastContainer');
            if (!$container.length) {
                $container = $('<div id="validationToastContainer" ' +
                    'class="toast-container position-fixed top-0 end-0 p-3" ' +
                    'style="z-index: 10000; max-height: 100vh; overflow-y: auto;"></div>');
                $('body').append($container);
            }
            $container.empty(); // clear previous errors

            Object.keys(errors).forEach(function(field, index) {
                const $input = $('[name="' + field + '"]');
                const message = errors[field][0];

                // ---- One toast per error ----
                const toastEl = $(
                    '<div class="toast align-items-center text-bg-danger border-0 mb-2" role="alert">' +
                    '<div class="d-flex">' +
                    '<div class="toast-body">' +
                    '<i class="ti ti-alert-circle me-1"></i>' + message +
                    '</div>' +
                    '<button type="button" class="btn-close btn-close-white me-2 m-auto" ' +
                    'data-bs-dismiss="toast"></button>' +
                    '</div>' +
                    '</div>'
                );
                $container.append(toastEl);

                // Staggered auto-hide: first stays 8s, each next +0.4s so they
                // don't all vanish at once. User can close any manually.
                const bsToast = new bootstrap.Toast(toastEl[0], {
                    autohide: true,
                    delay: 8000 + (index * 400)
                });
                bsToast.show();

                // ---- Field highlight + inline message ----
                if ($input.length) {
                    $input.addClass('is-invalid');

                    let $wrapper = $input.closest('.mb-3');
                    let $feedback = $wrapper.find('.invalid-feedback');
                    if (!$feedback.length) {
                        $feedback = $('<div class="invalid-feedback"></div>');
                        if ($input.parent().hasClass('input-group')) {
                            $input.parent().after($feedback);
                        } else {
                            $input.after($feedback);
                        }
                    }
                    $feedback.html(message).addClass('d-block');

                    if (!firstField) firstField = $input;
                }
            });

            // Scroll + focus first error field
            if (firstField) {
                $('html, body').animate({
                    scrollTop: firstField.offset().top - 120
                }, 400);
                firstField.trigger('focus');
            }
        }

        /* ============================================================
         * Registration Number
         * ============================================================ */
        function generateRegistrationNumber(branchId = null) {
            const branch = branchId || document.getElementById('branch_id')?.value;
            if (!branch) {
                document.getElementById('RegistrationNoDisplay').value = 'Auto Generated';
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

        /* ============================================================
         * Auto-calculate age from DOB
         * ============================================================ */
        document.getElementById('DateOfBirth')?.addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            if (age >= 0 && age < 150) {
                document.getElementById('Age').value = age;
            } else {
                document.getElementById('Age').value = '';
                toast('warning', 'Please select a valid date of birth.');
            }
        });

        /* ============================================================
         * Whatsapp = Mobile copy
         * ============================================================ */
        document.getElementById('sameAsMobile')?.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('WhatsappMobile').value = document.getElementById('Mobile').value;
            } else {
                document.getElementById('WhatsappMobile').value = '';
            }
        });

        document.getElementById('Mobile')?.addEventListener('input', function() {
            if (document.getElementById('sameAsMobile').checked) {
                document.getElementById('WhatsappMobile').value = this.value;
            }
        });

        /* ============================================================
         * Cascading dropdowns: Country -> Zone/State -> City -> Branch
         * ============================================================ */
        document.getElementById('Country')?.addEventListener('change', function() {
            const countryId = this.options[this.selectedIndex]?.dataset.countryId;
            const stateSelect = document.getElementById('State');
            const zoneSelect = document.getElementById('Zone');
            const citySelect = document.getElementById('City');
            const branchSelect = document.getElementById('branch_id');

            stateSelect.innerHTML = '<option value="">-- Select State --</option>';
            zoneSelect.innerHTML = '<option value="">-- Select Zone --</option>';
            citySelect.innerHTML = '<option value="">-- Select City --</option>';
            branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
            generateRegistrationNumber();

            if (!countryId) return;

            // Load States
            fetch('{{ route('patient.get-states') }}?country_id=' + countryId, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.states) {
                        data.states.forEach(state => {
                            const opt = document.createElement('option');
                            // opt.value = state.state_name;
                            opt.value = state.state_id;
                            opt.dataset.stateId = state.state_id;
                            opt.textContent = state.state_name;
                            stateSelect.appendChild(opt);
                        });
                    }
                })
                .catch(err => console.error('Error loading states:', err));

            // Load Zones
            fetch('{{ route('patient.get-zones') }}?country_id=' + countryId, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
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
            generateRegistrationNumber();

            if (!stateId) return;

            fetch('{{ route('patient.get-cities') }}?state_id=' + stateId, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.cities) {
                        data.cities.forEach(city => {
                            const opt = document.createElement('option');
                            // opt.value = city.city_name;
                            opt.value = city.city_id;

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
            generateRegistrationNumber();

            if (!cityId) return;

            fetch('{{ route('patient.get-branches') }}?city_id=' + cityId, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
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
                        branchSelect.innerHTML +=
                            '<option disabled>No branches available for this city</option>';
                        toast('warning', 'No branches available for this city.');
                    }
                })
                .catch(err => console.error('Error loading branches:', err));
        });

        /* ============================================================
         * Loading overlay
         * ============================================================ */
        const loadingHTML = `
        <div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 align-items-center justify-content-center d-none" style="z-index: 9999; backdrop-filter: blur(3px);">
            <div class="text-center text-white">
                <div class="spinner-border mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5>Processing...</h5>
                <p class="small mb-0">Please wait while we save your information</p>
            </div>
        </div>`;

        /* ============================================================
         * AJAX Form Submission
         * ============================================================ */
        $(document).ready(function() {
            $('body').append(loadingHTML);

            let isSubmitting = false;
            const $form = $('#patientForm');
            const $submitBtn = $('#submitBtn');
            const $overlay = $('#loadingOverlay');

            function showLoading() {
                $overlay.removeClass('d-none').addClass('d-flex');
                $submitBtn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');
            }

            function hideLoading() {
                $overlay.removeClass('d-flex').addClass('d-none');
                $submitBtn.prop('disabled', false)
                    .html('<i class="ti ti-save me-1"></i> Save');
                $form.find(':input').prop('disabled', false);
            }

            $form.on('submit', function(e) {
                e.preventDefault();

                if (isSubmitting) return false;

                // Client-side quick check: mobile 10 digits (only if filled;
                // empty case is handled by server 422 with all other errors together)
                const mobile = $('#Mobile').val().trim();
                const waMobile = $('#WhatsappMobile').val().trim();
                if (mobile && !/^\d{10}$/.test(mobile)) {
                    $('#Mobile').addClass('is-invalid');
                    toast('error', 'Mobile number must be exactly 10 digits.');
                    return false;
                }
                if (waMobile && !/^\d{10}$/.test(waMobile)) {
                    $('#WhatsappMobile').addClass('is-invalid');
                    toast('error', 'Whatsapp mobile must be exactly 10 digits.');
                    return false;
                }

                isSubmitting = true;

                // IMPORTANT: serialize BEFORE disabling inputs
                const formData = $form.serialize();

                showLoading();
                $form.find(':input').not($submitBtn).prop('disabled', true);

                $.ajax({
                    url: '{{ route('patient.store') }}',
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
                            toast('error', response.message ||
                                'Something went wrong. Please try again.');
                        }
                    },
                    error: function(xhr) {
                        isSubmitting = false;
                        hideLoading();

                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Each validation error -> its own stacked toast + field highlights
                            showErrors('patient', xhr.responseJSON.errors);
                        } else if (xhr.status === 419) {
                            toast('error',
                                'Session expired. Please refresh the page and try again.');
                        } else {
                            let errorMessage = xhr.responseJSON && xhr.responseJSON.message ?
                                xhr.responseJSON.message :
                                'Something went wrong! Please try again.';
                            toast('error', errorMessage);
                        }
                    }
                });
            });

            // Clear field error on change/input
            $form.on('change input', ':input', function() {
                $(this).removeClass('is-invalid');
                $(this).closest('.mb-3').find('.invalid-feedback').html('').removeClass('d-block');
            });
        });
    </script>
@endsection
