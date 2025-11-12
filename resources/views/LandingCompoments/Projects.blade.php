<section id="projects" class="py-20 px-4 lg:px-0 bg-black">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="text-blue-400 font-semibold">OUR PROJECTS</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2 mb-4">Recent Solar Installations</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Explore our portfolio of successful solar energy projects across residential and commercial sectors.</p>
        </div>

        <!-- Swiper Container -->
        <div class="swiper projects-swiper">
            <div class="swiper-wrapper">
                @forelse ($projects as $project)
                    <div class="swiper-slide">
                        <div class="group relative overflow-hidden rounded-xl h-80">
                            <img 
    src="{{ !empty($project->image_path) && file_exists(public_path('uploaded/' . $project->image_path)) 
        ? asset('uploaded/' . $project->image_path) 
        : 'https://upload.wikimedia.org/wikipedia/commons/1/14/No_Image_Available.jpg' }}" 
    alt="{{ $project->title }}" 
    class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
/>
                         <div class="absolute inset-0 bg-gradient-to-t from-black via-transparent to-transparent opacity-80 group-hover:opacity-90 transition duration-500"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 transform translate-y-10 group-hover:translate-y-0 transition duration-500">
                                <h3 class="text-xl font-bold text-white mb-2">{{ $project->title }}</h3>
                                <p class="text-gray-300 mb-3 line-clamp-2">{{ $project->short_description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium {{ $project->category_color }}">{{ $project->category }}</span>
                                    <span class="text-sm text-gray-300">Completed: {{ $project->formatted_date }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-400">
                        No projects found.
                    </div>
                @endforelse
            </div>
            <!-- Navigation buttons -->
            <div class="swiper-button-prev !text-white"></div>
            <div class="swiper-button-next !text-white"></div>
            <!-- Pagination -->
            <div class="swiper-pagination !bottom-4"></div>
        </div>
    </div>

    <!-- Swiper JS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const swiper = new Swiper('.projects-swiper', {
                slidesPerView: 1,
                spaceBetween: 24,
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                loop: @json($projects->count() > 3),
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
            });
        });
    </script>
</section>