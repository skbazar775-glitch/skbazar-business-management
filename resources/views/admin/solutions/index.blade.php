<!-- resources/views/admin/solutions/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage Solar Solutions')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Manage Solar Solutions</h1>

    <a href="{{ route('admin.solutions.create') }}" class="inline-block mb-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Add New Solution</a>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-gray-700">
                <th class="py-2">Title</th>
                <th class="py-2">Description</th>
                <th class="py-2">Image</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($solarSolutions as $solution)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $solution->title }}</td>
                    <td class="py-2">{{ \Str::limit($solution->description, 50) }}</td>
                    <td class="py-2">
                        @if ($solution->image_path)
                            <img src="{{ asset('storage/' . $solution->image_path) }}" alt="{{ $solution->title }}" class="w-16 h-16 object-cover rounded">
                        @else
                            No image
                        @endif
                    </td>
                    <td class="py-2">
                        <a href="{{ route('admin.solutions.edit', $solution->id) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.solutions.destroy', $solution->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 ml-4" onclick="return confirm('Are you sure you want to delete this solution?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-2 text-center">No solar solutions found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection