<!-- resources/views/LandingCompoments/About.blade.php -->
<section id="about" class="py-20 px-4 lg:px-0 bg-black">
    <div class="max-w-7xl mx-auto">
        @if ($aboutUs)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <span class="text-blue-400 font-semibold">ABOUT US</span>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-6">{{ $aboutUs->title }}</h2>
                    <p class="text-gray-400 mb-6">{{ $aboutUs->description }}</p>
                    .
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-300">{{ $aboutUs->point_1 }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-300">{{ $aboutUs->point_2 }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-gray-300">{{ $aboutUs->point_3 }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <button class="frosted-card border border-gray-600 hover:border-white text-white font-medium py-3 px-8 rounded-lg hover:bg-white hover:bg-opacity-10 transition flex items-center justify-center gap-2">
                        {{ $aboutUs->button_text }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
                
                <div class="order-1 lg:order-2 relative">
                    <div class="frosted-card overflow-hidden rounded-2xl aspect-w-16 aspect-h-9">
                        <img src="{{ $aboutUs->main_image_path ? asset('uploaded/' . $aboutUs->main_image_path) : 'https://images.unsplash.com/photo-1508514177221-188b1cf16e9d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' }}" 
                             alt="About Us Image" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 rounded-full overflow-hidden border-4 border-gray-900 glow-border">
                        <img src="{{ $aboutUs->ceo_image_path ? asset('uploaded/' . $aboutUs->ceo_image_path) : 'https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=200&q=80' }}" 
                             alt="{{ $aboutUs->ceo_name }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-40"></div>
                        <div class="absolute bottom-2 left-0 right-0 text-center">
                            <p class="text-sm font-semibold text-white">{{ $aboutUs->ceo_name }}</p>
                            <p class="text-xs text-gray-300">{{ $aboutUs->ceo_title }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center text-gray-400">
                No About Us content found.
            </div>
        @endif
    </div>
</section>