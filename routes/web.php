<?php

use App\Events\TestNotification;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DesginationController;
use App\Http\Controllers\IssuesMasterController;
use App\Http\Controllers\VsupportController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth.custom', 'nocache'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/designation', [DesginationController::class, 'index'])->name('designation.index');

    Route::get('/branch', [BranchController::class, 'index'])->name('branch.index');
    Route::get('/role', [RoleController::class, 'index'])->name('role-permission.index');
    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');

    Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
    Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');

    Route::get('/department', [DepartmentController::class, 'index'])->name('department.index');
    Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');

    Route::get('/issues-master', [IssuesMasterController::class, 'index'])->name('issues-master.index');
    Route::post('/issues-master/store', [IssuesMasterController::class, 'store'])->name('issues-master.store');

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');
    Route::post('/tickets', [TicketController::class, 'store']);
    // Route::get('/ticket-details/{id}', [TicketController::class, 'show']);
    // Route::post('/update-complaint-status', [TicketController::class, 'updateStatus']);
    Route::get('/ticket/{id}', [TicketController::class, 'viewTicket'])->name('ticket.view');
    Route::get('/check-customer-ticket', [TicketController::class, 'checkCustomerTicket']);
    Route::get('/customer-tickets', [TicketController::class, 'customerTickets']);
    Route::post('/complaint/followup', [TicketController::class, 'followup'])
        ->name('complaint.followup');
    Route::post('/complaint/permanent', [TicketController::class, 'closeComplaint'])
        ->name('complaint.permanent');
    // Route::post('/followup', [TicketController::class, 'followup']);
    Route::get('/followup-history/{id}', [TicketController::class, 'followupHistory']);

    Route::get('/search-customer', [MasterController::class, 'searchCustomer']);
    Route::get('/departments', [MasterController::class, 'departments']);
    Route::get('/issue-categories', [MasterController::class, 'issueCategories']);
    Route::get('/issues/{id}', [MasterController::class, 'getIssuesByCategory']);
    Route::get('/get-categories/{department_id}', function ($department_id) {
        return \App\Models\IssueCategory::where('department_id', $department_id)->get();
    });
    Route::get('/employees', [MasterController::class, 'employees']);
});
Route::get('/test-db', function () {
    $data = DB::select('SELECT TOP 10 * FROM User_Master');

    return response()->json($data);
});
