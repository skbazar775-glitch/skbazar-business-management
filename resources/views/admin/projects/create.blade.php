<!-- resources/views/admin/projects/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Create Project')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Create New Project</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="title" class="block text-gray-300">Title</label>
            <input type="text" name="title" id="title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('title') }}">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-300">Description</label>
            <textarea name="description" id="description" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('description') }}</textarea>
        </div>
        <div class="mb-4">
            <label for="category" class="block text-gray-300">Category</label>
            <select name="category" id="category" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                <option value="Residential" {{ old('category') == 'Residential' ? 'selected' : '' }}>Residential</option>
                <option value="Commercial" {{ old('category') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                <option value="Industrial" {{ old('category') == 'Industrial' ? 'selected' : '' }}>Industrial</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="image_path" class="block text-gray-300">Image (Optional)</label>
            <input type="file" name="image_path" id="image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
        </div>
        <div class="mb-4">
            <label for="completed_date" class="block text-gray-300">Completed Date</label>
            <input type="date" name="completed_date" id="completed_date" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('completed_date') }}">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Create Project</button>
        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
    </form>
@endsection