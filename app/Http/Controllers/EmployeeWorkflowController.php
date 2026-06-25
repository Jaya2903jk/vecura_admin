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

    public function verifyEducationalDocument(Request $request, $employeeId, $docId)
    {
        $request->validate(['verified_by' => 'required|string']);
        try {
            $doc = \App\Models\EmployeeEducationalDocument::findOrFail($docId);
            $doc->update([
                'verification_status' => 'Verified',
                'verified_by' => $request->verified_by,
                'verification_date' => now(),
            ]);
            return response()->json(['status' => true, 'message' => 'Educational document verified']);
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

    public function verifyDocument(Request $request, $employeeId, $documentId)
    {
        $request->validate(['verified_by' => 'required|string']);
        try {
            $doc = \App\Models\EmployeeDocument::findOrFail($documentId);
            $doc->update([
                'verification_status' => 'Verified',
                'verified_by' => $request->verified_by,
                'verification_date' => now(),
            ]);
            return response()->json(['status' => true, 'message' => 'Official document verified']);
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

            return response()->json([
                'status' => true,
                'data' => [
                    'employee_id' => $employeeId,
                    'employee_name' => $employee->FullName,
                    'educational_documents' => $educationalDocs,
                    'official_documents' => $officialDocs,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
