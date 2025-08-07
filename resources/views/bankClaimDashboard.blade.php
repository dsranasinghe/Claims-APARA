@extends('layouts.app')

@section('content')
<div class="container py-4">
    @php
        $cards = [
            ['icon' => 'file-earmark-text', 'label' => 'Total Claims', 'count' => 142, 'color' => 'primary'],
            ['icon' => 'hourglass-split', 'label' => 'Pending', 'count' => 38, 'color' => 'warning'],
            ['icon' => 'check-circle', 'label' => 'Approved', 'count' => 84, 'color' => 'success'],
            ['icon' => 'x-circle', 'label' => 'Rejected', 'count' => 20, 'color' => 'danger'],
        ];
        
        $claims = [
            [
                'id' => 'CL001',
                'applicant' => 'Sachini Perera',
                'id_number' => '199934567890',
                'status' => 'pending',
                'status_color' => 'warning',
                'submitted_at' => '2025-07-01'
            ],
            [
                'id' => 'CL002',
                'applicant' => 'Chamath Silva',
                'id_number' => '199845678910',
                'status' => 'approved',
                'status_color' => 'success',
                'submitted_at' => '2025-07-10'
            ],
            [
                'id' => 'CL003',
                'applicant' => 'Dinithi Fernando',
                'id_number' => '200045678321',
                'status' => 'rejected',
                'status_color' => 'danger',
                'submitted_at' => '2025-07-20'
            ]
        ];
    @endphp

    <!-- Summary Cards -->
    <x-cards.summary-cards-container :cards="$cards" />

    <!-- Filter Form -->
    <x-claims.filter-form />

    <!-- Claims Table -->
    <x-claims.table :claims="$claims" />

    <!-- Pagination -->
    <x-claims.pagination />
</div>
@endsection