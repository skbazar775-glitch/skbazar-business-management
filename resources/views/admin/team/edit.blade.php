<!-- resources/views/admin/team/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Team Member')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Team Member</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.team.update', $teamMember->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-gray-300">Name</label>
            <input type="text" name="name" id="name" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('name', $teamMember->name) }}">
        </div>
        <div class="mb-4">
            <label for="designation" class="block text-gray-300">Designation</label>
            <input type="text" name="designation" id="designation" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('designation', $teamMember->designation) }}">
        </div>
        <div class="mb-4">
            <label for="bio" class="block text-gray-300">Bio</label>
            <textarea name="bio" id="bio" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('bio', $teamMember->bio) }}</textarea>
        </div>
        <div class="mb-4">
            <label for="image_path" class="block text-gray-300">Image (Optional)</label>
            <input type="file" name="image_path" id="image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
            @if ($teamMember->image_path)
                <img src="{{ asset('uploaded/' . $teamMember->image_path) }}" alt="Current Image" class="mt-2 w-32 h-32 object-cover rounded">
            @endif
        </div>
        <div class="mb-4">
            <label for="linkedin_url" class="block text-gray-300">LinkedIn URL (Optional)</label>
            <input type="url" name="linkedin_url" id="linkedin_url" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('linkedin_url', $teamMember->linkedin_url) }}">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-300">Email (Optional)</label>
            <input type="email" name="email" id="email" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('email', $teamMember->email) }}">
        </div>
        <div class="mb-4">
            <label for="color_class" class="block text-gray-300">Color Class (Optional)</label>
            <select name="color_class" id="color_class" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                <option value="" {{ old('color_class', $teamMember->color_class) == '' ? 'selected' : '' }}>None</option>
                <option value="text-blue-400" {{ old('color_class', $teamMember->color_class) == 'text-blue-400' ? 'selected' : '' }}>Blue</option>
                <option value="text-green-400" {{ old('color_class', $teamMember->color_class) == 'text-green-400' ? 'selected' : '' }}>Green</option>
                <option value="text-yellow-400" {{ old('color_class', $teamMember->color_class) == 'text-yellow-400' ? 'selected' : '' }}>Yellow</option>
                <option value="text-purple-400" {{ old('color_class', $teamMember->color_class) == 'text-purple-400' ? 'selected' : '' }}>Purple</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Update Team Member</button>
        <a href="{{ route('admin.team.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
    </form>
@endsection