<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiomedicalController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HrManpowerController;
use App\Http\Controllers\IssuesMasterController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\TicketSummaryController;
use App\Http\Controllers\MachineIssuesController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SettlementController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PcRequestController;
use App\Http\Controllers\PcBillController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\FacilityIssueCategoryController;
use App\Models\IssueCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Models\UserMaster;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware(['auth.custom', 'nocache'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/staff-dashboard', function () {
        return view('staff_dashboard');
    })->name('staff.dashboard');

    // ════════════════════════════════════════════════════════════════════
    // EMPLOYEE MANAGEMENT ROUTES
    // ════════════════════════════════════════════════════════════════════

    // Employee CRUD Operations
    Route::get('/staff', [StaffController::class, 'index'])->middleware('check.permission:read,staff')->name('staff.index');
    Route::get('/staff/generate-code', [StaffController::class, 'generateEmployeeCode'])->middleware('check.permission:create,staff')->name('staff.generate-code');
    Route::post('/staff/store', [StaffController::class, 'store'])->middleware('check.permission:create,staff')->name('staff.store');
    Route::get('/staff/{id}/details', [StaffController::class, 'details'])->middleware('check.permission:read,staff')->name('staff.details');
    Route::put('/staff/{id}', [StaffController::class, 'update'])->middleware('check.permission:edit,staff')->name('staff.update');

    // Role Management
    Route::get('/staff/{employeeId}/roles', [StaffController::class, 'getEmployeeRoles'])->middleware('check.permission:read,staff')->name('staff.get-roles');
    Route::post('/staff/{employeeId}/role', [StaffController::class, 'assignRole'])->middleware('check.permission:edit,staff')->name('staff.assign-role');
    Route::delete('/staff/{employeeId}/role/{roleId}', [StaffController::class, 'removeRole'])->middleware('check.permission:edit,staff')->name('staff.remove-role');

    // ════════════════════════════════════════════════════════════════════
    // EMPLOYEE DOCUMENT & EDUCATION WORKFLOW
    // ════════════════════════════════════════════════════════════════════

    // Educational Documents (Degree, 10th, 12th) - Auto-approved
    Route::post('/employee/{employeeId}/educational-document/add', [\App\Http\Controllers\EmployeeWorkflowController::class, 'addEducationalDocument'])->middleware('check.permission:edit,staff')->name('employee.edu-document.add');
    Route::get('/employee/{employeeId}/educational-document/list', [\App\Http\Controllers\EmployeeWorkflowController::class, 'getEducationalDocuments'])->middleware('check.permission:read,staff')->name('employee.edu-document.list');

    // Official Documents (Aadhar, PAN, Passport, etc.) - Auto-approved
    Route::post('/employee/{employeeId}/document/add', [\App\Http\Controllers\EmployeeWorkflowController::class, 'addDocument'])->middleware('check.permission:edit,staff')->name('employee.document.add');
    Route::get('/employee/{employeeId}/document/list', [\App\Http\Controllers\EmployeeWorkflowController::class, 'getDocuments'])->middleware('check.permission:read,staff')->name('employee.document.list');

    // Bond Management
    Route::post('/employee/{employeeId}/bond/create', [\App\Http\Controllers\EmployeeWorkflowController::class, 'createBond'])->middleware('check.permission:edit,staff')->name('employee.bond.create');
    Route::get('/employee/{employeeId}/bond/list', [\App\Http\Controllers\EmployeeWorkflowController::class, 'getBonds'])->middleware('check.permission:read,staff')->name('employee.bond.list');
    Route::post('/employee/{employeeId}/bond/{bondId}/complete', [\App\Http\Controllers\EmployeeWorkflowController::class, 'completeBond'])->middleware('check.permission:edit,staff')->name('employee.bond.complete');

    // Relieving/Exit Management (2-month notice period)
    Route::post('/employee/{employeeId}/relieving/initiate', [\App\Http\Controllers\EmployeeWorkflowController::class, 'initiateRelieving'])->middleware('check.permission:edit,staff')->name('employee.relieving.initiate');
    Route::post('/employee/{employeeId}/relieving/complete', [\App\Http\Controllers\EmployeeWorkflowController::class, 'completeRelieving'])->middleware('check.permission:edit,staff')->name('employee.relieving.complete');

    // ════════════════════════════════════════════════════════════════════
    // EMPLOYEE WORKFLOW STATUS
    // ════════════════════════════════════════════════════════════════════

    Route::get('/employee/{employeeId}/workflow/status', [\App\Http\Controllers\EmployeeWorkflowController::class, 'getEmployeeStatus'])->middleware('check.permission:read,staff')->name('employee.workflow.status');

    // ── Master Module ──────────────────────────────────────────────
    require app_path('Modules/Master/routes.php');

    Route::get('/role', [RoleController::class, 'index'])->name('role-permission.index');
    Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');

    Route::get('/category', [CategoryController::class, 'index'])->middleware('check.permission:read,category')->name('category.index');
    Route::post('/category/store', [CategoryController::class, 'store'])->middleware('check.permission:create,category')->name('category.store');

    Route::get('/issues-master', [IssuesMasterController::class, 'index'])->middleware('check.permission:read,issues')->name('issues-master.index');
    Route::post('/issues-master/store', [IssuesMasterController::class, 'store'])->middleware('check.permission:create,issues')->name('issues-master.store');

    Route::get('/machine', [MachineController::class, 'index'])->middleware('check.permission:read,machine')->name('machine.index');
    Route::post('/machine/store', [MachineController::class, 'store'])->middleware('check.permission:create,machine')->name('machine.store');

    Route::get('/machine-issues', [MachineIssuesController::class, 'index'])->middleware('check.permission:read,machine-issues')->name('machine-issues.index');
    Route::post('/machine-issues/store', [MachineIssuesController::class, 'store'])->middleware('check.permission:create,machine-issues')->name('machine-issues.store');

    Route::get('/machines', [MachineController::class, 'getMachines']);
    Route::get('/machine-issues-list', [MachineIssuesController::class, 'getMachineIssues']);

    // Route::get('/facility-issue-category',          [FacilityIssueCategoryController::class, 'index'])->name('facility.issue.category.index');
    // Route::post('/facility-issue-category',          [FacilityIssueCategoryController::class, 'store'])->name('facility.issue.category.store');
    // Route::put('/facility-issue-category/{id}',      [FacilityIssueCategoryController::class, 'update'])->name('facility.issue.category.update');
    // Route::delete('/facility-issue-category/{id}',   [FacilityIssueCategoryController::class, 'destroy'])->name('facility.issue.category.destroy');
    Route::get('/facility-issue-category',          [FacilityIssueCategoryController::class, 'index'])->middleware('check.permission:read,facility')->name('facility-issue-category.index');
    Route::post('/facility-issue-category',         [FacilityIssueCategoryController::class, 'store'])->middleware('check.permission:create,facility')->name('facility.issue.category.store');
    Route::put('/facility-issue-category/{id}',     [FacilityIssueCategoryController::class, 'update'])->middleware('check.permission:edit,facility')->name('facility-issue-category.update');
    Route::delete('/facility-issue-category/{id}',  [FacilityIssueCategoryController::class, 'destroy'])->middleware('check.permission:delete,facility')->name('facility-issue-category.destroy');

    Route::get('/test', function () {
        return view('ticket.test');
    })->name('test');

    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets');
    Route::get('/ticket-summary', [TicketSummaryController::class, 'index'])->name('ticket.summary');
    Route::get('/ticket-summary/listing', [TicketSummaryController::class, 'listing'])->name('ticket.summary.listing');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/ticket/{id}', [TicketController::class, 'viewTicket'])->name('ticket.view');
    Route::get('/check-customer-ticket', [TicketController::class, 'checkCustomerTicket']);
    Route::get('/customer-tickets', [TicketController::class, 'customerTickets']);
    Route::post('/complaint/followup', [TicketController::class, 'followup'])->name('complaint.followup');
    Route::post('/complaint/permanent', [TicketController::class, 'closeComplaint'])->name('complaint.permanent');
    Route::get('/followup-history/{id}', [TicketController::class, 'followupHistory']);
    Route::post('/hr/update-status', [TicketController::class, 'updateHrStatus'])->name('hr.update.status');

    Route::get('/manpower/{ticketId}', [HrManpowerController::class, 'view'])->name('manpower.view');
    Route::post('/approval/update', [HrManpowerController::class, 'updateApproval']);
    Route::post('/self-assign/{id}', [HrManpowerController::class, 'selfAssign']);
    Route::post('hr/candidate-status-update', [HrManpowerController::class, 'candidateStatusUpdate']);
    Route::post('/candidate/store/{id}', [HrManpowerController::class, 'candidateStore']);

    Route::get('/biomedical/{ticketId}', [BiomedicalController::class, 'view'])->name('biomedical.view');
    Route::post('/biomedical-ticket/{biomedicalId}/update-status', [BiomedicalController::class, 'updateStatus'])->name('biomedical.updateStatus');

    Route::get('/iou/{ticketId}', [AccountsController::class, 'view'])->name('iou.view');
    Route::get('/get-employee-iou-balance', [AccountsController::class, 'getEmployeeIouBalance']);
    Route::post('iou/{iouId}/approve', [AccountsController::class, 'approve'])->name('iou.approve');
    Route::post('iou/{iouId}/pay', [AccountsController::class, 'pay'])->name('iou.pay');

    Route::post('/settlement/{settlementId}/review', [SettlementController::class, 'review'])->name('settlement.review');
    Route::post('/settlement/{settlementId}/process-payment', [SettlementController::class, 'processPayment'])->name('settlement.process-payment');
    Route::post('/settlement/{settlementId}/close', [SettlementController::class, 'close'])->name('settlement.close');

    Route::get('/pc-request/{ticketId}', [PcRequestController::class, 'view'])->name('pc.view');
    Route::post('/pc-request/{requestId}/status', [PcRequestController::class, 'updateStatus'])->name('pc.request.status');

    Route::get('/pc-bill/{ticketId}',              [PcBillController::class, 'view'])->name('pc.bill.view');
    Route::post('/pc-bill/{submissionId}/status',  [PcBillController::class, 'updateStatus'])->name('pc.bill.status');
    Route::get('/expense-master',                  [PcBillController::class, 'getExpenseMaster']);

    Route::get('/facility-ticket/{ticketId}',   [FacilityController::class, 'view'])->name('facility.view');
    Route::post('/facility-ticket/{id}/status', [FacilityController::class, 'updateStatus'])->name('facility.status');

    Route::get('/search-customer', [MasterController::class, 'searchCustomer']);
    Route::get('/departments', [MasterController::class, 'departments']);
    Route::get('/issue-categories', [MasterController::class, 'issueCategories']);
    Route::get('/issues/{id}', [MasterController::class, 'getIssuesByCategory']);
    Route::get('/get-categories/{department_id}', function ($department_id) {
        return IssueCategory::where('department_id', $department_id)->get();
    });
    Route::get('/facility-categories', [MasterController::class, 'getFacilityCategories']);
    Route::get('/employees', [MasterController::class, 'employees']);
    Route::get('/get-petty-cash-balance', [MasterController::class, 'getPettyCashBalance']);

    Route::get('/expanse', [ExpenseController::class, 'index'])->middleware('check.permission:read,expense')->name('expanse.index');
    Route::post('/expanse/store', [ExpenseController::class, 'store'])->middleware('check.permission:create,expense')->name('expanse.store');

    // API endpoints for employees
    Route::prefix('api')->group(function () {
        Route::get('/employees/{id}', [\App\Http\Controllers\Api\EmployeeApiController::class, 'show']);
    });
});

Route::get('/test-db', function () {
    $data = DB::select('SELECT TOP 10 * FROM User_Master');
    return response()->json($data);
});
