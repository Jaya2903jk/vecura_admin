<?php

namespace App\Http\Controllers;

use App\Models\AppointmentFor;
use App\Models\DoctorMaster;
use App\Models\ScheduleConsultant;
use App\Models\ScheduleTimeDetails;
use App\Models\UserMaster;
use App\Repositories\PatientRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AppointmentController extends Controller
{
    protected $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    /**
     * AJAX-loaded data for the patient page's Appointments tab: the booking
     * table plus the dropdown options for the "Book New Scheduling" modal.
     * Kept out of the initial patient page load since it's a heavy multi-join
     * query only needed once the user actually opens this tab.
     */
    public function forPatient($id)
    {
        $patient = $this->patientRepository->getById($id);

        $appointments = ScheduleConsultant::byPatient($patient->RegistrationNo)
            ->with(['doctor.userMaster', 'appointmentFor'])
            ->orderBy('Sch_Datetime', 'desc')
            ->get();
        $bookedAppointmentFor = $appointments
            ->where('Sch_Status', 'SCHEDULED')
            ->pluck('Sch_AppointFor')
            ->filter()
            ->unique()
            ->toArray();

        $appointmentForOptions = AppointmentFor::whereNotIn(
            'AppointtCode',
            $bookedAppointmentFor
        )
            ->orderBy('AppointName')
            ->get();

        return response()->json([
            'html' => view('patient.partials.appointments-table', [
                'appointments' => $appointments,
            ])->render(),
            'appointment_for_options' => $appointmentForOptions->map(fn ($option) => [
                'code' => $option->AppointtCode,
                'name' => $option->AppointName,
            ]),
            'consultants' => $this->getConsultants()->map(fn ($consultant) => [
                'id' => $consultant->Doctor_Id ?? $consultant->UserID,
                'name' => $consultant->DoctorName ?? $consultant->FullName,
            ]),
        ]);
    }

    /**
     * Books a new ScheduleConsultant & ScheduleTimeDetails row from the "Book New Scheduling" modal
     * on the patient page with 20-minute time intervals between 10:00 AM and 7:00 PM.
     */
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'appointment_for' => 'required|string|exists:ApppointmentFor,AppointtCode',
            'consultant' => 'nullable|string',
            'schedule_date' => 'required|date',
            'schedule_time_from' => ['required', 'regex:/^\d{1,2}:\d{2} (AM|PM)$/i'],
        ]);

        $patient = $this->patientRepository->getById($id);

        $alreadyBooked = ScheduleConsultant::byPatient($patient->RegistrationNo)
            ->where('Sch_AppointFor', $validated['appointment_for'])
            ->whereIn('Sch_Status', ['SCHEDULED'])
            ->exists();

        if ($alreadyBooked) {
            return response()->json([
                'success' => false,
                'message' => 'This patient already has a scheduled appointment for that type.',
            ], 422);
        }

        $scheduleDateTime = Carbon::createFromFormat(
            'Y-m-d g:i A',
            $validated['schedule_date'].' '.strtoupper($validated['schedule_time_from'])
        );

        $doctorCode = null;

        // Conflict check for consultant at the exact date and time
        if (!empty($validated['consultant'])) {
            $consultantVal = $validated['consultant'];
            $isNumeric = is_numeric($consultantVal);

            $doctor = DoctorMaster::where('UserCode', $consultantVal)
                ->orWhere('Doctor_Id', $consultantVal)
                ->first();

            $doctorCode = $doctor?->Doctor_Id ?? $consultantVal;

            $user = UserMaster::where('UserCode', $consultantVal)
                ->when($isNumeric, function ($query) use ($consultantVal) {
                    $query->orWhere('UserID', $consultantVal);
                })
                ->first();

            $searchKeys = array_values(array_unique(array_map('strval', array_filter([
                $consultantVal,
                $doctor?->Doctor_Id,
                $doctor?->DoctorName,
                $doctor?->UserCode,
                $user?->UserID,
                $user?->UserCode,
                $user?->FullName,
            ]))));

            $dateStr = $scheduleDateTime->format('Y-m-d');
            $timeStr = $scheduleDateTime->format('g:i');

            $slotConflict = ScheduleConsultant::whereIn('Sch_Doctname', $searchKeys)
                ->where(function ($query) use ($dateStr) {
                    $query->whereDate('Sch_Datetime', $dateStr)
                        ->orWhereDate('FreeScheduleFrm', $dateStr)
                        ->orWhereDate('Sch_AppointDatetime', $dateStr);
                })
                ->where(function ($query) use ($timeStr, $scheduleDateTime) {
                    $query->where('Sch_Time', 'like', $timeStr.'%')
                        ->orWhere('Sch_Datetime', $scheduleDateTime)
                        ->orWhere('FreeScheduleFrm', $scheduleDateTime);
                })
                ->where(function ($query) {
                    $query->whereNull('Sch_Status')
                        ->orWhereIn('Sch_Status', ['SCHEDULED', '1']);
                })
                ->exists();

            if (!$slotConflict) {
                $slotConflict = ScheduleTimeDetails::whereIn('DoctorCode', $searchKeys)
                    ->whereDate('ScheduleDate', $dateStr)
                    ->where('ScheduleFromTime', $scheduleDateTime->format('g:i A'))
                    ->where(function ($query) {
                        $query->whereNull('Status')
                            ->orWhereIn('Status', ['SCHEDULED', '1']);
                    })
                    ->exists();
            }

            if ($slotConflict) {
                return response()->json([
                    'success' => false,
                    'message' => 'The selected consultant already has an appointment booked at '.$validated['schedule_time_from'].' on '.$validated['schedule_date'].'. Please select a different time slot.',
                ], 422);
            }
        }

        $locationCode = $patient->Loc_Id ?: 'ANR';
        $userCode = optional(UserMaster::find(session('user_id')))->UserCode ?: 'HEC-1355';
        $scheduleCode = $this->generateScheduleCode($locationCode);

        // 1. Create ScheduleConsultant entry
        $appointment = ScheduleConsultant::create([
            'ScheduleCode' => $scheduleCode,
            'Sch_Custid' => $patient->RegistrationNo,
            'Sch_Custname' => $patient->FirstName,
            'Sch_Doctname' => $doctorCode ?? ($validated['consultant'] ?? ''),
            'Sch_Datetime' => $scheduleDateTime,
            'Sch_AppointFor' => $validated['appointment_for'],
            'Sch_Status' => 'SCHEDULED',
            'Sch_Flag' => 'D',
            'Sch_CusType' => 'True',
            'FreeScheduleFrm' => '1900-01-01 00:00:00.000',
            'LOCATION' => $locationCode,
            'CreatedDate' => now(),
            'CreatedBy' => $userCode,
            'ModifiedDate' => now(),
            'Modifiedby' => $userCode,
            'Sch_Time' => $scheduleDateTime->format('g:i'),
            'ampm' => $scheduleDateTime->format('A'),
            'IsConverted' => 0,
        ]);

        // 2. Generate Next AppointmentCode (e.g., APC-118890)
        $lastAppt = ScheduleTimeDetails::orderBy('AppointmentId', 'desc')->first();
        $nextNum = 118890;
        if ($lastAppt && !empty($lastAppt->AppointmentCode) && preg_match('/APC-(\d+)/', $lastAppt->AppointmentCode, $matches)) {
            $nextNum = intval($matches[1]) + 1;
        }
        $appointmentCode = 'APC-' . $nextNum;

        // 3. Calculate 20-minute Interval (From Time & To Time)
        $fromTime = $scheduleDateTime->format('g:i A');
        $toTime = $scheduleDateTime->copy()->addMinutes(20)->format('g:i A');

        // 4. Create ScheduleTimeDetails entry
        ScheduleTimeDetails::create([
            'AppointmentCode' => $appointmentCode,
            'ScheduleCode' => $scheduleCode,
            'DoctorCode' => $doctorCode ?? ($validated['consultant'] ?? ''),
            'CustomerCode' => $patient->RegistrationNo,
            'ScheduleDate' => $scheduleDateTime->format('Y-m-d 00:00:00.000'),
            'ScheduleFromTime' => $fromTime,
            'ScheduleToTime' => $toTime,
            'ScheduleInterval' => 20,
            'Status' => 'SCHEDULED',
            'Location' => $locationCode,
            'CreatedBy' => $userCode,
            'CreatedDate' => now(),
            'ModifiedBy' => $userCode,
            'ModifiedDate' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully.',
            'schedule_id' => $appointment->ScheduleId,
            'patient_name' => $patient->FirstName,
            'schedule_date' => $scheduleDateTime->format('d-m-Y'),
            'schedule_time' => $scheduleDateTime->format('g:i A'),
        ]);
    }
    public function consultantSchedule(Request $request, $consultant)
    {
        $validated = $request->validate([
            'date' => 'required|date',
        ]);

        $isNumeric = is_numeric($consultant);

        // Find doctor or user record safely without SQL Server int conversion error
        $doctor = DoctorMaster::where('UserCode', $consultant)
            ->orWhere('Doctor_Id', $consultant)
            ->first();

        $user = UserMaster::where('UserCode', $consultant)
            ->when($isNumeric, function ($query) use ($consultant) {
                $query->orWhere('UserID', $consultant);
            })
            ->first();

        $searchKeys = array_values(array_unique(array_map('strval', array_filter([
            $consultant,
            $doctor?->Doctor_Id,
            $doctor?->DoctorName,
            $doctor?->UserCode,
            $user?->UserID,
            $user?->UserCode,
            $user?->FullName,
        ]))));

        $dateStr = Carbon::parse($validated['date'])->format('Y-m-d');
       // HDC-0239	priya P	ANR	Bol1	2026-07-25 12:29:51.073	Bol1	2026-07-25 12:29:51.073	Active	EMP-0002	VEC-4840

        $appointments = ScheduleConsultant::whereIn('Sch_Doctname', $searchKeys)
            ->where(function ($query) use ($dateStr) {
                $query->whereDate('Sch_Datetime', $dateStr)
                    ->orWhereDate('FreeScheduleFrm', $dateStr)
                    ->orWhereDate('Sch_AppointDatetime', $dateStr);
            })
            ->where(function ($query) {
                $query->whereNull('Sch_Status')
                    ->orWhereIn('Sch_Status', ['SCHEDULED', '1']);
            })
            ->with('appointmentFor')
            ->orderBy('Sch_Datetime')
            ->get();

        return response()->json([
            'appointments' => $appointments->map(function ($appt) {
                $formattedTime = null;
                if (!empty($appt->Sch_Time)) {
                    $timeClean = trim($appt->Sch_Time);
                    $ampmClean = !empty($appt->ampm) ? strtoupper(trim($appt->ampm)) : '';
                    if ($ampmClean && !str_contains(strtoupper($timeClean), 'AM') && !str_contains(strtoupper($timeClean), 'PM')) {
                        $formattedTime = $timeClean.' '.$ampmClean;
                    } else {
                        $formattedTime = $timeClean;
                    }
                } elseif ($appt->Sch_Datetime) {
                    $formattedTime = Carbon::parse($appt->Sch_Datetime)->format('g:i A');
                } elseif ($appt->FreeScheduleFrm) {
                    $formattedTime = Carbon::parse($appt->FreeScheduleFrm)->format('g:i A');
                } elseif ($appt->Sch_AppointDatetime) {
                    $formattedTime = Carbon::parse($appt->Sch_AppointDatetime)->format('g:i A');
                }

                if ($formattedTime) {
                    try {
                        $formattedTime = Carbon::parse($formattedTime)->format('g:i A');
                    } catch (\Exception $e) {
                        // Keep original formatted string if parse fails
                    }
                }
                return [
                    'time' => $formattedTime ?? 'N/A',
                    'patient' => $appt->Sch_Custname ?? 'Patient',
                    'type' => optional($appt->appointmentFor)->AppointName ?? $appt->Sch_AppointFor ?? 'Scheduled',
                ];
            }),
        ]);
    }
    private const TREATMENT_FIELDS = [
        // Session & status
        'Sch_Status', 'Sch_Treatmentname', 'Sch_Treatmenttype',
        'TotalSitting', 'CurrentSitting', 'NextSittingDate', 'SessionStatus',
        // Assigned team
        'Sch_Technician', 'Sch_Physiotherapist', 'Sch_Physiotherapist2',
        'Sch_Nutritionist', 'Sch_TreatmentPlannedBy', 'Sch_AreaOfExecution',
        // Weight
        'Sch_BeforeWeight', 'Sch_AfterWeight', 'Sch_TotalWeightLoss',
        // Upper/middle/lower measurements
        'Sch_BeforeUpper', 'Sch_BeforeMiddle', 'Sch_BeforeLower',
        'Sch_AfterUpper', 'Sch_AfterMiddle', 'Sch_AfterLower', 'Sch_TotalInches',
        // Body-part measurements: reading 1 (unsuffixed)
        'TUMMY', 'RIGHTARM', 'LEFTARM', 'FLANKS', 'BACK', 'HIP', 'RIGHTTHIGH', 'LEFTTHIGH', 'CHEST',
        // Body-part measurements: reading 2 ("_A")
        'TUMMY_A', 'RIGHTARM_A', 'LEFTARM_A', 'FLANKS_A', 'BACK_A', 'HIP_A', 'RIGHTTHIGH_A', 'LEFTTHIGH_A', 'CHEST_A',
        // Body-part measurements: reading 3 ("_B")
        'TUMMY_B', 'RIGHTARM_B', 'LEFTARM_B', 'FLANKS_B', 'BACK_B', 'HIP_B', 'RIGHTTHIGH_B', 'LEFTTHIGH_B', 'CHEST_B', 'BACK_C',
        // Documentation & misc status flags
        'BCAStatus', 'MicroscopyStatus', 'TrichoscanStatus', 'TrichologyformStatus',
        'BeforephotoStatus', 'BeforephotoMarkingArea', 'BeforephotoStatusWOMarkingArea',
        'BeforephotoStatusRegularClient', 'AfterPhotoStatus', 'RegularformStatus',
        'FormStatus', 'FormUploaded', 'MedicalStatus', 'SlipStatus', 'SurgeryStatus',
        'NutritionalReview', 'DietChat', 'IsConverted',
    ];

    public function show($id, $scheduleId)
    {
        $patient = $this->patientRepository->getById($id);

        $appointment = ScheduleConsultant::byPatient($patient->RegistrationNo)
            ->where('ScheduleId', $scheduleId)
            ->firstOrFail();

        $values = collect(self::TREATMENT_FIELDS)->mapWithKeys(function ($field) use ($appointment) {
            $value = $appointment->{$field};

            if ($value instanceof \Carbon\Carbon) {
                $value = $value->format('Y-m-d');
            }

            return [$field => $value];
        });

        return response()->json(['values' => $values]);
    }

   
    public function update(Request $request, $id, $scheduleId)
    {
        $patient = $this->patientRepository->getById($id);

        $appointment = ScheduleConsultant::byPatient($patient->RegistrationNo)
            ->where('ScheduleId', $scheduleId)
            ->firstOrFail();

        $validated = $request->validate([
            'Sch_Status' => 'required|in:SCHEDULED,COMPLETED,CANCELLED',
            'NextSittingDate' => 'nullable|date',
            'TotalSitting' => 'nullable|integer',
            'CurrentSitting' => 'nullable|integer',
            'Sch_BeforeWeight' => 'nullable|numeric',
            'Sch_AfterWeight' => 'nullable|numeric',
            'Sch_TotalWeightLoss' => 'nullable|numeric',
            'Sch_BeforeUpper' => 'nullable|numeric',
            'Sch_BeforeMiddle' => 'nullable|numeric',
            'Sch_BeforeLower' => 'nullable|numeric',
            'Sch_AfterUpper' => 'nullable|numeric',
            'Sch_AfterMiddle' => 'nullable|numeric',
            'Sch_AfterLower' => 'nullable|numeric',
            'Sch_TotalInches' => 'nullable|numeric',
        ]);

        // Everything else in TREATMENT_FIELDS is free-form text/status; take it
        // as-is if present in the request, without forcing an enum we can't verify.
        $freeform = collect(self::TREATMENT_FIELDS)
            ->diff(array_keys($validated))
            ->mapWithKeys(fn ($field) => [$field => $request->input($field)])
            ->filter(fn ($value) => $value !== null)
            ->toArray();

        $appointment->fill(array_merge($validated, $freeform));
        $appointment->ModifiedDate = now();
        $appointment->Modifiedby = optional(UserMaster::find(session('user_id')))->UserCode;
        $appointment->save();

        return response()->json([
            'success' => true,
            'message' => 'Treatment record saved.',
        ]);
    }

    public function consultationForm($id, $scheduleId)
    {
        $patient = $this->patientRepository->getById($id);

        $appointment = ScheduleConsultant::where('ScheduleId', $scheduleId)
            ->where(function ($q) use ($patient) {
                if (!empty($patient->RegistrationNo)) {
                    $q->where('Sch_Custid', $patient->RegistrationNo);
                }
                if (!empty($patient->FirstName)) {
                    $q->orWhere('Sch_Custname', $patient->FirstName);
                }
            })
            ->firstOrFail();

        return view('patient.consultation_form', [
            'patient' => $patient,
            'appointment' => $appointment,
        ]);
    }

    public function saveConsultationForm(Request $request, $id, $scheduleId)
    {
        $patient = $this->patientRepository->getById($id);

        $appointment = ScheduleConsultant::where('ScheduleId', $scheduleId)
            ->where(function ($q) use ($patient) {
                if (!empty($patient->RegistrationNo)) {
                    $q->where('Sch_Custid', $patient->RegistrationNo);
                }
                if (!empty($patient->FirstName)) {
                    $q->orWhere('Sch_Custname', $patient->FirstName);
                }
            })
            ->firstOrFail();

        // Handle uploaded image files if any
        foreach (['form_page_1', 'form_page_2', 'form_page_3', 'form_page_4'] as $field) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = time() . '_' . $field . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/consultation_forms'), $filename);
            }
        }

        // Save fields onto appointment model
        $fillable = (new ScheduleConsultant)->getFillable();
        foreach ($request->except(['_token', 'form_page_1', 'form_page_2', 'form_page_3', 'form_page_4']) as $key => $val) {
            if (in_array($key, $fillable)) {
                $appointment->{$key} = is_array($val) ? implode(', ', $val) : $val;
            }
        }

        $appointment->ModifiedDate = now();
        $appointment->Modifiedby = optional(UserMaster::find(session('user_id')))->UserCode;
        $appointment->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Consultation and Treatment form saved successfully.',
            ]);
        }

        return redirect()->back()->with('success', 'Consultation and Treatment form saved successfully.');
    }

    public function consultationFormSecure(Request $request)
    {
        $token = $request->input('token');

        if (empty($token)) {
            abort(403, 'Unauthorized access: Missing security token.');
        }

        try {
            $decrypted = Crypt::decryptString($token);
            $parts = explode(':', $decrypted);
            if (count($parts) !== 2) {
                abort(403, 'Invalid security token format.');
            }
            [$id, $scheduleId] = $parts;
        } catch (\Exception $e) {
            abort(403, 'Security token validation failed. Access denied.');
        }

        $patient = $this->patientRepository->getById($id);

        $appointment = ScheduleConsultant::where('ScheduleId', $scheduleId)
            ->where(function ($q) use ($patient) {
                if (!empty($patient->RegistrationNo)) {
                    $q->where('Sch_Custid', $patient->RegistrationNo);
                }
                if (!empty($patient->FirstName)) {
                    $q->orWhere('Sch_Custname', $patient->FirstName);
                }
            })
            ->firstOrFail();

        return view('patient.consultation_form', [
            'patient' => $patient,
            'appointment' => $appointment,
            'secureToken' => $token,
        ]);
    }

    public function saveConsultationFormSecure(Request $request)
    {
        $token = $request->input('token');

        if (empty($token)) {
            return response()->json(['success' => false, 'message' => 'Missing security token.'], 403);
        }

        try {
            $decrypted = Crypt::decryptString($token);
            $parts = explode(':', $decrypted);
            if (count($parts) !== 2) {
                return response()->json(['success' => false, 'message' => 'Invalid security token.'], 403);
            }
            [$id, $scheduleId] = $parts;
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Security token validation failed.'], 403);
        }

        return $this->saveConsultationForm($request, $id, $scheduleId);
    }

    /**
     * Best-effort match to the one legacy ScheduleCode sample we have
     * ("ANR/61192" = LocationCode + "/" + sequence). No generator for this
     * existed anywhere in the codebase, so this infers the next number from
     * the highest existing code for the same location prefix.
     */
    private function generateScheduleCode($locationCode)
    {
        $prefix = $locationCode.'/';

        $lastCode = ScheduleConsultant::where('ScheduleCode', 'like', $prefix.'%')
            ->orderByRaw('TRY_CAST(SUBSTRING(ScheduleCode, ?, 20) AS INT) DESC', [strlen($prefix) + 1])
            ->value('ScheduleCode');

        $nextNumber = $lastCode ? ((int) substr($lastCode, strlen($prefix))) + 1 : 1;

        return $prefix.$nextNumber;
    }

    private function getConsultants()
    {
        return UserMaster::where('User_Master.UserStatus', 'Active')
            ->join('employee_roles', 'User_Master.UserID', '=', 'employee_roles.employee_id')
            ->join('roles', 'employee_roles.role_id', '=', 'roles.id')
            ->leftJoin('Doctor_Master', 'User_Master.UserCode', '=', 'Doctor_Master.UserCode')
            ->where('roles.id', 8)
            ->where('employee_roles.is_active', 1)
            ->select(
                'User_Master.UserID',
                'User_Master.UserCode',
                'User_Master.FullName',
                'Doctor_Master.Doctor_Id',
                'Doctor_Master.DoctorName',
                'roles.name as role_name'
            )
            ->distinct()
            ->orderBy('User_Master.FullName')
            ->get();
    }
}
