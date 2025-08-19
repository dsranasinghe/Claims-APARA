<!-- summary-card.blade.php -->

<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-3">
        <div class="d-flex align-items-center">
            <div class="bg-{{ $color }}-subtle p-3 rounded-circle me-3">
                <i class="bi bi-{{ $icon }} text-{{ $color }} fs-4"></i>
            </div>
            <div>
                <h6 class="text-muted mb-1">{{ $label }}</h6>
                <h3 class="mb-0 fw-bold">{{ $count }}</h3>
            </div>
        </div>
    </div>
</div>
