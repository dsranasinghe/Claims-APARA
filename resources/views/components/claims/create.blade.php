@extends('layouts.bankapp')

@section('title', 'Overdue Claim Form')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            @if(!$application && $searchPerformed)
                <div class="alert alert-warning d-flex align-items-center mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4"></i>
                    <div>
                        <h5 class="alert-heading mb-1">No Application Found</h5>
                        <p class="mb-0">No application found with the provided identification details.</p>
                    </div>
                </div>
            @endif

            @if(!$application)
            <!-- Search Form -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-1"><i class="bi bi-search me-2"></i> Search Application</h3>
                    <p class="mb-0 text-white-50">Enter customer identification details</p>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('claims.create') }}" class="needs-validation" novalidate>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="id_no" class="form-label fw-semibold">ID Number</label>
                                <div class="input-group has-validation">
                                    <span class="input-group-text bg-light-primary"><i class="bi bi-person-vcard text-primary"></i></span>
                                    <input type="text" class="form-control" id="id_no" name="id_no" 
                                           value="{{ old('id_no') }}" placeholder="Enter ID number">
                                    <div class="invalid-feedback">
                                        Please provide a valid ID number
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="passport_no" class="form-label fw-semibold">Passport Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light-primary"><i class="bi bi-passport text-primary"></i></span>
                                    <input type="text" class="form-control" id="passport_no" name="passport_no" 
                                           value="{{ old('passport_no') }}" placeholder="Enter passport number">
                                </div>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-4 py-2">
                                    <i class="bi bi-search me-2"></i> Search Application
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

           

            @else
            <!-- Overdue Claim Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><i class="bi bi-file-earmark-text me-2"></i> Overdue Claim Report</h3>
                            <p class="mb-0 text-white-50">Application #{{ $application->application_no }}</p>
                        </div>
                        <span class="badge bg-white text-primary fs-6 px-3 py-2">
                            <i class="bi bi-clock-history me-1"></i> {{ date('d M Y') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-4 d-flex align-items-center">
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
                            <h5 class="fw-semibold text-secondary border-bottom pb-2 mb-4">
                                <i class="bi bi-building me-2"></i> Bank & Customer Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-overdue.input-group name="bank_name" label="Bank Name" 
                                        value="{{ $application->bank->bank_name }}" icon="bi-bank" readonly />
                                </div>
                                <div class="col-md-6">
                                    <x-overdue.input-group name="branch_name" label="Branch" 
                                        value="{{ $application->bank->branch_name }}" icon="bi-geo-alt" readonly />
                                </div>
                                <div class="col-md-6">
                                    <x-overdue.input-group name="customer_name" label="Customer Name" 
                                        value="{{ $application->customer_name }}" icon="bi-person" readonly />
                                </div>
                                <div class="col-md-6">
                                    <x-overdue.textarea-group name="customer_address" label="Customer Address" 
                                        value="{{ $application->customer_address }}" rows="2" icon="bi-house-door" readonly />
                                </div>
                            </div>
                        </section>

                        <!-- 2. Financial Info -->
                        <section class="mb-5">
                            <h5 class="fw-semibold text-secondary border-bottom pb-2 mb-4">
                                <i class="bi bi-cash-stack me-2"></i> Financial Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-overdue.input-group name="total_repayments" label="Total Repayments (LKR)" 
                                        type="number" step="0.01" icon="bi-cash-coin"
                                        value="{{ $claim->total_repayments ?? old('total_repayments') }}" required />
                                </div>
                                <div class="col-md-6">
                                    <x-overdue.input-group name="amount_outstanding" label="Amount Outstanding (LKR)" 
                                        type="number" step="0.01" icon="bi-currency-exchange"
                                        value="{{ $claim->amount_outstanding ?? old('amount_outstanding') }}" required />
                                </div>
                            </div>
                        </section>

                        <!-- 3. Default Info -->
                        <section class="mb-5">
                            <h5 class="fw-semibold text-secondary border-bottom pb-2 mb-4">
                                <i class="bi bi-exclamation-triangle me-2"></i> Default Information
                            </h5>
                            <x-overdue.textarea-group name="default_reasons" label="Reasons for Default" 
                                value="{{ $claim->default_reasons ?? old('default_reasons') }}" rows="4" 
                                icon="bi-chat-left-text" required />
                            
                            <div class="mb-4 mt-3">
                                <label class="form-label fw-semibold">Demand Letter Sent?</label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="demand_made" id="demand_yes" 
                                            value="1" {{ (old('demand_made', $claim->demand_made ?? null) == 1) ? 'checked' : '' }} required>
                                        <label class="form-check-label" for="demand_yes">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="demand_made" id="demand_no" 
                                            value="0" {{ (old('demand_made', $claim->demand_made ?? null) == 0) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="demand_no">No</label>
                                    </div>
                                </div>
                            </div>

                            <div id="demand-letter-section" class="{{ (old('demand_made', $claim->demand_made ?? 0) ? '' : 'd-none') }}">
                                <x-overdue.file-upload name="demand_letter" label="Demand Letter Copy" 
                                    :currentFile="$claim->demand_letter_path ?? null" />
                            </div>

                            <div id="no-demand-reason-section" class="{{ !(old('demand_made', $claim->demand_made ?? 0) ? '' : 'd-none') }}">
                                <x-overdue.textarea-group name="no_demand_reason" label="Reason for Not Sending Demand Letter" 
                                    value="{{ $claim->no_demand_reason ?? old('no_demand_reason') }}" rows="3" 
                                    icon="bi-chat-left-dots" />
                            </div>
                        </section>

                        <!-- 4. Recovery Info -->
                        <section class="mb-5">
                            <h5 class="fw-semibold text-secondary border-bottom pb-2 mb-4">
                                <i class="bi bi-lightning-charge me-2"></i> Recovery Information
                            </h5>
                            <x-overdue.textarea-group name="recovery_steps_taken" label="Steps Already Taken" 
                                value="{{ $claim->recovery_steps_taken ?? old('recovery_steps_taken') }}" rows="4" 
                                icon="bi-list-check" required />
                            <x-overdue.textarea-group name="proposed_steps" label="Proposed Next Steps" 
                                value="{{ $claim->proposed_steps ?? old('proposed_steps') }}" rows="4" 
                                icon="bi-list-ol" required />
                            <x-overdue.textarea-group name="additional_info" label="Additional Information" 
                                value="{{ $claim->additional_info ?? old('additional_info') }}" rows="3" 
                                icon="bi-info-circle" />
                        </section>

                        <!-- 5. Bank Representative -->
                        <section class="mb-4">
                            <h5 class="fw-semibold text-secondary border-bottom pb-2 mb-4">
                                <i class="bi bi-person-badge me-2"></i> Bank Representative
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <x-overdue.input-group name="signature_name" label="Representative Name" 
                                        value="{{ $claim->signature_name ?? old('signature_name') }}" 
                                        icon="bi-person-vcard" required />
                                </div>
                                <div class="col-md-4">
                                    <x-overdue.input-group name="signature_designation" label="Designation" 
                                        value="{{ $claim->signature_designation ?? old('signature_designation') }}" 
                                        icon="bi-person-lines-fill" required />
                                </div>
                                <div class="col-md-4">
                                    <x-overdue.input-group name="report_date" label="Report Date" type="date" 
                                        value="{{ $claim->report_date ?? old('report_date', date('Y-m-d')) }}" 
                                        icon="bi-calendar-date" required />
                                </div>
                                <div class="col-12">
                                    <x-overdue.textarea-group name="bank_address" label="Bank Address" 
                                        value="{{ $claim->bank_address ?? old('bank_address') }}" rows="2" 
                                        icon="bi-geo-alt" required />
                                </div>
                            </div>
                        </section>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary px-5 py-2">
                                <i class="bi bi-save me-2"></i> Save Claim
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
        --primary-blue: #1a3e72;
        --light-blue: #ebf2ff;
    }
    
    .bg-light-primary {
        background-color: rgba(26, 62, 114, 0.1);
    }
    
    .card {
        border-radius: 0.75rem;
        border: none;
    }
    
    .card-header {
        border-radius: 0.75rem 0.75rem 0 0 !important;
        padding: 1.25rem 1.5rem;
    }
    
    .form-control, .input-group-text, .form-select {
        height: 45px;
        border-radius: 0.5rem;
    }
    
    textarea.form-control {
        min-height: 120px;
    }
    
    .form-check-input {
        width: 1.1em;
        height: 1.1em;
        margin-top: 0.15em;
    }
    
    .form-label {
        color: #2d3748;
        font-weight: 500;
    }
    
    .input-group-text {
        background-color: var(--light-blue);
        color: var(--primary-blue);
    }
    
    .btn-primary {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
    }
    
    .border-bottom {
        border-color: #e2e8f0 !important;
    }
    
    .alert {
        border-left: 4px solid;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle demand letter sections
    const demandRadios = document.querySelectorAll('input[name="demand_made"]');
    const letterSection = document.getElementById('demand-letter-section');
    const reasonSection = document.getElementById('no-demand-reason-section');

    if (demandRadios.length > 0) {
        function toggleSections() {
            const isDemandMade = document.querySelector('input[name="demand_made"]:checked').value === '1';
            letterSection.classList.toggle('d-none', !isDemandMade);
            reasonSection.classList.toggle('d-none', isDemandMade);
        }

        demandRadios.forEach(radio => radio.addEventListener('change', toggleSections));
        toggleSections();
    }

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endsection