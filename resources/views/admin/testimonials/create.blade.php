<!-- resources/views/admin/testimonials/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Create Testimonial')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Create New Testimonial</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-300">Name</label>
            <input type="text" name="name" id="name" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('name') }}">
        </div>
        <div class="mb-4">
            <label for="designation" class="block text-gray-300">Designation</label>
            <input type="text" name="designation" id="designation" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('designation') }}">
        </div>
        <div class="mb-4">
            <label for="location" class="block text-gray-300">Location</label>
            <input type="text" name="location" id="location" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('location') }}">
        </div>
        <div class="mb-4">
            <label for="message" class="block text-gray-300">Message</label>
            <textarea name="message" id="message" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('message') }}</textarea>
        </div>
        <div class="mb-4">
            <label for="image_path" class="block text-gray-300">Image (Optional)</label>
            <input type="file" name="image_path" id="image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
            <img id="image_preview" src="#" alt="Image Preview" class="mt-2 w-32 h-32 object-cover rounded hidden">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Create Testimonial</button>
        <a href="{{ route('admin.testimonials.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
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