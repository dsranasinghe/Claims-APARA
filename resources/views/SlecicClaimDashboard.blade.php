@extends('layouts.app')

@section('content')
<div class="container py-4">
    @php
    $department = strtolower(session('department', 'claims'));

    $cards = [
    'claims' => [
    ['icon' => 'file-earmark-text', 'label' => 'Total Claims', 'count' => 142, 'color' => 'primary'],
    ['icon' => 'hourglass-split', 'label' => 'Pending', 'count' => 38, 'color' => 'warning'],
    ['icon' => 'check-circle', 'label' => 'Approved', 'count' => 84, 'color' => 'success'],
    ['icon' => 'x-circle', 'label' => 'Rejected', 'count' => 20, 'color' => 'danger'],
    ],
    'operations' => [
    ['icon' => 'gear', 'label' => 'Tasks Completed', 'count' => 256, 'color' => 'success'],
    ['icon' => 'clock', 'label' => 'Tasks Pending', 'count' => 17, 'color' => 'warning'],
    ['icon' => 'tools', 'label' => 'In Progress', 'count' => 8, 'color' => 'primary'],
    ],
    'marketing' => [
    ['icon' => 'megaphone', 'label' => 'Total Applications', 'count' => 56, 'color' => 'primary'],
    ['icon' => 'graph-up', 'label' => 'Approved', 'count' => 32, 'color' => 'success'],
    ['icon' => 'hourglass', 'label' => 'Pending', 'count' => 18, 'color' => 'warning'],
    ['icon' => 'x-circle', 'label' => 'Rejected', 'count' => 6, 'color' => 'danger'],
    ]
    ];

    // Example data for claims and operations dashboards
    $claimsData = [
    ['claim_no' => 'CLM2025001', 'debtor' => 'Rajapakse Traders', 'status' => 'approved', 'status_color' => 'success', 'submitted_at' => '2025-07-10'],
    ['claim_no' => 'CLM2025002', 'debtor' => 'Perera Enterprises', 'status' => 'pending', 'status_color' => 'warning', 'submitted_at' => '2025-07-12'],
    ];

    $operationsData = [
    ['task' => 'Inventory Update', 'assigned_to' => 'John Doe', 'status' => 'completed', 'status_color' => 'success', 'due_date' => '2025-07-18'],
    ['task' => 'Report Review', 'assigned_to' => 'Jane Smith', 'status' => 'pending', 'status_color' => 'warning', 'due_date' => '2025-07-20'],
    ];

    $marketingData = [
    ['application_no' => 'APP2025001', 'bank_name' => 'BOC', 'branch_name' => 'Colombo Main', 'debtor_name' => 'Rajapakse Traders', 'status' => 'approved', 'status_color' => 'success', 'submitted_at' => '2025-07-15', 'has_report' => true, 'has_claims' => true, 'priority' => 'high'],
    ['application_no' => 'APP2025002', 'bank_name' => 'DFCC', 'branch_name' => 'Kandy City', 'debtor_name' => 'Perera Enterprises', 'status' => 'pending', 'status_color' => 'warning', 'submitted_at' => '2025-07-20', 'has_report' => false, 'has_claims' => true, 'priority' => 'medium'],
    ];
    @endphp

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-capitalize">
                <span class="text-primary">{{ ucfirst($department) }}</span> Dashboard
            </h2>
            <p class="text-muted mb-0">Overview of recent activities</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <x-cards.summary-cards-container :cards="$cards[$department]" />

    <!-- Department Tables -->
    @if($department === 'marketing')
    <x-slecic-dashboard.marketing-table :marketing-data="$marketingData" />
    @elseif($department === 'claims')
    <x-slecic-dashboard.claims-table :claims-data="$claimsData" />
    @elseif($department === 'operations')
    <x-slecic-dashboard.operations-table :operations-data="$operationsData" />
    @endif

</div>

@push('styles')
<style>
    .card {
        border-radius: 12px;
        transition: transform 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .table th {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        text-align: center;
    }

    .table th:first-child {
        text-align: left;
    }

    .table td {
        vertical-align: middle;
        padding: 1rem;
    }

    .table tr {
        border-bottom: 1px solid #f0f0f0;
    }

    .table tr:last-child {
        border-bottom: 0;
    }

    .rounded-circle {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .input-group-text {
        background-color: transparent;
    }
</style>
@endpush
@endsection