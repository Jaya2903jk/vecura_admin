<?php

namespace App\Http\Controllers;

use App\Services\RbacService;
use App\Models\{Role, Permission, UserMaster, EmployeeRole, EmployeeDepartment, HierarchyAccess};
use Illuminate\Http\Request;

/**
 * RBAC Management Controller
 *
 * Manages Role-Based Access Control with 5-tier hierarchy:
 * 1. Admin (level 1) - Full system access
 * 2. Zone Manager (level 2) - Manage specific zones
 * 3. Branch Manager (level 3) - Manage specific branches
 * 4. Location Manager (level 4) - Manage specific locations
 * 5. Employee (level 5) - Access own data only
 *
 * Workflow:
 * 1. Create Module (auto-generates 5 permissions: read, create, edit, delete, approve)
 * 2. Create Role (assign a hierarchy level 1-5)
 * 3. Assign Permissions to Role (select which permissions role has)
 * 4. Assign Role to Employee (employee gets role)
 * 5. Assign Department to Employee (employee's department for filtering)
 * 6. Assign Hierarchy Access (employee's zone/branch/location scope)
 *
 * Permission Check: When employee accesses page, middleware checks:
 * - Does employee have permission for this module+action?
 * - Is data within employee's hierarchy scope?
 */
class RbacManagementController extends Controller
{
    public function __construct(private RbacService $rbac) {}

    // Step 2: Assign Role to Employee
    // Assigns a role (with specific hierarchy level) to an employee
    // Example: Assign "ZoneManager" role to employee ID 5
    public function assignRoleForm() {
        return view('rbac.assign-role', [
            'employees' => UserMaster::all(),
            'roles' => Role::where('is_active', 1)->get()
        ]);
    }

    public function assignRole(Request $request) {
        $validated = $request->validate(['employee_id' => 'required|exists:User_Master,UserID', 'role_id' => 'required|exists:roles,id']);
        $this->rbac->assignRole($validated['employee_id'], $validated['role_id'], session('user_id'));
        return response()->json(['status' => true, 'message' => 'Role assigned']);
    }

    // Step 3: Assign Department to Employee
    // Assigns a department to an employee (for data filtering)
    // Example: Assign "HR" department to employee ID 5
    public function assignDepartmentForm() {
        return view('rbac.assign-department', [
            'employees' => UserMaster::all(),
            'departments' => \App\Models\IssueDepartment::all()
        ]);
    }

    public function assignDepartment(Request $request) {
        $validated = $request->validate(['employee_id' => 'required|exists:User_Master,UserID', 'department_id' => 'required|exists:issueDepartmentMaster,Departmentid']);
        $this->rbac->assignDepartment($validated['employee_id'], $validated['department_id'], session('user_id'));
        return response()->json(['status' => true, 'message' => 'Department assigned']);
    }

    // Step 4: Assign Hierarchy Access to Employee
    // Defines the geographic/organizational scope for an employee's role
    // Example: ZoneManager can only see Zone 1 data
    //          BranchManager can only see Branch 5 data
    //          LocationManager can only see Location 12 data
    public function assignHierarchyForm() {
        return view('rbac.assign-hierarchy', [
            'employees' => UserMaster::all(),
            'roles' => Role::where('is_active', 1)->get(),
            'zones' => \App\Models\Zone::all(),
            'branches' => \App\Models\NewBranch::all(),
            'locations' => \App\Models\Location::all()
        ]);
    }

    public function assignHierarchy(Request $request) {
        $validated = $request->validate([
            'employee_id' => 'required|exists:User_Master,UserID',
            'role_id' => 'required|exists:roles,id',
            'zone_id' => 'nullable|exists:zone,zone_id',
            'branch_id' => 'nullable|exists:branch,branch_id',
            'location_id' => 'nullable|exists:location,location_id'
        ]);
        $this->rbac->assignHierarchyAccess(
            $validated['employee_id'], $validated['role_id'],
            $validated['zone_id'], $validated['branch_id'], $validated['location_id'],
            session('user_id')
        );
        return response()->json(['status' => true, 'message' => 'Hierarchy access assigned']);
    }

    // Step 1B: Manage Roles and Assign Permissions
    // Create roles and assign which permissions each role has
    // Example: "ZoneManager" role gets "read:ticket", "read:iou", "approve:iou" permissions
    public function manageRoles() {
        return view('rbac.manage-roles', ['roles' => Role::with('permissions')->paginate(10)]);
    }

