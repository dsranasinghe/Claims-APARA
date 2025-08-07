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
                @foreach($claims as $claim)
                <tr>
                    <td>{{ $claim['id'] }}</td>
                    <td>{{ $claim['applicant'] }}</td>
                    <td>{{ $claim['id_number'] }}</td>
                    <td>
                        <span class="badge bg-{{ $claim['status_color'] }}-subtle text-{{ $claim['status_color'] }} fw-semibold">
                            {{ ucfirst($claim['status']) }}
                        </span>
                    </td>
                    <td>{{ $claim['submitted_at'] }}</td>
                    <td class="text-center">
                        <a href="#" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>