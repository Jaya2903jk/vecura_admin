<?php

use App\Events\TestNotification;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\TicketController;
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

    Route::get('/v-support', [VsupportController::class, 'index'])->name('vsupport');
    Route::get('/complaint/view/{id}', [VsupportController::class, 'view']);
    Route::get('/complaints/{id}/followup', [VsupportController::class, 'create'])->name('complaints.followup');

});
Route::get('/test-db', function () {
    $data = DB::select('SELECT TOP 10 * FROM User_Master');

    return response()->json($data);
});
