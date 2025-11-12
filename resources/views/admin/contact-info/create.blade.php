<!-- resources/views/admin/contact-info/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Create Contact Info')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Create Contact Info</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.contact-info.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="office_title" class="block text-gray-300">Office Title</label>
            <input type="text" name="office_title" id="office_title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('office_title') }}">
        </div>
        <div class="mb-4">
            <label for="office_address" class="block text-gray-300">Office Address</label>
            <textarea name="office_address" id="office_address" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700">{{ old('office_address') }}</textarea>
        </div>
        <div class="mb-4">
            <label for="phone_title" class="block text-gray-300">Phone Title</label>
            <input type="text" name="phone_title" id="phone_title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('phone_title') }}">
        </div>
        <div class="mb-4">
            <label for="phone_1" class="block text-gray-300">Phone 1</label>
            <input type="text" name="phone_1" id="phone_1" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('phone_1') }}">
        </div>
        <div class="mb-4">
            <label for="phone_2" class="block text-gray-300">Phone 2 (Optional)</label>
            <input type="text" name="phone_2" id="phone_2" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('phone_2') }}">
        </div>
        <div class="mb-4">
            <label for="email_title" class="block text-gray-300">Email Title</label>
            <input type="text" name="email_title" id="email_title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('email_title') }}">
        </div>
        <div class="mb-4">
            <label for="email_1" class="block text-gray-300">Email 1</label>
            <input type="email" name="email_1" id="email_1" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('email_1') }}">
        </div>
        <div class="mb-4">
            <label for="email_2" class="block text-gray-300">Email 2 (Optional)</label>
            <input type="email" name="email_2" id="email_2" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('email_2') }}">
        </div>
        <div class="mb-4">
            <label for="hours_title" class="block text-gray-300">Hours Title</label>
            <input type="text" name="hours_title" id="hours_title" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('hours_title') }}">
        </div>
        <div class="mb-4">
            <label for="weekdays_hours" class="block text-gray-300">Weekdays Hours</label>
            <input type="text" name="weekdays_hours" id="weekdays_hours" class="w-full p-2 rounded bg-gray-800 text-white border border-gray-700" value="{{ old('weekdays_hours') }}">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 hover-glow">Create Contact Info</button>
        <a href="{{ route('admin.contact-info.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-2">Cancel</a>
    </form>
@endsection