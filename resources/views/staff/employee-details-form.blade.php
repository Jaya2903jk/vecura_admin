@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
            <div>
                <h3 class="fw-bold mb-1">Employee Management</h3>
                <p class="text-muted mb-0">Create & manage employee records with comprehensive details</p>
            </div>
            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                <i class="ti ti-plus me-1"></i>Add New Employee
            </button>
        </div>

        {{-- ADD EMPLOYEE MODAL --}}
        <div class="modal fade" id="addEmployeeModal" tabindex="-1" size="lg">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="ti ti-users me-2"></i>Add New Employee</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <form id="addEmployeeForm">
                        @csrf
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            {{-- NAV TABS --}}
                            <ul class="nav nav-tabs mb-3" role="tablist">
                                <li class="nav-item"><a class="nav-link active" href="#basic-info" data-bs-toggle="tab">Basic Info</a></li>
                                <li class="nav-item"><a class="nav-link" href="#personal-details" data-bs-toggle="tab">Personal Details</a></li>
                                <li class="nav-item"><a class="nav-link" href="#employment-details" data-bs-toggle="tab">Employment</a></li>
                                <li class="nav-item"><a class="nav-link" href="#financial-info" data-bs-toggle="tab">Financial & ID</a></li>
                                <li class="nav-item"><a class="nav-link" href="#medical-info" data-bs-toggle="tab">Medical</a></li>
                            </ul>

                            {{-- TAB 1: BASIC INFO --}}
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="basic-info">
                                    <h6 class="fw-bold mb-3">Basic Information</h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control" placeholder="First name" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control" placeholder="Last name" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Employee Code</label>
                                            <input type="text" name="employee_code" class="form-control" placeholder="Auto-generated if empty">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Birth</label>
                                            <input type="date" name="date_of_birth" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" class="form-control" placeholder="employee@company.com">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Department <span class="text-danger">*</span></label>
                                            <select name="department_id" class="form-select" required>
                                                <option value="">-- Select Department --</option>
                                                @foreach($departments as $dept)
                                                    <option value="{{ $dept->Departmentid }}">{{ $dept->DepartmentName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                                            <select name="designation_code" class="form-select" required>
                                                <option value="">-- Select Designation --</option>
                                                @foreach($designations as $des)
                                                    <option value="{{ $des->DesignationCode }}">{{ $des->Designation }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Branch <span class="text-danger">*</span></label>
                                            <select name="branch_id" class="form-select" required>
                                                <option value="">-- Select Branch --</option>
                                                @foreach($branches as $branch)
                                                    <option value="{{ $branch->BranchID }}">{{ $branch->BranchName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB 2: PERSONAL DETAILS --}}
                                <div class="tab-pane fade" id="personal-details">
                                    <h6 class="fw-bold mb-3">Personal Information</h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Gender</label>
                                            <select name="gender" class="form-select">
                                                <option value="">-- Select Gender --</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Employee Category</label>
                                            <select name="employee_category" class="form-select">
                                                <option value="White Collar">White Collar</option>
                                                <option value="Blue Collar">Blue Collar</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="tel" name="phone" class="form-control" placeholder="10-digit phone">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Alternate Phone</label>
                                            <input type="tel" name="alternate_phone" class="form-control" placeholder="Alternate phone">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="address" class="form-control" rows="3" placeholder="Full address"></textarea>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control" placeholder="City">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control" placeholder="State">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Postal Code</label>
                                            <input type="text" name="postal_code" class="form-control" placeholder="Postal code">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Emergency Contact Name</label>
                                            <input type="text" name="emergency_contact_name" class="form-control" placeholder="Contact name">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Emergency Contact Phone</label>
                                            <input type="tel" name="emergency_contact_phone" class="form-control" placeholder="Contact phone">
                                        </div>
                                    </div>
                                </div>

                                {{-- TAB 3: EMPLOYMENT DETAILS --}}
                                <div class="tab-pane fade" id="employment-details">
                                    <h6 class="fw-bold mb-3">Employment Information</h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Employee Type</label>
                                            <select name="employee_type" class="form-select">
                                                <option value="Permanent">Permanent</option>
                                                <option value="Temporary">Temporary</option>
                                                <option value="Contract">Contract</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Date of Joining</label>
                                            <input type="date" name="date_of_joining" class="form-control">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Employee Status <span class="text-danger">*</span></label>
                                        <select name="employee_status" class="form-select" required>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                            <option value="On Leave">On Leave</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- TAB 4: FINANCIAL & ID INFO --}}
                                <div class="tab-pane fade" id="financial-info">
                                    <h6 class="fw-bold mb-3">Financial & ID Information</h6>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Aadhar Number</label>
                                            <input type="text" name="aadhar_number" class="form-control" placeholder="12-digit Aadhar">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">PAN Number</label>
                                            <input type="text" name="pan_number" class="form-control" placeholder="PAN number">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Bank Account Number</label>
                                            <input type="text" name="bank_account" class="form-control" placeholder="Bank account number">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">IFSC Code</label>
                                            <input type="text" name="ifsc_code" class="form-control" placeholder="IFSC code">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Blood Group</label>
                                        <select name="blood_group" class="form-select">
                                            <option value="">-- Select Blood Group --</option>
                                            <option value="A+">A+</option>
                                            <option value="A-">A-</option>
                                            <option value="B+">B+</option>
                                            <option value="B-">B-</option>
                                            <option value="AB+">AB+</option>
                                            <option value="AB-">AB-</option>
                                            <option value="O+">O+</option>
                                            <option value="O-">O-</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- TAB 5: MEDICAL INFO --}}
                                <div class="tab-pane fade" id="medical-info">
                                    <h6 class="fw-bold mb-3">Medical Information</h6>

                                    <div class="mb-3">
                                        <label class="form-label">Medical Conditions</label>
                                        <textarea name="medical_conditions" class="form-control" rows="3" placeholder="Any medical conditions or health issues"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Allergies</label>
                                        <textarea name="allergies" class="form-control" rows="3" placeholder="Any allergies (medicines, food, etc.)"></textarea>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="ti ti-info-circle me-2"></i>
                                        <strong>Note:</strong> Medical records can be updated later with certificates and detailed history.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>Create Employee
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // Add Employee Form Submit
    document.getElementById('addEmployeeForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('{{ route("staff.store") }}', {
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
        .catch(e => Swal.fire('Error', e.message, 'error'));
    });
</script>

@endsection
