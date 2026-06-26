<?php

namespace App\Http\Controllers;

use App\Models\UserMaster;
use Illuminate\Http\Request;

class EmployeeWorkflowController extends Controller
{
    // ============ EDUCATIONAL DOCUMENT WORKFLOW ============

    public function addEducationalDocument(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:Degree,10th Certificate,12th Certificate,Diploma,Certificate Course,Other',
            'document_number' => 'nullable|string',
            'issue_date' => 'nullable|date',
            'file_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = 'emp_' . $employeeId . '_edu_doc_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['file_path'] = $file->storeAs('documents/education', $filename, 'public');
        }

        try {
            $doc = \App\Models\EmployeeEducationalDocument::create([
                'user_id' => $employeeId,
                'document_type' => $validated['document_type'],
                'document_number' => $validated['document_number'],
                'issue_date' => $validated['issue_date'],
                'file_path' => $validated['file_path'],
                'description' => $validated['description'],
                'verification_status' => 'Pending',
            ]);
            return response()->json(['status' => true, 'message' => 'Educational document uploaded', 'data' => $doc]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getEducationalDocuments($employeeId)
    {
        try {
            $docs = \App\Models\EmployeeEducationalDocument::where('user_id', $employeeId)->get();
            return response()->json(['status' => true, 'data' => $docs]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }


    // ============ OFFICIAL DOCUMENT WORKFLOW ============

    public function addDocument(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'document_type' => 'required|in:Aadhar,PAN,Passport,Driving License,Voter ID,Birth Certificate,Medical Report,Police Clearance,Experience Letter,Relieving Letter,Other',
            'document_number' => 'nullable|string',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
            'file_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filename = 'emp_' . $employeeId . '_doc_' . time() . '.' . $file->getClientOriginalExtension();
            $validated['file_path'] = $file->storeAs('documents/official', $filename, 'public');
        }

        try {
            $doc = \App\Models\EmployeeDocument::create([
                'user_id' => $employeeId,
                'document_type' => $validated['document_type'],
                'document_number' => $validated['document_number'],
                'issue_date' => $validated['issue_date'],
                'expiry_date' => $validated['expiry_date'],
                'file_path' => $validated['file_path'],
                'description' => $validated['description'],
                'verification_status' => 'Pending',
            ]);
            return response()->json(['status' => true, 'message' => 'Official document uploaded', 'data' => $doc]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getDocuments($employeeId)
    {
        try {
            $documents = \App\Models\EmployeeDocument::where('user_id', $employeeId)->get();
            return response()->json(['status' => true, 'data' => $documents]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ============ BOND WORKFLOW ============

    public function createBond(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'bond_duration_years' => 'required|integer|min:1',
            'bond_start_date' => 'required|date',
            'bond_amount' => 'nullable|numeric',
            'bond_conditions' => 'nullable|string',
            'bond_document_file' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        if ($request->hasFile('bond_document_file')) {
            $file = $request->file('bond_document_file');
            $filename = 'emp_' . $employeeId . '_bond_' . time() . '.pdf';
            $validated['bond_document_file'] = $file->storeAs('bonds', $filename, 'public');
        }

        try {
            $bondYears = (int)$validated['bond_duration_years'];
            $bondStartDate = \Carbon\Carbon::parse($validated['bond_start_date']);
            $bondEndDate = $bondStartDate->addYears($bondYears);

            $bond = \App\Models\EmployeeBond::create([
                'user_id' => $employeeId,
                'bond_duration_years' => $bondYears,
                'bond_start_date' => $validated['bond_start_date'],
                'bond_end_date' => $bondEndDate,
                'bond_amount' => $validated['bond_amount'],
                'bond_conditions' => $validated['bond_conditions'],
                'bond_document_file' => $validated['bond_document_file'] ?? null,
                'bond_status' => 'Active',
            ]);
            return response()->json(['status' => true, 'message' => 'Bond created successfully', 'data' => $bond]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getBonds($employeeId)
    {
        try {
            $bonds = \App\Models\EmployeeBond::where('user_id', $employeeId)->get();
            return response()->json(['status' => true, 'data' => $bonds]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function completeBond(Request $request, $employeeId, $bondId)
    {
        try {
            $bond = \App\Models\EmployeeBond::findOrFail($bondId);
            $bond->update(['bond_status' => 'Completed']);
            return response()->json(['status' => true, 'message' => 'Bond completed']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ============ RELIEVING WORKFLOW ============

    public function initiateRelieving(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'resignation_date' => 'required|date',
            'reason_for_resignation' => 'nullable|string',
        ]);

        try {
            $resignationDate = \Carbon\Carbon::parse($validated['resignation_date']);
            $noticeCompletionDate = $resignationDate->addMonths(2);

            $relieving = \App\Models\EmployeeRelieving::updateOrCreate(
                ['user_id' => $employeeId],
                [
                    'resignation_date' => $validated['resignation_date'],
                    'notice_completion_date' => $noticeCompletionDate,
                    'reason_for_resignation' => $validated['reason_for_resignation'],
                    'relieving_status' => 'Pending',
                ]
            );
            return response()->json(['status' => true, 'message' => 'Relieving initiated (2-month notice period)', 'data' => $relieving]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function completeRelieving(Request $request, $employeeId)
    {
        $validated = $request->validate([
            'relieving_date' => 'nullable|date',
            'all_dues_cleared' => 'required|boolean',
            'equipment_returned' => 'required|boolean',
            'final_remarks' => 'nullable|string',
        ]);

        try {
            $relieving = \App\Models\EmployeeRelieving::where('user_id', $employeeId)->first();
            if (!$relieving) {
                return response()->json(['status' => false, 'message' => 'No relieving record found'], 404);
            }

            $relieving->update([
                'relieving_date' => $validated['relieving_date'],
                'all_dues_cleared' => $validated['all_dues_cleared'],
                'equipment_returned' => $validated['equipment_returned'],
                'final_remarks' => $validated['final_remarks'],
                'relieving_status' => 'Completed',
            ]);

            \App\Models\UserMaster::where('UserID', $employeeId)->update(['employee_status' => 'Terminated']);

            return response()->json(['status' => true, 'message' => 'Relieving completed']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getEmployeeStatus($employeeId)
    {
        try {
            $employee = UserMaster::findOrFail($employeeId);
            $educationalDocs = \App\Models\EmployeeEducationalDocument::where('user_id', $employeeId)->get();
            $officialDocs = \App\Models\EmployeeDocument::where('user_id', $employeeId)->get();
            $bonds = \App\Models\EmployeeBond::where('user_id', $employeeId)->get();
            $relieving = \App\Models\EmployeeRelieving::where('user_id', $employeeId)->first();

            return response()->json([
                'status' => true,
                'data' => [
                    'employee_id' => $employeeId,
                    'employee_name' => $employee->FullName,
                    'educational_documents' => $educationalDocs,
                    'official_documents' => $officialDocs,
                    'bonds' => $bonds,
                    'relieving' => $relieving,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
