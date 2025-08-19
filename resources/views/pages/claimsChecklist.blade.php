<!-- resources/views/marketing/claims.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold display-6 mb-2">Claim Submission Checklist</h1>
            <p class="text-muted mb-0">APARA GUARANTEE SCHEME</p>
        </div>
          </div>

    <!-- Claims Table -->
    <div class="card shadow-sm border-0 rounded-lg overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">#</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Claim ID</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Finance Company</th> 
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Loan Account</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Borrower</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Guarantee Date</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Amount</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Claim Reason</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Submission Date</th>
                            <th class="py-3 px-4 text-uppercase small fw-semibold text-muted">Files</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        <!-- Example Claim 1 -->
                        <tr class="border-bottom">
                            <td class="py-4 px-4 fw-semibold">1</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">CLM-1001</span>
                            </td>
                            <td class="py-4 px-4">ABC Finance Ltd</td>
                            <td class="py-4 px-4">LN-45892</td>
                            <td class="py-4 px-4 fw-semibold">John Perera</td>
                            <td class="py-4 px-4">05 Jan 2023</td>
                            <td class="py-4 px-4 fw-bold text-success">Rs. 1,200,000</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-info bg-opacity-10 text-info">Section A - Mandatory</span>
                            </td>
                            <td class="py-4 px-4">18 Aug 2025</td>
                            <td class="py-4 px-4">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/files/formal-claim.pdf" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-file-pdf me-1"></i>Claim
                                    </a>
                                    <a href="/files/loan-schedule.pdf" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="fas fa-file-alt me-1"></i>Schedule
                                    </a>
                                    <a href="/files/disbursement-slip.pdf" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="fas fa-file-invoice me-1"></i>Slip
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Example Claim 2 -->
                        <tr class="border-bottom">
                            <td class="py-4 px-4 fw-semibold">2</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">CLM-1002</span>
                            </td>
                            <td class="py-4 px-4">XYZ Leasing PLC</td>
                            <td class="py-4 px-4">LN-78234</td>
                            <td class="py-4 px-4 fw-semibold">Kamal Silva</td>
                            <td class="py-4 px-4">22 Jun 2022</td>
                            <td class="py-4 px-4 fw-bold text-success">Rs. 800,000</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-danger bg-opacity-10 text-danger">Section C - Deceased</span>
                            </td>
                            <td class="py-4 px-4">17 Aug 2025</td>
                            <td class="py-4 px-4">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/files/death-certificate.pdf" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        <i class="fas fa-file-medical me-1"></i>Certificate
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Example Claim 3 -->
                        <tr class="border-bottom">
                            <td class="py-4 px-4 fw-semibold">3</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">CLM-1003</span>
                            </td>
                            <td class="py-4 px-4">National Finance Co.</td>
                            <td class="py-4 px-4">LN-33567</td>
                            <td class="py-4 px-4 fw-semibold">Sarath Fernando</td>
                            <td class="py-4 px-4">15 Mar 2021</td>
                            <td class="py-4 px-4 fw-bold text-success">Rs. 2,000,000</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">Section B1 - Missing</span>
                            </td>
                            <td class="py-4 px-4">15 Aug 2025</td>
                            <td class="py-4 px-4">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/files/police-complaint.pdf" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                        <i class="fas fa-file-contract me-1"></i>Complaint
                                    </a>
                                    <a href="/files/affidavit.pdf" target="_blank" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                        <i class="fas fa-file-signature me-1"></i>Affidavit
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- Example Claim 4 -->
                        <tr class="border-bottom">
                            <td class="py-4 px-4 fw-semibold">4</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">CLM-1004</span>
                            </td>
                            <td class="py-4 px-4">Global Leasing Ltd</td>
                            <td class="py-4 px-4">LN-99881</td>
                            <td class="py-4 px-4 fw-semibold">Nimal Jayasinghe</td>
                            <td class="py-4 px-4">10 Dec 2020</td>
                            <td class="py-4 px-4 fw-bold text-success">Rs. 950,000</td>
                            <td class="py-4 px-4">
                                <span class="badge bg-primary bg-opacity-10 text-primary">Section D - Unfit</span>
                            </td>
                            <td class="py-4 px-4">12 Aug 2025</td>
                            <td class="py-4 px-4">
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="/files/medical-certificate.pdf" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                        <i class="fas fa-file-medical-alt me-1"></i>Medical
                                    </a>
                                    <a href="/files/government-report.pdf" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                        <i class="fas fa-file-chart-line me-1"></i>Report
                                    </a>
                                </div>
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
            Showing <span class="fw-semibold">1</span> to <span class="fw-semibold">4</span> of <span class="fw-semibold">4</span> entries
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