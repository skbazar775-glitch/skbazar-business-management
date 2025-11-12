<!-- resources/views/admin/projects/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Project</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-gray-300">Title</label>
            <input type="text" name="title" id="title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('title', $project->title) }}">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-300">Description</label>
            <textarea name="description" id="description" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('description', $project->description) }}</textarea>
        </div>
        <div class="mb-4">
            <label for="category" class="block text-gray-300">Category</label>
            <select name="category" id="category" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                <option value="Residential" {{ old('category', $project->category) == 'Residential' ? 'selected' : '' }}>Residential</option>
                <option value="Commercial" {{ old('category', $project->category) == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                <option value="Industrial" {{ old('category', $project->category) == 'Industrial' ? 'selected' : '' }}>Industrial</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="image_path" class="block text-gray-300">Image (Optional)</label>
            <input type="file" name="image_path" id="image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
            @if ($project->image_path)
                <img src="{{ asset('uploaded/' . $project->image_path) }}" alt="Current Image" class="mt-2 w-32 h-32 object-cover rounded">
            @endif
        </div>
        <div class="mb-4">
            <label for="completed_date" class="block text-gray-300">Completed Date</label>
            <input type="date" name="completed_date" id="completed_date" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('completed_date', $project->completed_date->format('Y-m-d')) }}">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Update Project</button>
        <a href="{{ route('admin.projects.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
    </form>
@endsection