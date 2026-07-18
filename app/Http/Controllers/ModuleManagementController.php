<?php

namespace App\Http\Controllers;

use App\Models\{Module, Permission};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ModuleManagementController extends Controller
{
    public function index() {
        return view('modules.index');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|unique:modules|string|max:100',
            'parent' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'route_prefix' => 'nullable|string',
            'icon' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        try {
            $module = Module::create($validated + ['is_active' => $validated['is_active'] ?? 1]);

            $this->createPermissionsForModule($module->name);

            // Auto-clear sidebar cache on module creation
            Cache::forget('sidebar_modules');

            return response()->json([
                'status' => true,
                'message' => 'Module created with 5 permissions'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function createPermissionsForModule($moduleName) {
        $actions = ['read', 'create', 'edit', 'delete', 'approve'];
        $descriptions = [
            'read' => 'View records',
            'create' => 'Add new records',
            'edit' => 'Modify existing records',
            'delete' => 'Remove records',
            'approve' => 'Approve workflow'
        ];

        foreach ($actions as $action) {
            Permission::firstOrCreate(
                [
                    'name' => $action,
                    'module' => $moduleName
                ],
                [
                    'description' => $descriptions[$action],
                    'is_active' => 1
                ]
            );
        }
    }

    public function update(Request $request, $id) {
        try {
            $module = Module::findOrFail($id);

            $validated = $request->validate([
                'parent' => 'nullable|string|max:100',
                'description' => 'nullable|string',
                'icon' => 'nullable|string',
                'sort_order' => 'nullable|integer|min:0',
                'is_active' => 'nullable|boolean'
            ]);

            $module->update($validated);

            // Auto-clear sidebar cache on module update
            Cache::forget('sidebar_modules');

            return response()->json([
                'status' => true,
                'message' => 'Module updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id) {
        try {
            $module = Module::findOrFail($id);
            $moduleName = $module->name;

            Permission::where('module', $moduleName)->delete();
            $module->delete();

            // Auto-clear sidebar cache on module deletion
            Cache::forget('sidebar_modules');

            return response()->json([
                'status' => true,
                'message' => 'Module and its permissions deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
