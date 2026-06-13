<?php

namespace App\Http\Controllers;

use App\Models\NewBranch;
use App\Models\Zone;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class NewBranchController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = $request->get('per_page', 10);
        $branches   = NewBranch::with(['zone', 'city','wallet'])->orderBy('branch_id', 'asc')->paginate($perPage);
        $totalCount = NewBranch::count();
        $zones      = Zone::where('is_active', 1)->orderBy('zone_name')->get();
        $cities     = City::where('is_active', 1)->orderBy('city_name')->get();

        return view('new-branch.index', compact('branches', 'perPage', 'totalCount', 'zones', 'cities'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'branch_name' => 'required|string|max:150',
                'branch_code' => [
                    'required', 'string', 'max:20',
                    Rule::unique('sqlsrv.branch', 'branch_code'),
                ],
                'zone_id'      => 'required|integer|exists:sqlsrv.zone,zone_id',
                'city_id'      => 'required|integer|exists:sqlsrv.city,city_id',
                'manager_name' => 'nullable|string|max:100',
                'contact_no'   => 'nullable|string|max:20',
                'is_active'    => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            NewBranch::create([
                'branch_name'  => trim($request->branch_name),
                'branch_code'  => strtoupper(trim($request->branch_code)),
                'zone_id'      => $request->zone_id,
                'city_id'      => $request->city_id,
                'manager_name' => $request->manager_name,
                'contact_no'   => $request->contact_no,
                'is_active'    => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Branch created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $branch = NewBranch::findOrFail($id);

            // Status-only toggle (from Active/Inactive button)
            if ($request->boolean('_status_only')) {
                $branch->update(['is_active' => $request->is_active]);
                return response()->json(['status' => true, 'message' => 'Status updated successfully']);
            }

            $validator = Validator::make($request->all(), [
                'branch_name' => 'required|string|max:150',
                'branch_code' => [
                    'required', 'string', 'max:20',
                    Rule::unique('sqlsrv.branch', 'branch_code')->ignore($id, 'branch_id'),
                ],
                'zone_id'      => 'required|integer|exists:sqlsrv.zone,zone_id',
                'city_id'      => 'required|integer|exists:sqlsrv.city,city_id',
                'manager_name' => 'nullable|string|max:100',
                'contact_no'   => 'nullable|string|max:20',
                'is_active'    => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $branch->update([
                'branch_name'  => trim($request->branch_name),
                'branch_code'  => strtoupper(trim($request->branch_code)),
                'zone_id'      => $request->zone_id,
                'city_id'      => $request->city_id,
                'manager_name' => $request->manager_name,
                'contact_no'   => $request->contact_no,
                'is_active'    => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Branch updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            NewBranch::findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => 'Branch deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }
}
