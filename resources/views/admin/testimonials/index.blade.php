<!-- resources/views/admin/testimonials/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage Testimonials')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Manage Testimonials</h1>

    <a href="{{ route('admin.testimonials.create') }}" class="inline-block mb-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Add New Testimonial</a>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-gray-700">
                <th class="py-2">Name</th>
                <th class="py-2">Designation</th>
                <th class="py-2">Location</th>
                <th class="py-2">Message</th>
                <th class="py-2">Image</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($testimonials as $testimonial)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $testimonial->name }}</td>
                    <td class="py-2">{{ $testimonial->designation }}</td>
                    <td class="py-2">{{ $testimonial->location }}</td>
                    <td class="py-2">{{ \Str::limit($testimonial->message, 50) }}</td>
                    <td class="py-2">
                        @if ($testimonial->image_path)
                            <img src="{{ asset('uploaded/' . $testimonial->image_path) }}" alt="{{ $testimonial->name }}" class="w-16 h-16 object-cover rounded">
                        @else
                            No image
                        @endif
                    </td>
                    <td class="py-2">
                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.testimonials.destroy', $testimonial->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 ml-4" onclick="return confirm('Are you sure you want to delete this testimonial?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="py-2 text-center">No testimonials found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection