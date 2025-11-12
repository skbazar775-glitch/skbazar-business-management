<!-- resources/views/LandingCompoments/Team.blade.php -->
<section id="team" class="py-16 px-4 bg-gray-900">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <span class="text-blue-400 font-semibold text-sm uppercase">Our Team</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mt-2 mb-4">Meet Our Solar Experts</h2>
            <p class="text-gray-400 text-sm md:text-base max-w-xl mx-auto">The passionate professionals driving India's renewable energy revolution.</p>
        </div>

        <!-- Team Carousel Container -->
        <div class="relative">
            <!-- Slider Container -->
            <div class="team-carousel flex overflow-x-auto snap-x snap-mandatory scroll-smooth pb-6 space-x-4 px-2">
                @forelse ($teamMembers as $teamMember)
                    <div class="snap-start flex-shrink-0 w-72 sm:w-80 bg-gray-800 rounded-xl overflow-hidden shadow-lg transition-all hover:shadow-xl hover:-translate-y-2 group">
                        <div class="h-64 sm:h-72 overflow-hidden relative">
                            <img src="{{ $teamMember->image_path ? asset('uploaded/' . $teamMember->image_path) : 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80' }}" 
                                 alt="{{ $teamMember->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-70"></div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl sm:text-2xl font-bold text-white">{{ $teamMember->name }}</h3>
                            <p class="{{ $teamMember->color_class ?? 'text-blue-400' }} text-sm mb-3 font-medium">{{ $teamMember->designation }}</p>
                            <p class="text-gray-400 text-sm">{{ \Str::limit($teamMember->bio, 100) }}</p>
                            <div class="flex mt-4 space-x-4">
                                @if ($teamMember->linkedin_url)
                                    <a href="{{ $teamMember->linkedin_url }}" target="_blank" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
                                        <i class="fab fa-linkedin-in text-lg"></i>
                                    </a>
                                @endif
                                @if ($teamMember->email)
                                    <a href="mailto:{{ $teamMember->email }}" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
                                        <i class="fas fa-envelope text-lg"></i>
                                    </a>
                                @endif
                                @if ($teamMember->twitter_url)
                                    <a href="{{ $teamMember->twitter_url }}" target="_blank" class="text-gray-400 hover:text-blue-400 transition-colors duration-300">
                                        <i class="fab fa-twitter text-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 w-full py-12">
                        <i class="fas fa-users text-4xl mb-4 opacity-50"></i>
                        <p class="text-lg">No team members found</p>
                    </div>
                @endforelse
            </div>

            <!-- Navigation Arrows -->
            <button class="team-carousel-prev hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 -ml-4 bg-blue-500 text-white rounded-full w-10 h-10 items-center justify-center hover:bg-blue-600 transition z-10 shadow-lg">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="team-carousel-next hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 -mr-4 bg-blue-500 text-white rounded-full w-10 h-10 items-center justify-center hover:bg-blue-600 transition z-10 shadow-lg">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Dots Navigation -->
            <div class="flex justify-center mt-6 space-x-2 dots-container">
                <!-- Dots will be inserted here by JavaScript -->
            </div>
        </div>
    </div>
</section>

<!-- Include Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Carousel JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const carousel = document.querySelector('.team-carousel');
        const prevButton = document.querySelector('.team-carousel-prev');
        const nextButton = document.querySelector('.team-carousel-next');
        const dotsContainer = document.querySelector('.dots-container');
        const items = document.querySelectorAll('.team-carousel > div');
        let currentIndex = 0;
        let autoSlideInterval;
        const slideInterval = 3000; // 3 seconds

        // Create dots with better styling
        items.forEach((_, index) => {
            const dot = document.createElement('button');
            dot.classList.add('w-3', 'h-3', 'rounded-full', 'bg-gray-600', 'hover:bg-blue-400', 'transition-colors', 'duration-300');
            dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
            if (index === 0) dot.classList.add('bg-blue-500', 'w-5', 'rounded-lg');
            dot.addEventListener('click', () => {
                goToSlide(index);
            });
            dotsContainer.appendChild(dot);
        });

        const goToSlide = (index) => {
            currentIndex = index;
            updateCarousel();
            resetAutoSlide();
        };

        const updateCarousel = () => {
            const itemWidth = items[0].offsetWidth + 16; // Including margin
            carousel.scrollTo({
                left: currentIndex * itemWidth,
                behavior: 'smooth'
            });
            
            // Update active dot
            document.querySelectorAll('.dots-container button').forEach((dot, index) => {
                dot.classList.toggle('bg-blue-500', index === currentIndex);
                dot.classList.toggle('w-5', index === currentIndex);
                dot.classList.toggle('rounded-lg', index === currentIndex);
                dot.classList.toggle('bg-gray-600', index !== currentIndex);
                dot.classList.toggle('w-3', index !== currentIndex);
            });
        };

        const nextSlide = () => {
            if (currentIndex < items.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        };

        const prevSlide = () => {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = items.length - 1;
            }
            updateCarousel();
        };

        const startAutoSlide = () => {
            autoSlideInterval = setInterval(() => {
                nextSlide();
            }, slideInterval);
        };

        const resetAutoSlide = () => {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        };

        prevButton.addEventListener('click', () => {
            prevSlide();
            resetAutoSlide();
        });

        nextButton.addEventListener('click', () => {
            nextSlide();
            resetAutoSlide();
        });

        // Initialize carousel
        const adjustCarousel = () => {
            const width = window.innerWidth;
            let visibleItems = 1;
            if (width >= 1024) visibleItems = 3;
            else if (width >= 768) visibleItems = 2;

            items.forEach(item => {
                item.style.flex = `0 0 calc(${100 / visibleItems}% - 16px)`;
            });
            updateCarousel();
        };

        // Start auto-slide
        startAutoSlide();

        // Pause auto-slide on hover
        carousel.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
        carousel.addEventListener('mouseleave', startAutoSlide);

        // Handle window resize
        window.addEventListener('resize', adjustCarousel);
        adjustCarousel();
    });
</script>

<style>
    .team-carousel {
        display: flex;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .team-carousel::-webkit-scrollbar {
        display: none;
    }
    .team-carousel > div {
        scroll-snap-align: start;
        margin-right: 16px;
    }
    @media (min-width: 1024px) {
        .team-carousel > div {
            flex: 0 0 calc(33.33% - 11px);
        }
    }
    @media (min-width: 768px) and (max-width: 1023px) {
        .team-carousel > div {
            flex: 0 0 calc(50% - 8px);
        }
    }
    @media (max-width: 767px) {
        .team-carousel > div {
            flex: 0 0 100%;
        }
    }
</style>