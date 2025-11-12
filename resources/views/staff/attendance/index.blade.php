@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-4xl">
    <!-- Header Section -->
    <div class="text-center mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Attendance Dashboard</h1>
        <div class="flex items-center justify-center space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-base md:text-lg text-gray-600">Today is {{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <!-- Status Cards - Stack on mobile -->
        <!-- Today's Summary Card -->


        <!-- Current Time Card -->
<div class="bg-white rounded-lg md:rounded-xl shadow-sm md:shadow-md overflow-hidden p-4 md:p-6 border-l-4 border-purple-500 mb-[10px]">
            <div class="flex items-center mb-3 md:mb-4">
                <div class="p-2 md:p-3 rounded-full bg-purple-100 text-purple-600 mr-3 md:mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-lg md:text-xl font-semibold text-gray-800">Current Time</h2>
            </div>
            
            <div class="text-center py-2 md:py-4">
                <div id="live-clock" class="text-2xl md:text-3xl font-bold text-purple-600 mb-1 md:mb-2">00:00:00</div>
                <p class="text-xs md:text-sm text-gray-600">Office Hours: 9:00 AM - 1:00 PM & 3:00 PM - 7:00 PM</p>
            </div>
        </div>

    <!-- Flash Messages - Adjusted for mobile -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-3 md:p-4 mb-4 md:mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-4 w-4 md:h-5 md:w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-2 md:ml-3">
                    <p class="text-xs md:text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-3 md:p-4 mb-4 md:mb-6 rounded-r-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-4 w-4 md:h-5 md:w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-2 md:ml-3">
                    <p class="text-xs md:text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Attendance Actions - Stack on mobile -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
        <!-- First Half Card -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-sm md:shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-3 md:p-4 text-white">
                <h2 class="text-base md:text-lg font-semibold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    First Half (9:00 AM - 1:00 PM)
                </h2>
            </div>
            <div class="p-4 md:p-6">
                @if ($attendance && $attendance->first_half_in)
                    <div class="space-y-3 md:space-y-4 text-sm md:text-base">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Check-in:</span>
                            <span class="font-semibold text-green-600">{{ $attendance->first_half_in->format('h:i A') }}</span>
                        </div>
                        
                        @if ($attendance->first_half_out)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Check-out:</span>
                                <span class="font-semibold text-red-600">{{ $attendance->first_half_out->format('h:i A') }}</span>
                            </div>
                        @else
                            <form action="{{ route('staff.attendance.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="first_half_out">
                                <button type="submit" class="w-full mt-3 md:mt-4 bg-gradient-to-r from-red-500 to-red-600 text-white py-2 md:py-3 px-4 rounded-lg font-medium hover:from-red-600 hover:to-red-700 transition duration-300 flex items-center justify-center text-sm md:text-base">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                    </svg>
                                    Check-out First Half
                                </button>
                            </form>
                        @endif
                        
                        @if ($attendance->first_half_late_by)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Late by:</span>
                                <span class="font-semibold text-yellow-600">{{ $attendance->first_half_late_by }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <form action="{{ route('staff.attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="first_half_in">
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-2 md:py-3 px-4 rounded-lg font-medium hover:from-blue-600 hover:to-blue-700 transition duration-300 flex items-center justify-center text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l8-8m0 0l-8-8m8 8H4" />
                            </svg>
                            Check-in First Half
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Second Half Card -->
        <div class="bg-white rounded-lg md:rounded-xl shadow-sm md:shadow-md overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-3 md:p-4 text-white">
                <h2 class="text-base md:text-lg font-semibold flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                    Second Half (3:00 PM - 7:00 PM)
                </h2>
            </div>
            <div class="p-4 md:p-6">
                @if ($attendance && $attendance->second_half_in)
                    <div class="space-y-3 md:space-y-4 text-sm md:text-base">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 font-medium">Check-in:</span>
                            <span class="font-semibold text-green-600">{{ $attendance->second_half_in->format('h:i A') }}</span>
                        </div>
                        
                        @if ($attendance->second_half_out)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Check-out:</span>
                                <span class="font-semibold text-red-600">{{ $attendance->second_half_out->format('h:i A') }}</span>
                            </div>
                        @else
                            <form action="{{ route('staff.attendance.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="type" value="second_half_out">
                                <button type="submit" class="w-full mt-3 md:mt-4 bg-gradient-to-r from-red-500 to-red-600 text-white py-2 md:py-3 px-4 rounded-lg font-medium hover:from-red-600 hover:to-red-700 transition duration-300 flex items-center justify-center text-sm md:text-base">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                    </svg>
                                    Check-out Second Half
                                </button>
                            </form>
                        @endif
                        
                        @if ($attendance->second_half_late_by)
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-medium">Late by:</span>
                                <span class="font-semibold text-yellow-600">{{ $attendance->second_half_late_by }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <form action="{{ route('staff.attendance.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="second_half_in">
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-500 to-purple-600 text-white py-2 md:py-3 px-4 rounded-lg font-medium hover:from-purple-600 hover:to-purple-700 transition duration-300 flex items-center justify-center text-sm md:text-base">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l8-8m0 0l-8-8m8 8H4" />
                            </svg>
                            Check-in Second Half
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Actions - Stack on mobile -->
    <div class="flex flex-col space-y-2 sm:space-y-0 sm:flex-row justify-between items-center bg-white p-3 md:p-4 rounded-lg shadow-sm md:shadow-md border border-gray-200">
        <a href="{{ route('staff.attendance.history') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-3 py-2 border border-transparent text-xs md:text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4 mr-1 md:mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            View Attendance History
        </a>
        

    </div>
</div>

<!-- Live Clock Script -->
<script>
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    
    setInterval(updateClock, 1000);
    updateClock(); // Initialize immediately
</script>
@endsection