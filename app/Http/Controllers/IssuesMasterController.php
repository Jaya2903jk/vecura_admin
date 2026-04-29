<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ApprovalFlow;
use App\Models\IssueMaster;
use App\Models\IssueCategory;
use App\Models\IssueDepartment;
use Illuminate\Support\Facades\Validator;

class IssuesMasterController extends Controller {
    /**
    * GET ALL RECORDS
    */

    public function index( Request $request ) {
        $perPage = $request->get( 'per_page', 10 );

        $issues = IssueMaster::with( [ 'department', 'category' ] )
        ->orderBy( 'IssueId', 'desc' )
        ->paginate( $perPage );

        $departments = IssueDepartment::all();
        $categories = IssueCategory::all();
        // IMPORTANT
        return view( 'issues-master.index', compact( 'issues', 'perPage', 'departments', 'categories' ) );
    }
    /**
    * CREATE NEW RECORD
    */

    public function store( Request $request ) {
        // dd( $request->all() );
        try {

            $validator = Validator::make( $request->all(), [
                'issues_name'   => 'required|string|max:255',
                'department_id' => 'required',
                'category_id'   => 'required',
                'status'        => 'required',
            ] );

            if ( $validator->fails() ) {
                return response()->json( [
                    'errors' => $validator->errors()
                ], 422 );
            }

            $issue = IssueMaster::create( [
                'DepartmentId' => $request->department_id,
                'CategoryId'   => $request->category_id,
                'IssueName'    => $request->issues_name,
                'Status'       => $request->status,
            ] );

            return response()->json( [
                'status' => true,
                'message' => 'Issue Created Successfully',
                'id' => $issue->IssueId
            ] );

        } catch ( \Exception $e ) {

            return response()->json( [
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage() // remove in production
            ], 500 );
        }
    }
    /**
    * GET SINGLE RECORD
    */

    public function show( $id ) {
        $data = DB::connection( 'sqlsrv' )
        ->table( 'issueMasterTest' )
        ->where( 'id', $id )
        ->first();

        if ( !$data ) {
            return response()->json( [
                'status' => false,
                'message' => 'Not Found'
            ], 404 );
        }

        return response()->json( [
            'status' => true,
            'data' => $data
        ] );
    }

    /**
    * UPDATE RECORD
    */

    public function update( Request $request, $id ) {
        $record = DB::connection( 'sqlsrv' )
        ->table( 'issueMasterTest' )
        ->where( 'IssueId', $id )
        ->first();

        if ( !$record ) {
            return response()->json( [
                'status' => false,
                'message' => 'Not Found'
            ], 404 );
        }

        DB::connection( 'sqlsrv' )
        ->table( 'issueMasterTest' )
        ->where( 'IssueId', $id )
        ->update( [
            'name' => $request->name ?? $record->name,
            'status' => $request->status ?? $record->status,
            'updated_at' => now()
        ] );

        ApprovalFlow::where( 'issueId', $id )->delete();
        $approvalFlow = $request->approvalFlow ?? [];
        foreach ( $approvalFlow as $flow ) {
            ApprovalFlow::create( [
                'issueId'    => $id,
                'levelOrder' => $flow[ 'levelOrder' ],
                'roleId'     => $flow[ 'roleId' ],
                'levelName'  => $flow[ 'levelName' ],
                'status'     => $flow[ 'status' ] ?? 'Pending',
                'note'       => $flow[ 'note' ] ?? '',
            ] );
        }

        return response()->json( [
            'status' => true,
            'message' => 'Updated successfully'
        ] );
    }

    /**
    * DELETE RECORD
    */

    public function destroy( $id ) {
        $record = DB::connection( 'sqlsrv' )
        ->table( 'issueMasterTest' )
        ->where( 'IssueId', $id )
        ->first();

        if ( !$record ) {
            return response()->json( [
                'status' => false,
                'message' => 'Not Found'
            ], 404 );
        }

        DB::connection( 'sqlsrv' )
        ->table( 'issueMasterTest' )
        ->where( 'IssueId', $id )
        ->delete();

        return response()->json( [
            'status' => true,
            'message' => 'Deleted successfully'
        ] );
    }
}
