<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = $request->get('per_page', 10);
        $cities     = City::with('state')->orderBy('city_id', 'asc')->paginate($perPage);
        $totalCount = City::count();
        $states     = State::where('is_active', 1)->orderBy('state_name')->get();

        return view('city.index', compact('cities', 'perPage', 'totalCount', 'states'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'city_name' => 'required|string|max:100',
                'state_id'  => 'required|integer|exists:sqlsrv.state,state_id',
                'pincode'   => 'nullable|string|max:20',
                'is_active' => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            City::create([
                'city_name' => trim($request->city_name),
                'state_id'  => $request->state_id,
                'pincode'   => $request->pincode,
                'is_active' => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'City created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $city      = City::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'city_name' => 'required|string|max:100',
                'state_id'  => 'required|integer|exists:sqlsrv.state,state_id',
                'pincode'   => 'nullable|string|max:20',
                'is_active' => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $city->update([
                'city_name' => trim($request->city_name),
                'state_id'  => $request->state_id,
                'pincode'   => $request->pincode,
                'is_active' => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'City updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            City::findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => 'City deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }
}
