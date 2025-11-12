@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6 max-w-4xl">
    <!-- Header Section -->
    <div class="text-center mb-4 sm:mb-6">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 mb-2">
            Mark Attendance for {{ $staff->name }}
        </h1>
        <div class="flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-sm sm:text-base lg:text-lg text-gray-600">
                Today is {{ now()->format('l, F j, Y') }}
            </p>
        </div>
    </div>

    <!-- Current Time Card -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden p-4 sm:p-6 border-l-4 border-purple-500 mb-4 sm:mb-6 hover:shadow-md transform hover:-translate-y-1 transition duration-300">
        <div class="flex items-center mb-4">
            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Current Time</h2>
        </div>
        <div class="text-center py-4">
            <div id="live-clock" class="text-2xl sm:text-3xl font-bold text-purple-600 mb-2">00:00:00</div>
            <p class="text-sm sm:text-base text-gray-600">Office Hours: 9:00 AM - 1:00 PM & 3:00 PM - 7:00 PM</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg">
            <p class="text-sm sm:text-base text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-3 sm:p-4 mb-4 sm:mb-6 rounded-r-lg">
            <p class="text-sm sm:text-base text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Attendance Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- First Half -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transform hover:-translate-y-1 transition duration-300">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 text-white">
                <h2 class="text-base sm:text-lg font-semibold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    First Half (9:00 AM - 1:00 PM)
                </h2>
            </div>
            <div class="p-4 sm:p-6">
                @if ($attendance && $attendance->first_half_in)
                    <div class="space-y-4 text-sm sm:text-base">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Check-in:</span>
                            <span class="font-semibold text-green-600">{{ $attendance->first_half_in->format('h:i A') }}</span>
                        </div>

                        @if ($attendance->first_half_out)
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Check-out:</span>
                                <span class="font-semibold text-red-600">{{ $attendance->first_half_out->format('h:i A') }}</span>
                            </div>
                        @else
                            <form action="{{ route('admin.attendance.store', ['staffId' => $staff->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="first_half_out">
                                <button type="submit" class="w-full mt-4 bg-gradient-to-r from-red-500 to-red-600 text-white py-3 sm:py-4 rounded-lg font-medium hover:from-red-600 hover:to-red-700 transition flex items-center justify-center" id="first-half-out-btn">
                                    <span class="spinner hidden">
                                        <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                                        </svg>
                                    </span>
                                    <span>Check-out First Half</span>
                                </button>
                            </form>
                        @endif

                        @if ($attendance->first_half_late_by)
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Late by:</span>
                                <span class="font-semibold text-yellow-600">{{ $attendance->first_half_late_by }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <form action="{{ route('admin.attendance.store', ['staffId' => $staff->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="first_half_in">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-3 sm:py-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 transition flex items-center justify-center" id="first-half-in-btn">
                            <span class="spinner hidden">
                                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                                </svg>
                            </span>
                            <span>Check-in First Half</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Second Half -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden border border-gray-200 hover:shadow-md transform hover:-translate-y-1 transition duration-300">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-4 text-white">
                <h2 class="text-base sm:text-lg font-semibold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Second Half (3:00 PM - 7:00 PM)
                </h2>
            </div>
            <div class="p-4 sm:p-6">
                @if ($attendance && $attendance->second_half_in)
                    <div class="space-y-4 text-sm sm:text-base">
                        <div class="flex justify-between">
                            <span class="font-medium text-gray-600">Check-in:</span>
                            <span class="font-semibold text-green-600">{{ $attendance->second_half_in->format('h:i A') }}</span>
                        </div>

                        @if ($attendance->second_half_out)
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Check-out:</span>
                                <span class="font-semibold text-red-600">{{ $attendance->second_half_out->format('h:i A') }}</span>
                            </div>
                        @else
                            <form action="{{ route('admin.attendance.store', ['staffId' => $staff->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="second_half_out">
                                <button type="submit" class="w-full mt-4 bg-gradient-to-r from-red-500 to-red-600 text-white py-3 sm:py-4 rounded-lg font-medium hover:from-red-600 hover:to-red-700 transition flex items-center justify-center" id="second-half-out-btn">
                                    <span class="spinner hidden">
                                        <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                                        </svg>
                                    </span>
                                    <span>Check-out Second Half</span>
                                </button>
                            </form>
                        @endif

                        @if ($attendance->second_half_late_by)
                            <div class="flex justify-between">
                                <span class="font-medium text-gray-600">Late by:</span>
                                <span class="font-semibold text-yellow-600">{{ $attendance->second_half_late_by }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <form action="{{ route('admin.attendance.store', ['staffId' => $staff->id]) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="second_half_in">
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-3 sm:py-4 rounded-lg font-medium hover:from-purple-600 hover:to-purple-700 transition flex items-center justify-center" id="second-half-in-btn">
                            <span class="spinner hidden">
                                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8h8a8 8 0 01-8 8 8 8 0 01-8-8z"></path>
                                </svg>
                            </span>
                            <span>Check-in Second Half</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Live Clock -->
<script>
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Loading spinner for form submission
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function () {
            const button = this.querySelector('button[type="submit"]');
            const spinner = button.querySelector('.spinner');
            button.disabled = true;
            spinner.classList.remove('hidden');
        });
    });
</script>
@endsection
