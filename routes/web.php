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
        'system'     => $request->system_choice
    ]);

    return view('bankClaimDashboard');
});

// Display search form and process searches
Route::get('/claims/create', [OverdueClaimController::class, 'create'])
    ->name('claims.create');


// Store the claim form submission
Route::post('/claims/{application}', [OverdueClaimController::class, 'store'])
    ->name('claims.store');

Route::get('/claims/pending', [OverdueClaimController::class, 'pending'])
    ->name('claims.pending');

Route::get('/claims/{application_no}', [OverdueClaimController::class, 'show'])
    ->name('claims.show');

// Update payment status
Route::put('/claims/{id}/status', [OverdueClaimController::class, 'updateStatus'])->name('claims.update-status');


