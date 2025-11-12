<!-- resources/views/admin/team/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage Team Members')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Manage Team Members</h1>

    <a href="{{ route('admin.team.create') }}" class="inline-block mb-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Add New Team Member</a>

    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-gray-700">
                <th class="py-2">Name</th>
                <th class="py-2">Designation</th>
                <th class="py-2">Bio</th>
                <th class="py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($teamMembers as $teamMember)
                <tr class="border-b border-gray-800">
                    <td class="py-2">{{ $teamMember->name }}</td>
                    <td class="py-2">{{ $teamMember->designation }}</td>
                    <td class="py-2">{{ \Str::limit($teamMember->bio, 50) }}</td>
                    <td class="py-2">
                        <a href="{{ route('admin.team.edit', $teamMember->id) }}" class="text-blue-400 hover:text-blue-300">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.team.destroy', $teamMember->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 ml-4" onclick="return confirm('Are you sure you want to delete this team member?')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="py-2 text-center">No team members found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection