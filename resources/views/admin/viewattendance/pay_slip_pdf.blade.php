<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pay Slip for {{ $staff->name }}</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0.5cm;
            font-size: 10pt;
            color: #1f2937;
        }
        .container {
            width: 100%;
            max-width: 100%;
            background: #ffffff;
            padding: 1cm;
        }
        h1 {
            font-size: 20pt;
            color: #1f2937;
            text-align: center;
            margin-bottom: 1cm;
        }
        h2 {
            font-size: 14pt;
            color: #1f2937;
            margin-bottom: 0.5cm;
            display: flex;
            align-items: center;
        }
        h2 .material-icons {
            margin-right: 0.5cm;
            color: #1f2937;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5cm;
            background: #f9fafb;
            padding: 0.75cm;
            margin-bottom: 1cm;
        }
        .grid p {
            font-size: 10pt;
            color: #374151;
            margin: 0;
        }
        .grid p strong {
            color: #1f2937;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1cm;
            page-break-inside: auto;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 0.5cm;
            text-align: left;
            font-size: 10pt;
        }
        th {
            background: #e5e7eb;
            font-weight: 600;
            color: #1f2937;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        .font-bold {
            font-weight: 700;
        }
        .bg-gray-100 {
            background: #f3f4f6;
        }
        .text-gray-600 {
            color: #4b5563;
        }
        .text-indigo-700 {
            color: #1f2937;
        }
        .italic {
            font-style: italic;
        }
        @page {
            margin: 0.5cm;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Pay Slip for {{ $staff->name }}</h1>

        <!-- Calculate totals -->
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
        <div>
            <h2><span class="material-icons">person</span> Staff Details</h2>
            <div class="grid">
                <p><strong>Name:</strong> {{ $staff->name }}</p>
                <p><strong>Email:</strong> {{ $staff->email }}</p>
                <p><strong>Per Day Salary:</strong> ${{ number_format($staff->salary, 2) }} (both shifts)</p>
                <p><strong>Per Shift Salary:</strong> ${{ number_format($staff->salary / 2, 2) }} (single shift)</p>
                @if($monthFilter)
                    <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($monthFilter)->format('F Y') }}</p>
                @else
                    <p><strong>Period:</strong> All Records</p>
                @endif
            </div>
        </div>

        <!-- Monthly Working Hours Summary -->
        <div>
            <h2><span class="material-icons">schedule</span> Monthly Working Hours Summary</h2>
            @if(empty($monthlyHours))
                <p class="text-gray-600 text-center italic">No attendance records found for this staff.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Hours</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monthlyHours as $month => $hours)
                            <tr>
                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</td>
                                <td>{{ number_format($hours, 2) }}h</td>
                            </tr>
                        @endforeach
                        <tr class="font-bold bg-gray-100">
                            <td>Total Hours</td>
                            <td>{{ number_format(array_sum($monthlyHours), 2) }}h</td>
                        </tr>
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Salary Summary -->
        <div>
            <h2><span class="material-icons">attach_money</span> Salary Summary</h2>
            <div class="bg-gray-100" style="padding: 0.75cm;">
                @if(empty($dailyRecords))
                    <p class="italic">No salary records found for this staff.</p>
                @else
                    <p><strong>Total Shifts Worked:</strong> {{ $totalShifts }}</p>
                    <p><strong>Total Hours:</strong> {{ number_format($totalHours, 2) }}h</p>
                    <p><strong>Base Salary:</strong> ${{ number_format($totalSalary, 2) }}</p>
                    <p><strong>Total Bonus:</strong> ${{ number_format($totalBonus, 2) }}</p>
                    <p class="font-bold"><strong>Total Salary:</strong> ${{ number_format($totalSalary + $totalBonus, 2) }}</p>
                @endif
            </div>
        </div>
    </div>
</body>
</html>