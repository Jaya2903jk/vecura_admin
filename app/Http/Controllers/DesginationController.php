<?php

namespace App\Http\Controllers;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesginationController extends Controller {
    //

    public function index( Request $request ) {
        $perPage = $request->get( 'per_page', 10 );

        $designations = Designation::orderBy( 'id', 'asc' )
        ->paginate( $perPage );

        return view( 'designation.index', compact( 'designations', 'perPage' ) );
    }
}
