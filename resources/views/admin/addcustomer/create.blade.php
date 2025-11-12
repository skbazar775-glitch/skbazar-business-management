@extends('layouts.admin')

@section('content')
    <!-- Main container with padding and centered content -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Card with shadow, rounded corners, and subtle border -->
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-3xl mx-auto border border-gray-100">
            <!-- Heading with bold font and emoji -->
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8 tracking-tight">➕ Add New Customer</h1>

            <!-- Error messages display -->
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-xl mb-8 shadow-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form with spacing between fields -->
            <form action="{{ route('admin.customers.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700">Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="Enter full name"
                           class="mt-2 block w-full rounded-xl border-gray-300 text-black shadow-md focus:border-blue-500 focus:ring focus:ring-blue-400 focus:ring-opacity-40 @error('name') border-red-300 @enderror h-12 px-4 text-base transition-all duration-300 hover:scale-[1.02]"
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field (Optional) -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email (Optional)</label>
                    <input type="email"
                           name="email"
                           id="email"
                           value="{{ old('email') }}"
                           placeholder="example@email.com"
                           class="mt-2 block w-full rounded-xl border-gray-300 text-black shadow-md focus:border-blue-500 focus:ring focus:ring-blue-400 focus:ring-opacity-40 @error('email') border-red-300 @enderror h-12 px-4 text-base transition-all duration-300 hover:scale-[1.02]">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number Field with 10-digit validation -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700">Contact Number</label>
                    <input type="text"
                           name="phone"
                           id="phone"
                           maxlength="10"
                           value="{{ old('phone') }}"
                           placeholder="10-digit phone number"
                           pattern="[0-9]{10}"
                           oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                           class="mt-2 block w-full rounded-xl border-gray-300 text-black shadow-md focus:border-blue-500 focus:ring focus:ring-blue-400 focus:ring-opacity-40 @error('phone') border-red-300 @enderror h-12 px-4 text-base transition-all duration-300 hover:scale-[1.02]"
                           required>
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- City Field -->
                <div>
                    <label for="city" class="block text-sm font-semibold text-gray-700">City</label>
                    <input type="text"
                           name="city"
                           id="city"
                           value="{{ old('city') }}"
                           placeholder="Enter city"
                           class="mt-2 block w-full rounded-xl border-gray-300 text-black shadow-md focus:border-blue-500 focus:ring focus:ring-blue-400 focus:ring-opacity-40 @error('city') border-red-300 @enderror h-12 px-4 text-base transition-all duration-300 hover:scale-[1.02]"
                           required>
                    @error('city')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-5 pt-6">
                    <a href="{{ route('admin.customers.index') }}"
                       class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 font-medium shadow-sm transition duration-300 hover:scale-[1.05]">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-md transition duration-300 hover:scale-[1.05]">
                        Create Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection