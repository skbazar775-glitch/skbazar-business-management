@php
    // Simple navigation links - no database dependency
    $navLinks = [
        'home' => ['icon' => 'fas fa-home', 'label' => 'Home', 'href' => '#home'],
        'products' => ['icon' => 'fas fa-box-open', 'label' => 'Products', 'href' => '#products-section'],
        'projects' => ['icon' => 'fas fa-project-diagram', 'label' => 'Projects', 'href' => '#projects'],
        'team' => ['icon' => 'fas fa-users', 'label' => 'Team', 'href' => '#team'],
        'contact' => ['icon' => 'fas fa-envelope', 'label' => 'Contact', 'href' => '#contact'],
        'shop' => ['icon' => 'fas fa-shopping-cart', 'label' => 'Shop', 'href' => '/shop', 'class' => 'text-green-400 hover:text-green-300'],
    ];
@endphp

<nav class="fixed w-full z-50 py-4 px-6 lg:px-12 transition-all duration-300">
    <div class="max-w-7xl mx-auto flex justify-between items-center py-3 px-6 glass-morphism">
        
        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <img src="{{ asset('logo/logo.png') }}" alt="SkBazar Logo" class="h-8 w-auto" onerror="this.style.display='none'">
            <span class="text-white font-bold text-lg gradient-text">SK Bazar</span>
        </div>
        
        <!-- Desktop Menu -->
        <div class="hidden md:flex space-x-6 items-center">
            @foreach($navLinks as $key => $link)
                @if(isset($sections[$key]) || $key === 'shop')
                    <a href="{{ $link['href'] }}" 
                       class="nav-link {{ $link['class'] ?? '' }} px-3 py-2 rounded-lg transition-all duration-300 hover:bg-white/10">
                        <i class="{{ $link['icon'] }} mr-2"></i> 
                        {{ $link['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
        
        <!-- Mobile Menu Button -->
        <button id="mobileMenuButton" class="md:hidden text-gray-300 hover:text-white transition-colors p-2 rounded-lg hover:bg-white/10" aria-label="Toggle mobile menu">
            <i id="menuIcon" class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobileMenu" class="md:hidden hidden absolute top-full left-6 right-6 mt-2 flex-col space-y-2 bg-gray-900/95 backdrop-blur-lg px-6 py-4 border border-white/10 rounded-lg shadow-2xl">
        @foreach($navLinks as $key => $link)
            @if(isset($sections[$key]) || $key === 'shop')
                <a href="{{ $link['href'] }}" 
                   class="nav-link {{ $link['class'] ?? '' }} px-4 py-3 rounded-lg transition-all duration-300 hover:bg-white/10 border-l-2 border-transparent hover:border-blue-400">
                    <i class="{{ $link['icon'] }} mr-3 w-5 text-center"></i> 
                    {{ $link['label'] }}
                </a>
            @endif
        @endforeach
    </div>
</nav>

<style>
    .glass-morphism {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 
            0 8px 32px rgba(0, 0, 0, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.05);
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #00b4db, #6a11cb);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .nav-link {
        color: #d1d5db;
        display: flex;
        align-items: center;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        color: #ffffff;
        transform: translateY(-1px);
    }
    
    /* Scroll behavior */
    html {
        scroll-behavior: smooth;
    }
</style>

<script>
    // Mobile menu toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const menuIcon = document.getElementById('menuIcon');

        if (mobileMenuButton && mobileMenu && menuIcon) {
            mobileMenuButton.addEventListener('click', () => {
                const isHidden = mobileMenu.classList.contains('hidden');
                
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    menuIcon.classList.remove('fa-bars');
                    menuIcon.classList.add('fa-times');
                    mobileMenu.style.animation = 'slideDown 0.3s ease-out';
                } else {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
                    mobileMenu.classList.add('hidden');
                    menuIcon.classList.remove('fa-times');
                    menuIcon.classList.add('fa-bars');
                }
            });
        }

        // Add scroll effect to navbar
        let lastScrollY = window.scrollY;
        const nav = document.querySelector('nav');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 100) {
                nav.style.background = 'rgba(15, 23, 42, 0.95)';
                nav.style.backdropFilter = 'blur(25px)';
            } else {
                nav.style.background = 'rgba(15, 23, 42, 0.8)';
                nav.style.backdropFilter = 'blur(20px)';
            }

            lastScrollY = window.scrollY;
        });
    });

    // Add slide down animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>