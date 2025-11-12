@foreach ($customers as $user)
    @php
        $totalReceived = $user->skCredits->where('type', 'received')->sum('amount');
        $totalDue = $user->skCredits->where('type', 'due')->sum('amount');
        $balance = $totalReceived - $totalDue;
    @endphp
    <a href="{{ route('admin.customers.account', $user) }}"
       class="flex items-center p-4 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-50 {{ isset($selectedCustomerId) && $selectedCustomerId === $user->id ? 'bg-blue-50 border-l-4 border-l-blue-500' : '' }}">
        <div class="relative">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" alt="{{ $user->name }}" class="w-10 h-10 rounded-full mr-3">
            @if($balance < 0)
                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">!</span>
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ $user->name }}</p>
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500">Last active {{ $user->updated_at->setTimezone('Asia/Kolkata')->diffForHumans() }}</p>
                <p class="text-xs font-medium {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    ₹{{ number_format(abs($balance), 2) }} {{ $balance >= 0 ? 'Advance' : 'Due' }}
                </p>
            </div>
        </div>
        <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </a>
@endforeach
@if ($customers->isEmpty())
    <p class="text-center text-sm text-gray-500 p-4">No customers found.</p>
@endif