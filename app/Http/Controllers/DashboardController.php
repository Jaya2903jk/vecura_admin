<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use App\Models\UserMaster;
use App\Models\IssueDepartment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $rbacRoleName = session('rbac_role_name', 'Employee');

        $data = match ($rbacRoleName) {
            'Admin' => $this->getAdminDashboardData(),
            'ZoneManager' => $this->getZoneManagerDashboardData($userId),
            'BranchManager' => $this->getBranchManagerDashboardData($userId),
            'LocationManager' => $this->getLocationManagerDashboardData($userId),
            default => $this->getEmployeeDashboardData($userId),
        };

        return view('dashboard', $data);
    }

    private function getAdminDashboardData()
    {
        return [
            'totalUsers' => UserMaster::count(),
            'activeRoles' => Role::where('is_active', 1)->count(),
            'totalPermissions' => Permission::where('is_active', 1)->count(),
            'totalDepartments' => IssueDepartment::count(),
        ];
    }

    private function getZoneManagerDashboardData($userId)
    {
        // Get zone assigned to this ZoneManager
        $hierarchy = DB::table('hierarchy_access')
            ->where('employee_id', $userId)
            ->where('role_id', function ($q) {
                $q->select('id')->from('roles')->where('name', 'ZoneManager');
            })
            ->first();

        $zoneId = $hierarchy->zone_id ?? null;

        return [
            'zoneBranches' => $zoneId ? DB::table('branch')->where('zone_id', $zoneId)->count() : 0,
            'zoneLocations' => $zoneId ? DB::table('location')->where('zone_id', $zoneId)->count() : 0,
            'zoneStaff' => $zoneId ? DB::table('User_Master')->where('Loc_id', $zoneId)->count() : 0,
            'pendingApprovals' => 0, // To be calculated based on tickets
        ];
    }

    private function getBranchManagerDashboardData($userId)
    {
        $hierarchy = DB::table('hierarchy_access')
            ->where('employee_id', $userId)
            ->where('role_id', function ($q) {
                $q->select('id')->from('roles')->where('name', 'BranchManager');
            })
            ->first();

        $branchId = $hierarchy->branch_id ?? null;

        return [
            'branchLocations' => $branchId ? DB::table('location')->where('branch_id', $branchId)->count() : 0,
            'branchStaff' => $branchId ? DB::table('User_Master')->where('branch_id', $branchId)->count() : 0,
            'branchTickets' => 0, // To be calculated from tickets table
            'branchPending' => 0, // To be calculated from pending tickets
        ];
    }

    private function getLocationManagerDashboardData($userId)
    {
        $hierarchy = DB::table('hierarchy_access')
            ->where('employee_id', $userId)
            ->where('role_id', function ($q) {
                $q->select('id')->from('roles')->where('name', 'LocationManager');
            })
            ->first();

        $locationId = $hierarchy->location_id ?? null;

        return [
            'departmentStaff' => $locationId ? DB::table('User_Master')->where('Loc_id', $locationId)->count() : 0,
            'assignedTickets' => 0, // To be calculated
            'myPending' => 0, // To be calculated
        ];
    }

    private function getEmployeeDashboardData($userId)
    {
        return [
            'myTickets' => 0, // To be calculated from tickets
            'myCompleted' => 0, // To be calculated
            'myPending' => 0, // To be calculated
        ];
    }
}