    public function createRole(Request $request) {
        $validated = $request->validate(['name' => 'required|unique:roles|string|max:100', 'level' => 'required|integer', 'description' => 'nullable|string']);
        Role::create($validated + ['is_active' => 1]);
        return response()->json(['status' => true, 'message' => 'Role created']);
    }

    // Adds a permission to a role
    // Example: Add "read:ticket" permission to "Employee" role
    public function assignPermissionToRole(Request $request, $roleId) {
        $validated = $request->validate(['permission_id' => 'required|exists:permissions,id']);
        $role = Role::findOrFail($roleId);
        $role->permissions()->syncWithoutDetaching($validated['permission_id']);
        return response()->json(['status' => true, 'message' => 'Permission assigned']);
    }

    // Removes a permission from a role
    // Example: Remove "delete:ticket" from "Employee" role
    public function revokePermission(Request $request, $roleId, $permissionId) {
        $role = Role::findOrFail($roleId);
        $role->permissions()->detach($permissionId);
        return response()->json(['status' => true, 'message' => 'Permission revoked']);
    }

    // View all access granted to a specific employee
    // Shows: Roles, Departments, Hierarchy scope (zone/branch/location)
    public function employeeAccess($employeeId) {
        $employee = UserMaster::findOrFail($employeeId);
        return view('rbac.employee-access', [
            'employee' => $employee,
            'roles' => $this->rbac->getEmployeeRoles($employeeId),
            'departments' => $this->rbac->getEmployeeDepartments($employeeId),
            'hierarchy' => $this->rbac->getEmployeeHierarchy($employeeId)
        ]);
    }

    // Remove a role from an employee
    public function removeRole($employeeId, $roleId) {
        $this->rbac->removeRole($employeeId, $roleId);
        return response()->json(['status' => true, 'message' => 'Role removed']);
    }

    // Remove department assignment from employee
    public function removeDepartment($employeeId, $departmentId) {
        $this->rbac->removeDepartment($employeeId, $departmentId);
        return response()->json(['status' => true, 'message' => 'Department removed']);
    }

    // API: Get all permissions assigned to a role
    public function getRolePermissions($roleId) {
        $role = Role::with('permissions')->findOrFail($roleId);
        return response()->json(['permissions' => $role->permissions]);
    }

    // API: Get all permissions in system
    public function getAllPermissions() {
        $permissions = Permission::get();
        return response()->json($permissions);
    }

    // API: Get all active modules in system
    public function getAllModules() {
        $modules = \App\Models\Module::where('is_active', 1)->get();
        return response()->json($modules);
    }

    // Step 1A: Create Modules (auto-creates 5 permissions)
    // Admin-only page to create modules and manage permissions
    // Typically used by senior admin to set up new features
    public function managePermissionsAdmin() {
        if (!session('is_admin')) {
            abort(403, 'Admin only');
        }

        return view('rbac.manage-permissions-admin', [
            'modules' => \App\Models\Module::where('is_active', 1)->get()
        ]);
    }

    // Create a permission manually (if not auto-created with module)
    // Rarely used - permissions are auto-created when module is created
    public function createPermission(Request $request) {
        if (!session('is_admin')) {
            abort(403, 'Admin only');
        }

        $validated = $request->validate([
            'name' => 'required|string|in:read,create,edit,delete,approve',
            'module' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean'
        ]);

        $exists = Permission::where('name', $validated['name'])
            ->where('module', $validated['module'])
            ->first();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'Permission already exists'
            ]);
        }

        Permission::create([
            'name' => $validated['name'],
            'module' => $validated['module'],
            'description' => $validated['description'] ?? "{$validated['name']} on {$validated['module']}",
            'is_active' => $validated['is_active'] ?? 1
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Permission created successfully'
        ]);
    }

    // Delete a permission (admin only)
    // Warning: This affects all roles using this permission
    public function deletePermission($id) {
        if (!session('is_admin')) {
            abort(403, 'Admin only');
        }

        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return response()->json([
                'status' => true,
                'message' => 'Permission deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAllRoles() {
        try {
            $roles = Role::select('id', 'role_name', 'description')->get();
            return response()->json([
                'status' => true,
                'roles' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
