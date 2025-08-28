<form action="{{ route('claims.upload-specific-document', $claim->application_no) }}" method="POST" enctype="multipart/form-data" class="mb-3 p-3 border rounded document-upload-form" data-document-type="{{ $field }}">
    @csrf
    <input type="hidden" name="document_type" value="{{ $field }}">
    
    <div class="mb-2 d-flex align-items-center justify-content-between">
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

   @if($claim->documents && !empty($claim->documents->$field))
<div class="mt-3">
    <div class="d-flex gap-2">
        <!-- VIEW -->
        <a href="{{ asset('storage/' . $claim->documents->$field) }}" target="_blank">View Document</a>


        <!-- DELETE -->
        <button type="button" 
                class="btn btn-sm btn-outline-danger delete-document" 
                data-document-type="{{ $field }}">
            <i class="bi bi-trash me-1"></i> Delete
        </button>
    </div>
</div>
@endif

</form>