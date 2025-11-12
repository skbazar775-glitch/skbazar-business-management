@extends('layouts.shiprocket')

@section('content')
    <div class="container mx-auto mt-10 max-w-5xl bg-white p-6 rounded shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">🚚 Shiprocket Shipping Dashboard</h1>

        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        @php
            $user = session('shiprocket_user') ?? ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@example.com', 'id' => '12345', 'company_id' => '67890', 'created_at' => '2025-01-01'];
            $token = session('shiprocket_token') ?? 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...';
        @endphp

        @if ($user && $token)
            <!-- User Info and Token -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-100 p-4 rounded shadow">
                    <h2 class="text-xl font-semibold mb-2">👤 User Info</h2>
                    <ul class="text-gray-700 space-y-1">
                        <li><strong>Name:</strong> {{ $user['first_name'] }} {{ $user['last_name'] }}</li>
                        <li><strong>Email:</strong> {{ $user['email'] }}</li>
                        <li><strong>User ID:</strong> {{ $user['id'] }}</li>
                        <li><strong>Company ID:</strong> {{ $user['company_id'] }}</li>
                        <li><strong>Created At:</strong> {{ $user['created_at'] }}</li>
                    </ul>
                </div>
                <div class="bg-blue-100 p-4 rounded shadow break-words">
                    <h2 class="text-xl font-semibold mb-2">🔑 Access Token</h2>
  <p class="text-xs text-gray-800 bg-white p-2 rounded shadow-inner break-all">
    {!! $token !!}
</p>

                </div>
            </div>
<div class="text-xs text-gray-800 bg-white p-2 rounded shadow-inner overflow-auto max-h-64 whitespace-pre-wrap break-words">
    {{ $token }}
</div>

            <!-- AWB Generation and Tracking -->
            <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-800">📦 Auto AWB Generation & Tracking</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AWB Number</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SR-1001</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">43062728295</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">John Smith</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">COD</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Delivered</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Print Label</a>
                                    <span class="mx-2">|</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Track</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SR-1002</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">43062728296</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sarah Johnson</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Prepaid</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">In Transit</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Print Label</a>
                                    <span class="mx-2">|</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Track</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#SR-1003</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">43062728297</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Michael Brown</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">COD</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Print Label</a>
                                    <span class="mx-2">|</span>
                                    <a href="#" class="text-blue-600 hover:text-blue-900">Track</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Logout -->
            <div class="mt-6 text-right">
                <a href="{{ route('shiprocket.logout') }}"
                   class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                   Logout
                </a>
            </div>
        @else
            <div class="bg-red-100 text-red-800 p-4 rounded shadow">
                ⚠️ You are not logged in. Please <a href="{{ route('shiprocket.login.form') }}" class="underline">log in</a> to continue.
            </div>
        @endif
    </div>
@endsection