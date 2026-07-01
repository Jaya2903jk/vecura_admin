<?php

namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\UserMaster;
use App\Models\NewBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        $query = Target::with(['employee', 'branch']);

        if ($request->target_type) {
            $query->where('target_type', $request->target_type);
        }

        if ($request->branch_id) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->employee_id) {
            $query->where('user_id', $request->employee_id);
        }

        if ($request->search) {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('FullName', 'like', "%{$request->search}%")
                  ->orWhere('UserCode', 'like', "%{$request->search}%");
            });
        }

        $targets = $query->paginate(15);
        $branches = NewBranch::where('is_active', 1)->get();
        $employees = UserMaster::orderBy('FullName')->limit(100)->get();

        if ($request->ajax()) {
            return response()->json([
                'data' => $targets,
                'branches' => $branches,
                'employees' => $employees,
            ]);
        }

        return view('targets.index', compact('targets', 'branches', 'employees'));
    }

    public function create()
    {
        $branches = NewBranch::where('is_active', 1)->get();
        $employees = UserMaster::orderBy('FullName')->get();

        return view('targets.create', compact('branches', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:User_Master,UserID',
            'branch_id' => 'nullable|exists:branch,branch_id',
            'target_type' => 'required|in:day,month',
            'target_amount' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'required|date|after_or_equal:effective_from',
            'description' => 'nullable|string|max:255',
        ]);

        $validated['created_by'] = session('user_code') ?? auth()->user()->UserCode ?? 'System';

        Target::create($validated);

        return redirect()->route('targets.index')->with('success', 'Target created successfully');
    }

    public function edit(Target $target)
    {
        $branches = NewBranch::where('is_active', 1)->get();
        $employees = UserMaster::orderBy('FullName')->get();

        return view('targets.edit', compact('target', 'branches', 'employees'));
    }

    public function update(Request $request, Target $target)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:User_Master,UserID',
            'branch_id' => 'nullable|exists:branch,branch_id',
            'target_type' => 'required|in:day,month',
            'target_amount' => 'required|numeric|min:0',
            'effective_from' => 'required|date',
            'effective_to' => 'required|date|after_or_equal:effective_from',
            'description' => 'nullable|string|max:255',
        ]);

        $target->update($validated);

        return redirect()->route('targets.index')->with('success', 'Target updated successfully');
    }

    public function destroy(Target $target)
    {
        $target->delete();

        return redirect()->route('targets.index')->with('success', 'Target deleted successfully');
    }
}
