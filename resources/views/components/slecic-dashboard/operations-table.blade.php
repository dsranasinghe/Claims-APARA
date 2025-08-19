<!-- resources/views/components/slecic-dashboard/operations-table.blade.php -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 py-3">
        <h5 class="mb-0 fw-bold">Operations Tasks</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Task</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Due Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($operationsData as $task)
                    <tr>
                        <td>{{ $task['task'] }}</td>
                        <td>{{ $task['assigned_to'] }}</td>
                        <td>
                            <span class="badge bg-{{ $task['status_color'] }}-subtle text-{{ $task['status_color'] }} rounded-pill px-3 py-1">
                                {{ ucfirst($task['status']) }}
                            </span>
                        </td>
                        <td>{{ date('M d, Y', strtotime($task['due_date'])) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
