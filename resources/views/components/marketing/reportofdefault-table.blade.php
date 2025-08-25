@props(['reports' => []])

<div class="card border-0 rounded-4 overflow-hidden shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-primary bg-opacity-10">
                    <tr>
                        <th class="text-uppercase fw-semibold text-muted small py-3 ps-4">
                            <i class="bi bi-hash me-1"></i>
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            Debtor
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            Bank
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            Branch
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            </i> Submitted
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            </i> Letter of Demand
                        </th>

                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            <i class="bi bi-person-check me-1"></i> Initial Approver
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            <i class="bi bi-shield-check me-1"></i> Final Approver
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3">
                            Status
                        </th>
                        <th class="text-uppercase fw-semibold text-muted small py-3 pe-4 text-end">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    @foreach($reports as $report)
                    <tr class="border-bottom border-light">
                        <td class="ps-4 fw-semibold text-muted">{{ $report['id'] }}</td>
                        <td class="fw-semibold">
                            <div class="d-flex align-items-center">
                                <span>{{ $report['debtor'] }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span>{{ $report['bank'] }}</span>
                            </div>
                        </td>
                        <td>{{ $report['branch'] }}</td>
                        <td>
                            <div class="text-muted">
                                {{ date('d M, Y', strtotime($report['submitted_date'])) }}
                            </div>
                        </td>
                        <td>
                            @if($report['letter_of_demand'])
                            <a href="{{ asset($report['letter_of_demand']) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i> View
                            </a>
                            @else
                            <span class="text-muted">Not Attached</span>
                            @endif
                        </td>

                        <td>
                            {{-- Initial Approver column --}}
                            @if($report['status'] === 'Pending')
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-success rounded-circle" title="Approve Initial">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-circle" title="Reject Initial">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            @else
                            <div class="d-flex align-items-center">
                                <span>{{ $report['initial_approver'] }}</span>
                            </div>
                            @endif
                        </td>

                        <td>
                            {{-- Final Approver column --}}
                            @if($report['status'] === 'Initial Approved')
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-success rounded-circle" title="Approve Final">
                                    <i class="bi bi-check"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-circle" title="Reject Final">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                            @elseif($report['status'] === 'Final Approved')
                            <div class="d-flex align-items-center">
                                <span>{{ $report['final_approver'] }}</span>
                            </div>
                            @else
                            {{-- Restrict premature final approval --}}
                            <span class="text-muted">Not available</span>
                            @endif
                        </td>

                        <td>
                            @php
                            $statusColor = [
                            'Pending' => 'warning',
                            'Initial Approved' => 'info',
                            'Final Approved' => 'success'
                            ][$report['status']] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-2 rounded-pill d-inline-flex align-items-center">
                                <i class="bi bi-circle-fill me-2" style="font-size: 6px;"></i>
                                {{ $report['status'] }}
                            </span>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="btn-group">
                                <!-- View button -->
                                <button class="btn btn-sm btn-light rounded-circle p-2 me-1 shadow-sm" title="View" data-bs-toggle="tooltip">
                                    <i class="bi bi-eye-fill text-primary"></i>
                                </button>

                                <!-- Download PDF button -->
                                <button class="btn btn-sm btn-light rounded-circle p-2 me-1 shadow-sm download-overdue-pdf"
                                    title="Download Overdue PDF" data-bs-toggle="tooltip" data-record-id="{{ $report['id'] }}">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Enhanced Pagination -->
        <div class="d-flex justify-content-between align-items-center p-4 border-top">
            <div class="text-muted small">
                Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">{{ count($reports) }}</span> of <span class="fw-semibold">{{ count($reports) }}</span> entries
            </div>
            <div class="btn-group shadow-sm">
                <button class="btn btn-sm btn-outline-primary rounded-start-pill px-3" disabled>
                    <i class="bi bi-chevron-left me-1"></i> Previous
                </button>
                <button class="btn btn-sm btn-outline-primary active">1</button>
                <button class="btn btn-sm btn-outline-primary rounded-end-pill px-3" disabled>
                    Next <i class="bi bi-chevron-right ms-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>