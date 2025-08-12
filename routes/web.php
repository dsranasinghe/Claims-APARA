<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/bank-claim-dashboard', function (\Illuminate\Http\Request $request) {
    if ($request->has('username')) {
        session(['username' => $request->query('username')]);
    }
    return view('bankClaimDashboard');
});
