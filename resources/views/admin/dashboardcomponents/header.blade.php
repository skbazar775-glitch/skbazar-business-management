<div class="flex flex-col md:flex-row md:justify-between md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-white">Dashboard Overview</h1>
        <p class="text-gray-200">Welcome back, {{ Auth::guard('admin')->user()->name }}!</p>
    </div>
    <div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="group relative overflow-hidden px-4 py-2 rounded-lg">
                <span class="absolute inset-0 bg-red-500 transition-all duration-300 ease-out group-hover:translate-y-full"></span>
                <span class="absolute inset-0 flex items-center justify-center gap-2 text-white group-hover:text-white transition-colors duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </span>
                <span class="relative flex items-center justify-center gap-2 text-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </span>
            </button>
        </form>
    </div>
</div>