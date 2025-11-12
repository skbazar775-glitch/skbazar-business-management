@extends('layouts.staff')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4 text-center">Attendance History</h1>

    <!-- Filter Form -->
    <div class="mb-6 bg-white p-4 rounded-lg shadow-md">
        <form method="GET" action="{{ route('staff.attendance.history') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1 min-w-0">
                <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                <select name="month" id="month" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2">
                    <option value="">All Months</option>
                    @foreach ($months as $value => $name)
                        <option value="{{ $value }}" {{ request('month') == $value ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-0">
                <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                <select name="year" id="year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2">
                    <option value="">All Years</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end sm:self-end">
                <button type="submit" class="w-full sm:w-auto bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Half</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Second Half</th>
                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($attendances as $attendance)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                            {{ $attendance->attendance_date->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            @if ($attendance->first_half_in)
                                <div class="flex flex-col space-y-1">
                                    <span>In: {{ $attendance->first_half_in->format('h:i A') }}</span>
                                    @if ($attendance->first_half_out)
                                        <span>Out: {{ $attendance->first_half_out->format('h:i A') }}</span>
                                    @endif
                                    <span class="capitalize">
                                        {{ $attendance->first_half_status }}
                                        @if ($attendance->first_half_late_by)
                                            <span class="text-xs text-gray-500">(Late by {{ $attendance->first_half_late_by }})</span>
                                        @endif
                                    </span>
                                </div>
                            @else
                                <span class="text-red-500">Absent</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            @if ($attendance->second_half_in)
                                <div class="flex flex-col space-y-1">
                                    <span>In: {{ $attendance->second_half_in->format('h:i A') }}</span>
                                    @if ($attendance->second_half_out)
                                        <span>Out: {{ $attendance->second_half_out->format('h:i A') }}</span>
                                    @endif
                                    <span class="capitalize">
                                        {{ $attendance->second_half_status }}
                                        @if ($attendance->second_half_late_by)
                                            <span class="text-xs text-gray-500">(Late by {{ $attendance->second_half_late_by }})</span>
                                        @endif
                                    </span>
                                </div>
                            @else
                                <span class="text-red-500">Absent</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ $attendance->remarks ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-sm text-gray-500">
                            No records found for the selected period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-4 sm:px-0">
        {{ $attendances->appends(request()->query())->links() }}
    </div>
</div>
@endsection