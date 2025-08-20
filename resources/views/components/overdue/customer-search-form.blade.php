@props(['old'])

<div class="form-card">
    <h3 class="section-title"><i class="bi bi-search-heart"></i> Customer Search</h3>
    
    <form method="GET" action="{{ route('claims.create') }}" class="needs-validation" novalidate>
        <div class="row">
            <div class="col-md-6 mb-4">
                <label for="id_no" class="form-label">ID Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                    <input type="text" class="form-control" id="id_no" name="id_no" 
                           value="{{ $old['id_no'] ?? '' }}" placeholder="Enter ID number">
                </div>
                <div class="form-text">Enter the customer's national ID number</div>
            </div>
            
            <div class="col-md-6 mb-4">
                <label for="passport_no" class="form-label">Passport Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-passport"></i></span>
                    <input type="text" class="form-control" id="passport_no" name="passport_no" 
                           value="{{ $old['passport_no'] ?? '' }}" placeholder="Enter passport number">
                </div>
                <div class="form-text">Enter passport number if applicable</div>
            </div>
        </div>
        
        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search me-2"></i> Search Applications
            </button>
        </div>
    </form>
</div>