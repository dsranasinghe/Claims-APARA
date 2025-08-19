<!-- resources/views/components/slecic-dashboard/claims-table.blade.php -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold">Recent Claims</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Claim No</th>
                        <th>Debtor</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($claimsData as $claim)
                    <tr>
                        <td>{{ $claim['claim_no'] }}</td>
                        <td>{{ $claim['debtor'] }}</td>
                        <td>
                            <span class="badge bg-{{ $claim['status_color'] }}-subtle text-{{ $claim['status_color'] }} rounded-pill px-3 py-1">
                                {{ ucfirst($claim['status']) }}
                            </span>
                        </td>
                        <td>{{ date('M d, Y', strtotime($claim['submitted_at'])) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
