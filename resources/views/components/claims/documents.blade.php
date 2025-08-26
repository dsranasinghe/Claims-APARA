@extends('layouts.bankapp')

@section('title', 'Upload Documents - ' . $claim->application_no)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">
                            <i class="bi bi-file-earmark-arrow-up me-2"></i> 
                            Upload Documents for Application #{{ $claim->application_no }}
                        </h3>
                        <a href="{{ route('claims.pending') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Application Details</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Application No:</th>
                                            <td>{{ $claim->application_no }}</td>
                                        </tr>
                                        <tr>
                                            <th>Customer Name:</th>
                                            <td>{{ $claim->customer_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Bank Name:</th>
                                            <td>{{ $claim->bank_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Branch:</th>
                                            <td>{{ $claim->branch_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Default Reason:</th>
                                            <td>
                                                @php
                                                    $reasonLabels = [
                                                        'missing_abroad' => 'Missing/Absconded in Abroad (B1)',
                                                        'missing_local' => 'Missing in Sri Lanka After Arrival (B2)',
                                                        'deceased' => 'Borrower is Deceased (C)',
                                                        'medically_unfit' => 'Borrower is Medically Unfit/Critically Ill (D)',
                                                        'fraud' => 'Fraud (E)',
                                                        'refusal_pay' => 'Refusal to Pay (F)',
                                                        'job_loss' => 'Loss of Job (G)',
                                                        'job_shift' => 'Borrower Shifted Job (H)'
                                                    ];
                                                @endphp
                                                <span class="badge bg-info">
                                                    {{ $reasonLabels[$claim->default_reason] ?? 'Not specified' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Amount Outstanding:</th>
                                            <td class="fw-bold">LKR {{ number_format($claim->amount_outstanding, 2) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge bg-{{ $claim->status === 'paid' ? 'success' : 'warning' }}">
                                                    {{ ucfirst($claim->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Date Submitted:</th>
                                            <td>
                                                @if(is_string($claim->created_at))
                                                    {{ \Carbon\Carbon::parse($claim->created_at)->format('M d, Y') }}
                                                @else
                                                    {{ $claim->created_at->format('M d, Y') }}
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-cloud-upload me-2"></i>Upload Documents</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('claims.upload-documents', $claim->application_no) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label for="document_type" class="form-label fw-semibold">Document Type</label>
                                            <select class="form-select" id="document_type" name="document_type" required>
                                                <option value="">-- Select Document Type --</option>
                                                
                                                <!-- Section A - Mandatory Documents (Always shown) -->
                                                <optgroup label="Section A - Mandatory Documents">
                                                    <option value="formal_claim_application">Formal Claim Application</option>
                                                    <option value="facility_offer_letter">Facility/Offer Letter</option>
                                                    <option value="guarantors_bond">Guarantor's Bond</option>
                                                    <option value="guarantors_statement">Guarantor's Statement</option>
                                                    <option value="loan_repayment_schedule">Loan Repayment Schedule</option>
                                                    <option value="proof_of_disbursement">Proof of Disbursement</option>
                                                    <option value="recovery_actions">Recovery Actions</option>
                                                    <option value="kyc_documents">KYC Documents</option>
                                                </optgroup>
                                                
                                                <!-- Dynamic sections based on default reason -->
                                                @if($claim->default_reason === 'missing_abroad')
                                                    <!-- Section B1 -->
                                                    <optgroup label="Section B1 - Missing/absconded abroad">
                                                        <option value="b1_police_complaint">Police Complaint</option>
                                                        <option value="b1_affidavit">Affidavit</option>
                                                        <option value="b1_tracing_proof">Tracing Proof</option>
                                                    </optgroup>
                                                @elseif($claim->default_reason === 'missing_local')
                                                    <!-- Section B2 -->
                                                    <optgroup label="Section B2 - Missing in Sri Lanka">
                                                        <option value="b2_police_complaint">Police Complaint</option>
                                                        <option value="b2_tracing_proof">Tracing Proof</option>
                                                    </optgroup>
                                                @elseif($claim->default_reason === 'deceased')
                                                    <!-- Section C -->
                                                    <optgroup label="Section C - Deceased">
                                                        <option value="death_certificate">Death Certificate</option>
                                                    </optgroup>
                                                @elseif($claim->default_reason === 'medically_unfit')
                                                    <!-- Section D -->
                                                    <optgroup label="Section D - Medically Unfit">
                                                        <option value="medical_certificate_abroad">Medical Certificate (Abroad)</option>
                                                        <option value="medical_report_local">Medical Report (Local)</option>
                                                    </optgroup>
                                                @elseif($claim->default_reason === 'fraud')
                                                    <!-- Section E -->
                                                    <optgroup label="Section E - Fraud">
                                                        <option value="fraud_evidence">Fraud Evidence</option>
                                                    </optgroup>
                                                @elseif($claim->default_reason === 'refusal_pay')
                                                    <!-- Section F -->
                                                    <optgroup label="Section F - Refusal to Pay">
                                                        <option value="refusal_correspondence">Refusal Correspondence</option>
                                                    </optgroup>
                                                @elseif($claim->default_reason === 'job_loss')
                                                    <!-- Section G -->
                                                    <optgroup label="Section G - Loss of Job">
                                                        <option value="termination_letter">Termination Letter</option>
                                                        <option value="unemployment_proof">Unemployment Proof</option>
                                                    </optgroup>
                                                @elseif($claim->default_reason === 'job_shift')
                                                    <!-- Section H -->
                                                    <optgroup label="Section H - Borrower Shifted Job">
                                                        <option value="new_employment_letter">New Employment Letter</option>
                                                        <option value="income_change_evidence">Income Change Evidence</option>
                                                    </optgroup>
                                                @endif
                                            </select>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="document" class="form-label fw-semibold">Select Document</label>
                                            <input type="file" class="form-control" id="document" name="document" required>
                                            <div class="form-text">
                                                Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="description" class="form-label fw-semibold">Description (Optional)</label>
                                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Brief description of the document"></textarea>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-upload me-2"></i> Upload Document
                                            </button>
                                            <a href="{{ route('claims.pending') }}" class="btn btn-secondary">
                                                <i class="bi bi-arrow-left me-2"></i> Back to List
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Uploaded Documents Section -->
                    <div class="card mt-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="bi bi-files me-2"></i>Uploaded Documents</h5>
                            @if($claim->documents)
                                <span class="badge bg-primary">
                                    {{ count(array_filter($claim->documents->toArray())) - 4 }} documents
                                </span>
                            @else
                                <span class="badge bg-primary">0 documents</span>
                            @endif
                        </div>
                        <div class="card-body">
                            @if($claim->documents && count(array_filter($claim->documents->toArray())) > 4)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Document Type</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach([
                                                'formal_claim_application' => 'Formal Claim Application',
                                                'facility_offer_letter' => 'Facility/Offer Letter',
                                                'guarantors_bond' => 'Guarantor\'s Bond',
                                                'guarantors_statement' => 'Guarantor\'s Statement',
                                                'loan_repayment_schedule' => 'Loan Repayment Schedule',
                                                'proof_of_disbursement' => 'Proof of Disbursement',
                                                'recovery_actions' => 'Recovery Actions',
                                                'kyc_documents' => 'KYC Documents',
                                                'b1_police_complaint' => 'B1 Police Complaint',
                                                'b1_affidavit' => 'B1 Affidavit',
                                                'b1_tracing_proof' => 'B1 Tracing Proof',
                                                'b2_police_complaint' => 'B2 Police Complaint',
                                                'b2_tracing_proof' => 'B2 Tracing Proof',
                                                'death_certificate' => 'Death Certificate',
                                                'medical_certificate_abroad' => 'Medical Certificate (Abroad)',
                                                'medical_report_local' => 'Medical Report (Local)',
                                                'fraud_evidence' => 'Fraud Evidence',
                                                'refusal_correspondence' => 'Refusal Correspondence',
                                                'termination_letter' => 'Termination Letter',
                                                'unemployment_proof' => 'Unemployment Proof',
                                                'new_employment_letter' => 'New Employment Letter',
                                                'income_change_evidence' => 'Income Change Evidence'
                                            ] as $field => $label)
                                                @if(!empty($claim->documents->$field))
                                                    <tr>
                                                        <td>{{ $label }}</td>
                                                        <td>
                                                            <span class="badge bg-success">Uploaded</span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ Storage::url($claim->documents->$field) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle me-2"></i> 
                                    No documents have been uploaded yet. Use the upload form to add required documents.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Document Requirements Section -->
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Required Documents Checklist</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Section A - Always required -->
                                    <h6 class="text-primary">Section A - Mandatory Documents</h6>
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
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" 
                                                id="check_{{ $field }}" 
                                                {{ $claim->documents && !empty($claim->documents->$field) ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label {{ $claim->documents && !empty($claim->documents->$field) ? 'text-success' : '' }}" 
                                                for="check_{{ $field }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <!-- Dynamic sections based on default reason -->
                                    <h6 class="text-primary">Additional Required Documents</h6>
                                    @if($claim->default_reason === 'missing_abroad')
                                        <!-- Section B1 -->
                                        @foreach([
                                            'b1_police_complaint' => 'B1 Police Complaint',
                                            'b1_affidavit' => 'B1 Affidavit',
                                            'b1_tracing_proof' => 'B1 Tracing Proof'
                                        ] as $field => $label)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="check_{{ $field }}" 
                                                    {{ $claim->documents && !empty($claim->documents->$field) ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label {{ $claim->documents && !empty($claim->documents->$field) ? 'text-success' : '' }}" 
                                                    for="check_{{ $field }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @elseif($claim->default_reason === 'missing_local')
                                        <!-- Section B2 -->
                                        @foreach([
                                            'b2_police_complaint' => 'B2 Police Complaint',
                                            'b2_tracing_proof' => 'B2 Tracing Proof'
                                        ] as $field => $label)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="check_{{ $field }}" 
                                                    {{ $claim->documents && !empty($claim->documents->$field) ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label {{ $claim->documents && !empty($claim->documents->$field) ? 'text-success' : '' }}" 
                                                    for="check_{{ $field }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @elseif($claim->default_reason === 'deceased')
                                        <!-- Section C -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" 
                                                id="check_death_certificate" 
                                                {{ $claim->documents && !empty($claim->documents->death_certificate) ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label {{ $claim->documents && !empty($claim->documents->death_certificate) ? 'text-success' : '' }}" 
                                                for="check_death_certificate">
                                                Death Certificate
                                            </label>
                                        </div>
                                    @elseif($claim->default_reason === 'medically_unfit')
                                        <!-- Section D -->
                                        @foreach([
                                            'medical_certificate_abroad' => 'Medical Certificate (Abroad)',
                                            'medical_report_local' => 'Medical Report (Local)'
                                        ] as $field => $label)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="check_{{ $field }}" 
                                                    {{ $claim->documents && !empty($claim->documents->$field) ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label {{ $claim->documents && !empty($claim->documents->$field) ? 'text-success' : '' }}" 
                                                    for="check_{{ $field }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @elseif($claim->default_reason === 'fraud')
                                        <!-- Section E -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" 
                                                id="check_fraud_evidence" 
                                                {{ $claim->documents && !empty($claim->documents->fraud_evidence) ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label {{ $claim->documents && !empty($claim->documents->fraud_evidence) ? 'text-success' : '' }}" 
                                                for="check_fraud_evidence">
                                                Fraud Evidence
                                            </label>
                                        </div>
                                    @elseif($claim->default_reason === 'refusal_pay')
                                        <!-- Section F -->
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" 
                                                id="check_refusal_correspondence" 
                                                {{ $claim->documents && !empty($claim->documents->refusal_correspondence) ? 'checked' : '' }}
                                                disabled>
                                            <label class="form-check-label {{ $claim->documents && !empty($claim->documents->refusal_correspondence) ? 'text-success' : '' }}" 
                                                for="check_refusal_correspondence">
                                                Refusal Correspondence
                                            </label>
                                        </div>
                                    
                                    @elseif($claim->default_reason === 'job_loss')
                                        <!-- Section G -->
                                        @foreach([
                                            'termination_letter' => 'Termination Letter',
                                            'unemployment_proof' => 'Unemployment Proof'
                                        ] as $field => $label)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="check_{{ $field }}" 
                                                    {{ $claim->documents && !empty($claim->documents->$field) ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label {{ $claim->documents && !empty($claim->documents->$field) ? 'text-success' : '' }}" 
                                                    for="check_{{ $field }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @elseif($claim->default_reason === 'job_shift')
                                        <!-- Section H -->
                                        @foreach([
                                            'new_employment_letter' => 'New Employment Letter',
                                            'income_change_evidence' => 'Income Change Evidence'
                                        ] as $field => $label)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" 
                                                    id="check_{{ $field }}" 
                                                    {{ $claim->documents && !empty($claim->documents->$field) ? 'checked' : '' }}
                                                    disabled>
                                                <label class="form-check-label {{ $claim->documents && !empty($claim->documents->$field) ? 'text-success' : '' }}" 
                                                    for="check_{{ $field }}">
                                                    {{ $label }}
                                                </label>
                                            </div>

                                            
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .form-check-input:checked:disabled {
        background-color: #198754;
        border-color: #198754;
    }
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .text-success {
        font-weight: 500;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('document');
        const documentType = document.getElementById('document_type');
        
        if (fileInput.files.length === 0) {
            e.preventDefault();
            alert('Please select a document to upload.');
            fileInput.focus();
            return;
        }
        
        if (documentType.value === '') {
            e.preventDefault();
            alert('Please select a document type.');
            documentType.focus();
        }
    });
    
    // Update progress indicator
    function updateProgress() {
        const totalItems = document.querySelectorAll('.form-check-input').length;
        const completedItems = document.querySelectorAll('.form-check-input:checked').length;
        const progress = Math.round((completedItems / totalItems) * 100);
        
        // You can add a progress bar here if needed
        console.log(`Document completion: ${progress}%`);
    }
    
    // Initial progress update
    updateProgress();
});
</script>
@endpush