@extends('layouts.bankapp')

@section('title', 'Overdue Claim Form')

@section('content')
<div class="container py-5" style="max-width: 1100px;">
    @if(!isset($application))
    {{-- Search Form --}}
    <div class="bg-white p-4 shadow rounded mb-4">
        <div class="border-bottom pb-3 mb-4">
            <h2 class="fw-bold text-primary mb-1">Search Application</h2>
            <span class="text-muted">Enter customer identification details</span>
        </div>

        <form method="GET" action="{{ route('claims.create') }}" class="needs-validation" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="id_no" class="form-label fw-semibold">NIC Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                        <input type="text" class="form-control" id="id_no" name="id_no" placeholder="Enter id_no number" required>
                        <div class="invalid-feedback">
                            Please provide a valid NIC number
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="passport_no" class="form-label fw-semibold">Passport Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-passport_no"></i></span>
                        <input type="text" class="form-control" id="passport_no" name="passport_no" placeholder="Enter passport_no number">
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-search me-2"></i> Search Application
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if(isset($searchPerformed) && $searchPerformed)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> No application found with the provided identification details.
        </div>
    @endif

    @else
    {{-- Overdue Claim Form --}}
    <div class="bg-white p-4 shadow rounded">
        <div class="border-bottom pb-3 mb-4">
            <h2 class="fw-bold text-primary mb-1">Overdue Claim Report</h2>
            <span class="text-muted">Application #{{ $application->application_no }}</span>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('claims.store', $application->application_no) }}" enctype="multipart/form-data">
            @csrf

            {{-- 1. Bank & Customer Info --}}
            <section class="mb-5">
                <h5 class="fw-semibold text-secondary border-bottom pb-2">1. Bank & Customer Information</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <x-overdue.input-group name="bank_name" label="Bank Name" value="{{ $application->bank_name }}" readonly />
                    </div>
                    <div class="col-md-6">
                        <x-overdue.input-group name="branch_name" label="Branch" value="{{ $application->branch_name }}" readonly />
                    </div>
                    <div class="col-md-6">
                        <x-overdue.input-group name="customer_name" label="Customer Name" value="{{ $application->customer_name }}" readonly />
                    </div>
                    <div class="col-md-6">
                        <x-overdue.textarea-group name="customer_address" label="Customer Address" value="{{ $application->customer_address }}" rows="2" readonly />
                    </div>
                </div>
            </section>

            {{-- 2. Financial Info --}}
            <section class="mb-5">
                <h5 class="fw-semibold text-secondary border-bottom pb-2">2. Financial Information</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <x-overdue.input-group name="total_repayments" label="Total Repayments (LKR)" type="number" step="0.01" value="{{ $claim->total_repayments ?? old('total_repayments') }}" required />
                    </div>
                    <div class="col-md-6">
                        <x-overdue.input-group name="amount_outstanding" label="Amount Outstanding (LKR)" type="number" step="0.01" value="{{ $claim->amount_outstanding ?? old('amount_outstanding') }}" required />
                    </div>
                </div>
            </section>

            {{-- 3. Default Info --}}
            <section class="mb-5">
                <h5 class="fw-semibold text-secondary border-bottom pb-2">3. Default Information</h5>
                <x-overdue.textarea-group name="default_reasons" label="Reasons for Default" value="{{ $claim->default_reasons ?? old('default_reasons') }}" rows="4" required />
                
                <div class="mb-3 mt-3">
                    <label class="form-label fw-semibold">Demand Letter Sent?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="demand_made" id="demand_yes" value="1" {{ (old('demand_made', $claim->demand_made ?? null) == 1) ? 'checked' : '' }} required>
                        <label class="form-check-label" for="demand_yes">Yes</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="demand_made" id="demand_no" value="0" {{ (old('demand_made', $claim->demand_made ?? null) == 0) ? 'checked' : '' }}>
                        <label class="form-check-label" for="demand_no">No</label>
                    </div>
                </div>

                <div id="demand-letter-section" class="{{ (old('demand_made', $claim->demand_made ?? 0) ? '' : 'd-none') }}">
                    <x-overdue.file-upload name="demand_letter" label="Demand Letter Copy" :currentFile="$claim->demand_letter_path ?? null" />
                </div>

                <div id="no-demand-reason-section" class="{{ !(old('demand_made', $claim->demand_made ?? 0) ? '' : 'd-none') }}">
                    <x-overdue.textarea-group name="no_demand_reason" label="Reason for Not Sending Demand Letter" value="{{ $claim->no_demand_reason ?? old('no_demand_reason') }}" rows="3" />
                </div>
            </section>

            {{-- 4. Recovery Info --}}
            <section class="mb-5">
                <h5 class="fw-semibold text-secondary border-bottom pb-2">4. Recovery Information</h5>
                <x-overdue.textarea-group name="recovery_steps_taken" label="Steps Already Taken" value="{{ $claim->recovery_steps_taken ?? old('recovery_steps_taken') }}" rows="4" required />
                <x-overdue.textarea-group name="proposed_steps" label="Proposed Next Steps" value="{{ $claim->proposed_steps ?? old('proposed_steps') }}" rows="4" required />
                <x-overdue.textarea-group name="additional_info" label="Additional Information" value="{{ $claim->additional_info ?? old('additional_info') }}" rows="3" />
            </section>

            {{-- 5. Bank Representative --}}
            <section class="mb-5">
                <h5 class="fw-semibold text-secondary border-bottom pb-2">5. Bank Representative</h5>
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <x-overdue.input-group name="signature_name" label="Representative Name" value="{{ $claim->signature_name ?? old('signature_name') }}" required />
                    </div>
                    <div class="col-md-4">
                        <x-overdue.input-group name="signature_designation" label="Designation" value="{{ $claim->signature_designation ?? old('signature_designation') }}" required />
                    </div>
                    <div class="col-md-4">
                        <x-overdue.input-group name="report_date" label="Report Date" type="date" value="{{ $claim->report_date ?? old('report_date', date('Y-m-d')) }}" required />
                    </div>
                    <div class="col-12">
                        <x-overdue.textarea-group name="bank_address" label="Bank Address" value="{{ $claim->bank_address ?? old('bank_address') }}" rows="2" required />
                    </div>
                </div>
            </section>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm">
                    <i class="bi bi-save me-2"></i> Save Claim
                </button>
            </div>
        </form>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search form validation
    const searchForm = document.querySelector('.needs-validation');
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            if (!searchForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            searchForm.classList.add('was-validated');
        }, false);
    }

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
});
</script>

<style>
    .input-group-text {
        background-color: #f8f9fa;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #1a3e72;
        box-shadow: 0 0 0 0.25rem rgba(26, 62, 114, 0.25);
    }
    
    .was-validated .form-control:invalid, .was-validated .form-control:valid {
        background-position: right calc(0.375em + 0.5rem) center;
        padding-right: calc(1.5em + 1rem);
    }
    
    .alert {
        border-left: 4px solid;
    }
    
    .alert-warning {
        border-left-color: #ffc107;
    }
    
    .alert-success {
        border-left-color: #198754;
    }
</style>
@endsection