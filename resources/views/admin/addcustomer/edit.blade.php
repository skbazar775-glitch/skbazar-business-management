@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Customer</h1>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-lg mb-6" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           placeholder="Enter full name"
                           value="{{ old('name', $customer->name) }}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('name') border-red-300 @enderror"
                           required>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           placeholder="Enter email address"
                           value="{{ old('email', $customer->email) }}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('email') border-red-300 @enderror"
                           required>
                </div>

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text"
                           name="phone"
                           id="phone"
                           placeholder="Enter 10 digit phone number"
                           value="{{ old('phone', $customer->customerAddress->phone ?? '') }}"
                           maxlength="10"
                           pattern="\d{10}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('phone') border-red-300 @enderror">
                    <p class="text-xs text-gray-500">Max 10 digits allowed</p>
                </div>

                {{-- District --}}
                <div>
                    <label for="district" class="block text-sm font-medium text-gray-700">District</label>
                    <input type="text"
                           name="district"
                           id="district"
                           placeholder="Enter district"
                           value="{{ old('district', $customer->customerAddress->district ?? '') }}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('district') border-red-300 @enderror">
                </div>

                {{-- Landmark --}}
                <div>
                    <label for="landmark" class="block text-sm font-medium text-gray-700">Landmark</label>
                    <input type="text"
                           name="landmark"
                           id="landmark"
                           placeholder="Enter landmark (optional)"
                           value="{{ old('landmark', $customer->customerAddress->landmark ?? '') }}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('landmark') border-red-300 @enderror">
                </div>

                {{-- City --}}
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                    <input type="text"
                           name="city"
                           id="city"
                           placeholder="Enter city"
                           value="{{ old('city', $customer->customerAddress->city ?? '') }}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('city') border-red-300 @enderror">
                </div>

                {{-- State --}}
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                    <input type="text"
                           name="state"
                           id="state"
                           placeholder="Enter state"
                           value="{{ old('state', $customer->customerAddress->state ?? '') }}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('state') border-red-300 @enderror">
                </div>

                {{-- Pin --}}
                <div>
                    <label for="pin" class="block text-sm font-medium text-gray-700">Pin Code</label>
                    <input type="text"
                           name="pin"
                           id="pin"
                           placeholder="Enter 6 digit pin code"
                           value="{{ old('pin', $customer->customerAddress->pin ?? '') }}"
                           maxlength="6"
                           pattern="\d{6}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('pin') border-red-300 @enderror">
                    <p class="text-xs text-gray-500">Max 6 digits allowed</p>
                </div>

                {{-- Country --}}
                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                    <input type="text"
                           name="country"
                           id="country"
                           placeholder="Enter country"
                           value="{{ old('country', $customer->customerAddress->country ?? '') }}"
                           class="mt-1 block w-full h-12 px-3 rounded-lg border border-gray-300 text-[#000000] shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('country') border-red-300 @enderror">
                </div>

                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('admin.customers.index') }}"
                       class="text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-200">
                        Update Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
