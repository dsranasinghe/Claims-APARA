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

                    <!-- Application Details -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Application Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
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
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Default Reason:</th>
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="{{ $field }}">
                                    @csrf
                                    <input type="hidden" name="document_type" value="{{ $field }}">
                                    
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">{{ $label }}</label>
                                        <span class="upload-status badge ms-2" id="status-{{ $field }}">
                                            @if($claim->documents && !empty($claim->documents->$field))
                                                <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <input type="file" class="form-control" name="document" required>
                                            <div class="form-text">
                                                Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                <i class="bi bi-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
                                    <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="{{ $field }}">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $field }}">
                                        
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">{{ $label }}</label>
                                            <span class="upload-status badge ms-2" id="status-{{ $field }}">
                                                @if($claim->documents && !empty($claim->documents->$field))
                                                    <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="row align-items-end">
                                            <div class="col-md-8">
                                                <input type="file" class="form-control" name="document" required>
                                                <div class="form-text">
                                                    Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                    <i class="bi bi-upload me-1"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endforeach
                            @endif

                            <!-- Section B2 - Missing in Sri Lanka -->
                            @if($claim->default_reason === 'missing_local')
                                <h6 class="text-primary mb-3 mt-4">Section B2 - Missing in Sri Lanka</h6>
                                
                                @foreach([
                                    'b2_police_complaint' => 'Police Complaint',
                                    'b2_tracing_proof' => 'Tracing Proof'
                                ] as $field => $label)
                                    <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="{{ $field }}">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $field }}">
                                        
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">{{ $label }}</label>
                                            <span class="upload-status badge ms-2" id="status-{{ $field }}">
                                                @if($claim->documents && !empty($claim->documents->$field))
                                                    <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="row align-items-end">
                                            <div class="col-md-8">
                                                <input type="file" class="form-control" name="document" required>
                                                <div class="form-text">
                                                    Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                    <i class="bi bi-upload me-1"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endforeach
                            @endif

                            <!-- Section C - Deceased -->
                            @if($claim->default_reason === 'deceased')
                                <h6 class="text-primary mb-3 mt-4">Section C - Deceased</h6>
                                
                                <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="death_certificate">
                                    @csrf
                                    <input type="hidden" name="document_type" value="death_certificate">
                                    
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Death Certificate</label>
                                        <span class="upload-status badge ms-2" id="status-death_certificate">
                                            @if($claim->documents && !empty($claim->documents->death_certificate))
                                                <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <input type="file" class="form-control" name="document" required>
                                            <div class="form-text">
                                                Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                <i class="bi bi-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <!-- Section D - Medically Unfit -->
                            @if($claim->default_reason === 'medically_unfit')
                                <h6 class="text-primary mb-3 mt-4">Section D - Medically Unfit/Critically Ill</h6>
                                
                                @foreach([
                                    'medical_certificate_abroad' => 'Medical Certificate (Abroad)',
                                    'medical_report_local' => 'Medical Report (Local)'
                                ] as $field => $label)
                                    <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="{{ $field }}">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $field }}">
                                        
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">{{ $label }}</label>
                                            <span class="upload-status badge ms-2" id="status-{{ $field }}">
                                                @if($claim->documents && !empty($claim->documents->$field))
                                                    <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="row align-items-end">
                                            <div class="col-md-8">
                                                <input type="file" class="form-control" name="document" required>
                                                <div class="form-text">
                                                    Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                    <i class="bi bi-upload me-1"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endforeach
                            @endif

                            <!-- Section E - Fraud -->
                            @if($claim->default_reason === 'fraud')
                                <h6 class="text-primary mb-3 mt-4">Section E - Fraud</h6>
                                
                                <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="fraud_evidence">
                                    @csrf
                                    <input type="hidden" name="document_type" value="fraud_evidence">
                                    
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Fraud Evidence</label>
                                        <span class="upload-status badge ms-2" id="status-fraud_evidence">
                                            @if($claim->documents && !empty($claim->documents->fraud_evidence))
                                                <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <input type="file" class="form-control" name="document" required>
                                            <div class="form-text">
                                                Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                <i class="bi bi-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <!-- Section F - Refusal to Pay -->
                            @if($claim->default_reason === 'refusal_pay')
                                <h6 class="text-primary mb-3 mt-4">Section F - Refusal to Pay</h6>
                                
                                <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="refusal_correspondence">
                                    @csrf
                                    <input type="hidden" name="document_type" value="refusal_correspondence">
                                    
                                    <div class="mb-2">
                                        <label class="form-label fw-semibold">Refusal Correspondence</label>
                                        <span class="upload-status badge ms-2" id="status-refusal_correspondence">
                                            @if($claim->documents && !empty($claim->documents->refusal_correspondence))
                                                <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="row align-items-end">
                                        <div class="col-md-8">
                                            <input type="file" class="form-control" name="document" required>
                                            <div class="form-text">
                                                Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                <i class="bi bi-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            @endif

                            <!-- Section G - Loss of Job -->
                            @if($claim->default_reason === 'job_loss')
                                <h6 class="text-primary mb-3 mt-4">Section G - Loss of Job</h6>
                                
                                @foreach([
                                    'termination_letter' => 'Termination Letter',
                                    'unemployment_proof' => 'Unemployment Proof'
                                ] as $field => $label)
                                    <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="{{ $field }}">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $field }}">
                                        
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">{{ $label }}</label>
                                            <span class="upload-status badge ms-2" id="status-{{ $field }}">
                                                @if($claim->documents && !empty($claim->documents->$field))
                                                    <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="row align-items-end">
                                            <div class="col-md-8">
                                                <input type="file" class="form-control" name="document" required>
                                                <div class="form-text">
                                                    Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                    <i class="bi bi-upload me-1"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endforeach
                            @endif

                            <!-- Section H - Borrower Shifted Job -->
                            @if($claim->default_reason === 'job_shift')
                                <h6 class="text-primary mb-3 mt-4">Section H - Borrower Shifted Job</h6>
                                
                                @foreach([
                                    'new_employment_letter' => 'New Employment Letter',
                                    'income_change_evidence' => 'Income Change Evidence'
                                ] as $field => $label)
                                    <form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="{{ $field }}">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $field }}">
                                        
                                        <div class="mb-2">
                                            <label class="form-label fw-semibold">{{ $label }}</label>
                                            <span class="upload-status badge ms-2" id="status-{{ $field }}">
                                                @if($claim->documents && !empty($claim->documents->$field))
                                                    <i class="bi bi-check-circle-fill text-success"></i> Uploaded
                                                @else
                                                    <i class="bi bi-x-circle-fill text-danger"></i> Pending
                                                @endif
                                            </span>
                                        </div>
                                        
                                        <div class="row align-items-end">
                                            <div class="col-md-8">
                                                <input type="file" class="form-control" name="document" required>
                                                <div class="form-text">
                                                    Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. Maximum 5MB.
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="submit" class="btn btn-primary w-100 upload-btn">
                                                    <i class="bi bi-upload me-1"></i> Upload
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                @endforeach
                            @endif

                        </div>
                    </div>

                    <!-- Submit Application Button -->
                    <div class="card mt-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="bi bi-send me-2"></i>Submit Application</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i> 
                                Please upload all required documents before submitting your application.
                            </div>
                            
                            <form action="{{ route('claims.submit', $claim->application_no) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-2"></i> Submit Application for Review
                                </button>
                            </form>
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
    .card {
        border: none;
        border-radius: 0.5rem;
    }
    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }
    .border-rounded {
        border-radius: 0.5rem;
    }
    .upload-status {
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle document upload forms with AJAX
    const uploadForms = document.querySelectorAll('.document-upload-form');
    
    uploadForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const documentType = this.dataset.documentType;
            const uploadBtn = this.querySelector('.upload-btn');
            const statusElement = document.getElementById(`status-${documentType}`);
            
            // Show loading state
            const originalText = uploadBtn.innerHTML;
            uploadBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Uploading...';
            uploadBtn.disabled = true;
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    // Update status to show uploaded
                    statusElement.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> Uploaded';
                    
                    // Show success message
                    showAlert('Document uploaded successfully!', 'success');
                    
                    // Clear the file input
                    this.querySelector('input[type="file"]').value = '';
                    
                } else {
                    throw new Error(result.message || 'Upload failed');
                }
            } catch (error) {
                showAlert(error.message || 'Error uploading document. Please try again.', 'error');
            } finally {
                // Restore button state
                uploadBtn.innerHTML = originalText;
                uploadBtn.disabled = false;
            }
        });
    });
    
    // Function to show alert messages
    function showAlert(message, type) {
        // Remove existing alerts
        const existingAlerts = document.querySelectorAll('.dynamic-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show dynamic-alert`;
        alertDiv.innerHTML = `
            <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'} me-2"></i> 
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        // Insert at the top of the card body
        const cardBody = document.querySelector('.card-body');
        cardBody.insertBefore(alertDiv, cardBody.firstChild);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentElement) {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }
        }, 5000);
    }
    
    // Add spinner animation
    const style = document.createElement('style');
    style.textContent = `
        .spinner {
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});
</script>
@endpush