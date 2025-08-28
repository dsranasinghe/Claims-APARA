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
                    @include('components.claims.partials.alerts')
                    @include('components.claims.partials.application-details', ['claim' => $claim])
                    @include('components.claims.partials.document-upload-forms', ['claim' => $claim])
                    @include('components.claims.partials.submit-application', ['claim' => $claim])
                </div>
            </div>
        </div>
    </div>
</div>
                    @include('components.claims.partials.delete-modal')

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
                    
                    // Reload the page to show view/delete buttons
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                    
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

    // Handle view document buttons - check if file exists
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('view-document') || e.target.closest('.view-document')) {
            e.preventDefault();
            const viewBtn = e.target.classList.contains('view-document') ? e.target : e.target.closest('.view-document');
            const documentPath = viewBtn.dataset.documentPath;
            
            // Check if document exists before opening
            fetch("{{ route('claims.check-document', ['application_no' => $claim->application_no]) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ document_path: documentPath })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    window.open(viewBtn.href, '_blank');
                } else {
                    showAlert('Document not found. It may have been deleted or moved.', 'error');
                }
            })
            .catch(error => {
                showAlert('Error checking document: ' + error.message, 'error');
            });
        }
    });

    // Handle delete document buttons
    const deleteButtons = document.querySelectorAll('.delete-document');
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteDocumentModal'));
    const deleteForm = document.getElementById('deleteDocumentForm');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const documentType = this.dataset.documentType;
            const deleteUrl = "{{ route('claims.delete-document', ['application_no' => $claim->application_no, 'document_type' => 'DOC_TYPE']) }}".replace('DOC_TYPE', documentType);
            
            deleteForm.action = deleteUrl;
            deleteModal.show();
        });
    });

    // Handle delete form submission
    deleteForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = '<i class="bi bi-arrow-repeat spinner"></i> Deleting...';
        submitBtn.disabled = true;
        
        try {
            const response = await fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                deleteModal.hide();
                showAlert('Document deleted successfully!', 'success');
                
                // Reload the page to update the UI
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                throw new Error(result.message || 'Delete failed');
            }
        } catch (error) {
            showAlert(error.message || 'Error deleting document. Please try again.', 'error');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
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