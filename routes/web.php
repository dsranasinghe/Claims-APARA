<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OverdueClaimController;
use App\Http\Controllers\ClaimApprovalController;

use Illuminate\Http\Request;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('welcome');  
});


Route::middleware('store.slecic.session')->group(function () {
    Route::get('/slecic-claim-dashboard', function () {
        return view('SlecicClaimDashboard');
    })->name('slecic-claim-dashboard');
});


 
Route::get('/report-of-default', [OverdueClaimController::class, 'reportOfDefault'])
    ->name('report-of-default');

Route::post('/claims/{id}/approve', [ClaimApprovalController::class, 'approve'])
    ->name('claims.approve');

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

Route::post('/claims/{application_no}/submit', [OverdueClaimController::class, 'submit'])
    ->name('claims.submit');

Route::post('/claims/{application_no}/upload-specific', [OverdueClaimController::class, 'uploadSpecificDocument'])
    ->name('claims.upload-specific-document');