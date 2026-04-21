<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IssueDepartment;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller {
    //

    public function index( Request $request ) {
        $perPage = $request->get( 'per_page', 10 );
        $IssueDepartment = IssueDepartment::orderBy( 'Departmentid', 'asc' )->paginate( $perPage );
        return view( 'department.index', compact( 'IssueDepartment', 'perPage' ) );
    }

    public function store( Request $request ) {
        dd($request->all());
        try {

            $validator = Validator::make( $request->all(), [
                'department_name' => 'required|string|max:255',
                'status'        => 'required',
            ] );

            if ( $validator->fails() ) {
                return response()->json( [
                    'errors' => $validator->errors()
                ], 422 );
            }

            IssueDepartment::create( [
                'DepartmentName' => $request->department_name,
                'status'        => $request->status,
            ] );

            return response()->json( [
                'status' => true,
                'message' => 'Department Created Successfully'
            ] );
        } catch ( \Exception $e ) {

            return response()->json( [
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage() // remove in production
            ], 500 );
        }
    }
}
