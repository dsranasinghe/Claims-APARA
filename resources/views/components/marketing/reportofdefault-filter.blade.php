<div class="card border-0 bg-white bg-opacity-75 rounded-4 mb-5 shadow-sm" style="backdrop-filter: blur(10px);">
    <div class="card-body p-4">
        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-funnel-fill text-primary me-2 fs-5"></i>
            <h5 class="fw-semibold mb-0">Filter Options</h5>
        </div>
        <form method="GET" action="{{ $action ?? url()->current() }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="form-control rounded-3 shadow-none border">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold text-muted">Status</label>
                    <select name="status" class="form-select rounded-3 shadow-none border">
                        <option value="">All Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Initial Approved" {{ request('status') == 'Initial Approved' ? 'selected' : '' }}>Initial Approved</option>
                        <option value="Final Approved" {{ request('status') == 'Final Approved' ? 'selected' : '' }}>Final Approved</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold text-muted">Search Debtor</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 rounded-start-3">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="debtor" value="{{ request('debtor') }}" class="form-control rounded-end-3 shadow-none border-start-0" placeholder="Debtor name...">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary rounded-3 w-100 shadow-sm py-2">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
