<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technician Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="bg-blue-600 text-white p-4 shadow-md">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">Technician Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-sm">Welcome, {{ $technician->name }}</span>
                    <form method="POST" action="{{ route('technician.logout') }}">
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
                <h2 class="text-xl font-semibold mb-4">Service Overview</h2>
                <p class="text-gray-600 mb-4">
                    Welcome to your technician dashboard. Manage service requests, track repairs, and view schedules here.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Sample Card 1 -->
                    <div class="bg-blue-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-blue-800">Service Requests</h3>
                        <p class="text-2xl font-bold text-blue-600">20</p>
                        <p class="text-sm text-gray-600">Pending service requests.</p>
                    </div>
                    <!-- Sample Card 2 -->
                    <div class="bg-green-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-green-800">Repairs</h3>
                        <p class="text-2xl font-bold text-green-600">8</p>
                        <p class="text-sm text-gray-600">Ongoing repair tasks.</p>
                    </div>
                    <!-- Sample Card 3 -->
                    <div class="bg-yellow-100 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-yellow-800">Schedules</h3>
                        <p class="text-2xl font-bold text-yellow-600">5</p>
                        <p class="text-sm text-gray-600">Upcoming appointments.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white text-center p-4 mt-auto">
            <p class="text-sm">© {{ date('Y') }} Technician Portal. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>