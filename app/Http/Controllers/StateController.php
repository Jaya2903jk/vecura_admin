<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = $request->get('per_page', 10);
        $states     = State::with('country')->orderBy('state_id', 'asc')->paginate($perPage);
        $totalCount = State::count();
        $countries  = Country::where('is_active', 1)->orderBy('country_name')->get();

        return view('state.index', compact('states', 'perPage', 'totalCount', 'countries'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'state_code' => 'required|string|max:10',
                'state_name' => 'required|string|max:100',
                'country_id' => 'required|integer|exists:sqlsrv.country,country_id',
                'is_active'  => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            State::create([
                'state_code' => strtoupper(trim($request->state_code)),
                'state_name' => trim($request->state_name),
                'country_id' => $request->country_id,
                'is_active'  => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'State created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $state     = State::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'state_code' => 'required|string|max:10',
                'state_name' => 'required|string|max:100',
                'country_id' => 'required|integer|exists:sqlsrv.country,country_id',
                'is_active'  => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $state->update([
                'state_code' => strtoupper(trim($request->state_code)),
                'state_name' => trim($request->state_name),
                'country_id' => $request->country_id,
                'is_active'  => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'State updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            State::findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => 'State deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }
}
