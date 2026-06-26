<?php

namespace App\Services;

use App\Models\UserMaster;
use App\Models\EmployeeOnboarding;
use App\Models\EmployeeOffboarding;
use App\Models\EmployeeEducation;
use App\Models\EmployeeCertificate;
use App\Models\EmployeeDocument;
use App\Models\EmployeeBond;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeWorkflowService
{
    // ============ EMPLOYEE CREATION WITH ONBOARDING ============

    public function createEmployeeWithOnboarding($data)
    {
        try {
            DB::beginTransaction();

            // Create employee (from StaffController logic)
            $empCode = $data['employee_code'] ?? 'EMP-' . time();
            $username = strtolower(str_replace(' ', '.', $data['first_name'] . '.' . $data['last_name']));

            $employee = UserMaster::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'employee_code' => $empCode,
                'UserName' => $username,
                'Password' => bcrypt('Vecura@123'),
                'EmailId' => $data['email'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'department_id' => $data['department_id'],
                'designation_code' => $data['designation_code'],
                'office_type' => $data['office_type'],
                'branch_id' => $data['branch_id'] ?? null,
                'manager_id' => $data['manager_id'] ?? null,
                'employee_status' => 'Active',
                'UserStatus' => 'Active',
                'FullName' => $data['first_name'] . ' ' . $data['last_name'],
                'UserCode' => $empCode,
                'CreatedBy' => session('user_id'),
                'CreatedDate' => now(),
            ]);

            // Create employee profile
            DB::table('employee_profiles')->insert([
                'user_id' => $employee->UserID,
                'employee_category' => $data['employee_category'] ?? 'White Collar',
                'date_of_birth' => $data['date_of_birth'],
                'gender' => $data['gender'] ?? null,
                'phone_number' => $data['phone'] ?? null,
                'alternate_phone' => $data['alternate_phone'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'postal_code' => $data['postal_code'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'employee_type' => $data['employee_type'] ?? 'Permanent',
                'date_of_joining' => $data['date_of_joining'] ?? now(),
                'blood_group' => $data['blood_group'] ?? null,
                'aadhar_number' => $data['aadhar_number'] ?? null,
                'pan_number' => $data['pan_number'] ?? null,
                'bank_account_number' => $data['bank_account'] ?? null,
                'ifsc_code' => $data['ifsc_code'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create employee medical
            DB::table('employee_medical')->insert([
                'user_id' => $employee->UserID,
                'blood_group' => $data['blood_group'] ?? null,
                'medical_conditions' => $data['medical_conditions'] ?? null,
                'allergies' => $data['allergies'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Create onboarding record
            EmployeeOnboarding::create([
                'user_id' => $employee->UserID,
                'onboarding_date' => now(),
                'onboarding_status' => 'Pending',
                'conducted_by' => session('user_id'),
            ]);

            DB::commit();

            return [
                'status' => true,
                'message' => 'Employee created successfully',
                'employee' => $employee,
                'credentials' => [
                    'username' => $username,
                    'password' => 'Vecura@123',
                    'employee_code' => $empCode,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ ONBOARDING WORKFLOW ============

    public function completeOnboarding($employeeId)
    {
        try {
            $onboarding = EmployeeOnboarding::where('user_id', $employeeId)->firstOrFail();

            $onboarding->update([
                'system_access_provided' => true,
                'system_access_date' => now(),
                'id_card_issued' => true,
                'id_card_date' => now(),
                'equipment_provided' => true,
                'equipment_date' => now(),
                'orientation_completed' => true,
                'orientation_date' => now(),
                'training_started' => true,
                'training_start_date' => now(),
                'documentation_submitted' => true,
                'documentation_date' => now(),
                'onboarding_status' => 'Completed',
            ]);

            return ['status' => true, 'message' => 'Onboarding completed'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ EDUCATION MANAGEMENT ============

    public function addEducation($employeeId, $data)
    {
        try {
            $education = EmployeeEducation::create([
                'user_id' => $employeeId,
                'education_level' => $data['education_level'],
                'institution_name' => $data['institution_name'],
                'course_name' => $data['course_name'] ?? null,
                'field_of_study' => $data['field_of_study'] ?? null,
                'graduation_date' => $data['graduation_date'] ?? null,
                'grade_percentage' => $data['grade_percentage'] ?? null,
                'certificate_file' => $data['certificate_file'] ?? null,
                'status' => 'Active',
            ]);

            return ['status' => true, 'message' => 'Education record added', 'data' => $education];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function markEducationHandover($educationId, $receivedBy)
    {
        try {
            $education = EmployeeEducation::findOrFail($educationId);
            $education->update([
                'certificate_handover_received' => true,
                'handover_date' => now(),
                'received_by' => $receivedBy,
            ]);

            return ['status' => true, 'message' => 'Education certificate marked as handed over'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ CERTIFICATE MANAGEMENT ============

    public function addCertificate($employeeId, $data)
    {
        try {
            $certificate = EmployeeCertificate::create([
                'user_id' => $employeeId,
                'certificate_name' => $data['certificate_name'],
                'issuing_organization' => $data['issuing_organization'] ?? null,
                'issue_date' => $data['issue_date'] ?? null,
                'expiry_date' => $data['expiry_date'] ?? null,
                'certificate_number' => $data['certificate_number'] ?? null,
                'file_path' => $data['file_path'] ?? null,
                'status' => 'Active',
            ]);

            return ['status' => true, 'message' => 'Certificate added', 'data' => $certificate];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function markCertificateHandover($certificateId, $receivedBy)
    {
        try {
            $certificate = EmployeeCertificate::findOrFail($certificateId);
            $certificate->update([
                'is_handover_received' => true,
                'handover_date' => now(),
                'received_by' => $receivedBy,
                'is_original_submitted' => true,
            ]);

            return ['status' => true, 'message' => 'Certificate marked as handed over'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ DOCUMENT MANAGEMENT ============

    public function addDocument($employeeId, $data)
    {
        try {
            $document = EmployeeDocument::create([
                'user_id' => $employeeId,
                'document_type' => $data['document_type'],
                'document_number' => $data['document_number'] ?? null,
                'issue_date' => $data['issue_date'] ?? null,
                'expiry_date' => $data['expiry_date'] ?? null,
                'file_path' => $data['file_path'] ?? null,
                'verification_status' => 'Pending',
            ]);

            return ['status' => true, 'message' => 'Document added', 'data' => $document];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function verifyDocument($documentId, $verifiedBy)
    {
        try {
            $document = EmployeeDocument::findOrFail($documentId);
            $document->update([
                'verification_status' => 'Verified',
                'verified_by' => $verifiedBy,
                'verification_date' => now(),
            ]);

            return ['status' => true, 'message' => 'Document verified'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ BOND MANAGEMENT ============

    public function createBond($employeeId, $data)
    {
        try {
            $startDate = Carbon::parse($data['bond_start_date']);
            $bondYears = (int)$data['bond_duration_years'];
            $endDate = $startDate->copy()->addYears($bondYears);

            $bond = EmployeeBond::create([
                'user_id' => $employeeId,
                'bond_duration_years' => $data['bond_duration_years'],
                'bond_start_date' => $startDate,
                'bond_end_date' => $endDate,
                'bond_amount' => $data['bond_amount'] ?? null,
                'bond_conditions' => $data['bond_conditions'] ?? null,
                'bond_document_file' => $data['bond_document_file'] ?? null,
                'status' => 'Active',
            ]);

            return ['status' => true, 'message' => 'Bond created', 'data' => $bond];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function completeBond($bondId)
    {
        try {
            $bond = EmployeeBond::findOrFail($bondId);
            $bond->update([
                'years_completed' => now(),
                'status' => 'Completed',
            ]);

            return ['status' => true, 'message' => 'Bond marked as completed'];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ RELIEVING PROCESS (2-MONTH NOTICE) ============

    public function initiateRelieving($employeeId, $data)
    {
        try {
            DB::beginTransaction();

            // Calculate notice period end date (2 months = 60 days)
            $resignationDate = Carbon::parse($data['resignation_date']);
            $noticeEndDate = $resignationDate->copy()->addDays(60);
            $relievingDate = $noticeEndDate->copy();

            // Create offboarding record
            $offboarding = EmployeeOffboarding::create([
                'user_id' => $employeeId,
                'resignation_date' => $resignationDate,
                'notice_period_days' => 60,
                'notice_period_end_date' => $noticeEndDate,
                'relieving_date' => $relievingDate,
                'reason_for_resignation' => $data['reason_for_resignation'] ?? null,
                'offboarding_status' => 'In Progress',
                'processed_by' => session('user_id'),
                'processed_date' => now(),
            ]);

            // Update employee status
            UserMaster::findOrFail($employeeId)->update([
                'employee_status' => 'On Leave',
            ]);

            DB::commit();

            return [
                'status' => true,
                'message' => 'Relieving initiated with 2-month notice period',
                'data' => [
                    'resignation_date' => $resignationDate->format('Y-m-d'),
                    'notice_period_end_date' => $noticeEndDate->format('Y-m-d'),
                    'relieving_date' => $relievingDate->format('Y-m-d'),
                    'days_remaining' => $noticeEndDate->diffInDays(now()),
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    public function completeRelieving($employeeId, $data)
    {
        try {
            DB::beginTransaction();

            $offboarding = EmployeeOffboarding::where('user_id', $employeeId)->firstOrFail();

            // Check if all certificates are submitted
            $allCertificatesSubmitted = EmployeeCertificate::where('user_id', $employeeId)
                ->where('is_handover_received', false)
                ->doesntExist();

            // Check if all education is handed over
            $allEducationHandedOver = EmployeeEducation::where('user_id', $employeeId)
                ->where('certificate_handover_received', false)
                ->doesntExist();

            // Check if all documents are verified
            $allDocumentsVerified = EmployeeDocument::where('user_id', $employeeId)
                ->where('verification_status', '!=', 'Verified')
                ->doesntExist();

            $offboarding->update([
                'relieving_date' => $data['relieving_date'] ?? now(),
                'all_certificates_returned' => $allCertificatesSubmitted,
                'all_dues_cleared' => $data['all_dues_cleared'] ?? false,
                'equipment_returned' => $data['equipment_returned'] ?? false,
                'offboarding_status' => 'Completed',
                'final_remarks' => $data['final_remarks'] ?? null,
            ]);

            // Update employee status
            UserMaster::findOrFail($employeeId)->update([
                'employee_status' => 'Inactive',
            ]);

            DB::commit();

            return [
                'status' => true,
                'message' => 'Employee relieving completed',
                'checklist' => [
                    'certificates_handed_over' => $allCertificatesSubmitted,
                    'education_handed_over' => $allEducationHandedOver,
                    'documents_verified' => $allDocumentsVerified,
                    'dues_cleared' => $data['all_dues_cleared'] ?? false,
                    'equipment_returned' => $data['equipment_returned'] ?? false,
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }

    // ============ GET EMPLOYEE WORKFLOW STATUS ============

    public function getEmployeeStatus($employeeId)
    {
        try {
            $employee = UserMaster::with([
                'profile',
                'manager',
            ])->findOrFail($employeeId);

            $onboarding = EmployeeOnboarding::where('user_id', $employeeId)->first();
            $offboarding = EmployeeOffboarding::where('user_id', $employeeId)->first();
            $educations = EmployeeEducation::where('user_id', $employeeId)->get();
            $certificates = EmployeeCertificate::where('user_id', $employeeId)->get();
            $documents = EmployeeDocument::where('user_id', $employeeId)->get();
            $bonds = EmployeeBond::where('user_id', $employeeId)->get();

            return [
                'status' => true,
                'employee' => $employee,
                'onboarding' => $onboarding,
                'offboarding' => $offboarding,
                'educations' => $educations,
                'certificates' => $certificates,
                'documents' => $documents,
                'bonds' => $bonds,
                'summary' => [
                    'onboarding_status' => $onboarding?->onboarding_status,
                    'offboarding_status' => $offboarding?->offboarding_status,
                    'education_count' => $educations->count(),
                    'certificates_count' => $certificates->count(),
                    'documents_count' => $documents->count(),
                    'bonds_count' => $bonds->count(),
                    'pending_certificates' => $certificates->where('is_handover_received', false)->count(),
                    'pending_education' => $educations->where('certificate_handover_received', false)->count(),
                    'pending_documents' => $documents->where('verification_status', 'Pending')->count(),
                ]
            ];
        } catch (\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
}
