@extends('layouts.bankapp')

@section('title', 'Overdue Claim Form')

@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Modern Card Design -->
            @if(!$application && $searchPerformed)
                <div class="alert alert-glass alert-warning d-flex align-items-center mb-4 animate-fade-in">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">No Application Found</h5>
                        <p class="mb-0">We couldn't find any applications matching your search criteria.</p>
                    </div>
                </div>
            @endif

            @if(!$application)
            <!-- Modern Search Card -->
            <div class="card glass-card border-0 shadow-soft mb-5 animate-slide-up">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-search-heart fs-3 me-3"></i>
                        <div>
                            <h3 class="mb-0 fw-semibold">Customer Search</h3>
                            <p class="mb-0 opacity-75">Find applications by identification details</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <form method="GET" action="{{ route('claims.create') }}" class="needs-validation" novalidate>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-soft border-0" id="id_no" name="id_no" 
                                           value="{{ old('id_no') }}" placeholder="ID Number">
                                    <label for="id_no" class="text-muted">
                                        <i class="bi bi-person-vcard me-2"></i> ID Number
                                    </label>
                                    <div class="invalid-feedback">
                                        Please provide a valid ID number
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-soft border-0" id="passport_no" name="passport_no" 
                                           value="{{ old('passport_no') }}" placeholder="Passport Number">
                                    <label for="passport_no" class="text-muted">
                                        <i class="bi bi-passport me-2"></i> Passport Number
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4 py-3 shadow-sm">
                                    <i class="bi bi-search me-2"></i> Search Applications
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @else
            <!-- Modern Overdue Claim Form -->
            <div class="card glass-card border-0 shadow-soft mb-5 animate-slide-up">
                <div class="card-header bg-gradient-primary text-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-file-earmark-text fs-3 me-3"></i>
                            <div>
                                <h3 class="mb-0 fw-semibold">Overdue Claim Report</h3>
                                <p class="mb-0 opacity-75">Application #{{ $application->application_no }}</p>
                            </div>
                        </div>
                        <span class="badge bg-white text-primary fs-6 px-3 py-2 rounded-pill shadow-sm">
                            <i class="bi bi-calendar-check me-1"></i> {{ date('d M Y') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4 p-lg-5">
                    @if(session('success'))
                        <div class="alert alert-glass alert-success alert-dismissible fade show mb-4 d-flex align-items-center animate-fade-in">
                            <i class="bi bi-check-circle-fill me-3 fs-4"></i>
                            <div class="flex-grow-1">
                                {{ session('success') }}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('claims.store', $application->application_no) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf

                        <!-- 1. Bank & Customer Info -->
                        <section class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-soft-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-building fs-4 text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-semibold">Bank & Customer Information</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-soft border-0" id="bank_name" name="bank_name" 
                                               value="{{ $application->bank->bank_name }}" readonly>
                                        <label for="bank_name" class="text-muted">Bank Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-soft border-0" id="branch_name" name="branch_name" 
                                               value="{{ $application->bank->branch_name }}" readonly>
                                        <label for="branch_name" class="text-muted">Branch</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-soft border-0" id="customer_name" name="customer_name" 
                                               value="{{ $application->customer_name }}" readonly>
                                        <label for="customer_name" class="text-muted">Customer Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-soft border-0" id="customer_address" name="customer_address" 
                                                  style="height: 100px" readonly>{{ $application->customer_address }}</textarea>
                                        <label for="customer_address" class="text-muted">Customer Address</label>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- 2. Financial Info -->
                        <section class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-soft-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-cash-stack fs-4 text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-semibold">Financial Information</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" class="form-control bg-soft border-0" id="total_repayments" name="total_repayments" 
                                               value="{{ $claim->total_repayments ?? old('total_repayments') }}" required>
                                        <label for="total_repayments" class="text-muted">Total Repayments (LKR)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="number" step="0.01" class="form-control bg-soft border-0" id="amount_outstanding" name="amount_outstanding" 
                                               value="{{ $claim->amount_outstanding ?? old('amount_outstanding') }}" required>
                                        <label for="amount_outstanding" class="text-muted">Amount Outstanding (LKR)</label>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- 3. Default Info -->
                        <section class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-soft-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-exclamation-triangle fs-4 text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-semibold">Default Information</h5>
                            </div>
                            
                            <div class="form-floating mb-4">
                                <textarea class="form-control bg-soft border-0" id="default_reasons" name="default_reasons" 
                                          style="height: 120px" required>{{ $claim->default_reasons ?? old('default_reasons') }}</textarea>
                                <label for="default_reasons" class="text-muted">Reasons for Default</label>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-muted mb-3">Demand Letter Sent?</label>
                                <div class="btn-group w-100 shadow-sm" role="group">
                                    <input type="radio" class="btn-check" name="demand_made" id="demand_yes" 
                                           value="1" {{ (old('demand_made', $claim->demand_made ?? null) == 1) ? 'checked' : '' }} required>
                                    <label class="btn btn-outline-primary" for="demand_yes">
                                        <i class="bi bi-check-circle me-2"></i> Yes
                                    </label>
                                    
                                    <input type="radio" class="btn-check" name="demand_made" id="demand_no" 
                                           value="0" {{ (old('demand_made', $claim->demand_made ?? null) == 0) ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary" for="demand_no">
                                        <i class="bi bi-x-circle me-2"></i> No
                                    </label>
                                </div>
                            </div>

                            <div id="demand-letter-section" class="{{ (old('demand_made', $claim->demand_made ?? 0) ? '' : 'd-none') }} animate-fade-in">
                                <div class="file-upload-card bg-soft p-4 rounded-3 mb-3">
                                    <label class="form-label fw-semibold text-muted mb-3">Demand Letter Copy</label>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <input type="file" class="form-control bg-soft border-0" id="demand_letter" name="demand_letter">
                                        </div>
                                        @if(isset($claim->demand_letter_path))
                                            <a href="{{ Storage::url($claim->demand_letter_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ms-3">
                                                <i class="bi bi-eye me-1"></i> View
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div id="no-demand-reason-section" class="{{ !(old('demand_made', $claim->demand_made ?? 0) ? '' : 'd-none') }} animate-fade-in">
                                <div class="form-floating">
                                    <textarea class="form-control bg-soft border-0" id="no_demand_reason" name="no_demand_reason" 
                                              style="height: 100px">{{ $claim->no_demand_reason ?? old('no_demand_reason') }}</textarea>
                                    <label for="no_demand_reason" class="text-muted">Reason for Not Sending Demand Letter</label>
                                </div>
                            </div>
                        </section>

                        <!-- 4. Recovery Info -->
                        <section class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-soft-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-lightning-charge fs-4 text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-semibold">Recovery Information</h5>
                            </div>
                            
                            <div class="form-floating mb-4">
                                <textarea class="form-control bg-soft border-0" id="recovery_steps_taken" name="recovery_steps_taken" 
                                          style="height: 120px" required>{{ $claim->recovery_steps_taken ?? old('recovery_steps_taken') }}</textarea>
                                <label for="recovery_steps_taken" class="text-muted">Steps Already Taken</label>
                            </div>
                            
                            <div class="form-floating mb-4">
                                <textarea class="form-control bg-soft border-0" id="proposed_steps" name="proposed_steps" 
                                          style="height: 120px" required>{{ $claim->proposed_steps ?? old('proposed_steps') }}</textarea>
                                <label for="proposed_steps" class="text-muted">Proposed Next Steps</label>
                            </div>
                            
                            <div class="form-floating">
                                <textarea class="form-control bg-soft border-0" id="additional_info" name="additional_info" 
                                          style="height: 100px">{{ $claim->additional_info ?? old('additional_info') }}</textarea>
                                <label for="additional_info" class="text-muted">Additional Information</label>
                            </div>
                        </section>

                        <!-- 5. Bank Representative -->
                        <section class="mb-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="bg-soft-primary rounded-circle p-2 me-3">
                                    <i class="bi bi-person-badge fs-4 text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-semibold">Bank Representative</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-soft border-0" id="signature_name" name="signature_name" 
                                               value="{{ $claim->signature_name ?? old('signature_name') }}" required>
                                        <label for="signature_name" class="text-muted">Representative Name</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control bg-soft border-0" id="signature_designation" name="signature_designation" 
                                               value="{{ $claim->signature_designation ?? old('signature_designation') }}" required>
                                        <label for="signature_designation" class="text-muted">Designation</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <input type="date" class="form-control bg-soft border-0" id="report_date" name="report_date" 
                                               value="{{ $claim->report_date ?? old('report_date', date('Y-m-d')) }}" required>
                                        <label for="report_date" class="text-muted">Report Date</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control bg-soft border-0" id="bank_address" name="bank_address" 
                                                  style="height: 100px" required>{{ $claim->bank_address ?? old('bank_address') }}</textarea>
                                        <label for="bank_address" class="text-muted">Bank Address</label>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="d-flex justify-content-end mt-5">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm">
                                <i class="bi bi-save me-2"></i> Save Claim Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    :root {
        --primary: #4361ee;
        --primary-light: #4895ef;
        --primary-dark: #3a0ca3;
        --secondary: #f72585;
        --success: #4cc9f0;
        --warning: #f8961e;
        --danger: #ef233c;
        --light: #f8f9fa;
        --dark: #212529;
        --glass: rgba(255, 255, 255, 0.15);
        --soft-bg: rgba(67, 97, 238, 0.05);
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    }
    
    .bg-soft {
        background-color: var(--soft-bg);
    }
    
    .bg-soft-primary {
        background-color: rgba(67, 97, 238, 0.1);
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .alert-glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .shadow-soft {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .file-upload-card {
        border: 2px dashed rgba(67, 97, 238, 0.2);
        transition: all 0.3s ease;
    }
    
    .file-upload-card:hover {
        border-color: var(--primary);
        background-color: rgba(67, 97, 238, 0.08);
    }
    
    /* Animations */
    .animate-slide-up {
        animation: slideUp 0.5s ease forwards;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.5s ease forwards;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    /* Form styling */
    .form-control, .form-select, .form-floating>.form-control, .form-floating>.form-select {
        border-radius: 0.5rem;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15);
    }
    
    .btn-check:checked + .btn-outline-primary {
        background-color: var(--primary);
        color: white;
    }
    
    /* Floating labels */
    .form-floating>label {
        color: #6c757d;
    }
    
    .form-floating>.form-control:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.form-select~label {
        color: var(--primary);
        background: white;
        padding: 0 0.5rem;
        transform: scale(0.85) translateY(-1.5rem) translateX(0.15rem);
    }

    
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle demand letter sections with animation
    const demandRadios = document.querySelectorAll('input[name="demand_made"]');
    const letterSection = document.getElementById('demand-letter-section');
    const reasonSection = document.getElementById('no-demand-reason-section');

    if (demandRadios.length > 0) {
        function toggleSections() {
            const isDemandMade = document.querySelector('input[name="demand_made"]:checked').value === '1';
            
            if (isDemandMade) {
                reasonSection.classList.add('d-none');
                letterSection.classList.remove('d-none');
            } else {
                letterSection.classList.add('d-none');
                reasonSection.classList.remove('d-none');
            }
        }

        demandRadios.forEach(radio => radio.addEventListener('change', toggleSections));
        toggleSections();
    }

    // Form validation with better UX
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Add shake animation to invalid fields
                const invalidFields = form.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.classList.add('animate__animated', 'animate__headShake');
                    field.addEventListener('animationend', () => {
                        field.classList.remove('animate__animated', 'animate__headShake');
                    });
                });
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Add animation to file upload area
    const fileUpload = document.querySelector('.file-upload-card');
    if (fileUpload) {
        fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUpload.classList.add('border-primary', 'bg-soft-primary');
        });
        
        fileUpload.addEventListener('dragleave', () => {
            fileUpload.classList.remove('border-primary', 'bg-soft-primary');
        });
    }
});
</script>
@endsection