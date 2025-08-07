<form method="GET" action="" class="row g-3 align-items-end mb-4">
    <div class="col-md-4">
        <label class="form-label">Search</label>
        <input type="text" name="search" class="form-control" placeholder="Search by Name or ID">
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-dark w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
    </div>
</form>