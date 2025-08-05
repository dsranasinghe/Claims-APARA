@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <!-- Summary Cards -->
        <div class="col-md-3">
            <div class="card shadow rounded-xl p-3 text-center">
                <i class="bi bi-file-earmark-text fs-2 text-primary"></i>
                <h5 class="mt-2">Total Claims</h5>
                <h3>142</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow rounded-xl p-3 text-center">
                <i class="bi bi-hourglass-split fs-2 text-warning"></i>
                <h5 class="mt-2">Pending</h5>
                <h3>38</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow rounded-xl p-3 text-center">
                <i class="bi bi-check-circle fs-2 text-success"></i>
                <h5 class="mt-2">Approved</h5>
                <h3>84</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow rounded-xl p-3 text-center">
                <i class="bi bi-x-circle fs-2 text-danger"></i>
                <h5 class="mt-2">Rejected</h5>
                <h3>20</h3>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="" class="row g-3 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by Name or ID">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary"><i class="bi bi-funnel"></i> Filter</button>
        </div>
    </form>

    <!-- Claims Table -->
    <div class="card shadow rounded-xl">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Claims List</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Applicant Name</th>
                        <th>ID Number</th>
                        <th>Status</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>CL001</td>
                        <td>Sachini Perera</td>
                        <td>199934567890</td>
                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                        <td>2025-07-01</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>CL002</td>
                        <td>Chamath Silva</td>
                        <td>199845678910</td>
                        <td><span class="badge bg-success">Approved</span></td>
                        <td>2025-07-10</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>CL003</td>
                        <td>Dinithi Fernando</td>
                        <td>200045678321</td>
                        <td><span class="badge bg-danger">Rejected</span></td>
                        <td>2025-07-20</td>
                        <td>
                            <a href="#" class="btn btn-outline-primary btn-sm">
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
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
  