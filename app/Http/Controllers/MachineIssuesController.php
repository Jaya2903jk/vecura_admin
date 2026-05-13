<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineIssues;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MachineIssuesController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $MachineIssues = MachineIssues::orderBy('machineIssueId', 'asc')->paginate($perPage);
        $machines = Machine::all();

        return view('machine-issues.index', compact('MachineIssues', 'perPage', 'machines'));
    }

    public function store(Request $request)
    {
        $loginUserId = session('user_id');
        try {

            $validator = Validator::make($request->all(), [
                'issues_name' => 'required|string|max:255',
                'machine_id' => 'required',
                'type' => 'required|string|max:50',
                'status' => 'required|string|max:20',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            MachineIssues::create([
                'IssuesName' => $request->issues_name,
                'MachineId' => $request->machine_id,
                'Type' => $request->type,
                'Status' => $request->status,
                'CreatedBy' => $loginUserId,
                'CreatedDate' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Machine Issue Created Successfully',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMachineIssues(Request $request)
    {
        $issues = MachineIssues::where('MachineId', $request->machine_id)
            ->where('Type', $request->type)
            ->get();

        return response()->json([
            'status' => true,
            'data' => $issues,
        ]);
    }
}
