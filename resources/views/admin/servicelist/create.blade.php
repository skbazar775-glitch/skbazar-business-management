@extends('layouts.admin')

@section('content')

<div class="container-fluid px-4"> <div class="d-sm-flex align-items-center justify-content-between mb-4"> <h1 class="h3 mb-0 text-gray-800">Create New Service</h1> <a href="{{ route('admin.servicelist.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm"> <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Services </a> </div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white py-3">
        <h6 class="m-0 font-weight-bold">Add New Service Details</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.servicelist.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Active</option>
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="name" class="form-label">Service Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" placeholder="Enter service name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-4">
                <label for="price" class="form-label">Price ($) <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text">$</span>
                    <input type="number" name="price" id="price" step="0.01" 
                           class="form-control @error('price') is-invalid @enderror" 
                           value="{{ old('price') }}" placeholder="0.00">
                </div>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-1"></i> Create Service
                </button>
                <a href="{{ route('admin.servicelist.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
</div><style> .form-label { font-weight: 600; color: #333; } .card { border: none; border-radius: 0.75rem; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15); } .card-header { border-radius: 0.75rem 0.75rem 0 0 !important; } .input-group-text { background-color: #f8f9fa; border-right: none; } #price { border-left: none; } .btn { border-radius: 0.5rem; padding: 0.5rem 1.5rem; } .form-control:focus, .form-select:focus { border-color: #86b7fe; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25); } </style><script> document.addEventListener('DOMContentLoaded', function() { // Add focus styles to form inputs const formInputs = document.querySelectorAll('input, select'); formInputs.forEach(input => { input.addEventListener('focus', function() { this.parentElement.classList.add('focused'); }); input.addEventListener('blur', function() { this.parentElement.classList.remove('focused'); }); }); }); </script>
@endsection