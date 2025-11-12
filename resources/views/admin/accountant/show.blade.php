@extends('layouts.admin')

@section('title', 'Accountant Details')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<div class="container-fluid px-4 py-6">
    <!-- Card -->
    <div class="card bg-white shadow-lg rounded-xl overflow-hidden" data-aos="fade-up">
        <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6">
            <h4 class="text-xl font-bold mb-0">Accountant Details</h4>
        </div>
        <div class="card-body p-6">
            <!-- Back Button -->
            <a href="{{ route('admin.accountants.index') }}" class="btn bg-gradient-to-r from-gray-600 to-gray-800 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all mb-6 inline-block" data-aos="fade-right">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <!-- Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-gray-50 border-l-4 border-blue-500 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="100">
                    <dt class="text-blue-600 font-semibold">ID</dt>
                    <dd class="text-gray-800">{{ $accountant->id }}</dd>
                </div>
                <div class="bg-gray-50 border-l-4 border-blue-500 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="200">
                    <dt class="text-blue-600 font-semibold">Name</dt>
                    <dd class="text-gray-800">{{ $accountant->name }}</dd>
                </div>
                <div class="bg-gray-50 border-l-4 border-blue-500 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="300">
                    <dt class="text-blue-600 font-semibold">Email</dt>
                    <dd class="text-gray-800">{{ $accountant->email }}</dd>
                </div>
                <div class="bg-gray-50 border-l-4 border-blue-500 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="400">
                    <dt class="text-blue-600 font-semibold">Created At</dt>
                    <dd class="text-gray-800">{{ $accountant->created_at->format('d M Y, h:i A') }}</dd>
                </div>
                <div class="bg-gray-50 border-l-4 border-blue-500 p-4 rounded-lg" data-aos="fade-up" data-aos-delay="500">
                    <dt class="text-blue-600 font-semibold">Updated At</dt>
                    <dd class="text-gray-800">{{ $accountant->updated_at->format('d M Y, h:i A') }}</dd>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<style>
    .card:hover {
        transform: translateY(-4px);
        transition: transform 0.3s ease;
    }
    .grid div:hover {
        background-color: #e0f2fe;
        transition: background-color 0.3s ease;
    }
    .swal2-popup {
        border-radius: 0.75rem;
        box-shadow: 0 0.15rem 1.75rem rgba(0, 0, 0, 0.1);
    }
    .swal2-confirm {
        background: linear-gradient(to right, #3b82f6, #1e40af) !important;
        border-radius: 0.5rem;
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animations
        AOS.init({ duration: 800, easing: 'ease-in-out', once: true });

        // Show success message if present
        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal2-popup',
                    confirmButton: 'swal2-confirm'
                }
            });
        @endif
    });
</script>
@endsection