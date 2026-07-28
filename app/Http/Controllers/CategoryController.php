<?php

namespace App\Http\Controllers;

use App\Models\IssueCategory;
use App\Models\IssueDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $status = $request->get('status');

        $query = IssueCategory::with('department')->orderBy('category_id', 'asc');

        if ($search) {
            $query->where('category_name', 'like', "%{$search}%");
        }

        if ($status) {
            $query->where('status', $status);
        }

        $categories = $query->paginate($perPage);
        $departments = IssueDepartment::all();

        return view('category.index', compact('categories', 'perPage', 'departments'));
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'category_name' => 'required|string|max:255',
                'department_id' => 'required|exists:issueDepartmentMaster,Departmentid',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }

            IssueCategory::create([
                'category_name' => $request->category_name,
                'department_id' => $request->department_id,
                'status' => $request->status,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Category Created Successfully',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(), // remove in production
            ], 500);
        }
    }
}
