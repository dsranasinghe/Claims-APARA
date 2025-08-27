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