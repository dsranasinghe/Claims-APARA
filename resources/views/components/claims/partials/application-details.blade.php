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