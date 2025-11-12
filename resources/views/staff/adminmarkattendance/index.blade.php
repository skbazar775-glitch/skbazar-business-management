@extends('layouts.admin')

@section('content')
<style>
    /* 3D Flip for Cards */
    .flip-card {
        perspective: 1000px;
    }
    .flip-card-inner {
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }
    .flip-card:hover .flip-card-inner {
        transform: rotateY(180deg);
    }
    .flip-card-front, .flip-card-back {
        backface-visibility: hidden;
        position: absolute;
        width: 100%;
        height: 100%;
    }
    .flip-card-back {
        transform: rotateY(180deg);
    }

    /* Modal Slide-In */
    .modal-enter {
        transform: translateY(100%);
        opacity: 0;
    }
    .modal-enter-active {
        transition: all 0.3s ease-out;
        transform: translateY(0);
        opacity: 1;
    }

    /* Flash Message Slide-In */
    .flash-message {
        animation: slideIn 0.5s ease-out;
    }
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    /* Button Ripple Effect */
    .ripple {
        position: relative;
        overflow: hidden;
    }
    .ripple::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 50%;
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }
    .ripple:active::after {
        width: 200px;
        height: 200px;
    }
</style>

<div class="container mx-auto p-4 sm:p-6 max-w-7xl" x-data="{ 
    openModal: false, 
    selectedStaff: null, 
    selectedType: null, 
    confirmAction: () => {} 
}">
    <!-- Header -->
    <div class="text-center mb-8 relative">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-black">Admin Attendance Dashboard</h1>
        <div class="w-24 h-1 bg-gradient-to-r from-gray-300 to-gray-500 mx-auto mt-2 rounded"></div>
        <div class="flex items-center justify-center space-x-2 mt-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-lg text-black">Today is {{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    <!-- Live Clock -->
    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-black mb-8 hover:shadow-2xl transition duration-300 animate-pulse animate-once animate-duration-1000">
        <div class="flex items-center mb-4">
            <div class="p-3 rounded-full bg-gray-100 text-black mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-black">Current Time</h2>
        </div>
        <div class="text-center py-4">
            <div id="live-clock" class="text-4xl font-bold text-black mb-2">00:00:00</div>
            <p class="text-lg text-black">Office Hours: 9:00 AM - 1:00 PM & 3:00 PM - 7:00 PM</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="bg-gray-100 border-l-4 border-black p-4 mb-8 rounded-r-lg shadow-md flash-message">
            <p class="text-lg text-black">{{ session('success') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-gray-100 border-l-4 border-black p-4 mb-8 rounded-r-lg shadow-md flash-message">
            <p class="text-lg text-black">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Staff Grid -->
    <h2 class="text-2xl font-bold mb-6 text-black">Staff List</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($staffs as $staff)
            <div class="flip-card relative bg-white rounded-xl shadow-lg border border-gray-200 hover:shadow-2xl transition duration-300 h-64">
                <div class="flip-card-inner relative w-full h-full">
                    <!-- Front of Card -->
                    <div class="flip-card-front bg-white rounded-xl p-6 flex flex-col space-y-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-black text-lg">Name:</span>
                            <span class="text-black text-lg">{{ $staff->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-black text-lg">Email:</span>
                            <span class="text-black text-lg">{{ $staff->email }}</span>
                        </div>
                    </div>
                    <!-- Back of Card -->
                    <div class="flip-card-back bg-white rounded-xl p-6 flex flex-col space-y-3">
                        @if(isset($attendances[$staff->id]))
                            @if($attendances[$staff->id]->first_half_in)
                                <div class="flex justify-between">
                                    <span class="font-medium text-black">First Half In:</span>
                                    <span class="font-semibold text-black">{{ $attendances[$staff->id]->first_half_in->format('h:i A') }}</span>
                                </div>
                            @endif
                            @if($attendances[$staff->id]->first_half_out)
                                <div class="flex justify-between">
                                    <span class="font-medium text-black">First Half Out:</span>
                                    <span class="font-semibold text-black">{{ $attendances[$staff->id]->first_half_out->format('h:i A') }}</span>
                                </div>
                            @endif
                            @if($attendances[$staff->id]->second_half_in)
                                <div class="flex justify-between">
                                    <span class="font-medium text-black">Second Half In:</span>
                                    <span class="font-semibold text-black">{{ $attendances[$staff->id]->second_half_in->format('h:i A') }}</span>
                                </div>
                            @endif
                            @if($attendances[$staff->id]->second_half_out)
                                <div class="flex justify-between">
                                    <span class="font-medium text-black">Second Half Out:</span>
                                    <span class="font-semibold text-black">{{ $attendances[$staff->id]->second_half_out->format('h:i A') }}</span>
                                </div>
                            @endif
                        @else
                            <p class="text-black text-center">No attendance recorded</p>
                        @endif
                    </div>
                </div>
                <!-- Attendance Buttons (Always Visible) -->
                <div class="absolute bottom-0 left-0 right-0 p-4 bg-white rounded-b-xl">
                    <div class="grid grid-cols-2 gap-2">
                        <button 
                            class="bg-gray-200 text-black py-2 px-4 rounded-lg hover:scale-105 transition transform ripple disabled:opacity-50 disabled:cursor-not-allowed" 
                            @click="openModal = true; selectedStaff = '{{ $staff->name }}'; selectedType = 'first_half_in'; confirmAction = () => document.getElementById('form-first-half-in-{{ $staff->id }}').submit()"
                            :disabled="@json(isset($attendances[$staff->id]) && $attendances[$staff->id]->first_half_in)">
                            Check-in First
                        </button>
                        <button 
                            class="bg-gray-200 text-black py-2 px-4 rounded-lg hover:scale-105 transition transform ripple disabled:opacity-50 disabled:cursor-not-allowed" 
                            @click="openModal = true; selectedStaff = '{{ $staff->name }}'; selectedType = 'first_half_out'; confirmAction = () => document.getElementById('form-first-half-out-{{ $staff->id }}').submit()"
                            :disabled="@json(!isset($attendances[$staff->id]) || !$attendances[$staff->id]->first_half_in || $attendances[$staff->id]->first_half_out)">
                            Check-out First
                        </button>
                        <button 
                            class="bg-gray-200 text-black py-2 px-4 rounded-lg hover:scale-105 transition transform ripple disabled:opacity-50 disabled:cursor-not-allowed" 
                            @click="openModal = true; selectedStaff = '{{ $staff->name }}'; selectedType = 'second_half_in'; confirmAction = () => document.getElementById('form-second-half-in-{{ $staff->id }}').submit()"
                            :disabled="@json(isset($attendances[$staff->id]) && $attendances[$staff->id]->second_half_in)">
                            Check-in Second
                        </button>
                        <button 
                            class="bg-gray-200 text-black py-2 px-4 rounded-lg hover:scale-105 transition transform ripple disabled:opacity-50 disabled:cursor-not-allowed" 
                            @click="openModal = true; selectedStaff = '{{ $staff->name }}'; selectedType = 'second_half_out'; confirmAction = () => document.getElementById('form-second-half-out-{{ $staff->id }}').submit()"
                            :disabled="@json(!isset($attendances[$staff->id]) || !$attendances[$staff->id]->second_half_in || $attendances[$staff->id]->second_half_out)">
                            Check-out Second
                        </button>
                    </div>
                </div>

                <!-- Hidden Forms -->
                <form id="form-first-half-in-{{ $staff->id }}" action="{{ route('admin.attendance.store', $staff->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="first_half_in">
                </form>
                <form id="form-first-half-out-{{ $staff->id }}" action="{{ route('admin.attendance.store', $staff->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="first_half_out">
                </form>
                <form id="form-second-half-in-{{ $staff->id }}" action="{{ route('admin.attendance.store', $staff->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="second_half_in">
                </form>
                <form id="form-second-half-out-{{ $staff->id }}" action="{{ route('admin.attendance.store', $staff->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="second_half_out">
                </form>
            </div>
        @endforeach
    </div>

    <!-- Modal -->
    <div x-show="openModal" 
         x-transition:enter="modal-enter" 
         x-transition:enter-start="modal-enter" 
         x-transition:enter-end="modal-enter-active"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white bg-opacity-90 backdrop-blur-md rounded-xl p-8 max-w-md w-full shadow-2xl">
            <h3 class="text-xl font-semibold text-black mb-4">Confirm Attendance</h3>
            <p class="mb-6 text-black text-lg">Mark <span x-text="selectedStaff"></span>'s <span x-text="selectedType.replace('_', ' ').toUpperCase()"></span> at {{ now()->format('h:i A') }}?</p>
            <div class="flex justify-end space-x-3">
                <button class="bg-gray-200 text-black py-2 px-6 rounded-lg hover:scale-105 transition transform ripple" @click="openModal = false">Cancel</button>
                <button class="bg-gray-200 text-black py-2 px-6 rounded-lg hover:scale-105 transition transform ripple" @click="confirmAction(); openModal = false">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    // ✅ Live clock
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        document.getElementById('live-clock').textContent = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>
@endsection