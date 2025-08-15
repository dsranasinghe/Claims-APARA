<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OverdueClaimController;

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    return view('welcome');  
});

Route::get('/bank-claim-dashboard', function (\Illuminate\Http\Request $request) {
    session([
        'username'   => $request->username,
        'employee_id' => $request->employee_id,
        'department' => $request->department,
    ]);

    return view('bankClaimDashboard');
});


Route::get('/claims/create', function (Request $request) {
    // Store session data
    session([
        'username'   => $request->username ?? session('username'),
        'employee_id' => $request->employee_id ?? session('employee_id'),
        'department' => $request->department ?? session('department'),
    ]);

    // Initialize empty application data
    $application = null;
    $searchPerformed = false;

    return view('components.claims.create', [
        'username' => session('username'),
        'application' => $application,
        'searchPerformed' => $searchPerformed
    ]);
})->name('claims.create');




// Store the claim form submission
Route::post('/claims/{application}', [OverdueClaimController::class, 'store'])
    ->name('claims.store');

Route::get('/claims/pending', [OverdueClaimController::class, 'pending'])
    ->name('claims.pending');

Route::get('/claims/{application_no}', [OverdueClaimController::class, 'show'])
    ->name('claims.show');

// Update payment status
Route::put('/claims/{id}/status', [OverdueClaimController::class, 'updateStatus'])->name('claims.update-status');


