<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\City;
use App\Models\State;
use App\Models\NewBranch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = $request->get('per_page', 10);
        $locations  = Location::with(['city.state', 'branch'])->orderBy('location_id', 'asc')->paginate($perPage);
        $totalCount = Location::count();
        $cities     = City::where('is_active', 1)->orderBy('city_name')->get();
        $branches   = NewBranch::where('is_active', 1)->orderBy('branch_name')->get();

        return view('location.index', compact(
            'locations',
            'perPage',
            'totalCount',
            'cities',
            'branches'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'location_name' => 'required|string|max:150',
                'city_id'       => 'required|integer|exists:sqlsrv.city,city_id',
                'branch_id'     => 'nullable|integer|exists:sqlsrv.branch,branch_id',
                'address'       => 'nullable|string',
                'pincode'       => 'nullable|string|max:20',
                'latitude'      => 'nullable|numeric|between:-90,90',
                'longitude'     => 'nullable|numeric|between:-180,180',
                'is_active'     => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            Location::create([
                'location_name' => trim($request->location_name),
                'city_id'       => $request->city_id,
                'branch_id'     => $request->branch_id ?: null,
                'address'       => $request->address,
                'pincode'       => $request->pincode,
                'latitude'      => $request->latitude ?: null,
                'longitude'     => $request->longitude ?: null,
                'is_active'     => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Location created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $location  = Location::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'location_name' => 'required|string|max:150',
                'city_id'       => 'required|integer|exists:sqlsrv.city,city_id',
                'branch_id'     => 'nullable|integer|exists:sqlsrv.branch,branch_id',
                'address'       => 'nullable|string',
                'pincode'       => 'nullable|string|max:20',
                'latitude'      => 'nullable|numeric|between:-90,90',
                'longitude'     => 'nullable|numeric|between:-180,180',
                'is_active'     => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $location->update([
                'location_name' => trim($request->location_name),
                'city_id'       => $request->city_id,
                'branch_id'     => $request->branch_id ?: null,
                'address'       => $request->address,
                'pincode'       => $request->pincode,
                'latitude'      => $request->latitude ?: null,
                'longitude'     => $request->longitude ?: null,
                'is_active'     => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Location updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Location::findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => 'Location deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }
}
