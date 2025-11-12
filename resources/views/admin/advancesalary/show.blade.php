@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Advance/Loan Salary Details</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Advance/Loan Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="font-bold text-gray-900">Staff:</p>
                    <p class="text-gray-700">{{ $advanceSalary->staff->name }}</p>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Amount:</p>
                    <p class="text-gray-700">{{ number_format($advanceSalary->amount, 2) }}</p>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Total EMI:</p>
                    <p class="text-gray-700">{{ $advanceSalary->total_emi }}</p>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Monthly EMI:</p>
                    <p class="text-gray-700">{{ number_format($advanceSalary->monthly_emi, 2) }}</p>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Remaining Balance:</p>
                    <p class="text-gray-700">{{ number_format($advanceSalary->remaining_balance, 2) }}</p>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Advance Date:</p>
                    <p class="text-gray-700">{{ $advanceSalary->advance_date }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="font-bold text-gray-900">Note:</p>
                    <p class="text-gray-700">{{ $advanceSalary->note ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 mb-4">Repayments</h2>
    
<form action="{{ route('admin.advancesalary.repayment', $advanceSalary->id) }}" method="POST" class="mb-8 bg-white shadow-md rounded-lg p-6">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div>
            <label for="emi_number" class="block font-bold text-black mb-2">EMI Number</label>
            <input type="number" name="emi_number" id="emi_number" class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div>
            <label for="paid_amount" class="block font-bold text-black mb-2">Paid Amount</label>
            <input type="number" name="paid_amount" id="paid_amount" class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" step="0.01" required>
        </div>
        <div>
            <label for="paid_date" class="block font-bold text-black mb-2">Paid Date</label>
            <input type="date" name="paid_date" id="paid_date" class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div>
            <label for="note" class="block font-bold text-black mb-2">Note</label>
            <input type="text" name="note" id="note" class="w-full px-3 py-2 border border-gray-300 text-black rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Add Repayment
    </button>
</form>

<div class="bg-black shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider">EMI Number</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider">Paid Amount</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider">Paid Date</th>
                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider">Note</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($advanceSalary->repayments as $repayment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black">{{ $repayment->emi_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ number_format($repayment->paid_amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $repayment->paid_date }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $repayment->note ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

    <div class="mt-6">
        <a href="{{ route('admin.advancesalary.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring-2 focus:ring-gray-300 disabled:opacity-25 transition">
            Back to List
        </a>
    </div>
</div>
@endsection