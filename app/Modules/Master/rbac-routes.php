<?php

use App\Http\Controllers\RbacManagementController;
use App\Http\Controllers\ModuleManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.custom'])->prefix('rbac')->group(function () {
    Route::get('/assign-role', [RbacManagementController::class, 'assignRoleForm'])->name('rbac.assign-role.form');
    Route::post('/assign-role', [RbacManagementController::class, 'assignRole'])->name('rbac.assign-role');

    Route::get('/assign-department', [RbacManagementController::class, 'assignDepartmentForm'])->name('rbac.assign-dept.form');
    Route::post('/assign-department', [RbacManagementController::class, 'assignDepartment'])->name('rbac.assign-dept');

    Route::get('/assign-hierarchy', [RbacManagementController::class, 'assignHierarchyForm'])->name('rbac.assign-hier.form');
    Route::post('/assign-hierarchy', [RbacManagementController::class, 'assignHierarchy'])->name('rbac.assign-hier');

    Route::get('/manage-roles', [RbacManagementController::class, 'manageRoles'])->name('rbac.manage-roles');
    Route::get('/permission-guide', function() {
        return view('rbac.permission-guide');
    })->name('rbac.permission-guide');
    Route::post('/roles', [RbacManagementController::class, 'createRole'])->name('rbac.create-role');
    Route::post('/role/{roleId}/permission', [RbacManagementController::class, 'assignPermissionToRole'])->name('rbac.assign-perm');
    Route::delete('/role/{roleId}/permission/{permissionId}', [RbacManagementController::class, 'revokePermission'])->name('rbac.revoke-perm');

    Route::get('/employee/{employeeId}/access', [RbacManagementController::class, 'employeeAccess'])->name('rbac.employee-access');
    Route::post('/remove-role/{employeeId}/{roleId}', [RbacManagementController::class, 'removeRole'])->name('rbac.remove-role');
    Route::post('/remove-department/{employeeId}/{departmentId}', [RbacManagementController::class, 'removeDepartment'])->name('rbac.remove-dept');
});

// Module Management
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/modules', [ModuleManagementController::class, 'index'])->name('modules.index');
    Route::post('/modules', [ModuleManagementController::class, 'store'])->name('modules.store');
    Route::put('/modules/{id}', [ModuleManagementController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{id}', [ModuleManagementController::class, 'destroy'])->name('modules.destroy');
});

// API endpoints for RBAC (for AJAX requests) - Admin only
Route::middleware(['auth.custom', 'admin.only'])->prefix('api')->group(function () {
    Route::get('/roles/{roleId}/permissions', [RbacManagementController::class, 'getRolePermissions']);
    Route::get('/permissions', [RbacManagementController::class, 'getAllPermissions']);
    Route::get('/modules', [RbacManagementController::class, 'getAllModules']);
    Route::post('/permissions', [RbacManagementController::class, 'createPermission']);
    Route::delete('/permissions/{id}', [RbacManagementController::class, 'deletePermission']);
});

// Permission Management (Admin Only)
Route::middleware(['auth.custom'])->group(function () {
    Route::get('/rbac/manage-permissions-admin', [RbacManagementController::class, 'managePermissionsAdmin'])->name('rbac.manage-permissions-admin');
});
