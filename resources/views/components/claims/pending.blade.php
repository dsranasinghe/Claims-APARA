@extends('layouts.bankapp')

@section('title', 'Pending Applications')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="mb-0">
                    <i class="bi bi-list-check me-2"></i> Pending Applications
                </h3>
                <div class="d-flex">
                    <form method="GET" action="{{ route('claims.pending') }}" class="d-flex">
                        <input type="text" name="search" class="form-control form-control-sm me-2" 
                               placeholder="Search..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-sm btn-light">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    <button class="btn btn-sm btn-light ms-2" id="refreshBtn">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Application No</th>
                            <th>Customer</th>
                            <th>Amount Outstanding</th>
                            <th>Date Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingApplications as $claim)
                        <tr>
                            <td>{{ $claim->application_no }}</td>
                            <td>{{ $claim->customer_name }}</td>
                            <td>LKR {{ number_format($claim->amount_outstanding, 2) }}</td>
<td>
    @if(is_string($claim->created_at))
        {{ \Carbon\Carbon::parse($claim->created_at)->format('Y-m-d') }}
    @else
        {{ $claim->created_at->format('Y-m-d') }}
    @endif
</td>
                           
<td x-data="{ status: '{{ $claim->status }}' }">
    <template x-if="status === 'paid'">
        <span class="badge bg-success">Paid</span>
    </template>
    <template x-if="status !== 'paid'">
        <select 
            x-model="status"
            @change="
                fetch('{{ route('claims.update-status', $claim->id) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: status })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Optional: Show success message
                        const toast = new bootstrap.Toast(document.getElementById('statusToast'));
                        toast.show();
                    }
                })
            "
            class="form-select form-select-sm"
        >
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
        </select>
    </template>
</td>

                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('claims.show', $claim->application_no) }}" 
                                       class="btn btn-sm btn-outline-primary"
                                       data-bs-toggle="tooltip" title="View Details">
                                       <i class="bi bi-eye"></i>
                                    </a>
@if(Route::has('claims.documents'))
    <a href="{{ route('claims.documents', $claim->application_no) }}" 
       class="btn btn-sm btn-outline-info"
       data-bs-toggle="tooltip" title="Upload Documents">
       <i class="bi bi-arrow-right-circle"></i>
    </a>
@else
    <button class="btn btn-sm btn-outline-secondary" 
            data-bs-toggle="tooltip" title="Document upload not available"
            disabled>
        <i class="bi bi-arrow-right-circle"></i>
    </button>
@endif
                                    
                                   @if(auth()->check() && auth()->user()->can('approve-payments'))
    <form action="{{ route('claims.update-status', $claim->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="status" value="approved">
        <button type="submit" class="btn btn-sm btn-outline-success"
                data-bs-toggle="tooltip" title="Approve Claim">
            <i class="bi bi-check-circle"></i>
        </button>
    </form>
@endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No pending applications found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($pendingApplications->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted">
                    Showing {{ $pendingApplications->firstItem() }} to {{ $pendingApplications->lastItem() }} of {{ $pendingApplications->total() }} entries
                </div>
                {{ $pendingApplications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Toast for status updates -->
<div class="toast position-fixed top-0 end-0 p-3" id="statusToast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <strong class="me-auto">System Notification</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        Status updated successfully.
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-form select').forEach(select => {
        select.addEventListener('change', function(e) {
            e.preventDefault(); // Prevent default form submission
            
            const form = this.closest('form');
            const row = this.closest('tr');
            
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: JSON.stringify({ status: this.value })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Update the UI
                    const statusCell = this.closest('td');
                    statusCell.innerHTML = this.value === 'paid' 
                        ? '<span class="badge bg-success">Paid</span>'
                        : '<span class="badge bg-warning">Pending</span>';
                    
                    // Optional: Change row styling
                    row.classList.toggle('table-success', this.value === 'paid');
                    row.classList.toggle('table-warning', this.value === 'pending');
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endpush