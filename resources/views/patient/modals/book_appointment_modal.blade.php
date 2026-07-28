{{-- Book Appointment Modal --}}
<div id="bookAppointmentModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-light">
                <h5 class="text-dark modal-title fw-bold">
                    <i class="ti ti-calendar-plus me-2 text-primary"></i>Book Scheduling for {{ $patient->FirstName ?? 'Patient' }}
                </h5>
                <button type="button" class="btn-close btn-close-modal custom-btn-close" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="ti ti-x"></i>
                </button>
            </div>
            <form id="bookAppointmentForm"
                action="{{ route('patient.appointments.store', ['id' => $patient->PatientID]) }}"
                method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div id="bookAppointmentError" class="alert alert-danger fs-13 py-2 d-none"></div>

                    <div class="row g-3">
                        {{-- Scheduling For --}}
                        <div class="col-md-12">
                            <label class="form-label fw-semibold fs-13">Scheduling For<span class="text-danger ms-1">*</span></label>
                            <select class="form-select" name="appointment_for" id="appointmentForSelect" required>
                                <option value="" selected disabled>Select any one</option>
                                @foreach ($appointment_for_options ?? [] as $option)
                                    <option value="{{ $option->AppointtCode }}">{{ $option->AppointName }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Customer Name & Registration No --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Customer Name</label>
                            <input type="text" class="form-control bg-light" value="{{ $patient->FirstName ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold fs-13">Registration No</label>
                            <input type="text" class="form-control bg-light" value="{{ $patient->RegistrationNo ?? '' }}" readonly>
                        </div>

                        {{-- Date Selection (Shown when Consultation option is selected) --}}
                        <div class="col-md-6 d-none" id="scheduleDateGroup">
                            <label class="form-label fw-semibold fs-13">Date<span class="text-danger ms-1">*</span></label>
                            <input type="date" class="form-control" name="schedule_date" id="scheduleDate">
                        </div>

                        {{-- Wellness Expert / Consultant (Shown when Consultation option is selected) --}}
                        <div class="col-md-6 d-none" id="consultantSelectGroup">
                            <label class="form-label fw-semibold fs-13">Wellness Expert / Consultant<span class="text-danger ms-1">*</span></label>
                            <select class="form-select" name="consultant" id="consultantSelect">
                                <option value="" selected disabled>Select any one</option>
                                @foreach ($consultants ?? [] as $consultant)
                                    <option value="{{ $consultant->UserCode ?: ($consultant->Doctor_Id ?? $consultant->UserID) }}">
                                        {{ $consultant->DoctorName ?? $consultant->FullName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Schedule Time Inline UI (Shown ONLY AFTER Consultant & Date are selected) --}}
                        <div class="col-md-12 d-none" id="scheduleTimeGroup">
                            <div class="card border bg-light-subtle mb-0">
                                <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <span class="fw-bold fs-13 text-dark">
                                        <i class="ti ti-clock me-1 text-primary"></i>Select Available Time Slot (10:00 AM - 7:00 PM - 20 mins each)
                                    </span>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success text-white px-2 py-1 fs-11"><i class="ti ti-check me-1"></i>Available</span>
                                        <span class="badge bg-danger text-white px-2 py-1 fs-11"><i class="ti ti-x me-1"></i>Booked</span>
                                        <span class="badge bg-primary text-white px-2 py-1 fs-11"><i class="ti ti-pointer me-1"></i>Selected</span>
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    {{-- Selected Time Readout --}}
                                    <div class="d-flex align-items-center gap-2 mb-3 p-2 bg-white border rounded shadow-xs">
                                        <span class="fs-13 text-secondary fw-semibold">Selected Slot:</span>
                                        <input type="text" class="form-control form-control-sm text-center bg-light fw-bold text-primary fs-14 border-primary"
                                            id="scheduleTimeFrom" name="schedule_time_from" placeholder="From" readonly style="width: 120px;">
                                        <span class="text-muted fw-bold">to</span>
                                        <input type="text" class="form-control form-control-sm text-center bg-light fw-bold text-primary fs-14 border-primary"
                                            id="scheduleTimeTo" name="schedule_time_to" placeholder="To" readonly style="width: 120px;">
                                    </div>

                                    {{-- Inline Grid of All Available & Booked 30-min Time Slots --}}
                                    <div class="time-slot-grid-inline p-2 border rounded bg-white" id="timeSlotGrid" style="max-height: 240px; overflow-y: auto;">
                                        <!-- Time slot buttons generated dynamically by JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light px-4 py-2 d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4" id="bookAppointmentSubmit" disabled>
                        <i class="ti ti-check me-1"></i>Save Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End Book Appointment Modal --}}
