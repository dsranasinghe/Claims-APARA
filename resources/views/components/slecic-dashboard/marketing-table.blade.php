<!-- resources/views/components/slecic-dashboard/marketing-table.blade.php -->
 <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Recent </h5>
            <div class="d-flex gap-2">
                <div class="input-group input-group-sm" style="width: 200px;">
                    <span class="input-group-text bg-transparent border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Search...">
                </div>
                <select class="form-select form-select-sm" style="width: 150px;">
                    <option>All Status</option>
                    <option>Approved</option>
                    <option>Pending</option>
                    <option>Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Application</th>
                        <th>Bank</th>
                        <th>Debtor</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th class="text-center">Report of Default</th>
                        <th class="text-center">Claims Docs</th>
                        <th class="text-center">Initial Approval</th>
                        <th class="text-center">Final Approval</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($marketingData as $row)
                        <tr>
                            <!-- Application No + Branch -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    @if($row['priority'] === 'high')
                                        <span class="badge bg-danger badge-dot me-2"></span>
                                    @elseif($row['priority'] === 'medium')
                                        <span class="badge bg-warning badge-dot me-2"></span>
                                    @else
                                        <span class="badge bg-success badge-dot me-2"></span>
                                    @endif
                                    <div>
                                        <div class="fw-semibold">{{ $row['application_no'] }}</div>
                                        <small class="text-muted">{{ $row['branch_name'] }}</small>
                                    </div>
                                </div>
                            </td>

                            <!-- Bank -->
                            <td><div class="fw-medium">{{ $row['bank_name'] }}</div></td>

                            <!-- Debtor -->
                            <td><div class="fw-medium">{{ $row['debtor_name'] }}</div></td>

                            <!-- Status -->
                            <td>
                                <span class="badge bg-{{ $row['status_color'] }}-subtle text-{{ $row['status_color'] }} rounded-pill px-3 py-1">
                                    <i class="bi bi-circle-fill small me-1"></i>
                                    {{ ucfirst($row['status']) }}
                                </span>
                            </td>

                            <!-- Submitted Date -->
                            <td><div class="text-muted">{{ date('M d, Y', strtotime($row['submitted_at'])) }}</div></td>

                            <!-- Report of Default -->
                            <td class="text-center py-3">
                                @if($row['has_report'])
                                    <button class="btn btn-sm btn-outline-info rounded-3 px-3 d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-file-text"></i>
                                        <span>View</span>
                                    </button>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <!-- Claims Docs -->
                            <td class="text-center py-3">
                                @if($row['has_claims'])
                                    <button class="btn btn-sm btn-outline-secondary rounded-3 px-3 d-inline-flex align-items-center gap-1">
                                        <i class="bi bi-file-earmark-text"></i>
                                        <span>View</span>
                                    </button>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <!-- Initial Approval -->
                            <td class="text-center">
                                @if($row['status'] === 'pending')
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button class="btn btn-sm btn-outline-success rounded-circle">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger rounded-circle">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>

                            <!-- Final Approval -->
                            <td class="text-center">
                                @if($row['status'] === 'pending')
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button class="btn btn-sm btn-success rounded-circle">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger rounded-circle">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer bg-white border-0">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">{{ count($marketingData) }}</span>
                of <span class="fw-semibold">{{ count($marketingData) }}</span> entries
            </div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
