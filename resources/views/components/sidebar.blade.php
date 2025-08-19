<!-- sidebar.blade.php -->
<div class="sidebar d-flex flex-column flex-shrink-0 p-3">
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <i class="bi bi-speedometer2 fs-4 me-2 text-primary"></i>
        <span class="fs-5 fw-semibold">Dashboard</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link" >
                <i class="bi bi-house-door me-2"></i>
                Home
            </a>
        </li>
        <li>
            <a href="{{ route('report-of-default') }}" 
               class="nav-link {{ request()->routeIs('report-of-default') ? 'active' : '' }}">
                <i class="bi bi-file-earmark me-2"></i>
                Report of Default
            </a>
        </li>
        <li><a href="{{ route('Claims-checklist') }}" 
               class="nav-link {{ request()->routeIs('Claims-checklist') ? 'active' : '' }}">
                <i class="bi bi-list me-2"></i>
                Claims Checklist
            </a>
        </li>
        <li>
            <a href="#" class="nav-link">
                <i class="bi bi-hourglass me-2"></i>
                Pending Claims
            </a>
        </li>
        <li>
            <a href="#" class="nav-link">
                <i class="bi bi-check-circle me-2"></i>
                Approved Claims
            </a>
        </li>
        <li>
            <a href="#" class="nav-link">
                <i class="bi bi-x-circle me-2"></i>
                Rejected Claims
            </a>
        </li>
    </ul>
</div>