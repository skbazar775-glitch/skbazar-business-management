@extends('layouts.admin')

@section('title', 'Create Staff')

@section('content')
<div class="container my-5">
    <div class="mx-auto bg-white shadow-lg rounded-3 p-4" style="max-width: 650px;">
        {{-- 🔹 Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary fw-bold">
                <i class="fas fa-user-plus me-2"></i> Create Staff
            </h4>
            <a href="{{ route('admin.staff.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>

        {{-- 🔹 Form --}}
        <form action="{{ route('admin.staff.store') }}" method="POST" class="needs-validation">
            @csrf

            {{-- Name --}}
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Full Name</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       placeholder="Enter full name"
                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email Address</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       placeholder="Enter email"
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">Password</label>
                <input type="password" 
                       name="password" 
                       id="password" 
                       placeholder="Create a strong password"
                       class="form-control form-control-lg @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                <input type="password" 
                       name="password_confirmation" 
                       id="password_confirmation" 
                       placeholder="Re-enter password"
                       class="form-control form-control-lg">
            </div>

            {{-- Salary --}}
            <div class="mb-3">
                <label for="salary" class="form-label fw-semibold">Salary</label>
                <input type="number" 
                       name="salary" 
                       id="salary" 
                       placeholder="Enter salary"
                       step="0.01" 
                       class="form-control form-control-lg @error('salary') is-invalid @enderror" 
                       value="{{ old('salary') }}">
                @error('salary')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-save me-2"></i> Create Staff
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        </script>
    @endif
@endsection
