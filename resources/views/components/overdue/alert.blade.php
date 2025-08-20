@props(['type' => 'info', 'icon' => 'info-circle', 'heading' => '', 'dismissible' => false])

<div class="alert alert-{{ $type }} d-flex align-items-center {{ $attributes->get('class') }}">
    <i class="bi bi-{{ $icon }} me-3 fs-4"></i>
    <div class="flex-grow-1">
        @if($heading)
            <h5 class="alert-heading mb-1">{{ $heading }}</h5>
        @endif
        {{ $slot }}
    </div>
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>