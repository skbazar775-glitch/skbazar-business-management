<!-- resources/views/admin/about-us/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit About Us')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit About Us Content</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.about-us.update', $aboutUs->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="title" class="block text-gray-300">Title</label>
            <input type="text" name="title" id="title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('title', $aboutUs->title) }}">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-300">Description</label>
            <textarea name="description" id="description" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('description', $aboutUs->description) }}</textarea>
        </div>
        <div class="mb-4">
            <label for="point_1" class="block text-gray-300">Point 1</label>
            <input type="text" name="point_1" id="point_1" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('point_1', $aboutUs->point_1) }}">
        </div>
        <div class="mb-4">
            <label for="point_2" class="block text-gray-300">Point 2</label>
            <input type="text" name="point_2" id="point_2" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('point_2', $aboutUs->point_2) }}">
        </div>
        <div class="mb-4">
            <label for="point_3" class="block text-gray-300">Point 3</label>
            <input type="text" name="point_3" id="point_3" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('point_3', $aboutUs->point_3) }}">
        </div>
        <div class="mb-4">
            <label for="button_text" class="block text-gray-300">Button Text</label>
            <input type="text" name="button_text" id="button_text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('button_text', $aboutUs->button_text) }}">
        </div>
        <div class="mb-4">
            <label for="main_image_path" class="block text-gray-300">Main Image (Optional)</label>
            <input type="file" name="main_image_path" id="main_image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
            @if ($aboutUs->main_image_path)
                <img id="main_image_preview" src="{{ asset('storage/' . $aboutUs->main_image_path) }}" alt="Main Image Preview" class="mt-2 w-32 h-32 object-cover rounded">
            @else
                <img id="main_image_preview" src="#" alt="Main Image Preview" class="mt-2 w-32 h-32 object-cover rounded hidden">
            @endif
        </div>
        <div class="mb-4">
            <label for="ceo_image_path" class="block text-gray-300">CEO Image (Optional)</label>
            <input type="file" name="ceo_image_path" id="ceo_image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
            @if ($aboutUs->ceo_image_path)
                <img id="ceo_image_preview" src="{{ asset('storage/' . $aboutUs->ceo_image_path) }}" alt="CEO Image Preview" class="mt-2 w-32 h-32 object-cover rounded">
            @else
                <img id="ceo_image_preview" src="#" alt="CEO Image Preview" class="mt-2 w-32 h-32 object-cover rounded hidden">
            @endif
        </div>
        <div class="mb-4">
            <label for="ceo_name" class="block text-gray-300">CEO Name</label>
            <input type="text" name="ceo_name" id="ceo_name" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('ceo_name', $aboutUs->ceo_name) }}">
        </div>
        <div class="mb-4">
            <label for="ceo_title" class="block text-gray-300">CEO Title</label>
            <input type="text" name="ceo_title" id="ceo_title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('ceo_title', $aboutUs->ceo_title) }}">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Update About Us</button>
        <a href="{{ route('admin.about-us.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
    </form>

    <script>
        function setupImagePreview(inputId, previewId) {
            document.getElementById(inputId).addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById(previewId);
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
        }

        setupImagePreview('main_image_path', 'main_image_preview');
        setupImagePreview('ceo_image_path', 'ceo_image_preview');
    </script>
@endsection