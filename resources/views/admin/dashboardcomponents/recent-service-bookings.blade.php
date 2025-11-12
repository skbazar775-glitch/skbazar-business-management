<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100">
        <div class="flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-800">Recent Service Bookings</h2>
            <a href="{{ route('admin.bookedservices.index') }}" class="text-sm text-blue-500 font-medium">View All</a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Staff</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @foreach ($recentServiceBookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">SRV-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->service->name ?? 'Unknown' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->staff->name ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if ($booking->statusText == 'Pending')
                                    bg-yellow-100 text-yellow-800
                                @elseif ($booking->statusText == 'Confirmed')
                                    bg-blue-100 text-blue-800
                                @elseif ($booking->statusText == 'Staff Assigned')
                                    bg-purple-100 text-purple-800
                                @elseif ($booking->statusText == 'Running Work')
                                    bg-orange-100 text-orange-800
                                @elseif ($booking->statusText == 'Work Done')
                                    bg-green-100 text-green-800
                                @elseif ($booking->statusText == 'Canceled')
                                    bg-red-100 text-red-800
                                @else
                                    bg-gray-100 text-gray-800
                                @endif">
                                {{ $booking->statusText }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>