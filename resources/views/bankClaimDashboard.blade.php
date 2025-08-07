@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        @php
            $cards = [
                ['icon' => 'file-earmark-text', 'label' => 'Total Claims', 'count' => 142, 'color' => 'primary'],
                ['icon' => 'hourglass-split', 'label' => 'Pending', 'count' => 38, 'color' => 'warning'],
                ['icon' => 'check-circle', 'label' => 'Approved', 'count' => 84, 'color' => 'success'],
                ['icon' => 'x-circle', 'label' => 'Rejected', 'count' => 20, 'color' => 'danger'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <i class="bi bi-{{ $card['icon'] }} fs-1 text-{{ $card['color'] }}"></i>
                <div class="mt-2">
                    <h6 class="mb-0 fw-semibold">{{ $card['label'] }}</h6>
                    <h3 class="fw-bold mt-1">{{ $card['count'] }}</h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Filter Form -->
    <form method="GET" action="" class="row g-3 align-items-end mb-4">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control" placeholder="Search by Name or ID">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-dark w-100"><i class="bi bi-funnel-fill me-1"></i> Filter</button>
        </div>
    </form>

    <!-- Claims Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Claims List</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Applicant</th>
                        <th>ID Number</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CL001</td>
                        <td>Sachini Perera</td>
                        <td>199934567890</td>
                        <td><span class="badge bg-warning-subtle text-warning fw-semibold">Pending</span></td>
                        <td>2025-07-01</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>CL002</td>
                        <td>Chamath Silva</td>
                        <td>199845678910</td>
                        <td><span class="badge bg-success-subtle text-success fw-semibold">Approved</span></td>
                        <td>2025-07-10</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>CL003</td>
                        <td>Dinithi Fernando</td>
                        <td>200045678321</td>
                        <td><span class="badge bg-danger-subtle text-danger fw-semibold">Rejected</span></td>
                        <td>2025-07-20</td>
                        <td class="text-center">
                            <a href="#" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4 text-center">
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link">Previous</a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
