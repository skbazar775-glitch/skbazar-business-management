@extends('layouts.admin')

@section('title', 'Edit Manager')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<div class="container-fluid px-4 py-6">
    <!-- Card -->
    <div class="card bg-white shadow-lg rounded-xl overflow-hidden" data-aos="fade-up">
        <div class="card-header bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-4 px-6">
            <h4 class="text-xl font-bold mb-0">Edit Manager</h4>
        </div>
        <div class="card-body p-6">
            <!-- Back Button -->
            <a href="{{ route('admin.managers.index') }}" class="btn bg-gradient-to-r from-gray-600 to-gray-800 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all mb-6 inline-block" data-aos="fade-right">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            <!-- Form -->
            <form action="{{ route('admin.managers.update', $manager) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4" data-aos="fade-up" data-aos-delay="100">
                    <label for="name" class="block text-gray-700 font-semibold mb-2">Name</label>
                    <input type="text" name="name" id="name" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" value="{{ old('name', $manager->name) }}">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4" data-aos="fade-up" data-aos-delay="200">
                    <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror" value="{{ old('email', $manager->email) }}">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4" data-aos="fade-up" data-aos-delay="300">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password (Leave blank to keep unchanged)</label>
                    <input type="password" name="password" id="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4" data-aos="fade-up" data-aos-delay="400">
                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end" data-aos="fade-up" data-aos-delay="500">
                    <button type="submit" class="btn bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg hover:shadow-lg hover:scale-105 transition-all">
                        <i class="fas fa-save mr-2"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-4px);
        transition: transform 0.3s ease;
    }
    input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
    .form-control {
        transition: all 0.3s ease;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS animations
        AOS.init({ duration: 800, easing: 'ease-in-out', once: true });
    });
</script>
@endsection