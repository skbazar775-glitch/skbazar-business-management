@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6 text-black">Salary Calculation for {{ $staff->name }}</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-black" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 text-black" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg p-6">
            <!-- Staff Details -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 text-black">Staff Details</h2>
                <p class="text-black"><strong>Name:</strong> {{ $staff->name }}</p>
                <p class="text-black"><strong>Email:</strong> {{ $staff->email }}</p>
                <p class="text-black"><strong>Per Day Salary:</strong> ${{ number_format($staff->salary, 2) }} (for both shifts attended)</p>
                <p class="text-black"><strong>Per Shift Salary:</strong> ${{ number_format($staff->salary / 2, 2) }} (first or second shift)</p>
            </div>

            <!-- Monthly Working Hours Summary -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 text-black">Monthly Working Hours Summary</h2>
                @if(empty($monthlyHours))
                    <p class="text-black">No attendance records found for this staff.</p>
                @else
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b text-left text-black">Month</th>
                                <th class="py-2 px-4 border-b text-left text-black">Total Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyHours as $month => $hours)
                                <tr>
                                    <td class="py-2 px-4 border-b text-black">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                    <td class="py-2 px-4 border-b text-black">{{ number_format($hours, 2) }}h</td>
                                </tr>
                            @endforeach
                            <tr class="font-semibold">
                                <td class="py-2 px-4 border-b text-black">Total Hours</td>
                                <td class="py-2 px-4 border-b text-black">{{ number_format(array_sum($monthlyHours), 2) }}h</td>
                            </tr>
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Date-wise Working Hours History -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold mb-4 text-black">Date-wise Working Hours History</h2>
                <div class="mb-4">
                    <a href="{{ route('admin.viewattendance.pay_slip', ['staffId' => $staff->id, 'month_filter' => $monthFilter]) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded">
                        View Pay Slip
                    </a>
                </div>
                @if(empty($dailyRecords))
                    <p class="text-black">No daily attendance records found for this staff.</p>
                @else
                    <form action="{{ route('admin.viewattendance.store_bonuses', $staff->id) }}" method="POST">
                        @csrf
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 border-b text-left text-black">Date</th>
                                    <th class="py-2 px-4 border-b text-left text-black">First Half Hours</th>
                                    <th class="py-2 px-4 border-b text-left text-black">Second Half Hours</th>
                                    <th class="py-2 px-4 border-b text-left text-black">Hours Worked</th>
                                    <th class="py-2 px-4 border-b text-left text-black">Daily Salary</th>
                                    <th class="py-2 px-4 border-b text-left text-black">Bonus ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $perShiftSalary = $staff->salary / 2;
                                    $totalSalary = 0;
                                    $totalBonus = 0;
                                @endphp
                                @foreach($dailyRecords as $index => $record)
                                    @php
                                        $hoursWorked = $record['total_hours'];
                                        $dailySalary = $record['daily_salary'];
                                        $bonus = floatval($record['bonus']);
                                        $totalSalary += $dailySalary;
                                        $totalBonus += $bonus;
                                        $hours = floor($hoursWorked);
                                        $minutes = round(($hoursWorked - $hours) * 60);
                                    @endphp
                                    <tr>
                                        <td class="py-2 px-4 border-b text-black">{{ $record['date'] }}</td>
                                        <td class="py-2 px-4 border-b text-black">{{ $record['first_half_hours'] > 0 ? number_format($record['first_half_hours'], 2) . 'h' : 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b text-black">{{ $record['second_half_hours'] > 0 ? number_format($record['second_half_hours'], 2) . 'h' : 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b text-black">{{ $hoursWorked > 0 ? $hours . 'h ' . $minutes . 'm' : 'N/A' }}</td>
                                        <td class="py-2 px-4 border-b text-black">${{ number_format($dailySalary, 2) }}</td>
                                        <td class="py-2 px-4 border-b text-black">
                                            <input type="number" name="bonuses[{{ $index }}][amount]" value="{{ number_format($bonus, 2) }}" min="0" step="0.01" class="border border-gray-300 rounded px-2 py-1 text-black w-24">
                                            <input type="hidden" name="bonuses[{{ $index }}][date]" value="{{ $record['date'] }}">
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="font-semibold">
                                    <td class="py-2 px-4 border-b text-black" colspan="3">Total</td>
                                    <td class="py-2 px-4 border-b text-black">{{ number_format(array_sum(array_column($dailyRecords, 'total_hours')), 2) }}h</td>
                                    <td class="py-2 px-4 border-b text-black">${{ number_format($totalSalary, 2) }}</td>
                                    <td class="py-2 px-4 border-b text-black">${{ number_format($totalBonus, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded">Save Bonuses</button>
                        </div>
                    </form>
                    <div class="mt-4 text-black">
                        <span>Base Salary: ${{ number_format($totalSalary, 2) }}</span><br>
                        <span>Total Bonus: ${{ number_format($totalBonus, 2) }}</span><br>
                        <span>Total Salary: ${{ number_format($totalSalary + $totalBonus, 2) }}</span>
                    </div>
                @endif
            </div>

            <!-- Back Button -->
            {{-- <div class="mt-6">
                <a href="{{ route('admin.viewattendance.show', $staff->id) }}" class="inline-block bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded">
                    Back to Attendance Details
                </a>
            </div> --}}
        </div>
    </div>
@endsection