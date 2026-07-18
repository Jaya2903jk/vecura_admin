<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\IssueDepartment;
use App\Models\DesignationDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DesignationDepartmentController extends Controller
{
    public function index()
    {
        $designations = Designation::with('departmentMappings.department')
            ->orderBy('DesignationCode')
            ->paginate(10);

        $departments = IssueDepartment::all();

        return view('designation.department-mapping', [
            'designations' => $designations,
            'departments' => $departments,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation_id' => 'required|exists:DesignationMaster,id',
            'department_ids' => 'required|array|min:1',
            'department_ids.*' => 'exists:IssueDepartment,Departmentid',
        ]);

        try {
            $designation = Designation::findOrFail($validated['designation_id']);

            // Delete existing mappings
            DesignationDepartment::where('designation_id', $designation->id)->delete();

            // Create new mappings
            foreach ($validated['department_ids'] as $deptId) {
                DesignationDepartment::create([
                    'designation_id' => $designation->id,
                    'designation_code' => $designation->DesignationCode,
                    'department_id' => $deptId,
                    'is_active' => true,
                ]);
            }

            Cache::forget('designations_list');
            Cache::forget('issue_departments');

            return response()->json([
                'status' => true,
                'message' => 'Departments mapped successfully to ' . $designation->Designation,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $designation = Designation::with('departmentMappings.department')->findOrFail($id);
            $departments = IssueDepartment::all();
            $mappedDepartments = $designation->departmentMappings->pluck('department_id')->toArray();

            return response()->json([
                'status' => true,
                'designation' => $designation,
                'departments' => $departments,
                'mapped_departments' => $mappedDepartments,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $mapping = DesignationDepartment::findOrFail($id);
            $mapping->delete();

            Cache::forget('designations_list');
            Cache::forget('issue_departments');

            return response()->json([
                'status' => true,
                'message' => 'Department mapping removed',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
