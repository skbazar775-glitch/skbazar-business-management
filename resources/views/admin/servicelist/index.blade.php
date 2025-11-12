@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4"> <div class="d-sm-flex align-items-center justify-content-between mb-4"> <h1 class="h3 mb-0 text-gray-800">Service Management</h1> <a href="{{ route('admin.servicelist.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"> <i class="fas fa-plus fa-sm text-white-50"></i> Add New Service </a> </div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">Filter Services</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.servicelist.index') }}">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Select Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">All Categories</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-filter me-1"></i> Apply Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">Service List</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td class="fw-bold">{{ $service->name }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $service->category->title ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-success fw-bold">${{ number_format($service->price, 2) }}</td>
                            <td>
                                <span class="badge {{ $service->status == 0 ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $service->status == 0 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $service->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.servicelist.edit', $service) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" 
                                       title="Edit Service">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.servicelist.destroy', $service) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this service?')"
                                                data-bs-toggle="tooltip" 
                                                title="Delete Service">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="fas fa-exclamation-circle fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No services found.</p>
                                <a href="{{ route('admin.servicelist.create') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-plus me-1"></i> Add Your First Service
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if ($services->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $services->links() }}
            </div>
        @endif
    </div>
</div>
</div><style> .table-hover tbody tr:hover { background-color: rgba(0, 123, 255, 0.1); transform: translateX(2px); transition: all 0.3s ease; } .badge { font-size: 0.85em; padding: 0.5em 0.75em; } .btn-group .btn { border-radius: 0.375rem; margin-right: 0.25rem; } .card { border: none; border-radius: 0.75rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); } .card-header { border-radius: 0.75rem 0.75rem 0 0 !important; } </style><script> // Initialize Bootstrap tooltips document.addEventListener('DOMContentLoaded', function() { var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')) var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) { return new bootstrap.Tooltip(tooltipTriggerEl) }) }); </script>
@endsection