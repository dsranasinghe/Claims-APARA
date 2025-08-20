@props(['icon', 'title'])

<section class="mb-5">
    <div class="d-flex align-items-center mb-4">
        <div class="bg-soft-primary rounded-circle p-2 me-3">
            <i class="bi bi-{{ $icon }} fs-4 text-primary"></i>
        </div>
        <h5 class="mb-0 fw-semibold">{{ $title }}</h5>
    </div>
    
    {{ $slot }}
</section>