@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h3 class="mb-3 mb-md-0">Attendance Records</h3>
            <div class="d-flex flex-column flex-md-row gap-3">
                <form class="d-flex" action="{{ route('admin.viewattendance.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Search by staff name..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-light">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                @if(request('search'))
                    <a href="{{ route('admin.viewattendance.index') }}" class="btn btn-outline-light">
                        <i class="fas fa-times"></i> Clear
                    </a>
                @endif
            </div>
        </div>

        <div class="card-body p-0">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($attendanceRecords->isEmpty())
                <div class="alert alert-info text-center mx-3 mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    No attendance records found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="ps-4">Staff Name</th>
                                <th scope="col">Date</th>
                                <th scope="col">First Half In</th>
                                <th scope="col">First Half Out</th>
                                <th scope="col">Second Half In</th>
                                <th scope="col">Second Half Out</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendanceRecords as $record)
                                <tr class="border-top">
                                    <td class="ps-4 fw-medium">{{ $record['staff_name'] }}</td>
                                    <td>{{ \Carbon\Carbon::parse($record['attendance_date'])->format('d M Y') }}</td>
                                    <td>{{ $record['first_half_in'] ?? '-' }}</td>
                                    <td>{{ $record['first_half_out'] ?? '-' }}</td>
                                    <td>{{ $record['second_half_in'] ?? '-' }}</td>
                                    <td>{{ $record['second_half_out'] ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.viewattendance.show', $record['id']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Details
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center px-3 py-3 border-top">
                    <div class="mb-2 mb-md-0 text-muted">
                        Showing {{ $attendanceRecords->firstItem() }} to {{ $attendanceRecords->lastItem() }} of {{ $attendanceRecords->total() }} records
                    </div>
                    <nav aria-label="Page navigation">
                        {{ $attendanceRecords->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>

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
    
    .table {
        margin-bottom: 0;
    }
    
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        color: #6c757d;
        background-color: #f8f9fa;
        vertical-align: middle;
    }
    
    .table td {
        vertical-align: middle;
        padding: 0.75rem;
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
    
    @media (max-width: 768px) {
        .table-responsive {
            border: none;
        }
        
        .table thead {
            display: none;
        }
        
        .table tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        
        .table td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table td::before {
            content: attr(data-label);
            font-weight: 600;
            margin-right: 1rem;
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        
        .table td:last-child {
            border-bottom: none;
        }
        
        .table td:nth11-child(1)::before { content: "Staff"; }
        .table td:nth-child(2)::before { content: "Date"; }
        .table td:nth-child(3)::before { content: "First In"; }
        .table td:nth-child(4)::before { content: "First Out"; }
        .table td:nth-child(5)::before { content: "Second In"; }
        .table td:nth-child(6)::before { content: "Second Out"; }
        .table td:nth-child(7)::before { content: "Actions"; }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (window.innerWidth < 768) {
            const labels = ["Staff", "Date", "First In", "First Out", "Second In", "Second Out", "Actions"];
            document.querySelectorAll('tbody tr').forEach(row => {
                row.querySelectorAll('td').forEach((td, index) => {
                    td.setAttribute('data-label', labels[index % labels.length]);
                });
            });
        }
    });
</script>
@endsection
@endsection