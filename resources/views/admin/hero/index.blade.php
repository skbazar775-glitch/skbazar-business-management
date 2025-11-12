@extends('layouts.admin')

@section('title', 'Manage Hero Sections')

@section('content')
    <!-- Heading with modern styling -->
    <h1 class="text-3xl font-bold text-white mb-8">Manage Hero Sections</h1>



    <!-- Single-column card layout -->
    <div class="space-y-6">
        @forelse ($heroSections as $heroSection)
            <!-- Individual card with hover effect -->
            <div class="bg-gray-800 rounded-lg shadow-lg p-6 hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                <!-- Heading -->
                <h3 class="text-lg font-semibold text-white mb-2">{{ $heroSection->heading }}</h3>
                <!-- Highlighted Text -->
                <p class="text-blue-400 mb-2">{{ $heroSection->highlighted_text }}</p>
                <!-- Subtext with truncation -->
                <p class="text-gray-400">{{ Str::limit($heroSection->subtext, 50) }}</p>
                <!-- Action buttons -->
                <div class="mt-4 flex space-x-4">
                    <!-- Edit button -->
                    <a href="{{ route('admin.hero.edit', $heroSection->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 focus:ring-2 focus:ring-blue-400 focus:outline-none transition duration-300 flex items-center" aria-label="Edit hero section">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <!-- Delete form -->
                    <form action="{{ route('admin.hero.destroy', $heroSection->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <!-- Delete button -->
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 focus:ring-2 focus:ring-red-400 focus:outline-none transition duration-300 flex items-center" onclick="return confirm('Are you sure you want to delete this hero section?')" aria-label="Delete hero section">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <!-- Empty state -->
            <div class="text-center text-gray-400 py-8">No hero sections found.</div>
        @endforelse
    </div>
@endsection