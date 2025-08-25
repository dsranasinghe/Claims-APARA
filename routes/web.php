<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OverdueClaimController;

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');  
});

Route::get('/slecic-claim-dashboard', function (\Illuminate\Http\Request $request) {
    session([
        'username'   => $request->username,
        'employee_id' => $request->employee_id,
        'department' => $request->department,
    ]);

    return view('SlecicClaimDashboard');
}) ->name('slecic-claim-dashboard');

Route::get('/report-of-default', function () {
    return view('pages.reportofdefault');
})->name('report-of-default');

Route::get('/Claims-checklist',function () {
    return view('pages.claimsChecklist');
})->name('Claims-checklist');

Route::post('/signout', function () {
 
    Auth::logout();
    session()->flush();
    
    $domain = request()->getHost();
    $secure = request()->secure();
    
    setcookie('username', '', time() - 3600, '/', $domain, $secure, true);
    setcookie('employee_id', '', time() - 3600, '/', $domain, $secure, true);
    setcookie('department', '', time() - 3600, '/', $domain, $secure, true);
    setcookie('shared_session', '', time() - 3600, '/', $domain, $secure, true);

    // Redirect to login page
    return redirect('http://localhost/APARA/index.php');
})->name('signout');

Route::get('/formal-claims', function () {
    return view('pages.formal-claim-application');
})->name('formal-claims');

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
//documents
Route::get('/claims/{application_no}/documents', [OverdueClaimController::class, 'documents'])
    ->name('claims.documents');

Route::post('/claims/{application_no}/documents/upload', [OverdueClaimController::class, 'uploadDocuments'])
    ->name('claims.upload-documents');