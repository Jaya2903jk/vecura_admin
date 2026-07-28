<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // =========================================================
        // BOOK APPOINTMENT MODAL MODULE & STATE MANAGEMENT
        // =========================================================
        function isConsultationOption(selectEl) {
            if (!selectEl || !selectEl.value) return false;
            var selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (!selectedOpt) return false;
            var text = (selectedOpt.textContent || '').toLowerCase();
            var val = (selectEl.value || '').toLowerCase();
            return text.includes('consultation') || val === 'amt1' || val === 'amt3';
        }

        function updateFormState() {
            var appointmentForSelect = document.getElementById('appointmentForSelect');
            var consultantGroup = document.getElementById('consultantSelectGroup');
            var consultantSelect = document.getElementById('consultantSelect');
            var scheduleTimeGroup = document.getElementById('scheduleTimeGroup');
            var scheduleTimeFrom = document.getElementById('scheduleTimeFrom');
            var scheduleTimeTo = document.getElementById('scheduleTimeTo');
            var scheduleDateGroup = document.getElementById('scheduleDateGroup');
            var scheduleDate = document.getElementById('scheduleDate');
            var submitBtn = document.getElementById('bookAppointmentSubmit');

            if (!appointmentForSelect) return;

            var isSelected = appointmentForSelect.value !== '';
            var isConsult = isConsultationOption(appointmentForSelect);
            var isDoctorSelected = (consultantSelect && consultantSelect.value !== '');
            var isDateSelected = (scheduleDate && scheduleDate.value !== '');

            // Show Doctor & Date ONLY when Consultation option is selected
            if (isConsult) {
                if (consultantGroup) {
                    consultantGroup.classList.remove('d-none');
                    if (consultantSelect) consultantSelect.setAttribute('required', 'required');
                }
                if (scheduleDateGroup) {
                    scheduleDateGroup.classList.remove('d-none');
                    if (scheduleDate) scheduleDate.setAttribute('required', 'required');
                }

                // Show Schedule Time Slot Grid ONLY AFTER both Doctor & Date are selected
                if (scheduleTimeGroup) {
                    if (isDoctorSelected && isDateSelected) {
                        scheduleTimeGroup.classList.remove('d-none');
                        if (scheduleTimeFrom) scheduleTimeFrom.setAttribute('required', 'required');
                        if (scheduleTimeTo) scheduleTimeTo.setAttribute('required', 'required');
                    } else {
                        scheduleTimeGroup.classList.add('d-none');
                        if (scheduleTimeFrom) {
                            scheduleTimeFrom.removeAttribute('required');
                            scheduleTimeFrom.value = '';
                        }
                        if (scheduleTimeTo) {
                            scheduleTimeTo.removeAttribute('required');
                            scheduleTimeTo.value = '';
                        }
                    }
                }
            } else {
                // Hide Doctor, Date, Schedule Time if Consultation is not selected
                if (consultantGroup) {
                    consultantGroup.classList.add('d-none');
                    if (consultantSelect) {
                        consultantSelect.removeAttribute('required');
                        consultantSelect.value = '';
                    }
                }
                if (scheduleDateGroup) {
                    scheduleDateGroup.classList.add('d-none');
                    if (scheduleDate) {
                        scheduleDate.removeAttribute('required');
                    }
                }
                if (scheduleTimeGroup) {
                    scheduleTimeGroup.classList.add('d-none');
                    if (scheduleTimeFrom) {
                        scheduleTimeFrom.removeAttribute('required');
                        scheduleTimeFrom.value = '';
                    }
                    if (scheduleTimeTo) {
                        scheduleTimeTo.removeAttribute('required');
                        scheduleTimeTo.value = '';
                    }
                }
            }

            // Enable Save button ONLY when an available time slot is selected for Consultation, or valid non-consultation
            if (submitBtn) {
                if (!isSelected) {
                    submitBtn.disabled = true;
                } else if (isConsult) {
                    var hasTime = (scheduleTimeFrom && scheduleTimeFrom.value.trim() !== '' && scheduleTimeTo && scheduleTimeTo.value.trim() !== '');
                    submitBtn.disabled = !(isDoctorSelected && isDateSelected && hasTime);
                } else {
                    submitBtn.disabled = false;
                }
            }
        }

        function resetModal() {
            var form = document.getElementById('bookAppointmentForm');
            if (form) form.reset();

            var dateInput = document.getElementById('scheduleDate');
            if (dateInput) {
                var today = new Date();
                var yyyy = today.getFullYear();
                var mm = String(today.getMonth() + 1).padStart(2, '0');
                var dd = String(today.getDate()).padStart(2, '0');
                dateInput.value = yyyy + '-' + mm + '-' + dd;
            }

            var timeFrom = document.getElementById('scheduleTimeFrom');
            var timeTo = document.getElementById('scheduleTimeTo');
            if (timeFrom) timeFrom.value = '';
            if (timeTo) timeTo.value = '';

            var errorEl = document.getElementById('bookAppointmentError');
            if (errorEl) {
                errorEl.textContent = '';
                errorEl.classList.add('d-none');
            }

            buildTimeSlotGrid();
            updateFormState();
        }

        // ===== 20-Minute Interval Time Slots (10:00 AM to 7:00 PM) =====
        var SLOT_START_MIN = 10 * 60; // 10:00 AM
        var SLOT_END_MIN = 19 * 60;   // 7:00 PM
        var SLOT_INTERVAL_MIN = 20;   // 20-minute intervals
        var SLOT_DURATION_MIN = 20;   // 20-minute appointment length

        function formatMinutesToAmPm(totalMinutes) {
            var h = Math.floor(totalMinutes / 60);
            var m = totalMinutes % 60;
            var period = h >= 12 ? 'PM' : 'AM';
            var h12 = h % 12;
            if (h12 === 0) h12 = 12;
            return h12 + ':' + String(m).padStart(2, '0') + ' ' + period;
        }

        function parseTimeToMinutes(timeStr) {
            if (!timeStr) return null;
            var str = String(timeStr).trim();
            var match = str.match(/^(\d{1,2}):(\d{2})(?::\d{2})?(?:\s*(AM|PM))?$/i);
            if (match) {
                var h = parseInt(match[1], 10);
                var m = parseInt(match[2], 10);
                var period = match[3] ? match[3].toUpperCase() : null;
                if (period === 'PM' && h < 12) h += 12;
                if (period === 'AM' && h === 12) h = 0;
                return h * 60 + m;
            }
            return null;
        }

        function buildTimeSlotGrid() {
            var grid = document.getElementById('timeSlotGrid');
            if (!grid) return;
            grid.innerHTML = '';

            // Generate 20-min slots from 10:00 AM to 6:40 PM (ends at 7:00 PM)
            for (var t = SLOT_START_MIN; t < SLOT_END_MIN; t += SLOT_INTERVAL_MIN) {
                var tEnd = Math.min(t + SLOT_DURATION_MIN, SLOT_END_MIN);
                var btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'time-slot-btn available';
                btn.dataset.minutes = t;
                btn.dataset.minutesEnd = tEnd;
                btn.innerHTML = '<span class="slot-time">' + formatMinutesToAmPm(t) + ' - ' + formatMinutesToAmPm(tEnd) + '</span>' +
                                '<span class="slot-status text-success font-semibold"><i class="ti ti-check me-1"></i>Available</span>';
                grid.appendChild(btn);
            }
        }

        function loadConsultantAvailability() {
            var appointmentForSelect = document.getElementById('appointmentForSelect');
            var consultantSelect = document.getElementById('consultantSelect');
            var scheduleDate = document.getElementById('scheduleDate');

            if (!appointmentForSelect || !consultantSelect || !scheduleDate) return;

            var isConsultation = isConsultationOption(appointmentForSelect);
            var consultant = consultantSelect.value;
            var date = scheduleDate.value;

            // Preserve currently selected slot if valid
            var currentFrom = document.getElementById('scheduleTimeFrom')?.value;

            // Reset time slot buttons back to default available state
            document.querySelectorAll('.time-slot-btn').forEach(function(btn) {
                btn.classList.remove('slot-booked', 'selected');
                btn.classList.add('available');
                btn.disabled = false;
                btn.removeAttribute('title');
                var tStart = parseInt(btn.dataset.minutes, 10);
                var tEnd = parseInt(btn.dataset.minutesEnd, 10);
                btn.innerHTML = '<span class="slot-time">' + formatMinutesToAmPm(tStart) + ' - ' + formatMinutesToAmPm(tEnd) + '</span>' +
                                '<span class="slot-status text-success font-semibold"><i class="ti ti-check me-1"></i>Available</span>';
            });

            if (!isConsultation || !consultant || !date) return;

            fetch('/consultants/' + encodeURIComponent(consultant) + '/schedule?date=' + encodeURIComponent(date), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function(res) {
                if (!res.ok) throw new Error('Failed to fetch schedule');
                return res.json();
            })
            .then(function(data) {
                var appointments = data.appointments || [];

                appointments.forEach(function(appt) {
                    var apptMin = parseTimeToMinutes(appt.time);
                    if (apptMin === null) return;

                    document.querySelectorAll('.time-slot-btn').forEach(function(btn) {
                        var btnMin = parseInt(btn.dataset.minutes, 10);
                        var btnEnd = parseInt(btn.dataset.minutesEnd, 10);

                        // If booked appointment overlaps with this 20-min slot window
                        if (apptMin < btnEnd && (apptMin + 20) > btnMin) {
                            btn.classList.remove('available', 'selected');
                            btn.classList.add('slot-booked');
                            btn.disabled = true;
                            btn.title = 'Booked: ' + (appt.patient || 'Patient') + ' (' + (appt.type || 'Appointment') + ')';
                            btn.innerHTML = '<span class="slot-time">' + formatMinutesToAmPm(btnMin) + ' - ' + formatMinutesToAmPm(btnEnd) + '</span>' +
                                            '<span class="slot-status text-danger font-bold"><i class="ti ti-x me-1"></i>Booked</span>';
                        }
                    });
                });

                // Restore selected slot styling if still available
                if (currentFrom) {
                    document.querySelectorAll('.time-slot-btn').forEach(function(btn) {
                        var btnStart = parseInt(btn.dataset.minutes, 10);
                        if (formatMinutesToAmPm(btnStart) === currentFrom && !btn.disabled) {
                            btn.classList.add('selected');
                        }
                    });
                }
            })
            .catch(function(err) {
                console.error('Consultant schedule error:', err);
            });
        }

        // Handle clicking a 30-minute time slot box
        var timeSlotGrid = document.getElementById('timeSlotGrid');
        var scheduleTimeFrom = document.getElementById('scheduleTimeFrom');
        var scheduleTimeTo = document.getElementById('scheduleTimeTo');

        if (timeSlotGrid) {
            buildTimeSlotGrid();

            timeSlotGrid.addEventListener('click', function(e) {
                var btn = e.target.closest('.time-slot-btn');
                if (!btn || btn.disabled || btn.classList.contains('slot-booked')) return;

                timeSlotGrid.querySelectorAll('.time-slot-btn.selected').forEach(function(el) {
                    el.classList.remove('selected');
                });
                btn.classList.add('selected');

                var startMin = parseInt(btn.dataset.minutes, 10);
                var endMin = parseInt(btn.dataset.minutesEnd, 10);

                if (scheduleTimeFrom) scheduleTimeFrom.value = formatMinutesToAmPm(startMin);
                if (scheduleTimeTo) scheduleTimeTo.value = formatMinutesToAmPm(endMin);

                updateFormState();
            });
        }

        // Change Event Listeners
        var appointmentForSelectEl = document.getElementById('appointmentForSelect');
        if (appointmentForSelectEl) {
            appointmentForSelectEl.addEventListener('change', function() {
                if (scheduleTimeFrom) scheduleTimeFrom.value = '';
                if (scheduleTimeTo) scheduleTimeTo.value = '';

                updateFormState();
                loadConsultantAvailability();
            });
        }

        var consultantSelectEl = document.getElementById('consultantSelect');
        if (consultantSelectEl) {
            consultantSelectEl.addEventListener('change', function() {
                if (scheduleTimeFrom) scheduleTimeFrom.value = '';
                if (scheduleTimeTo) scheduleTimeTo.value = '';

                updateFormState();
                loadConsultantAvailability();
            });
        }

        var scheduleDateEl = document.getElementById('scheduleDate');
        if (scheduleDateEl) {
            scheduleDateEl.addEventListener('change', function() {
                updateFormState();
                loadConsultantAvailability();
            });
        }

        // AJAX Submit for Book Appointment Form
        var bookAppointmentFormEl = document.getElementById('bookAppointmentForm');
        if (bookAppointmentFormEl) {
            bookAppointmentFormEl.addEventListener('submit', function(e) {
                e.preventDefault();

                var errorEl = document.getElementById('bookAppointmentError');
                if (errorEl) {
                    errorEl.textContent = '';
                    errorEl.classList.add('d-none');
                }

                var submitBtn = document.getElementById('bookAppointmentSubmit');
                if (submitBtn) submitBtn.disabled = true;

                var formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        return { status: res.status, ok: res.ok, data: data };
                    });
                })
                .then(function(result) {
                    if (submitBtn) submitBtn.disabled = false;
                    if (result.ok && result.data.success) {
                        var modalEl = document.getElementById('bookAppointmentModal');
                        var modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (modalInstance) modalInstance.hide();

                        if (typeof Swal !== 'undefined') {
                            var pName = result.data.patient_name || '';
                            var sDate = result.data.schedule_date || '';
                            var sTime = result.data.schedule_time || '';

                            var detailHtml = '';
                            if (sDate || sTime) {
                                detailHtml = '<div style="background: #f8f9fa; border-radius: 12px; padding: 14px 18px; margin-top: 12px; border: 1px solid #e9ecef; text-align: left; font-size: 14px;">' +
                                    (pName ? '<div style="margin-bottom: 6px;"><strong style="color: #6c757d;">Patient:</strong> <span style="color: #0d6efd; font-weight: 600; margin-left: 6px;">' + pName + '</span></div>' : '') +
                                    (sDate ? '<div style="margin-bottom: 6px;"><strong style="color: #6c757d;">Date:</strong> <span style="color: #212529; font-weight: 500; margin-left: 6px;">' + sDate + '</span></div>' : '') +
                                    (sTime ? '<div style="margin-bottom: 6px;"><strong style="color: #6c757d;">Time Slot:</strong> <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="margin-left: 6px; font-weight: 600;">' + sTime + '</span></div>' : '') +
                                    '<div><strong style="color: #6c757d;">Status:</strong> <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="margin-left: 6px; font-weight: 600;"><i class="ti ti-check me-1"></i>SCHEDULED</span></div>' +
                                '</div>';
                            }

                            Swal.fire({
                                icon: 'success',
                                title: '<span style="font-weight: 700; color: #198754; font-size: 22px;">Appointment Booked Successfully!</span>',
                                html: (result.data.message || 'Appointment has been confirmed.') + detailHtml,
                                confirmButtonText: '<i class="ti ti-check me-1"></i> Done',
                                confirmButtonColor: '#0d6efd',
                                customClass: {
                                    popup: 'rounded-4 shadow-lg border-0'
                                }
                            }).then(function() {
                                window.location.reload();
                            });
                        } else {
                            alert(result.data.message || 'Appointment booked successfully');
                            window.location.reload();
                        }
                    } else {
                        var msg = result.data.message || 'Failed to book appointment';
                        if (result.data.errors) {
                            msg = Object.values(result.data.errors).flat().join('<br>');
                        }
                        if (errorEl) {
                            errorEl.innerHTML = msg;
                            errorEl.classList.remove('d-none');
                        } else if (typeof Swal !== 'undefined') {
                            Swal.fire('Error', msg, 'error');
                        } else {
                            alert(msg);
                        }
                    }
                })
                .catch(function(err) {
                    if (submitBtn) submitBtn.disabled = false;
                    console.error('Submit error:', err);
                    if (errorEl) {
                        errorEl.textContent = 'An unexpected error occurred. Please try again.';
                        errorEl.classList.remove('d-none');
                    }
                });
            });
        }

        // Modal show/hide events
        var bookAppointmentModalEl = document.getElementById('bookAppointmentModal');
        if (bookAppointmentModalEl) {
            bookAppointmentModalEl.addEventListener('show.bs.modal', function() {
                resetModal();
                loadConsultantAvailability();
            });
            bookAppointmentModalEl.addEventListener('hidden.bs.modal', function() {
                resetModal();
            });
        }
    });
</script>
