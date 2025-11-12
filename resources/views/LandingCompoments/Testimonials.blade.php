<!-- resources/views/LandingCompoments/Testimonials.blade.php -->
<section class="py-20 px-4 lg:px-0 bg-gray-900">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="text-blue-400 font-semibold">TESTIMONIALS</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-4">What Our Clients Say</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Hear from our satisfied customers who have made the switch to solar energy with Sk Bazar.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($testimonials as $testimonial)
                <div class="frosted-card p-8 relative">
                    <div class="absolute -top-5 -left-5 w-14 h-14 rounded-full overflow-hidden border-4 border-gray-900 glow-border">
                        <img src="{{ $testimonial->image_path ? asset('uploaded/' . $testimonial->image_path) : 'https://randomuser.me/api/portraits/women/45.jpg' }}" 
                             alt="{{ $testimonial->name }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <p class="text-gray-300 mb-6 italic">"{{ $testimonial->message }}"</p>
                    <div>
                        <p class="font-semibold text-white">{{ $testimonial->name }}</p>
                        <p class="text-sm text-gray-400">{{ $testimonial->designation }}, {{ $testimonial->location }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-400">
                    No testimonials found.
                </div>
            @endforelse
        </div>
    </div>
</section>