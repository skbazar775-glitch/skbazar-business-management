@extends('layouts.staff')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <div class="text-center mb-10">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Assigned Services</h1>
        <p class="text-sm sm:text-base text-gray-600">View and manage your assigned services</p>
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

    <!-- Assigned Services Table -->
    @if ($bookings->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No assigned services found</h3>
            <p class="mt-2 text-sm text-gray-500">You have no services assigned at the moment.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <!-- Desktop Table -->
                <table class="min-w-full divide-y divide-gray-200 hidden sm:table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 truncate max-w-[150px]" title="{{ $booking->service->name ?? 'N/A' }}">{{ $booking->service->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->service->category->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">${{ $booking->service->price ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 truncate max-w-[200px]" title="{{ $booking->address ? ($booking->address->area . ', ' . $booking->address->city . ', ' . $booking->address->district . ' ' . $booking->address->pin_code) : 'N/A' }}">
                                    {{ $booking->address ? ($booking->address->area . ', ' . $booking->address->city) : 'N/A' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->address->phone ?? 'N/A' }}</td>
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
                            </td>
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
                                <form action="{{ route('staff.assignedservice.status', $booking->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <select
                                        name="status"
                                        class="block w-full pl-3 pr-10 py-2 text-xs border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm text-black"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Staff Assigned</option>
                                        <option value="3" {{ $booking->status == 3 ? 'selected' : '' }}>Running Work</option>
                                        <option value="4" {{ $booking->status == 4 ? 'selected' : '' }}>Work Done</option>
                                        <option value="5" {{ $booking->status == 5 ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Mobile Card Layout -->
                <div class="sm:hidden space-y-4">
                    @foreach ($bookings as $booking)
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->service->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->service->category->title ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 text-sm">
                            <div>
                                <span class="font-medium text-gray-700">Price:</span>
                                <span class="text-blue-600 font-semibold">${{ $booking->service->price ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Customer:</span>
                                <span class="text-gray-900">{{ $booking->user->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Address:</span>
                                <span class="text-gray-500" title="{{ $booking->address ? ($booking->address->area . ', ' . $booking->address->city . ', ' . $booking->address->district . ' ' . $booking->address->pin_code) : 'N/A' }}">
                                    {{ $booking->address ? ($booking->address->area . ', ' . $booking->address->city) : 'N/A' }}
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Phone:</span>
                                <span class="text-gray-500">{{ $booking->address->phone ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Date & Time:</span>
                                <span class="text-gray-900 flex items-center">
                                    <svg class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $booking->date->format('M d, Y') }}
                                    <span class="ml-2 flex items-center">
                                        <svg class="h-4 w-4 text-gray-400 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $booking->date->format('h:i A') }}
                                    </span>
                                </span>
                            </div>
                            <div>
                                <span class="font-medium text-gray-700">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if ($booking->status == 0) bg-yellow-100 text-yellow-800 
                                    @elseif ($booking->status == 1) bg-blue-100 text-blue-800 
                                    @elseif ($booking->status == 2) bg-indigo-100 text-indigo-800 
                                    @elseif ($booking->status == 3) bg-purple-100 text-purple-800 
                                    @elseif ($booking->status == 4) bg-green-100 text-green-800 
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ $booking->getStatusTextAttribute() }}
                                </span>
                            </div>
                            <div>
                                <form action="{{ route('staff.assignedservice.status', $booking->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <select
                                        name="status"
                                        class="block w-full pl-3 pr-10 py-2 text-xs border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 rounded-md shadow-sm text-black"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="2" {{ $booking->status == 2 ? 'selected' : '' }}>Staff Assigned</option>
                                        <option value="3" {{ $booking->status == 3 ? 'selected' : '' }}>Running Work</option>
                                        <option value="4" {{ $booking->status == 4 ? 'selected' : '' }}>Work Done</option>
                                        <option value="5" {{ $booking->status == 5 ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    @media (max-width: 640px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .text-3xl {
            font-size: 1.5rem;
        }
        .text-sm {
            font-size: 0.875rem;
        }
        .text-xs {
            font-size: 0.75rem;
        }
        .p-8 {
            padding: 1.5rem;
        }
        .max-w-7xl {
            max-width: 100%;
        }
    }
</style>
@endsection