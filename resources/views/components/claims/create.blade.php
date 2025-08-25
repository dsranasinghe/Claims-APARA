@extends('layouts.bankapp')

@section('title', 'Overdue Claim Form')

@section('content')
<div class="container-fluid px-4 py-5 bg-light min-vh-100">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <div class="glass-card shadow-soft rounded-4 p-4 p-md-5 animate-fade-in">
                <div class="text-center mb-5">
                    <h2 class="fw-bold text-gradient">Overdue Claim Form</h2>
                    <p class="text-muted">Please search for the customer and proceed with the overdue claim submission.</p>
                </div>

                {{-- Alert Section --}}
                @if(!$application && $searchPerformed)
                    <x-overdue.alert 
                        type="warning" 
                        icon="exclamation-triangle-fill" 
                        heading="No Application Found"
                        class="mb-4"
                    >
                        We couldn't find any applications matching your search criteria.
                    </x-overdue.alert>
                @endif

                {{-- Search Form OR Claim Form --}}
                <div class="animate-slide-up">
                    @if(!$application)
                        <x-overdue.customer-search-form :old="old()" />
                    @else
                        <x-overdue.claim-form :application="$application" :claim="$claim ?? null" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Link to external CSS --}}
<link href="{{ asset('css/overdue-claims.css') }}" rel="stylesheet">

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle demand letter sections with animation
    const demandRadios = document.querySelectorAll('input[name="demand_made"]');
    const letterSection = document.getElementById('demand-letter-section');
    const reasonSection = document.getElementById('no-demand-reason-section');

    if (demandRadios.length > 0) {
        function toggleSections() {
            const isDemandMade = document.querySelector('input[name="demand_made"]:checked').value === '1';
            
            if (isDemandMade) {
                reasonSection.classList.add('d-none');
                letterSection.classList.remove('d-none', 'animate-slide-up');
                letterSection.classList.add('animate-slide-up');
            } else {
                letterSection.classList.add('d-none');
                reasonSection.classList.remove('d-none', 'animate-slide-up');
                reasonSection.classList.add('animate-slide-up');
            }
        }

        demandRadios.forEach(radio => radio.addEventListener('change', toggleSections));
        toggleSections();
    }

    // Form validation with shake animation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                const invalidFields = form.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.classList.add('animate__animated', 'animate__headShake');
                    field.addEventListener('animationend', () => {
                        field.classList.remove('animate__animated', 'animate__headShake');
                    });
                });
            }
            form.classList.add('was-validated');
        }, false);
    });

    // File upload area highlight (only for demand letter)
    const fileUpload = document.querySelector('.file-upload-card');
    if (fileUpload) {
        fileUpload.addEventListener('dragover', (e) => {
            e.preventDefault();
            fileUpload.classList.add('border-primary', 'bg-soft-primary');
        });
        
        fileUpload.addEventListener('dragleave', () => {
            fileUpload.classList.remove('border-primary', 'bg-soft-primary');
        });
    }
});
</script>
@endsection
