<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sk Bazar - Premium Solar Solutions</title>
    <link rel="icon" href="{{ asset('logo/fevicon2.ico') }}" type="image/x-icon">

    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @viteReactRefresh
    @vite(['resources/js/blade-mount.jsx'])

    <style>
        :root {
            --electric-blue: rgba(0, 180, 255, 0.6);
            --sun-yellow: rgba(255, 213, 0, 0.5);
            --eco-green: rgba(0, 200, 150, 0.5);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #0a0a0a;
            color: #e5e5e5;
        }
        
        .glass-panel {
            background: rgba(15, 15, 15, 0.5);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, 0.03);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        .frosted-card {
            background: rgba(20, 20, 20, 0.4);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .glow-border {
            box-shadow: 0 0 15px rgba(0, 180, 255, 0.3);
        }
        
        .glow-border-yellow {
            box-shadow: 0 0 15px rgba(255, 213, 0, 0.3);
        }
        
        .glow-border-green {
            box-shadow: 0 0 15px rgba(0, 200, 150, 0.3);
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(0, 180, 255, 0.4);
            transform: translateY(-3px);
            transition: all 0.3s ease;
        }
        
        .hover-glow-yellow:hover {
            box-shadow: 0 0 20px rgba(255, 213, 0, 0.4);
            transform: translateY(-3px);
            transition: all 0.3s ease;
        }
        
        .hover-glow-green:hover {
            box-shadow: 0 0 20px rgba(0, 200, 150, 0.4);
            transform: translateY(-3px);
            transition: all 0.3s ease;
        }
        
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1509391366360-2e959784a276?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
        }
        
        .dark-glass {
            background: rgba(5, 5, 5, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(0, 180, 255, 0.05), rgba(0, 200, 150, 0.05));
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 0;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--electric-blue);
            border: 3px solid rgba(10, 10, 10, 0.5);
        }
        
        .timeline-line::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(255, 255, 255, 0.05);
        }
        
        .ultra-glass {
            background: rgba(30, 30, 30, 0.3);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.02);
        }
    </style>
</head>
<body class="antialiased">
    <!-- Fetch sections once for reuse -->
    @php
        $sections = App\Models\Section::all()->keyBy('name');
    @endphp

    <!-- Navigation -->
    @if ($sections['navigation']->is_visible ?? true)
        @include('LandingCompoments.Navigation')
    @endif

    <!-- Hero -->
    @if ($sections['hero']->is_visible ?? true)
        @include('LandingCompoments.hero')
    @endif

    <!-- Products Section -->
    @if ($sections['products']->is_visible ?? true)
        <section id="products-section" class="w-full my-12 px-4 md:px-8">
            <!-- Add your products content here -->
        </section>
    @endif

    <!-- Projects -->
    @if ($sections['projects']->is_visible ?? true)
        @include('LandingCompoments.Projects')
    @endif

    <!-- Team -->
    @if ($sections['team']->is_visible ?? true)
        @include('LandingCompoments.Team')
    @endif

    <!-- Solutions -->
    <!--@if ($sections['solutions']->is_visible ?? true)-->
    <!--    @include('LandingCompoments.Solutions')-->
    <!--@endif-->

    <!-- Testimonials -->
    @if ($sections['testimonials']->is_visible ?? true)
        @include('LandingCompoments.Testimonials')
    @endif


    <!-- Contact -->
    @if ($sections['contact']->is_visible ?? true)
        @include('LandingCompoments.Contact')
    @endif

    <!-- Stats Section -->
    @if ($sections['stats']->is_visible ?? true)
        <section class="py-12 bg-gradient-to-b from-black to-gray-900">
            <div class="max-w-7xl mx-auto px-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="stats-card frosted-card p-6 text-center rounded-xl">
                        <p class="text-4xl font-bold text-blue-400 mb-2">5K+</p>
                        <p class="text-gray-300">Solar Installations</p>
                    </div>
                    <div class="stats-card frosted-card p-6 text-center rounded-xl">
                        <p class="text-4xl font-bold text-green-400 mb-2">90%</p>
                        <p class="text-gray-300">Energy Savings</p>
                    </div>
                    <div class="stats-card frosted-card p-6 text-center rounded-xl">
                        <p class="text-4xl font-bold text-yellow-400 mb-2">25+</p>
                        <p class="text-gray-300">Years Warranty</p>
                    </div>
                    <div class="stats-card frosted-card p-6 text-center rounded-xl">
                        <p class="text-4xl font-bold text-purple-400 mb-2">24/7</p>
                        <p class="text-gray-300">Customer Support</p>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    @if ($sections['footer']->is_visible ?? true)
        @include('LandingCompoments.Footer')
    @endif

    <!-- Back to Top Button -->
    <button id="backToTop" class="fixed bottom-6 right-6 z-50 w-12 h-12 rounded-full bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <script>
        // Simple scroll to section functionality
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Back to top button
        const backToTopButton = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.remove('hidden');
            } else {
                backToTopButton.classList.add('hidden');
            }
        });
        backToTopButton.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // FAQ accordion functionality
        document.querySelectorAll('.frosted-card button').forEach(button => {
            button.addEventListener('click', () => {
                const content = button.nextElementSibling;
                const icon = button.querySelector('svg');
                
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            });
        });

        // Mobile menu toggle
        const mobileMenuButton = document.querySelector('.md\\:hidden');
        mobileMenuButton.addEventListener('click', () => {
            // Implement mobile menu toggle functionality
            console.log('Mobile menu clicked');
        });
    </script>
</body>
</html>