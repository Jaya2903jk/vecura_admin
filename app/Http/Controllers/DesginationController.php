<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DesginationController extends Controller
{
    //
    public function index( Request $request ) {
        return view( 'designation.index' );
    }
}
