@extends('layouts.admin')

@section('title', 'Edit Hero Section')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Hero Section</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.hero.update', $heroSection->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="heading" class="block text-gray-300">Heading</label>
            <input type="text" name="heading" id="heading" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                value="{{ old('heading', $heroSection->heading) }}" placeholder="e.g., Premium Solar Energy Solutions">
            <small class="text-gray-400 text-sm">Optional. Leave blank if not needed.</small>
        </div>

        <div class="mb-4">
            <label for="highlighted_text" class="block text-gray-300">Highlighted Text</label>
            <input type="text" name="highlighted_text" id="highlighted_text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                value="{{ old('highlighted_text', $heroSection->highlighted_text) }}" placeholder="e.g., Modern India">
            <small class="text-gray-400 text-sm">Optional. Appears with gradient highlight.</small>
        </div>

        <div class="mb-4">
            <label for="subtext" class="block text-gray-300">Subtext</label>
            <textarea name="subtext" id="subtext" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                rows="3" placeholder="e.g., Cut your energy costs by up to 90%...">{{ old('subtext', $heroSection->subtext) }}</textarea>
            <small class="text-gray-400 text-sm">Optional short paragraph under the heading.</small>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="button1_text" class="block text-gray-300">Button 1 Text</label>
                <input type="text" name="button1_text" id="button1_text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                    value="{{ old('button1_text', $heroSection->button1_text) }}" placeholder="e.g., Get Free Quote">
            </div>
            <div class="mb-4">
                <label for="button1_link" class="block text-gray-300">Button 1 Link</label>
                <input type="url" name="button1_link" id="button1_link" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                    value="{{ old('button1_link', $heroSection->button1_link) }}" placeholder="e.g., https://yourlink.com">
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="button2_text" class="block text-gray-300">Button 2 Text</label>
                <input type="text" name="button2_text" id="button2_text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                    value="{{ old('button2_text', $heroSection->button2_text) }}" placeholder="e.g., Watch Demo">
            </div>
            <div class="mb-4">
                <label for="button2_link" class="block text-gray-300">Button 2 Link</label>
                <input type="url" name="button2_link" id="button2_link" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                    value="{{ old('button2_link', $heroSection->button2_link) }}" placeholder="https://demo-link.com">
            </div>
        </div>

        <div class="mb-4">
            <label for="icon_svg" class="block text-gray-300">Icon SVG</label>
            <textarea name="icon_svg" id="icon_svg" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                rows="4" placeholder="<svg>...</svg>">{{ old('icon_svg', $heroSection->icon_svg) }}</textarea>
            <small class="text-gray-400 text-sm">Optional. Paste raw SVG icon if needed.</small>
        </div>

        <div class="mb-4">
            <label for="image_path" class="block text-gray-300">Background Image</label>
            <input type="file" name="image_path" id="image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
            @if ($heroSection->image_path && file_exists(public_path('storage/' . $heroSection->image_path)))
                <img src="{{ asset('storage/' . $heroSection->image_path) }}" alt="Current Image"
                    class="mt-2 w-40 h-40 object-cover rounded shadow">
            @else
                <p class="text-sm text-gray-500 mt-2">No image uploaded.</p>
            @endif
        </div>

        <div class="mb-6">
            <label for="scroll_target" class="block text-gray-300">Scroll Target</label>
            <input type="text" name="scroll_target" id="scroll_target" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700"
                value="{{ old('scroll_target', $heroSection->scroll_target) }}" placeholder="e.g., #solutions">
            <small class="text-gray-400 text-sm">Optional. Used for down arrow link to scroll.</small>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow transition">
                Update Hero Section
            </button>
            <a href="{{ route('admin.hero.index') }}" class="px-5 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                Cancel
            </a>
        </div>
    </form>
@endsection
