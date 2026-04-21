<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BranchController extends Controller
{
    //
    public function index( Request $request ) {
        return view( 'branch.index' );
    }
}
