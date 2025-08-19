@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header with improved spacing -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold display-6 mb-2">
                <span class="text-gradient-primary">Report of Default </span>
            </h1>
            <p class="text-muted mb-0">Monitor and manage submitted default reports with comprehensive details</p>
        </div>
    </div>
    <!-- Hardcoded Data Array -->
    @php
    $reports = [
    [
    'id' => 1,
    'debtor' => 'John Smith',
    'bank' => 'Commercial Bank',
    'branch' => 'Colombo Main',
    'submitted_date' => '2023-10-15',
    'initial_approver' => 'Alex Johnson',
    'final_approver' => 'Sam Wilson',
    'status' => 'Pending'
    ],
    [
    'id' => 2,
    'debtor' => 'Maria Garcia',
    'bank' => 'National Savings',
    'branch' => 'Kandy City',
    'submitted_date' => '2023-10-18',
    'initial_approver' => 'Taylor Swift',
    'final_approver' => '-',
    'status' => 'Initial Approved'
    ],
    [
    'id' => 3,
    'debtor' => 'David Kim',
    'bank' => 'Global Finance',
    'branch' => 'Galle Fort',
    'submitted_date' => '2023-10-20',
    'initial_approver' => 'Jamie Lee',
    'final_approver' => 'Chris Evans',
    'status' => 'Final Approved'
    ]
    ];
    @endphp
    <!-- Filter Section with glass morphism effect -->
    <div class="card border-0 bg-white bg-opacity-75 rounded-4 mb-5 shadow-sm" style="backdrop-filter: blur(10px);">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-funnel-fill text-primary me-2 fs-5"></i>
                <h5 class="fw-semibold mb-0">Filter Options</h5>
            </div>
            <form>
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Date </label>
                        <input type="date" class="form-control rounded-3 shadow-none border">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-semibold text-muted">Status</label>
                        <select class="form-select rounded-3 shadow-none border">
                            <option selected>All Status</option>
                            <option>Pending</option>
                            <option>Initial Approved</option>
                            <option>Final Approved</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-muted">Search Debtor</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0 rounded-start-3">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control rounded-end-3 shadow-none border-start-0" placeholder="Debtor name...">
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

    <!-- Reports Table with enhanced design -->
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
                                <i class="bi bi-person me-1"></i> Debtor
                            </th>
                            <th class="text-uppercase fw-semibold text-muted small py-3">
                                <i class="bi bi-bank me-1"></i> Bank
                            </th>
                            <th class="text-uppercase fw-semibold text-muted small py-3">
                                <i class="bi bi-geo-alt me-1"></i> Branch
                            </th>
                            <th class="text-uppercase fw-semibold text-muted small py-3">
                                <i class="bi bi-calendar me-1"></i> Submitted
                            </th>
                            <th class="text-uppercase fw-semibold text-muted small py-3">
                                <i class="bi bi-person-check me-1"></i> Initial Approver
                            </th>
                            <th class="text-uppercase fw-semibold text-muted small py-3">
                                <i class="bi bi-shield-check me-1"></i> Final Approver
                            </th>
                            <th class="text-uppercase fw-semibold text-muted small py-3">
                                <i class="bi bi-info-circle me-1"></i> Status
                            </th>
                            <th class="text-uppercase fw-semibold text-muted small py-3 pe-4 text-end">
                                <i class="bi bi-gear me-1"></i> Actions
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
                                    <button class="btn btn-sm btn-light rounded-circle p-2 me-1 shadow-sm" title="View" data-bs-toggle="tooltip">
                                        <i class="bi bi-eye-fill text-primary"></i>
                                    </button>

                                    <button class="btn btn-sm btn-light rounded-circle p-2 shadow-sm" title="More" data-bs-toggle="tooltip">
                                        <i class="bi bi-three-dots-vertical"></i>
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
</div>

@push('styles')
<style>
    .text-gradient-primary {
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        display: inline;
    }

    .avatar {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-xs {
        width: 24px;
        height: 24px;
        font-size: 12px;
    }

    .avatar-sm {
        width: 36px;
        height: 36px;
        font-size: 16px;
    }

    .table th {
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
    }

    .table td {
        padding-top: 1rem;
        padding-bottom: 1rem;
        vertical-align: middle;
    }

    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.4px;
    }

    .form-control,
    .form-select {
        border-color: rgba(0, 0, 0, 0.1);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.1);
    }

    .btn-light {
        background-color: rgba(0, 0, 0, 0.02);
        border-color: rgba(0, 0, 0, 0.05);
    }

    .btn-outline-success,
    .btn-outline-danger {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    // Enable tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    })
</script>
@endpush
@endsection