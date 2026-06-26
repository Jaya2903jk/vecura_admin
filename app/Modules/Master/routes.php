<?php

use App\Modules\Master\Controllers\DepartmentController;
use App\Modules\Master\Controllers\DesignationController;
use App\Modules\Master\Controllers\BranchController;
use App\Modules\Master\Controllers\LocationController;
use App\Modules\Master\Controllers\CountryController;
use App\Modules\Master\Controllers\ZoneController;
use App\Modules\Master\Controllers\StateController;
use App\Modules\Master\Controllers\CityController;
use Illuminate\Support\Facades\Route;

// Department
Route::middleware('check.permission:read,department')->get('/department', [DepartmentController::class, 'index'])->name('department.index');
Route::middleware('check.permission:create,department')->post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
Route::middleware('check.permission:edit,department')->put('/department/{id}', [DepartmentController::class, 'update'])->name('department.update');
Route::middleware('check.permission:delete,department')->delete('/department/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');

// Designation
Route::middleware('check.permission:read,designation')->get('/designation', [DesignationController::class, 'index'])->name('designation.index');
Route::middleware('check.permission:read,designation')->get('/designation/search', [DesignationController::class, 'search'])->name('designation.search');
Route::middleware('check.permission:create,designation')->post('/designation/store', [DesignationController::class, 'store'])->name('designation.store');
Route::middleware('check.permission:read,designation')->get('/designation/{id}', [DesignationController::class, 'show'])->name('designation.show');
Route::middleware('check.permission:edit,designation')->put('/designation/{id}', [DesignationController::class, 'update'])->name('designation.update');
Route::middleware('check.permission:delete,designation')->delete('/designation/{id}', [DesignationController::class, 'destroy'])->name('designation.destroy');
Route::middleware('check.permission:read,designation')->get('/designation/export/excel', [DesignationController::class, 'exportExcel'])->name('designation.export.excel');


// Branch
Route::middleware('check.permission:read,branch')->get('/new-branch', [BranchController::class, 'index'])->name('new-branch.index');
Route::middleware('check.permission:create,branch')->post('/new-branch/store', [BranchController::class, 'store'])->name('new-branch.store');
Route::middleware('check.permission:edit,branch')->put('/new-branch/{id}', [BranchController::class, 'update'])->name('new-branch.update');
Route::middleware('check.permission:delete,branch')->delete('/new-branch/{id}', [BranchController::class, 'destroy'])->name('new-branch.destroy');

// Location
Route::middleware('check.permission:read,location')->get('/location', [LocationController::class, 'index'])->name('location.index');
Route::middleware('check.permission:create,location')->post('/location/store', [LocationController::class, 'store'])->name('location.store');
Route::middleware('check.permission:edit,location')->put('/location/{id}', [LocationController::class, 'update'])->name('location.update');
Route::middleware('check.permission:delete,location')->delete('/location/{id}', [LocationController::class, 'destroy'])->name('location.destroy');

// Country
Route::get('/country', [CountryController::class, 'index'])->name('country.index');
Route::post('/country/store', [CountryController::class, 'store'])->name('country.store');
Route::put('/country/{id}', [CountryController::class, 'update'])->name('country.update');
Route::delete('/country/{id}', [CountryController::class, 'destroy'])->name('country.destroy');

// Zone
Route::get('/zone', [ZoneController::class, 'index'])->name('zone.index');
Route::post('/zone/store', [ZoneController::class, 'store'])->name('zone.store');
Route::put('/zone/{id}', [ZoneController::class, 'update'])->name('zone.update');
Route::delete('/zone/{id}', [ZoneController::class, 'destroy'])->name('zone.destroy');

// State
Route::get('/state', [StateController::class, 'index'])->name('state.index');
Route::post('/state/store', [StateController::class, 'store'])->name('state.store');
Route::put('/state/{id}', [StateController::class, 'update'])->name('state.update');
Route::delete('/state/{id}', [StateController::class, 'destroy'])->name('state.destroy');

// City
Route::get('/city', [CityController::class, 'index'])->name('city.index');
Route::post('/city/store', [CityController::class, 'store'])->name('city.store');
Route::put('/city/{id}', [CityController::class, 'update'])->name('city.update');
Route::delete('/city/{id}', [CityController::class, 'destroy'])->name('city.destroy');

// RBAC Management
require __DIR__ . '/rbac-routes.php';
