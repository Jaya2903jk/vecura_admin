<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ZoneController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = $request->get('per_page', 10);
        $zones      = Zone::with('country')->orderBy('zone_id', 'asc')->paginate($perPage);
        $totalCount = Zone::count();
        $countries  = Country::where('is_active', 1)->orderBy('country_name')->get();

        return view('zone.index', compact('zones', 'perPage', 'totalCount', 'countries'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'zone_code'   => 'required|string|max:20',
                'zone_name'   => 'required|string|max:100',
                'country_id'  => 'required|integer|exists:sqlsrv.country,country_id',
                'region_type' => 'nullable|string|max:50',
                'is_active'   => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            Zone::create([
                'zone_code'   => strtoupper(trim($request->zone_code)),
                'zone_name'   => trim($request->zone_name),
                'country_id'  => $request->country_id,
                'region_type' => $request->region_type,
                'is_active'   => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Zone created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $zone      = Zone::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'zone_code'   => 'required|string|max:20',
                'zone_name'   => 'required|string|max:100',
                'country_id'  => 'required|integer|exists:sqlsrv.country,country_id',
                'region_type' => 'nullable|string|max:50',
                'is_active'   => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $zone->update([
                'zone_code'   => strtoupper(trim($request->zone_code)),
                'zone_name'   => trim($request->zone_name),
                'country_id'  => $request->country_id,
                'region_type' => $request->region_type,
                'is_active'   => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Zone updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Zone::findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => 'Zone deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }
}
