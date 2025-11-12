<!-- resources/views/LandingCompoments/hero.blade.php -->
@php
    $hero = $heroSection ?? null;
@endphp

<section id="home" class="hero-bg h-screen flex items-center justify-center relative overflow-hidden pt-16" style="{{ $hero && $hero->image_path ? 'background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(\'' . asset('uploaded/' . $hero->image_path) . '\')' : 'background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url(\'https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80\')' }}; background-size: cover; background-position: center;">
    <div class="glass-panel max-w-4xl mx-4 p-8 md:p-12 text-center">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
            {{ $hero->heading ?? 'Premium Solar Energy Solutions for' }}
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-green-400">
                {{ $hero->highlighted_text ?? 'Modern India' }}
            </span>
        </h1>
        <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">
            {{ $hero->subtext ?? 'Cut your energy costs by up to 90% with our government-approved solar systems and premium components.' }}
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ $hero->button1_link ?? '#' }}" class="bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-white font-medium py-3 px-8 rounded-lg glow-border hover-glow transition">
                {{ $hero->button1_text ?? 'Get Free Quote' }}
            </a>
            @if ($hero && $hero->button2_text && $hero->button2_link)
                <a href="{{ $hero->button2_link }}" class="frosted-card border border-gray-600 hover:border-white text-white font-medium py-3 px-8 rounded-lg hover:bg-white hover:bg-opacity-10 transition flex items-center justify-center gap-2">
                    {!! $hero->icon_svg ?? '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' !!}
                    {{ $hero->button2_text }}
                </a>
            @endif
        </div>
    </div>
    @if ($hero && $hero->scroll_target)
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="{{ $hero->scroll_target }}" class="text-gray-300 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </a>
        </div>
    @endif
</section>