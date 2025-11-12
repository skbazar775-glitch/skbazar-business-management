@extends('layouts.admin')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Supplier</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-lg">

            <style>
                input[type="text"],
                input[type="email"],
                input[type="number"] {
                    color: #000 !important;   /* Force solid black text */
                }

                input::placeholder {
                    color: #9ca3af; /* Tailwind placeholder-gray-400 */
                }
            </style>

            <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                  focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                  text-sm text-black placeholder-gray-400"
                           value="{{ old('name', $supplier->name) }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                  focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                  text-sm text-black placeholder-gray-400"
                           value="{{ old('email', $supplier->email) }}">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="contact_number" class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" id="contact_number" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                  focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                  text-sm text-black placeholder-gray-400"
                           value="{{ old('contact_number', $supplier->contact_number) }}">
                    @error('contact_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input type="text" name="location" id="location" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                  focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                  text-sm text-black placeholder-gray-400"
                           value="{{ old('location', $supplier->location) }}">
                    @error('location')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Initial Amount (₹)</label>
                    <input type="number" name="amount" id="amount" step="0.01" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                                  focus:outline-none focus:ring-blue-500 focus:border-blue-500 
                                  text-sm text-black placeholder-gray-400"
                           value="{{ old('amount', $supplier->amount) }}">
                    @error('amount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 bg-gray-200 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 rounded-md text-sm font-medium text-white hover:bg-blue-700">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
@endsection
