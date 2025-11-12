<!-- resources/views/admin/hero/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Create Hero Section')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Create New Hero Section</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.hero.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label for="heading" class="block text-gray-300">Heading</label>
            <input type="text" name="heading" id="heading" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('heading', 'Premium Solar Energy Solutions for') }}">
        </div>
        <div class="mb-4">
            <label for="highlighted_text" class="block text-gray-300">Highlighted Text</label>
            <input type="text" name="highlighted_text" id="highlighted_text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('highlighted_text', 'Modern India') }}">
        </div>
        <div class="mb-4">
            <label for="subtext" class="block text-gray-300">Subtext</label>
            <textarea name="subtext" id="subtext" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('subtext', 'Cut your energy costs by up to 90% with our government-approved solar systems and premium components.') }}</textarea>
        </div>
        <div class="mb-4">
            <label for="button1_text" class="block text-gray-300">Button 1 Text</label>
            <input type="text" name="button1_text" id="button1_text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('button1_text', 'Get Free Quote') }}">
        </div>
        <div class="mb-4">
            <label for="button1_link" class="block text-gray-300">Button 1 Link</label>
            <input type="url" name="button1_link" id="button1_link" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('button1_link', '#') }}">
        </div>
        <div class="mb-4">
            <label for="button2_text" class="block text-gray-300">Button 2 Text (Optional)</label>
            <input type="text" name="button2_text" id="button2_text" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('button2_text', 'Watch Demo') }}">
        </div>
        <div class="mb-4">
            <label for="button2_link" class="block text-gray-300">Button 2 Link (Optional)</label>
            <input type="url" name="button2_link" id="button2_link" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('button2_link', '#') }}">
        </div>
        <div class="mb-4">
            <label for="icon_svg" class="block text-gray-300">Icon SVG (Optional)</label>
            <textarea name="icon_svg" id="icon_svg" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('icon_svg', '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>') }}</textarea>
        </div>
        <div class="mb-4">
            <label for="image_path" class="block text-gray-300">Background Image (Optional)</label>
            <input type="file" name="image_path" id="image_path" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">
        </div>
        <div class="mb-4">
            <label for="scroll_target" class="block text-gray-300">Scroll Target (Optional)</label>
            <input type="text" name="scroll_target" id="scroll_target" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('scroll_target', '#solutions') }}">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Create Hero Section</button>
        <a href="{{ route('admin.hero.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
    </form>
@endsection