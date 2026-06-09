<?php

namespace App\Http\Controllers;

use App\Models\LocationMaster;
use App\Models\StateMaster;
use App\Models\CityMaster;
use App\Models\ZoneMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller {
    public function index( Request $request ) {
        $perPage = $request->get( 'per_page', 10 );
        $locations = LocationMaster::orderBy( 'LocationID', 'asc' )->paginate( $perPage );
        $totalCount = LocationMaster::count();
        $states = StateMaster::orderBy( 'state_name')->get();
        $cities = CityMaster::orderBy( 'city_name' )->get();
        $zones = ZoneMaster::orderBy( 'StateName' )->get();
        // dd(    $locations->toArray() );
        // adjust column name
        return view( 'location.index', compact(
            'locations',
            'perPage',
            'totalCount',
            'states',
            'cities',
            'zones'
        ) );
    }

    public function store( Request $request ) {
        dd( $request->all() );
        try {
            $validator = Validator::make( $request->all(), [
                'location_code' => 'required|string|max:50',
                'location_name' => 'required|string|max:255',
                'location_address' => 'required|string',
                'pincode' => 'required|string|max:10',
                'status' => 'required|string',
            ] );

            if ( $validator->fails() ) {
                return response()->json( [
                    'errors' => $validator->errors(),
                ], 422 );
            }

            LocationMaster::create( [
                'LocationCode' => $request->location_code,
                'LocationName' => $request->location_name,
                'LocationAddress' => $request->location_address,
                'LocPinCode' => $request->pincode,
                'LStatus' => $request->status, // e.g., 'Active'
                'StateCode' => $request->state_code,
                'CityCode' => $request->city_code,
                'ZoneState' => $request->zone_state,
                'GSTNo' => $request->gst_no,
                'CountryCode' => $request->country_code ?? 'ADY1',
                'phoneno' => $request->phone_no,
            ] );

            return response()->json( [
                'status' => true,
                'message' => 'Location Created Successfully',
            ] );
        } catch ( \Exception $e ) {
            return response()->json( [
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(),
            ], 500 );
        }
    }
}
