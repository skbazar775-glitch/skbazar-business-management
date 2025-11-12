@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Booked Services</h1>
        <p class="text-gray-600">Manage all booked services and update their statuses</p>
    </div>

    <!-- Status Messages -->
    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Bookings Table -->
    @if ($bookings->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No bookings found</h3>
            <p class="mt-2 text-sm text-gray-500">There are no booked services at the moment.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Info</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- Service Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->service->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->service->category->title ?? 'N/A' }}</div>
                                        <div class="text-sm font-semibold text-blue-600">${{ $booking->service->price ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Customer Info -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->address->phone ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400 mt-1 truncate max-w-[200px]" title="{{ $booking->address ? ($booking->address->area . ', ' . $booking->address->city . ', ' . $booking->address->district . ' ' . $booking->address->pin_code) : 'N/A' }}">
                                    {{ $booking->address ? ($booking->address->area . ', ' . $booking->address->city) : 'N/A' }}
                                </div>
                            </td>

                            <!-- Booking Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    <svg class="inline-block h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $booking->date->format('M d, Y') }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    <svg class="inline-block h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $booking->date->format('h:i A') }}
                                </div>
                                <div class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ $booking->staff ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $booking->staff ? $booking->staff->name : 'No staff assigned' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if ($booking->status == 0) bg-yellow-100 text-yellow-800 
                                    @elseif ($booking->status == 1) bg-blue-100 text-blue-800 
                                    @elseif ($booking->status == 2) bg-indigo-100 text-indigo-800 
                                    @elseif ($booking->status == 3) bg-purple-100 text-purple-800 
                                    @elseif ($booking->status == 4) bg-green-100 text-green-800 
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $booking->getStatusTextAttribute() }}
                                </span>
                                
                                <form action="{{ route('admin.bookedservices.admin.bookings.status', $booking->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <select
                                        name="status"
                                        class="block w-full pl-3 pr-10 py-2 text-xs border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm text-black"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="0" {{ $booking->status == 0 ? 'selected' : '' }}>Pending</option>
                                        <option value="1" {{ $booking->status == 1 ? 'selected' : '' }}>Confirmed</option>
                                        <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Staff Assigned</option>
                                        <option value="3" {{ $booking->status == 3 ? 'selected' : '' }}>Running Work</option>
                                        <option value="4" {{ $booking->status == 4 ? 'selected' : '' }}>Work Done</option>
                                        <option value="5" {{ $booking->status == 5 ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </form>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if (!$booking->staff_id)
                                    <form action="{{ route('admin.bookedservices.assign-staff', $booking->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select
                                            name="staff_id"
                                            class="block w-full pl-3 pr-10 py-2 text-xs border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm text-black"
                                            onchange="this.form.submit()"
                                        >
                                            <option value="">Assign Staff</option>
                                            @foreach ($staffMembers as $staff)
                                                <option value="{{ $staff->id }}" {{ $booking->staff_id == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-900">{{ $booking->staff->name }}</span>
                                    <form action="{{ route('admin.bookedservices.assign-staff', $booking->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        @method('PUT')
                                        <select
                                            name="staff_id"
                                            class="block w-full pl-3 pr-10 py-2 text-xs border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm text-black"
                                            onchange="if(confirm('Are you sure you want to change the assigned staff for this booking?')) this.form.submit()"
                                        >
                                            <option value="">Change Staff</option>
                                            @foreach ($staffMembers as $staff)
                                                <option value="{{ $staff->id }}" {{ $booking->staff_id == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection