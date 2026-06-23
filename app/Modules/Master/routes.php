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
Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');
Route::put('/department/{id}', [DepartmentController::class, 'update'])->name('department.update');
Route::delete('/department/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');

// Designation
Route::get('/designation', [DesignationController::class, 'index'])->name('designation.index');
Route::post('/designation/store', [DesignationController::class, 'store'])->name('designation.store');
Route::put('/designation/{id}', [DesignationController::class, 'update'])->name('designation.update');
Route::delete('/designation/{id}', [DesignationController::class, 'destroy'])->name('designation.destroy');
Route::get('/designation/export/excel', [DesignationController::class, 'exportExcel'])->name('designation.export.excel');

// Branch
Route::get('/new-branch', [BranchController::class, 'index'])->name('new-branch.index');
Route::post('/new-branch/store', [BranchController::class, 'store'])->name('new-branch.store');
Route::put('/new-branch/{id}', [BranchController::class, 'update'])->name('new-branch.update');
Route::delete('/new-branch/{id}', [BranchController::class, 'destroy'])->name('new-branch.destroy');

// Location
Route::get('/location', [LocationController::class, 'index'])->name('location.index');
Route::post('/location/store', [LocationController::class, 'store'])->name('location.store');
Route::put('/location/{id}', [LocationController::class, 'update'])->name('location.update');
Route::delete('/location/{id}', [LocationController::class, 'destroy'])->name('location.destroy');

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
