<!-- Document Upload Forms -->
<div class="card">
    <div class="card-header bg-light">
        <h5 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Required Documents</h5>
    </div>
    <div class="card-body">
        <!-- Section A - Mandatory Documents (ALWAYS SHOWN) -->
        <h6 class="text-primary mb-3">Section A - Mandatory Documents</h6>
        
        @foreach([
            'formal_claim_application' => 'Formal Claim Application',
            'facility_offer_letter' => 'Facility/Offer Letter',
            'guarantors_bond' => 'Guarantor\'s Bond',
            'guarantors_statement' => 'Guarantor\'s Statement',
            'loan_repayment_schedule' => 'Loan Repayment Schedule',
            'proof_of_disbursement' => 'Proof of Disbursement',
            'recovery_actions' => 'Recovery Actions',
            'kyc_documents' => 'KYC Documents'
        ] as $field => $label)
            @include('components.claims.partials.document-upload-form', [
                'field' => $field,
                'label' => $label,
                'claim' => $claim
            ])
        @endforeach

        <!-- DYNAMIC SECTIONS BASED ON DEFAULT REASON -->

        <!-- Section B1 - Missing/Absconded in Abroad -->
        @if($claim->default_reason === 'missing_abroad')
            <h6 class="text-primary mb-3 mt-4">Section B1 - Missing/Absconded in Abroad</h6>
            
            @foreach([
                'b1_police_complaint' => 'Police Complaint',
                'b1_affidavit' => 'Affidavit',
                'b1_tracing_proof' => 'Tracing Proof'
            ] as $field => $label)
                @include('components.claims.partials.document-upload-form', [
                    'field' => $field,
                    'label' => $label,
                    'claim' => $claim
                ])
            @endforeach
        @endif

        <!-- Section B2 - Missing in Sri Lanka -->
        @if($claim->default_reason === 'missing_local')
            <h6 class="text-primary mb-3 mt-4">Section B2 - Missing in Sri Lanka</h6>
            
            @foreach([
                'b2_police_complaint' => 'Police Complaint',
                'b2_tracing_proof' => 'Tracing Proof'
            ] as $field => $label)
                @include('components.claims.partials.document-upload-form', [
                    'field' => $field,
                    'label' => $label,
                    'claim' => $claim
                ])
            @endforeach
        @endif

        <!-- Section C - Deceased -->
        @if($claim->default_reason === 'deceased')
            <h6 class="text-primary mb-3 mt-4">Section C - Deceased</h6>
            
            @include('components.claims.partials.document-upload-form', [
                'field' => 'death_certificate',
                'label' => 'Death Certificate',
                'claim' => $claim
            ])
        @endif

        <!-- Section D - Medically Unfit -->
        @if($claim->default_reason === 'medically_unfit')
            <h6 class="text-primary mb-3 mt-4">Section D - Medically Unfit/Critically Ill</h6>
            
            @foreach([
                'medical_certificate_abroad' => 'Medical Certificate (Abroad)',
                'medical_report_local' => 'Medical Report (Local)'
            ] as $field => $label)
                @include('components.claims.partials.document-upload-form', [
                    'field' => $field,
                    'label' => $label,
                    'claim' => $claim
                ])
            @endforeach
        @endif

        <!-- Section E - Fraud -->
        @if($claim->default_reason === 'fraud')
            <h6 class="text-primary mb-3 mt-4">Section E - Fraud</h6>
            
            @include('components.claims.partials.document-upload-form', [
                'field' => 'fraud_evidence',
                'label' => 'Fraud Evidence',
                'claim' => $claim
            ])
        @endif

        <!-- Section F - Refusal to Pay -->
        @if($claim->default_reason === 'refusal_pay')
            <h6 class="text-primary mb-3 mt-4">Section F - Refusal to Pay</h6>
            
            @include('components.claims.partials.document-upload-form', [
                'field' => 'refusal_correspondence',
                'label' => 'Refusal Correspondence',
                'claim' => $claim
            ])
        @endif

        <!-- Section G - Loss of Job -->
        @if($claim->default_reason === 'job_loss')
            <h6 class="text-primary mb-3 mt-4">Section G - Loss of Job</h6>
            
            @foreach([
                'termination_letter' => 'Termination Letter',
                'unemployment_proof' => 'Unemployment Proof'
            ] as $field => $label)
                @include('components.claims.partials.document-upload-form', [
                    'field' => $field,
                    'label' => $label,
                    'claim' => $claim
                ])
            @endforeach
        @endif

        <!-- Section H - Borrower Shifted Job -->
        @if($claim->default_reason === 'job_shift')
            <h6 class="text-primary mb-3 mt-4">Section H - Borrower Shifted Job</h6>
            
            @foreach([
                'new_employment_letter' => 'New Employment Letter',
                'income_change_evidence' => 'Income Change Evidence'
            ] as $field => $label)
                @include('components.claims.partials.document-upload-form', [
                    'field' => $field,
                    'label' => $label,
                    'claim' => $claim
                ])
            @endforeach
        @endif
    </div>
</div>