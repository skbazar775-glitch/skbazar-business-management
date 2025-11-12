@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Attendance Details</h3>
            <a href="{{ route('admin.viewattendance.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>

        <div class="card-body">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <h6 class="fw-bold">Staff Information</h6>
                    <p class="mb-1"><span class="text-muted">Name:</span> {{ $attendanceRecord['staff_name'] }}</p>
                    <p class="mb-1"><span class="text-muted">Date:</span> {{ \Carbon\Carbon::parse($attendanceRecord['attendance_date'])->format('d M Y, l') }}</p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">First Half Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2"><strong>In:</strong> {{ $attendanceRecord['first_half_in'] ?? '-' }}</p>
                                    <p class="mb-2"><strong>Out:</strong> {{ $attendanceRecord['first_half_out'] ?? '-' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2">
                                        <strong>Status:</strong> 
                                        <span class="badge rounded-pill {{ $attendanceRecord['first_half_status'] == 'Present' ? 'bg-success' : ($attendanceRecord['first_half_status'] == 'Absent' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ $attendanceRecord['first_half_status'] }}
                                        </span>
                                    </p>
                                    <p class="mb-2"><strong>Late By:</strong> {{ $attendanceRecord['first_half_late_by'] ?: '-' }}</p>
                                    <p class="mb-0"><strong>Hours:</strong> {{ $attendanceRecord['first_half_hours'] ? $attendanceRecord['first_half_hours'] . 'h' : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Second Half Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-2"><strong>In:</strong> {{ $attendanceRecord['second_half_in'] ?? '-' }}</p>
                                    <p class="mb-2"><strong>Out:</strong> {{ $attendanceRecord['second_half_out'] ?? '-' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-2">
                                        <strong>Status:</strong> 
                                        <span class="badge rounded-pill {{ $attendanceRecord['second_half_status'] == 'Present' ? 'bg-success' : ($attendanceRecord['second_half_status'] == 'Absent' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            {{ $attendanceRecord['second_half_status'] }}
                                        </span>
                                    </p>
                                    <p class="mb-2"><strong>Late By:</strong> {{ $attendanceRecord['second_half_late_by'] ?: '-' }}</p>
                                    <p class="mb-0"><strong>Hours:</strong> {{ $attendanceRecord['second_half_hours'] ? $attendanceRecord['second_half_hours'] . 'h' : '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Summary</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Total Hours:</strong> {{ $attendanceRecord['total_hours'] ? $attendanceRecord['total_hours'] . 'h' : '-' }}</p>
                            <p class="mb-0"><strong>Remarks:</strong> {{ $attendanceRecord['remarks'] ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Hours Filter and Table -->
<!-- Monthly Hours Filter and Table -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Monthly Working Hours</h6>
                <form method="GET" action="{{ route('admin.viewattendance.show', $attendanceRecord['id']) }}" class="d-flex">
                    <input type="month" name="month_filter" value="{{ $monthFilter }}" class="form-control me-2" style="width: 150px;">
                    <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                    <a href="{{ route('admin.viewattendance.show', $attendanceRecord['id']) }}" class="btn btn-outline-secondary btn-sm ms-2">Clear</a>
                </form>
            </div>
            <div class="card-body">
                @if (!empty($monthlyHours))
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Month</th>
                                <th>Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthlyHours as $month => $hours)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                    <td>{{ $hours }}h</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">No records found for the selected month.</p>
                @endif
            </div>
                        <!-- Calculate Salary Button -->
            <div class="mt-6">
                <a href="{{ route('admin.viewattendance.calculate_salary', $attendanceRecord['staff_id']) }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">
                    View Salary Calculation
                </a>
                <a href="{{ route('admin.viewattendance.index') }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded ml-4">
                    Back to Attendance List
                </a>
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-radius: 0.5rem 0.5rem 0 0 !important;
    }

    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
        font-size: 0.75em;
    }

    .btn-outline-primary {
        transition: all 0.2s ease;
    }

    .btn-outline-primary:hover {
        transform: translateY(-1px);
    }

    .table {
        width: 100%;
        margin-bottom: 1rem;
        color: #212529;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }
</style>
@endsection