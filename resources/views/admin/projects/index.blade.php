<!-- resources/views/admin/projects/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage Projects')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Manage Projects</h1>

    <a href="{{ route('admin.projects.create') }}" class="inline-block mb-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Add New Project</a>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-gray-700">
                <th class="py-2">Title</th>
                <th class="py-2">Category</th>
                <th class="py-2">Completed Date</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($projects as $project)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $project->title }}</td>
                    <td class="py-2">{{ $project->category }}</td>
                    <td class="py-2">{{ $project->formatted_date }}</td>
                    <td class="py-2">
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 ml-4" onclick="return confirm('Are you sure you want to delete this project?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-2 text-center">No projects found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection