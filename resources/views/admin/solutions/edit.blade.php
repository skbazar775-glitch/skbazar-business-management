<!-- resources/views/admin/solutions/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Solar Solution')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Solar Solution</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.solutions.update', $solarSolution->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-gray-300">Title</label>
            <input type="text" name="title" id="title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('title', $solarSolution->title) }}">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-300">Description</label>
            <textarea name="description" id="description" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('description', $solarSolution->description) }}</textarea>
        </div>
        <div class="mb-4">
            <label for="color_class" class="block text-gray-300">Color Class (Optional)</label>
            <select name="color_class" id="color_class" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
                <option value="" {{ old('color_class', $solarSolution->color_class) == '' ? 'selected' : '' }}>Default (Blue)</option>
                <option value="hover-glow" {{ old('color_class', $solarSolution->color_class) == 'hover-glow' ? 'selected' : '' }}>Blue</option>
                <option value="hover-glow-green" {{ old('color_class', $solarSolution->color_class) == 'hover-glow-green' ? 'selected' : '' }}>Green</option>
                <option value="hover-glow-yellow" {{ old('color_class', $solarSolution->color_class) == 'hover-glow-yellow' ? 'selected' : '' }}>Yellow</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="image_path" class="block text-gray-300">Image (Optional)</label>
            <input type="file" name="image_path" id="image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
            @if ($solarSolution->image_path)
                <img id="image_preview" src="{{ asset('storage/' . $solarSolution->image_path) }}" alt="Current Image" class="mt-2 w-32 h-32 object-cover rounded">
            @else
                <img id="image_preview" src="#" alt="Image Preview" class="mt-2 w-32 h-32 object-cover rounded hidden">
            @endif
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Update Solution</button>
        <a href="{{ route('admin.solutions.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
    </form>

    <script>
        document.getElementById('image_path').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('image_preview');
            if (file) {
                if (!['image/jpeg', 'image/png', 'image/jpg', 'image/gif'].includes(file.type)) {
                    alert('Please upload a valid image file (JPEG, PNG, JPG, GIF).');
                    e.target.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                if (file.size > 2 * 1024 * 1024) {
                    alert('Image size must not exceed 2MB.');
                    e.target.value = '';
                    preview.classList.add('hidden');
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        });
    </script>
@endsection