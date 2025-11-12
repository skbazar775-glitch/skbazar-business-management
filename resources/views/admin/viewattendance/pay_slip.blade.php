@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8 bg-gray-50 min-h-screen print:bg-white print:p-4 pdf:bg-white pdf:p-4">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-5xl mx-auto print:shadow-none print:p-4 print:max-w-full print:rounded-none pdf:shadow-none pdf:p-4 pdf:max-w-full pdf:rounded-none">
            <h1 class="text-3xl font-bold mb-8 text-indigo-600 text-center print:text-gray-800 print:text-2xl pdf:text-gray-800 pdf:text-2xl">
                Pay Slip for {{ $staff->name }}
            </h1>

            <!-- Calculate totals once -->
            @php
                $totalSalary = 0;
                $totalBonus = 0;
                $totalShifts = 0;
                $totalHours = 0;
                foreach ($dailyRecords as $record) {
                    $dailySalary = floatval($record['daily_salary']);
                    $bonus = floatval($record['bonus']);
                    $hoursWorked = floatval($record['total_hours']);
                    $totalSalary += $dailySalary;
                    $totalBonus += $bonus;
                    $totalHours += $hoursWorked;
                    if ($record['first_half_hours'] > 0) $totalShifts++;
                    if ($record['second_half_hours'] > 0) $totalShifts++;
                }
            @endphp

            <!-- Staff Details -->
            <div class="mb-8 print:mb-4 pdf:mb-4">
                <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center print:text-lg print:mb-2 pdf:text-lg pdf:mb-2">
                    <span class="material-icons mr-2 text-indigo-600 print:text-gray-800 pdf:text-gray-800">person</span> Staff Details
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-100 p-6 rounded-lg print:bg-white print:p-4 print:rounded-none print:grid-cols-2 print:gap-2 pdf:bg-white pdf:p-4 pdf:rounded-none pdf:grid-cols-2 pdf:gap-2">
                    <p class="text-gray-700 text-base print:text-sm pdf:text-sm"><strong class="text-indigo-700 print:text-gray-800 pdf:text-gray-800">Name:</strong> {{ $staff->name }}</p>
                    <p class="text-gray-700 text-base print:text-sm pdf:text-sm"><strong class="text-indigo-700 print:text-gray-800 pdf:text-gray-800">Email:</strong> {{ $staff->email }}</p>
                    <p class="text-gray-700 text-base print:text-sm pdf:text-sm"><strong class="text-indigo-700 print:text-gray-800 pdf:text-gray-800">Per Day Salary:</strong> ${{ number_format($staff->salary, 2) }} (both shifts)</p>
                    <p class="text-gray-700 text-base print:text-sm pdf:text-sm"><strong class="text-indigo-700 print:text-gray-800 pdf:text-gray-800">Per Shift Salary:</strong> ${{ number_format($staff->salary / 2, 2) }} (single shift)</p>
                    @if($monthFilter)
                        <p class="text-gray-700 text-base print:text-sm pdf:text-sm"><strong class="text-indigo-700 print:text-gray-800 pdf:text-gray-800">Period:</strong> {{ \Carbon\Carbon::parse($monthFilter)->format('F Y') }}</p>
                    @else
                        <p class="text-gray-700 text-base print:text-sm pdf:text-sm"><strong class="text-indigo-700 print:text-gray-800 pdf:text-gray-800">Period:</strong> All Records</p>
                    @endif
                </div>
            </div>

            <!-- Monthly Working Hours Summary -->
            <div class="mb-8 print:mb-4 pdf:mb-4">
                <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center print:text-lg print:mb-2 pdf:text-lg pdf:mb-2">
                    <span class="material-icons mr-2 text-indigo-600 print:text-gray-800 pdf:text-gray-800">schedule</span> Monthly Working Hours Summary
                </h2>
                @if(empty($monthlyHours))
                    <p class="text-gray-600 text-center text-base italic print:text-sm print:text-gray-800 pdf:text-sm pdf:text-gray-800">No attendance records found for this staff.</p>
                @else
                    <div class="overflow-x-auto print:overflow-visible pdf:overflow-visible">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg print:border-gray-400 print:shadow-none print:rounded-none pdf:border-gray-400 pdf:shadow-none pdf:rounded-none">
                            <thead>
                                <tr class="bg-gray-100 text-gray-800 print:bg-gray-200 pdf:bg-gray-200">
                                    <th class="py-3 px-4 text-left font-semibold text-base print:text-sm print:py-2 print:px-2 pdf:text-sm pdf:py-2 pdf:px-2">Month</th>
                                    <th class="py-3 px-4 text-left font-semibold text-base print:text-sm print:py-2 print:px-2 pdf:text-sm pdf:py-2 pdf:px-2">Total Hours</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyHours as $month => $hours)
                                    <tr class="border-b print:border-gray-400 pdf:border-gray-400">
                                        <td class="py-3 px-4 text-gray-700 text-base print:text-sm print:py-2 print:px-2 pdf:text-sm pdf:py-2 pdf:px-2">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                        <td class="py-3 px-4 text-gray-700 text-base print:text-sm print:py-2 print:px-2 pdf:text-sm pdf:py-2 pdf:px-2">{{ number_format($hours, 2) }}h</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-50 print:bg-gray-100 pdf:bg-gray-100">
                                    <td class="py-3 px-4 text-indigo-700 text-base print:text-sm print:py-2 print:px-2 print:text-gray-800 pdf:text-sm pdf:py-2 pdf:px-2 pdf:text-gray-800">Total Hours</td>
                                    <td class="py-3 px-4 text-indigo-700 text-base print:text-sm print:py-2 print:px-2 print:text-gray-800 pdf:text-sm pdf:py-2 pdf:px-2 pdf:text-gray-800">{{ number_format(array_sum($monthlyHours), 2) }}h</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Date-wise Salary Details (Screen Only) -->
            <div class="mb-8 print:hidden pdf:hidden">
                <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-indigo-600">attach_money</span> Date-wise Salary Details
                </h2>
                @if(empty($dailyRecords))
                    <p class="text-gray-600 text-center text-base italic">No salary records found for this staff.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                            <thead>
                                <tr class="bg-gray-100 text-gray-800">
                                    <th class="py-3 px-4 text-left font-semibold text-base">Date</th>
                                    <th class="py-3 px-4 text-left font-semibold text-base">First Half Hours</th>
                                    <th class="py-3 px-4 text-left font-semibold text-base">Second Half Hours</th>
                                    <th class="py-3 px-4 text-left font-semibold text-base">Hours Worked</th>
                                    <th class="py-3 px-4 text-left font-semibold text-base">Daily Salary</th>
                                    <th class="py-3 px-4 text-left font-semibold text-base">Bonus ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyRecords as $record)
                                    @php
                                        $hoursWorked = floatval($record['total_hours']);
                                        $hours = floor($hoursWorked);
                                        $minutes = round(($hoursWorked - $hours) * 60);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-3 px-4 border-b text-gray-700 text-base">{{ $record['date'] }}</td>
                                        <td class="py-3 px-4 border-b text-gray-700 text-base">{{ $record['first_half_hours'] > 0 ? number_format($record['first_half_hours'], 2) . 'h' : 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b text-gray-700 text-base">{{ $record['second_half_hours'] > 0 ? number_format($record['second_half_hours'], 2) . 'h' : 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b text-gray-700 text-base">{{ $hoursWorked > 0 ? $hours . 'h ' . $minutes . 'm' : 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b text-gray-700 text-base">${{ number_format($record['daily_salary'], 2) }}</td>
                                        <td class="py-3 px-4 border-b text-gray-700 text-base">${{ number_format($record['bonus'], 2) }}</td>
                                    </tr>
                                @endforeach
                                <tr class="font-bold bg-gray-50">
                                    <td class="py-3 px-4 border-b text-indigo-700 text-base" colspan="3">Total</td>
                                    <td class="py-3 px-4 border-b text-indigo-700 text-base">{{ number_format($totalHours, 2) }}h</td>
                                    <td class="py-3 px-4 border-b text-indigo-700 text-base">${{ number_format($totalSalary, 2) }}</td>
                                    <td class="py-3 px-4 border-b text-indigo-700 text-base">${{ number_format($totalBonus, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                        <p class="text-base text-gray-700"><strong class="text-indigo-700">Base Salary:</strong> ${{ number_format($totalSalary, 2) }}</p>
                        <p class="text-base text-gray-700"><strong class="text-indigo-700">Total Bonus:</strong> ${{ number_format($totalBonus, 2) }}</p>
                        <p class="text-lg font-bold text-gray-800"><strong class="text-indigo-700">Total Salary:</strong> ${{ number_format($totalSalary + $totalBonus, 2) }}</p>
                    </div>
                @endif
            </div>

            <!-- Salary Summary (Print and PDF Only) -->
            <div class="hidden print:block print:mb-4 pdf:block pdf:mb-4">
                <h2 class="text-lg font-bold mb-2 text-gray-800 flex items-center">
                    <span class="material-icons mr-2 text-gray-800">attach_money</span> Salary Summary
                </h2>
                <div class="p-4 bg-gray-100 rounded-lg print:rounded-none pdf:rounded-none">
                    @if(empty($dailyRecords))
                        <p class="text-sm text-gray-800 italic">No salary records found for this staff.</p>
                    @else
                        <p class="text-sm text-gray-800"><strong>Total Shifts Worked:</strong> {{ $totalShifts }}</p>
                        <p class="text-sm text-gray-800"><strong>Total Hours:</strong> {{ number_format($totalHours, 2) }}h</p>
                        <p class="text-sm text-gray-800"><strong>Base Salary:</strong> ${{ number_format($totalSalary, 2) }}</p>
                        <p class="text-sm text-gray-800"><strong>Total Bonus:</strong> ${{ number_format($totalBonus, 2) }}</p>
                        <p class="text-base font-bold text-gray-800"><strong>Total Salary:</strong> ${{ number_format($totalSalary + $totalBonus, 2) }}</p>
                    @endif
                </div>
            </div>

            <!-- Buttons (Screen Only) -->
            <div class="mt-8 flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-6 print:hidden pdf:hidden">
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition-all">
                    Print Pay Slip
                </button>
                <a href="{{ route('admin.viewattendance.pay_slip', ['staffId' => $staff->id, 'month_filter' => $monthFilter ?? null, 'format' => 'pdf']) }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition-all">
                    Download PDF
                </a>
                <a href="{{ route('admin.viewattendance.calculate_salary', ['staffId' => $staff->id, 'month_filter' => $monthFilter ?? null]) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-8 rounded-full shadow-lg hover:scale-105 transition-all">
                    Back to Salary Calculation
                </a>
            </div>
        </div>

    <!-- Include Material Icons and Roboto Font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        /* Fallback for PDF: Remove gradients */
        .bg-gradient-to-r {
            background: #e0e7ff !important; /* Light indigo fallback */
        }
        @media print, (format: pdf) {
            @page {
                margin: 0.5cm;
            }
            body {
                margin: 0;
                padding: 0;
                font-size: 10pt;
            }
            .container {
                width: 100%;
                max-width: 100%;
            }
            table {
                page-break-inside: auto;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            thead {
                display: table-header-group;
            }
            /* Ensure content fits */
            .container > div {
                max-width: 100%;
            }
        }
        /* Custom class for PDF */
        .pdf\: {
            @apply property;
        }
    </style>
@endsection