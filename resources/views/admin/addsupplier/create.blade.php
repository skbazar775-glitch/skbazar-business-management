@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
<h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
    <i class="fas fa-truck text-blue-600"></i>
    Add New Supplier
</h2>


        <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-8 max-w-xl mx-auto">
            <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" required
                           placeholder="Enter supplier name"
                           class="w-full h-12 px-4 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  text-base text-gray-900 placeholder-gray-400"
                           value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (Optional)</label>
                    <input type="email" name="email" id="email" 
                           placeholder="Enter email address"
                           class="w-full h-12 px-4 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  text-base text-gray-900 placeholder-gray-400"
                           value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Number -->
                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" maxlength="10"
                           placeholder="Enter 10-digit contact number"
                           class="w-full h-12 px-4 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  text-base text-gray-900 placeholder-gray-400"
                           value="{{ old('contact_number') }}">
                    @error('contact_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" id="location"
                           placeholder="Enter supplier location"
                           class="w-full h-12 px-4 border border-gray-300 rounded-lg shadow-sm 
                                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                                  text-base text-gray-900 placeholder-gray-400"
                           value="{{ old('location') }}">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

<!-- Amount -->
<div>
    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">
        Initial Amount (₹)
    </label>
    <input type="number" name="amount" id="amount" step="0.01"
           placeholder="Enter initial amount"
           class="w-full h-12 px-4 border border-gray-300 rounded-lg shadow-sm 
                  focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                  text-base text-gray-900 placeholder-gray-400"
           value="{{ old('amount', 0) }}"> {{-- default 0 rakha --}}
    @error('amount')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('admin.suppliers.index') }}" 
                       class="px-5 py-2.5 bg-gray-200 rounded-lg text-sm font-medium text-gray-700 
                              hover:bg-gray-300 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-5 py-2.5 bg-blue-600 rounded-lg text-sm font-medium text-white 
                                   hover:bg-blue-700 shadow-md transition">
                        Create Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
