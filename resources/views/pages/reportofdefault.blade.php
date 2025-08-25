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
    'status' => 'Pending',
    'letter_of_demand' => 'letters/john_smith.pdf'
    ],
    [
    'id' => 2,
    'debtor' => 'Maria Garcia',
    'bank' => 'National Savings',
    'branch' => 'Kandy City',
    'submitted_date' => '2023-10-18',
    'initial_approver' => 'Taylor Swift',
    'final_approver' => '-',
    'status' => 'Initial Approved',
    'letter_of_demand' => null
    ],
    [
    'id' => 3,
    'debtor' => 'David Kim',
    'bank' => 'Global Finance',
    'branch' => 'Galle Fort',
    'submitted_date' => '2023-10-20',
    'initial_approver' => 'Jamie Lee',
    'final_approver' => 'Chris Evans',
    'status' => 'Final Approved',
    'letter_of_demand' => null
    ]
    ];
    @endphp
    
    <!-- Filter Section -->
    <x-marketing.reportofdefault-filter />
    
    <!-- Reports Table -->
    <x-marketing.reportofdefault-table :reports="$reports" />
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

    document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all download buttons
    const downloadButtons = document.querySelectorAll('.download-overdue-pdf');
    
    downloadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const recordId = this.getAttribute('data-record-id');
            downloadOverduePDF(recordId);
        });
    });
    
    // Function to handle PDF download
    function downloadOverduePDF(recordId) {
        // You would typically fetch record data from your server here
        // For now, we'll redirect to the printOverduepdf page with the record ID
        window.open(`/printOverduepdf.html?id=${recordId}`, '_blank');
        
        // Alternative approach if you want to pass data directly:
        // generateOverduePDF(recordId);
    }
    
    // If you want to generate the PDF directly without a separate page
    function generateOverduePDF(recordId) {
        // Fetch record data from your server
        fetch(`/api/get-record-data/${recordId}`)
            .then(response => response.json())
            .then(data => {
                // Create a hidden iframe to load the printOverduepdf page
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = '/printOverduepdf.html';
                document.body.appendChild(iframe);
                
                // Wait for the iframe to load, then trigger download
                iframe.onload = function() {
                    // Pass data to the iframe (this requires coordination with printOverduepdf page)
                    iframe.contentWindow.postMessage({
                        type: 'generateOverduePDF',
                        data: data
                    }, '*');
                };
            })
            .catch(error => {
                console.error('Error fetching record data:', error);
                alert('Failed to generate PDF. Please try again.');
            });
    }
});
</script>
@endpush
@endsection