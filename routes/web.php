<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OverdueClaimController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/bank-claim-dashboard', function () {
    return view('bankClaimDashboard');
});

// Display search form and process searches
Route::get('/claims/create', [OverdueClaimController::class, 'create'])
    ->name('claims.create');

// Store the claim form submission
Route::post('/claims/{application}', [OverdueClaimController::class, 'store'])
    ->name('claims.store');