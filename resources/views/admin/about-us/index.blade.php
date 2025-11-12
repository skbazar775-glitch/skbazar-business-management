<!-- resources/views/admin/about-us/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage About Us')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Manage About Us</h1>

    @if ($aboutUs)
        <div class="glass-panel p-6 mb-6">
            <h2 class="text-xl font-semibold text-white mb-4">{{ $aboutUs->title }}</h2>
            <p class="text-gray-400 mb-4">{{ \Str::limit($aboutUs->description, 100) }}</p>
            <ul class="text-gray-300 mb-4">
                <li>{{ $aboutUs->point_1 }}</li>
                <li>{{ $aboutUs->point_2 }}</li>
                <li>{{ $aboutUs->point_3 }}</li>
            </ul>
            <p class="text-gray-400 mb-4">Button Text: {{ $aboutUs->button_text }}</p>
            <p class="text-gray-400 mb-4">CEO: {{ $aboutUs->ceo_name }} ({{ $aboutUs->ceo_title }})</p>
            <div class="flex gap-4 mb-4">
                @if ($aboutUs->main_image_path)
                    <img src="{{ asset('storage/' . $aboutUs->main_image_path) }}" alt="Main Image" class="w-32 h-32 object-cover rounded">
                @else
                    <p class="text-gray-400">No main image</p>
                @endif
                @if ($aboutUs->ceo_image_path)
                    <img src="{{ asset('storage/' . $aboutUs->ceo_image_path) }}" alt="CEO Image" class="w-32 h-32 object-cover rounded">
                @else
                    <p class="text-gray-400">No CEO image</p>
                @endif
            </div>
            <a href="{{ route('admin.about-us.edit', $aboutUs->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow mr-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.about-us.destroy', $aboutUs->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 hover-glow" onclick="return confirm('Are you sure you want to delete this About Us content?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    @else
        <div class="text-center text-gray-400 mb-6">
            No About Us content found.
        </div>
        <a href="{{ route('admin.about-us.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Add About Us Content</a>
    @endif
@endsection