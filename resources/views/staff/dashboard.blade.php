@extends('layouts.staff')

@section('content')
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-blue-600 text-white p-4 shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">Staff Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Welcome, {{ $staff->name }}</span>
                    <form method="POST" action="{{ route('staff.logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm font-semibold transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="container mx-auto p-6 flex-grow">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold mb-4">Staff Overview</h2>
                <p class="text-gray-600 mb-4">
                    Welcome to your staff dashboard. Manage your tasks, view schedules, and access resources here.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Sample Card 1 -->
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-blue-800">Tasks</h3>
                        <p class="text-2xl font-bold text-blue-600">15</p>
                        <p class="text-sm text-gray-600">Pending tasks assigned to you.</p>
                    </div>
                    <!-- Sample Card 2 -->
                    <div class="bg-green-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-green-800">Schedules</h3>
                        <p class="text-2xl font-bold text-green-600">7</p>
                        <p class="text-sm text-gray-600">Upcoming shifts or meetings.</p>
                    </div>
                    <!-- Sample Card 3 -->
                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-yellow-800">Resources</h3>
                        <p class="text-2xl font-bold text-yellow-600">3</p>
                        <p class="text-sm text-gray-600">New resources available.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white text-center p-4 mt-auto">
            <p class="text-sm">© {{ date('Y') }} Staff Portal. All rights reserved.</p>
        </footer>
    </div>
@endsection