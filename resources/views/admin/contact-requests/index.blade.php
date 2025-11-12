@extends('layouts.admin')

@section('title', 'Manage Contact Requests')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-100">Contact Requests</h1>
            <div class="mt-4 md:mt-0">
                <p class="text-gray-400">Total: <span class="font-semibold text-blue-400">{{ count($contactRequests) }}</span></p>
            </div>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Message</th>
                            <th class="px-6 py-4 text-left text-xs font-medium uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-4 text-right text-xs font-medium uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse ($contactRequests as $request)
                            <tr class="hover:bg-gray-750 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-100">{{ $request->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    <a href="mailto:{{ $request->email }}" class="hover:text-blue-400 transition-colors">{{ $request->email }}</a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    @if($request->phone)
                                        <a href="tel:{{ $request->phone }}" class="hover:text-blue-400 transition-colors">{{ $request->phone }}</a>
                                    @else
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-900/50 text-blue-400">{{ $request->service }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400 max-w-xs truncate" title="{{ $request->message }}">
                                    {{ $request->message ? \Str::limit($request->message, 50) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400">
                                    {{ $request->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="{{ route('admin.contact-requests.destroy', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition-colors" onclick="return confirm('Are you sure you want to delete this contact request?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-400">No contact requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection