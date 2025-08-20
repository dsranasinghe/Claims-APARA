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
                            <input type="number" step="0.01" class="form-control" id="total_repayments" name="total_repayments" 
                                   value="{{ $claim->total_repayments ?? old('total_repayments') }}" required>
                            <label for="total_repayments">Total Repayments (LKR)</label>
                            <div class="invalid-feedback">
                                Please provide the total repayment amount.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="number" step="0.01" class="form-control" id="amount_outstanding" name="amount_outstanding" 
                                   value="{{ $claim->amount_outstanding ?? old('amount_outstanding') }}" required>
                            <label for="amount_outstanding">Amount Outstanding (LKR)</label>
                            <div class="invalid-feedback">
                                Please provide the outstanding amount.
                            </div>
                        </div>
                    </div>
                </div>
            </x-overdue.form-section>

            <!-- 3. Default Info -->
            <x-overdue.form-section icon="exclamation-triangle" title="Default Information">
                <div class="mb-4">
                    <label for="default_reason" class="form-label required-field">Reason for Default</label>
                    <select class="form-select" id="default_reason" name="default_reason" required>
                        <option value="">Select a reason</option>
                        <option value="missing_abroad" {{ (old('default_reason', $claim->default_reason ?? '') == 'missing_abroad') ? 'selected' : '' }}>Missing/Absconded in Abroad (B1)</option>
                        <option value="missing_local" {{ (old('default_reason', $claim->default_reason ?? '') == 'missing_local') ? 'selected' : '' }}>Missing in Sri Lanka After Arrival (B2)</option>
                        <option value="deceased" {{ (old('default_reason', $claim->default_reason ?? '') == 'deceased') ? 'selected' : '' }}>Borrower is Deceased (C)</option>
                        <option value="medically_unfit" {{ (old('default_reason', $claim->default_reason ?? '') == 'medically_unfit') ? 'selected' : '' }}>Borrower is Medically Unfit/Critically Ill (D)</option>
                        <option value="fraud" {{ (old('default_reason', $claim->default_reason ?? '') == 'fraud') ? 'selected' : '' }}>Fraud (E)</option>
                        <option value="refusal_pay" {{ (old('default_reason', $claim->default_reason ?? '') == 'refusal_pay') ? 'selected' : '' }}>Refusal to Pay (F)</option>
                        <option value="job_loss" {{ (old('default_reason', $claim->default_reason ?? '') == 'job_loss') ? 'selected' : '' }}>Loss of Job (G)</option>
                        <option value="job_shift" {{ (old('default_reason', $claim->default_reason ?? '') == 'job_shift') ? 'selected' : '' }}>Borrower Shifted Job (H)</option>
                    </select>
                    <div class="form-text">Select the primary reason for the loan default</div>
                    <div class="invalid-feedback">
                        Please select a reason for default.
                    </div>
                </div>
                
                <div class="form-floating mb-4">
                    <textarea class="form-control" id="default_reasons" name="default_reasons" 
                              style="height: 120px" required>{{ $claim->default_reasons ?? old('default_reasons') }}</textarea>
                    <label for="default_reasons">Detailed Reasons for Default</label>
                    <div class="invalid-feedback">
                        Please provide detailed reasons for the default.
                    </div>
                </div>
                
                <!-- Document Requirements Section (Dynamic) -->
                <div id="document-requirements-section" class="document-requirements d-none">
                    <h5 class="section-title"><i class="bi bi-file-earmark-text"></i> Required Documents</h5>
                    <p class="text-muted mb-3">Based on the selected default reason, please upload the following documents:</p>
                    
                    <div class="requirement-list">
                        <!-- Mandatory for all claims -->
                        <div class="requirement-item mandatory-all d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section A - Mandatory for all claims:</span>
                                <p class="requirement-text">1. Formal Claim application</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_formal_claim" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Formal Claim Application (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item mandatory-all d-none">
                            <p class="requirement-text">2. Copy of facility/offer letter, guarantors bond, guarantors statement</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_facility_letter" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Facility Documents (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item mandatory-all d-none">
                            <p class="requirement-text">3. Detailed loan repayment schedule</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_repayment_schedule" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Repayment Schedule (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item mandatory-all d-none">
                            <p class="requirement-text">4. Proof of Disbursement to borrower (loan disbursement voucher, fund transfer slip)</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_disbursement_proof" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Disbursement Proof (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item mandatory-all d-none">
                            <p class="requirement-text">5. Details of other recovery actions taken (copies of default notices and final demand letters)</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_recovery_actions" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Recovery Actions Documentation (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item mandatory-all d-none">
                            <p class="requirement-text">6. KYC documents of borrower (NIC, passport copies showing departure/arrival details)</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_kyc" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload KYC Documents (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <!-- Section B1 - Missing/absconded in abroad -->
                        <div class="requirement-item section-b1 d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section B1 - Missing/absconded in abroad:</span>
                                <p class="requirement-text">1. Police complaint by family member</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_b1_police_complaint" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Police Complaint (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-b1 d-none">
                            <p class="requirement-text">2. Affidavit confirming disappearance</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_b1_affidavit" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Affidavit (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-b1 d-none">
                            <p class="requirement-text">3. Proof of tracing attempts (letters, field reports)</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_b1_tracing_proof" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Tracing Proof (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <!-- Section B2 - Missing in Sri Lanka after arrival -->
                        <div class="requirement-item section-b2 d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section B2 - Missing in Sri Lanka after arrival:</span>
                                <p class="requirement-text">1. Police complaint</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_b2_police_complaint" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Police Complaint (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-b2 d-none">
                            <p class="requirement-text">2. Proof of tracing attempts if any</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_b2_tracing_proof" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Tracing Proof (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <!-- Section C - Borrower is deceased -->
                        <div class="requirement-item section-c d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section C - Borrower is deceased:</span>
                                <p class="requirement-text">1. Certified death certificate</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_c_death_certificate" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Death Certificate (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <!-- Section D - Borrower is medically unfit/critically ill -->
                        <div class="requirement-item section-d d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section D - Borrower is medically unfit/critically ill:</span>
                                <p class="requirement-text">1. Medical certificate issued by a medical officer in the relevant country (if abroad)</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_d_medical_certificate" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Medical Certificate (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-d d-none">
                            <p class="requirement-text">2. Certified medical report from a government medical officer or specialist (if in Sri Lanka)</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_d_medical_report" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Medical Report (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <!-- Sections E-H -->
                        <div class="requirement-item section-e d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section E - Fraud:</span>
                                <p class="requirement-text">1. Police report for fraud case</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_e_police_report" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Police Report (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-e d-none">
                            <p class="requirement-text">2. Evidence of fraudulent activity</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_e_fraud_evidence" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Fraud Evidence (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-f d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section F - Refusal to pay:</span>
                                <p class="requirement-text">1. Correspondence showing refusal to pay</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_f_refusal_correspondence" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Refusal Correspondence (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-f d-none">
                            <p class="requirement-text">2. Records of payment reminders and responses</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_f_payment_records" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Payment Records (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-g d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section G - Loss of job:</span>
                                <p class="requirement-text">1. Termination letter from employer</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_g_termination_letter" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Termination Letter (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-g d-none">
                            <p class="requirement-text">2. Proof of unemployment benefits application</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_g_unemployment_proof" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Unemployment Proof (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-h d-none">
                            <div class="requirement-header">
                                <span class="requirement-section">Section H - Borrower shifted job:</span>
                                <p class="requirement-text">1. Proof of job change (new employment letter)</p>
                            </div>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_h_employment_letter" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Employment Letter (PDF, Word, or Image)</div>
                            </div>
                        </div>
                        
                        <div class="requirement-item section-h d-none">
                            <p class="requirement-text">2. Evidence of income change</p>
                            <div class="file-upload-area mb-3">
                                <input type="file" class="form-control" name="doc_h_income_evidence" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <div class="form-text">Upload Income Evidence (PDF, Word, or Image)</div>
                            </div>
                        </div>
                    </div>
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
                                <input type="file" class="form-control" id="demand_letter" name="demand_letter">
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
                        <textarea class="form-control" id="no_demand_reason" name="no_demand_reason" 
                                  style="height: 100px">{{ $claim->no_demand_reason ?? old('no_demand_reason') }}</textarea>
                        <label for="no_demand_reason">Reason for Not Sending Demand Letter</label>
                    </div>
                </div>
            </x-overdue.form-section>

            <!-- 4. Recovery Info -->
            <x-overdue.form-section icon="lightning-charge" title="Recovery Information">
                <div class="form-floating mb-4">
                    <textarea class="form-control" id="recovery_steps_taken" name="recovery_steps_taken" 
                              style="height: 120px" required>{{ $claim->recovery_steps_taken ?? old('recovery_steps_taken') }}</textarea>
                    <label for="recovery_steps_taken">Steps Already Taken</label>
                    <div class="invalid-feedback">
                        Please describe the recovery steps already taken.
                    </div>
                </div>
                
                <div class="form-floating mb-4">
                    <textarea class="form-control" id="proposed_steps" name="proposed_steps" 
                              style="height: 120px" required>{{ $claim->proposed_steps ?? old('proposed_steps') }}</textarea>
                    <label for="proposed_steps">Proposed Next Steps</label>
                    <div class="invalid-feedback">
                        Please describe the proposed next steps.
                    </div>
                </div>
                
                <div class="form-floating">
                    <textarea class="form-control" id="additional_info" name="additional_info" 
                              style="height: 100px">{{ $claim->additional_info ?? old('additional_info') }}</textarea>
                    <label for="additional_info">Additional Information</label>
                </div>
            </x-overdue.form-section>

            <!-- 5. Bank Representative -->
            <x-overdue.form-section icon="person-badge" title="Bank Representative">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="signature_name" name="signature_name" 
                                   value="{{ $claim->signature_name ?? old('signature_name') }}" required>
                            <label for="signature_name">Representative Name</label>
                            <div class="invalid-feedback">
                                Please provide the representative's name.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="signature_designation" name="signature_designation" 
                                   value="{{ $claim->signature_designation ?? old('signature_designation') }}" required>
                            <label for="signature_designation">Designation</label>
                            <div class="invalid-feedback">
                                Please provide the representative's designation.
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date" class="form-control" id="report_date" name="report_date" 
                                   value="{{ $claim->report_date ?? old('report_date', date('Y-m-d')) }}" required>
                            <label for="report_date">Report Date</label>
                            <div class="invalid-feedback">
                                Please provide the report date.
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control" id="bank_address" name="bank_address" 
                                      style="height: 100px" required>{{ $claim->bank_address ?? old('bank_address') }}</textarea>
                            <label for="bank_address">Bank Address</label>
                            <div class="invalid-feedback">
                                Please provide the bank address.
                            </div>
                        </div>
                    </div>
                </div>
            </x-overdue.form-section>

            <div class="d-flex justify-content-end mt-5">
                <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="bi bi-save me-2"></i> Save Claim Report
                </button>
            </div>
        </form>
    </div>
</div>