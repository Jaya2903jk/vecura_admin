<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    //
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10); // default 10

        $branches = Branch::orderBy('Id', 'asc')->paginate($perPage);

        return view('branch.index', compact('branches', 'perPage'));
    }
}
