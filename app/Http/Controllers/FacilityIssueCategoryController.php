<?php
namespace App\Http\Controllers;

use App\Models\FacilityIssueCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacilityIssueCategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = $request->get('per_page', 10);
        $search     = $request->get('search');
        $status     = $request->get('status');

        $query = FacilityIssueCategory::orderBy('id', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($status !== null && $status !== '') {
            $statusVal = strtolower($status) === 'active' ? 1 : 0;
            $query->where('status', $statusVal);
        }

        $categories = $query->paginate($perPage);
        $totalCount = $categories->total();

        return view('ticket.facility.index', compact('categories', 'perPage', 'totalCount'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        FacilityIssueCategory::create([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => strtolower($request->status) === 'active' ? 1 : 0,
        ]);

        return response()->json(['status' => true, 'message' => 'Category Created Successfully']);
    }

    public function update(Request $request, $id)
    {
        $category = FacilityIssueCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
            'status'      => strtolower($request->status) === 'active' ? 1 : 0,
        ]);

        return response()->json(['status' => true, 'message' => 'Category Updated Successfully']);
    }

    public function destroy($id)
    {
        FacilityIssueCategory::findOrFail($id)->delete();
        return response()->json(['status' => true, 'message' => 'Category Deleted Successfully']);
    }
}
