<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\KnownByMaster;
use App\Models\NewBranch;
use App\Models\OccupationMaster;
use App\Models\PatientPersonalDetail;
use App\Models\RegTypeMaster;
use App\Models\State;
use App\Models\UserMaster;
use App\Models\Zone;
use App\Repositories\PatientRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PatientController extends Controller
{
    protected $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'treatment_joined' => $request->input('treatment_joined'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'city' => $request->input('city'),
            'doctor' => $request->input('doctor'),
        ];

        $perPage = $request->input('per_page', 10);
        $patients = $this->patientRepository->getAll($filters, $perPage);

        $filterOptions = $this->patientRepository->getFilterOptions();
        $statusCounts = $this->patientRepository->getCountByStatus();

        return view('patient.index', [
            'patients' => $patients,
            'filterOptions' => $filterOptions,
            'statusCounts' => $statusCounts,
            'filters' => $filters,
        ]);
    }

    public function show($id)
    {
        $patient = $this->patientRepository->getById($id);
        $appointmentForOptions = \App\Models\AppointmentFor::orderBy('AppointName')->get();
        $consultants = $this->getConsultants();

        $appointments = \App\Models\ScheduleConsultant::where(function ($q) use ($patient) {
                if (!empty($patient->RegistrationNo)) {
                    $q->where('Sch_Custid', $patient->RegistrationNo);
                }
                if (!empty($patient->FirstName)) {
                    $q->orWhere('Sch_Custname', $patient->FirstName);
                }
            })
            ->with(['appointmentFor', 'doctor.userMaster'])
            ->orderBy('Sch_Datetime', 'DESC')
            ->get();

        return view('patient.view', [
            'patient' => $patient,
            'appointment_for_options' => $appointmentForOptions,
            'consultants' => $consultants,
            'appointments' => $appointments,
        ]);
    }

    public function create()
    {
        $filterOptions = $this->patientRepository->getFilterOptions();
        $consultants = $this->getConsultants();
        $branches = NewBranch::where('is_active', 1)
            ->orderBy('branch_name')
            ->get();
        $countries = Country::where('is_active', 1)
            ->orderBy('country_name')
            ->get();

        // Fetch master data for dropdowns
        $registrationTypes = RegTypeMaster::orderBy('RegType')->get();
        $occupations = OccupationMaster::orderBy('occupation_name')->get();
        $knownBys = KnownByMaster::where('kstatus', 'Active')->orderBy('KwnBy')->get();

        return view('patient.create', [
            'filterOptions' => $filterOptions,
            'consultants' => $consultants,
            'branches' => $branches,
            'countries' => $countries,
            'registrationTypes' => $registrationTypes,
            'occupations' => $occupations,
            'knownBys' => $knownBys,
        ]);
    }

    public function generateRegistrationNumber(Request $request)
    {
        $branchId = $request->input('branch_id');

        if (! $branchId) {
            return response()->json(['registration_number' => '']);
        }

        $branch = NewBranch::find($branchId);
        if (! $branch) {
            return response()->json(['registration_number' => '']);
        }

        $branchCode = strtoupper(substr($branch->branch_code, 0, 3));
        $yearMonth = date('ym');
        // $yearMonth =  2601;
        $lastPatient = PatientPersonalDetail::where('RegistrationNo', 'like', $branchCode.$yearMonth.'%')
            ->orderBy('RegistrationNo', 'desc')
            ->first();

        if (! $lastPatient || ! $lastPatient->RegistrationNo) {
            $nextNumber = $branchCode.$yearMonth.'0001';
        } else {
            $regNo = $lastPatient->RegistrationNo;
            if (preg_match('/^'.preg_quote($branchCode.$yearMonth, '/').'(\d+)$/', $regNo, $matches)) {
                $number = (int) $matches[1];
                $number++;
                $nextNumber = $branchCode.$yearMonth.str_pad($number, 4, '0', STR_PAD_LEFT);
            } else {
                $nextNumber = $branchCode.$yearMonth.'0001';
            }
        }

        return response()->json(['registration_number' => $nextNumber]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            // Branch & Registration
            'branch_id' => 'required|exists:branch,branch_id',
            'RegistrationNo' => 'nullable|string|max:50',
            'RegistrationDate' => 'nullable|date',

            // Basic details
            'Title' => 'required|in:Mr,Mrs,Ms,Dr',
            'FirstName' => 'required|string|max:100',
            'LastName' => 'nullable|string|max:100',
            'Sex' => 'required|in:M,F,Other',
            'DateOfBirth' => 'required|date|before:today',
            'Age' => 'required|integer|min:0|max:150',
            'Marital_Status' => 'nullable|string|max:50',
            'Occupation' => 'required|string|max:100',

            // Contact
            // 'Mobile'             => 'required|numeric|digits:10',
            'Mobile' => 'required|numeric|digits:10|unique:Patient_Personal_Details,Mobile',
            'WhatsappMobile' => 'required|numeric|digits:10',
            'EMail' => 'nullable|email',
            'Res_Telephone' => 'nullable|string|max:20',
            'Off_Telephone' => 'nullable|string|max:20',

            // Address
            'Country' => 'required',
            'Zone' => 'nullable|string|max:100',
            'State' => 'required|string|max:100',
            'City' => 'required|string|max:100',
            'Area' => 'required|string|max:100',
            'Street' => 'required|string|max:100',
            'PinCode' => 'required|string|max:10',

            // Medical & registration details
            'Doctorname' => 'required|string|max:100',
            'Type' => 'required|string|max:50',
            'Ref_By_Patient' => 'required|string|max:100',
            'Knownby' => 'required|string|max:100',
            'Complaints' => 'nullable|string',

            // Preferences & notifications
            'smsAlert' => 'required|in:Yes,No',
            'emailAlert' => 'nullable|in:Yes,No',
            'Whatsapp' => 'nullable|in:Yes,No',
            'OnlineConsultation' => 'nullable|in:Yes,No',
            'Mobile_Checkbox' => 'nullable|boolean',

            // Additional information
            'SpecialDiscount' => 'nullable|in:Yes,No',
            'GoogleReview' => 'nullable|in:Yes,No',
            'GoogleReviewLink' => 'nullable|url',
            'PANNumber' => 'nullable|string|max:20',
            'AadharNumber' => 'nullable|string|max:20',
            'Wedding_Day' => 'nullable|date',
        ], [
            // Friendly custom messages shown in the stacked error toasts
            'branch_id.required' => 'Please select a branch.',
            'branch_id.exists' => 'Selected branch is invalid.',
            'Title.required' => 'Please select a title.',
            'FirstName.required' => 'First name is required.',
            'Sex.required' => 'Please select gender.',
            'DateOfBirth.required' => 'Date of birth is required.',
            'DateOfBirth.before' => 'Date of birth must be in the past.',
            'Age.required' => 'Age is required (select date of birth).',
            'Occupation.required' => 'Please select an occupation.',

            'Mobile.required' => 'Mobile number is required.',
            'Mobile.digits' => 'Mobile number must be exactly 10 digits.',
            'Mobile.numeric' => 'Mobile number must contain digits only.',
            'Mobile.unique' => 'This mobile number is already registered.',

            'WhatsappMobile.required' => 'Whatsapp mobile is required.',
            'WhatsappMobile.digits' => 'Whatsapp mobile must be exactly 10 digits.',
            'WhatsappMobile.numeric' => 'Whatsapp mobile must contain digits only.',
            'EMail.email' => 'Please enter a valid email address.',
            'Country.required' => 'Please select a country.',
            'State.required' => 'Please select a state.',
            'City.required' => 'Please select a city.',
            'Area.required' => 'Please select an area.',
            'Street.required' => 'Street / address is required.',
            'PinCode.required' => 'Pin code is required.',
            'Doctorname.required' => 'Please select a consultant.',
            'Type.required' => 'Please select a registration type.',
            'Ref_By_Patient.required' => 'Ref. by customer is required.',
            'Knownby.required' => 'Please select how the customer knew about us.',
            'smsAlert.required' => 'Please select SMS/Whatsapp notification preference.',
            'GoogleReviewLink.url' => 'Google review link must be a valid URL.',
        ]);

        try {
            // Auto-generate registration number if not provided
            if (empty($validated['RegistrationNo'])) {
                $validated['RegistrationNo'] = $this->generateRegistrationNumber($validated['branch_id']);
            }
            $validated['Title'] = strtoupper($validated['Title']);                    // MR, MRS, MS, DR
            $validated['Occupation'] = strtoupper($validated['Occupation']);               // DOCTOR, ENGINEER
            $validated['Marital_Status'] = ! empty($validated['Marital_Status'])
                ? strtoupper($validated['Marital_Status'])                                     // SINGLE, MARRIED
                : null;
            // System fields
            $userCode = session('user_code') ?? optional(\App\Models\UserMaster::find(session('user_id')))->UserCode ?? 'USE-0209';
            $validated['CreatedBy'] = $userCode;
            $validated['CreatedDate'] = now();
            $validated['RegistrationDate'] = $validated['RegistrationDate'] ?? now();
            $validated['CustomerStatus'] = 'Active';

            // $validated['Mobile_Checkbox'] = $request->boolean('Mobile_Checkbox') ? 1 : 0;

            $get_country_code = Country::where('country_id', $validated['Country'])->first();
            $validated['Country'] = $get_country_code->country_code;
            $validated['Country'] = 'ADY1';
            $get_branch_code = NewBranch::where('branch_id', $validated['branch_id'])->first();
            unset($validated['Zone']);
            $validated['Loc_Id'] = $get_branch_code->branch_code;
            $validated['RegTypeId'] = $validated['Type'];
            $validated['Type'] = 'New';
            $validated['NonTreatable'] = 'Weight Loss';

            // dd($validated);
            $this->patientRepository->create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Patient registered successfully.',
                    'redirect' => route('patient.index'),
                ]);
            }

            return redirect()->route('patient.index')
                ->with('success', 'Patient registered successfully.');
        } catch (\Throwable $e) {
            Log::error('Patient store failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to register patient. Please try again.',
                ], 500);
            }

            return back()->withInput()
                ->with('error', 'Failed to register patient. Please try again.');
        }
    }

    private function getConsultants()
    {
        return UserMaster::where('User_Master.UserStatus', 'Active')
            ->join('employee_roles', 'User_Master.UserID', '=', 'employee_roles.employee_id')
            ->join('roles', 'employee_roles.role_id', '=', 'roles.id')
            ->where('roles.id', 8)
            ->where('employee_roles.is_active', 1)
            ->select('User_Master.UserID', 'User_Master.UserCode', 'User_Master.FullName', 'User_Master.EmailId', 'roles.name as role_name')
            ->distinct()
            ->orderBy('User_Master.FullName')
            ->get();
    }

    public function edit($id)
    {
        $patient = $this->patientRepository->getById($id);
        $filterOptions = $this->patientRepository->getFilterOptions();

        $consultants = UserMaster::where('User_Master.UserStatus', 'Active')
            ->join('employee_roles', 'User_Master.UserID', '=', 'employee_roles.employee_id')
            ->join('roles', 'employee_roles.role_id', '=', 'roles.id')
            ->where('roles.id', 8)
            ->where('employee_roles.is_active', 1)
            ->select('User_Master.UserID', 'User_Master.UserCode', 'User_Master.FullName', 'User_Master.EmailId', 'roles.name as role_name')
            ->distinct()
            ->orderBy('User_Master.FullName')
            ->get();

        $branches = NewBranch::where('is_active', 1)
            ->orderBy('branch_name')
            ->get();
        $countries = Country::where('is_active', 1)
            ->orderBy('country_name')
            ->get();

        // Fetch master data for dropdowns
        $registrationTypes = RegTypeMaster::orderBy('RegType')->get();
        $occupations = OccupationMaster::orderBy('occupation_name')->get();
        $knownBys = KnownByMaster::where('kstatus', 'Active')->orderBy('KwnBy')->get();

        return view('patient.edit', [
            'patient' => $patient,
            'filterOptions' => $filterOptions,
            'consultants' => $consultants,
            'branches' => $branches,
            'countries' => $countries,
            'registrationTypes' => $registrationTypes,
            'occupations' => $occupations,
            'knownBys' => $knownBys,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'branch_id' => 'nullable|exists:branch,branch_id',
            'FirstName' => 'required|string|max:100',
            'LastName' => 'nullable|string|max:100',
            'Mobile' => 'required|numeric|digits:10',
            'WhatsappMobile' => 'required|numeric|digits:10',
            'EMail' => 'nullable|email',
            'DateOfBirth' => 'required|date',
            'Age' => 'required|integer|min:0|max:150',
            'City' => 'required|string|max:100',
            'State' => 'required|string|max:100',
            'Country' => 'required|string|max:100',
            'PinCode' => 'required|string|max:10',
            'Street' => 'required|string|max:100',
            'Area' => 'required|string|max:100',
            'Sex' => 'required|in:M,F,Other',
            'Title' => 'required|in:Mr,Mrs,Ms,Dr',
            'Marital_Status' => 'nullable|string|max:50',
            'Occupation' => 'required|string|max:100',
            'Knownby' => 'required|string|max:100',
            'Doctorname' => 'required|string|max:100',
            'Type' => 'required|string|max:50',
            'Ref_By_Patient' => 'required|string|max:100',
            'smsAlert' => 'required|in:Yes,No',
            'Complaints' => 'nullable|string',
            'Res_Telephone' => 'nullable|string|max:20',
            'Off_Telephone' => 'nullable|string|max:20',
            'Wedding_Day' => 'nullable|date',
            'SpecialDiscount' => 'nullable|numeric|min:0',
            'emailAlert' => 'nullable|in:Yes,No',
            'Whatsapp' => 'nullable|in:Yes,No',
            'GoogleReview' => 'nullable|string|max:100',
            'PANNumber' => 'nullable|string|max:20',
            'OnlineConsultation' => 'nullable|in:Yes,No',
            'GoogleReviewLink' => 'nullable|url',
            'AadharNumber' => 'nullable|string|max:20',
            'Mobile_Checkbox' => 'nullable|boolean',
            'CustomerStatus' => 'nullable|string|max:50',
            'TreatmentJoined' => 'nullable|in:Yes,No',
        ]);

        $userCode = session('user_code') ?? optional(\App\Models\UserMaster::find(session('user_id')))->UserCode ?? 'USE-0209';
        $validated['ModifiedBy'] = $userCode;
        $validated['ModifiedDate'] = now();

        $this->patientRepository->update($id, $validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient updated successfully.',
                'redirect' => route('patient.show', $id),
            ]);
        }

        return redirect()->route('patient.show', $id)->with('success', 'Patient updated successfully.');
    }

    public function destroy($id)
    {
        $this->patientRepository->delete($id);

        return redirect()->route('patient.index')->with('success', 'Patient deleted successfully.');
    }

    public function search(Request $request)
    {
        $search = $request->input('q');

        $filters = ['search' => $search];
        $patients = $this->patientRepository->getAll($filters, 20);

        return response()->json($patients);
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'pdf');
        $filters = [
            'search' => $request->input('search'),
            'status' => $request->input('status'),
            'type' => $request->input('type'),
            'treatment_joined' => $request->input('treatment_joined'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'city' => $request->input('city'),
            'doctor' => $request->input('doctor'),
        ];

        $patients = $this->patientRepository->getAll($filters, 10000);
        $data = $patients->items();

        if ($format === 'excel') {
            return $this->exportToExcel($data);
        } else {
            return $this->exportToPdf($data);
        }
    }

    private function exportToExcel($patients)
    {
        $filename = 'patients_'.date('Y_m_d_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($patients) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Patient Name', 'Registration No', 'Mobile', 'Email', 'Status', 'Treatment', 'City']);

            foreach ($patients as $patient) {
                fputcsv($file, [
                    $patient->FirstName.' '.($patient->LastName ?? ''),
                    $patient->RegistrationNo ?? '',
                    $patient->Mobile ?? '',
                    $patient->EMail ?? '',
                    $patient->CustomerStatus ?? '',
                    $patient->TreatmentJoined ?? 'No',
                    $patient->City ?? '',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportToPdf($patients)
    {
        $html = view('patient.export_pdf', ['patients' => $patients])->render();
        $pdf = Pdf::loadHTML($html);

        return $pdf->download('patients_'.date('Y_m_d_His').'.pdf');
    }

    public function getStates(Request $request)
    {
        $countryId = $request->input('country_id');

        if (! $countryId) {
            return response()->json(['states' => []]);
        }

        $states = State::where('country_id', $countryId)
            ->where('is_active', 1)
            ->orderBy('state_name')
            ->select('state_id', 'state_name')
            ->get();

        return response()->json(['states' => $states]);
    }

    public function getCities(Request $request)
    {
        $stateId = $request->input('state_id');

        if (! $stateId) {
            return response()->json(['cities' => []]);
        }

        $cities = City::where('state_id', $stateId)
            ->where('is_active', 1)
            ->orderBy('city_name')
            ->select('city_id', 'city_name')
            ->get();

        return response()->json(['cities' => $cities]);
    }

    public function getZones(Request $request)
    {
        $countryId = $request->input('country_id');

        if (! $countryId) {
            return response()->json(['zones' => []]);
        }

        $zones = Zone::where('country_id', $countryId)
            ->where('is_active', 1)
            ->orderBy('zone_name')
            ->select('zone_id', 'zone_name')
            ->get();

        return response()->json(['zones' => $zones]);
    }

    public function getBranches(Request $request)
    {
        $cityId = $request->input('city_id');

        if (! $cityId) {
            return response()->json(['branches' => []]);
        }

        $branches = NewBranch::where('city_id', $cityId)
            ->where('is_active', 1)
            ->orderBy('branch_name')
            ->select('branch_id', 'branch_name', 'branch_code')
            ->get();

        return response()->json(['branches' => $branches]);
    }
}
