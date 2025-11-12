
<section id="solutions" class="py-20 px-4 lg:px-0 bg-gray-900">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="text-blue-400 font-semibold">OUR SOLUTIONS</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-4">Custom Solar Solutions for Every Need</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">We provide tailored solar energy systems designed to maximize efficiency and savings for your specific requirements.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($solarSolutions as $solution)
                <div class="frosted-card p-8 {{ $solution->color_class ?? 'hover-glow' }} transition">
                    <div class="w-16 h-16 rounded-full bg-{{ str_replace('hover-glow-', '', $solution->color_class ?? 'hover-glow') }}-900 bg-opacity-40 flex items-center justify-center mb-6 {{ str_replace('hover-', 'glow-border-', $solution->color_class ?? 'glow-border') }}">
                        <img src="{{ $solution->image_path ? asset('uploaded/' . $solution->image_path) : 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?ixlib=rb-1.2.1&auto=format&fit=crop&w=100&q=80' }}" 
                             alt="{{ $solution->title }}" 
                             class="w-full h-full object-cover rounded-full">
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-3">{{ $solution->title }}</h3>
                    <p class="text-gray-400 mb-4">{{ $solution->description }}</p>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-400">
                    No solar solutions found.
                </div>
            @endforelse
        </div>
    </div>
</section>