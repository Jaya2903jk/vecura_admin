<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MachineController extends Controller
{
    //

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $Machines = Machine::orderBy('MachineId', 'asc')->paginate($perPage);

        return view('machine.index', compact('Machines', 'perPage'));
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'machine_name' => 'required|string|max:255',
                'status' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }
            $status = strtolower($request->status) === 'active' ? 1 : 0;

            Machine::create([
                'MachineName' => $request->machine_name,
                'Status' => 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Machine Created Successfully',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage(), // remove in production
            ], 500);
        }
    }

    public function getMachines()
    {
        $machines = Machine::select('MachineId', 'MachineName')->get();

        return response()->json([
            'status' => true,
            'data' => $machines,
        ]);
    }
}
