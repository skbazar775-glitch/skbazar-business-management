@extends('layouts.admin')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4 text-white">Manage Sections</h1>
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4 frosted-card">
                {{ session('success') }}
            </div>
        @endif
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="p-2">Section Name</th>
                    <th class="p-2">Visible</th>
                    <th class="p-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sections as $section)
                    <tr class="frosted-card">
                        <td class="p-2">{{ ucfirst($section->name) }}</td>
                        <td class="p-2">{{ $section->is_visible ? 'Yes' : 'No' }}</td>
                        <td class="p-2">
                            <form action="{{ route('admin.sections.update', $section) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="is_visible" value="{{ $section->is_visible ? 0 : 1 }}">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 hover-glow">
                                    {{ $section->is_visible ? 'Hide' : 'Show' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection