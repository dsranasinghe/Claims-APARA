@props(['application', 'claim'])

<div class="card glass-card border-0 shadow-soft mb-5">
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
            <x-overdue.alert type="success" icon="check-circle-fill" :dismissible="true">
                {{ session('success') }}
            </x-overdue.alert>
        @endif

        <form method="POST" action="{{ route('claims.store', $application->application_no) }}" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf

            <!-- 1. Bank & Customer Info -->
            <x-overdue.form-section icon="building" title="Bank & Customer Information">
                <div class="bank-info-container">
                    <div class="bank-info-row">
                        <div class="bank-info-label">
                            <i class="bi bi-bank me-2"></i>Bank Name
                        </div>
                        <div class="bank-info-value">
                            {{ $application->bank->bank_name }}
                        </div>
                    </div>
                    
                    <div class="bank-info-row">
                        <div class="bank-info-label">
                            <i class="bi bi-geo-alt me-2"></i>Branch
                        </div>
                        <div class="bank-info-value">
                            {{ $application->bank->branch_name }}
                        </div>
                    </div>
                    
                    <div class="bank-info-row">
                        <div class="bank-info-label">
                            <i class="bi bi-person me-2"></i>Customer Name
                        </div>
                        <div class="bank-info-value">
                            {{ $application->customer_name }}
                        </div>
                    </div>
                    
                    <div class="bank-info-row">
                        <div class="bank-info-label">
                            <i class="bi bi-geo me-2"></i>Customer Address
                        </div>
                        <div class="bank-info-value address-field">
                            {{ $application->customer_address }}
                        </div>
                    </div>
                </div>
            </x-overdue.form-section>

            <!-- 2. Financial Info -->
            <x-overdue.form-section icon="cash-stack" title="Financial Information">
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
            </x-overdue.form-section>

            <!-- 3. Default Info -->
            <x-overdue.form-section icon="exclamation-triangle" title="Default Information">
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
            </x-overdue.form-section>

            <!-- 4. Recovery Info -->
            <x-overdue.form-section icon="lightning-charge" title="Recovery Information">
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
            </x-overdue.form-section>

            <!-- 5. Bank Representative -->
            <x-overdue.form-section icon="person-badge" title="Bank Representative">
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
            </x-overdue.form-section>

            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 py-3 shadow-sm">
                    <i class="bi bi-save me-2"></i> Save Claim Report
                </button>
            </div>
        </form>
    </div>
</div>