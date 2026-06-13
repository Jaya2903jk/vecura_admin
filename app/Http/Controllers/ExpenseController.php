<?php

namespace App\Http\Controllers;

use App\Models\ExpenseMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    //

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $ExpenseMaster = ExpenseMaster::orderBy('ExpenseId', 'asc')->paginate($perPage);
$totalCount = ExpenseMaster::count();
        return view('expense.index', compact('ExpenseMaster', 'perPage', 'totalCount'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        try {

            $validator = Validator::make($request->all(), [
                'expense_name' => 'required|string|max:255',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }
            $status = strtolower($request->status) === 'active' ? 1 : 0;

            ExpenseMaster::create([
                'ExpenseName' => $request->expense_name,
                'Status' => $status,
                'CreatedBy' => session('user_id'),
                'CreatedDate' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Expense Created Successfully',
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
