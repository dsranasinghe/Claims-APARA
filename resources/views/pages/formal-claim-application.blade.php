<!-- resources/views/marketing/formal-claims.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold display-6 mb-2">Formal Claim Applications</h1>
            <p class="text-muted mb-0">APARA GUARANTEE SCHEME</p>
        </div>
    </div>

    <!-- Formal Claims Table -->
    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">#</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Claimant Bank</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Branch</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Guarantee No</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Date of Issue</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Individual Permitted Limit</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Applicant</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Action</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <!-- Example Application 1 -->
                        <tr class="border-bottom">
                            <td class="py-4 px-4 fw-semibold">1</td>
                            <td class="py-4 px-4">ABC Finance Ltd</td>
                            <td class="py-4 px-4">Colombo Branch</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">GRT-45678</span>
                            </td>
                            <td class="py-4 px-4">05 Jan 2023</td>
                            <td class="py-4 px-4 fw-bold text-success">Rs. 1,500,000</td>
                            <td class="py-4 px-4 fw-semibold">John Perera</td>
                            <td class="py-4 px-4">
                                <a href="{{ url('/formal-claims/1') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i> View More
                                </a>
                            </td>
                        </tr>

                        <!-- Example Application 2 -->
                        <tr class="border-bottom">
                            <td class="py-4 px-4 fw-semibold">2</td>
                            <td class="py-4 px-4">XYZ Leasing PLC</td>
                            <td class="py-4 px-4">Kandy Branch</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">GRT-98211</span>
                            </td>
                            <td class="py-4 px-4">22 Jun 2022</td>
                            <td class="py-4 px-4 fw-bold text-success">Rs. 900,000</td>
                            <td class="py-4 px-4 fw-semibold">Kamal Silva</td>
                            <td class="py-4 px-4">
                                <a href="{{ url('/formal-claims/2') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i> View More
                                </a>
                            </td>
                        </tr>

                        <!-- Example Application 3 -->
                        <tr class="border-bottom">
                            <td class="py-4 px-4 fw-semibold">3</td>
                            <td class="py-4 px-4">National Finance Co.</td>
                            <td class="py-4 px-4">Galle Branch</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">GRT-77654</span>
                            </td>
                            <td class="py-4 px-4">15 Mar 2021</td>
                            <td class="py-4 px-4 fw-bold text-success">Rs. 2,000,000</td>
                            <td class="py-4 px-4 fw-semibold">Sarath Fernando</td>
                            <td class="py-4 px-4">
                                <a href="{{ url('/formal-claims/3') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="fas fa-eye me-1"></i> View More
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="text-muted">
            Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">3</span> of <span class="fw-semibold">3</span> entries
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-sm">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
@endsection
