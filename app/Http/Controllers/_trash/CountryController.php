<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $perPage    = $request->get('per_page', 10);
        $countries  = Country::orderBy('country_id', 'asc')->paginate($perPage);
        $totalCount = Country::count();

        return view('country.index', compact('countries', 'perPage', 'totalCount'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country_code' => 'required|string|max:2|unique:sqlsrv.country,country_code',
                'country_name' => 'required|string|max:100',
                'is_active'    => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            Country::create([
                'country_code' => strtoupper(trim($request->country_code)),
                'country_name' => trim($request->country_name),
                'is_active'    => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Country created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $country   = Country::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'country_code' => ['required', 'string', 'max:2', Rule::unique('sqlsrv.country', 'country_code')->ignore($id, 'country_id')],
                'country_name' => 'required|string|max:100',
                'is_active'    => 'required|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $country->update([
                'country_code' => strtoupper(trim($request->country_code)),
                'country_name' => trim($request->country_name),
                'is_active'    => $request->is_active,
            ]);

            return response()->json(['status' => true, 'message' => 'Country updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            Country::findOrFail($id)->delete();
            return response()->json(['status' => true, 'message' => 'Country deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }
}
