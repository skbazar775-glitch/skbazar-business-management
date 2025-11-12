<!-- resources/views/admin/contact-info/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Manage Contact Info')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Manage Contact Info</h1>

    @if ($contactInfo)
        <div class="glass-panel p-6 mb-6">
            <h2 class="text-xl font-semibold text-white mb-4">{{ $contactInfo->office_title }}</h2>
            <p class="text-gray-400 mb-2">Address: {{ $contactInfo->office_address }}</p>
            <p class="text-gray-400 mb-2">Phone Title: {{ $contactInfo->phone_title }}</p>
            <p class="text-gray-400 mb-2">Phone 1: {{ $contactInfo->phone_1 }}</p>
            @if ($contactInfo->phone_2)
                <p class="text-gray-400 mb-2">Phone 2: {{ $contactInfo->phone_2 }}</p>
            @endif
            <p class="text-gray-400 mb-2">Email Title: {{ $contactInfo->email_title }}</p>
            <p class="text-gray-400 mb-2">Email 1: {{ $contactInfo->email_1 }}</p>
            @if ($contactInfo->email_2)
                <p class="text-gray-400 mb-2">Email 2: {{ $contactInfo->email_2 }}</p>
            @endif
            <p class="text-gray-400 mb-2">Hours Title: {{ $contactInfo->hours_title }}</p>
            <p class="text-gray-400 mb-4">Weekdays Hours: {{ $contactInfo->weekdays_hours }}</p>
            <a href="{{ route('admin.contact-info.edit', $contactInfo->id) }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow mr-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('admin.contact-info.destroy', $contactInfo->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 hover-glow" onclick="return confirm('Are you sure you want to delete this contact information?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
        </div>
    @else
        <div class="text-center text-gray-400 mb-6">
            No contact information found.
        </div>
        <a href="{{ route('admin.contact-info.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Add Contact Info</a>
    @endif
@endsection