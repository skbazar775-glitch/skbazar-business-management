<!-- resources/views/LandingCompoments/Contact.blade.php -->
<section id="contact" class="py-16 px-4 bg-black">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
            <!-- Get In Touch Section -->
            <div class="frosted-card p-6 md:p-8 flex flex-col justify-between min-h-[480px] sm:min-h-[520px]">
                @if ($contactInfo)
                    <div>
                        <span class="text-blue-400 font-semibold text-sm uppercase">Contact Us</span>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mt-2 mb-4">Get In Touch</h2>
                        <p class="text-gray-400 text-sm md:text-base mb-6">Ready to switch to solar? Contact our team for a free consultation and quote.</p>
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1 text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base sm:text-lg font-semibold text-white">{{ $contactInfo->office_title }}</h3>
                                    <p class="text-gray-400 text-sm">{{ $contactInfo->office_address }}</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1 text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base sm:text-lg font-semibold text-white">{{ $contactInfo->phone_title }}</h3>
                                    <p class="text-gray-400 text-sm">{{ $contactInfo->phone_1 }}</p>
                                    @if ($contactInfo->phone_2)
                                        <p class="text-gray-400 text-sm">{{ $contactInfo->phone_2 }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1 text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base sm:text-lg font-semibold text-white">{{ $contactInfo->email_title }}</h3>
                                    <p class="text-gray-400 text-sm">{{ $contactInfo->email_1 }}</p>
                                    @if ($contactInfo->email_2)
                                        <p class="text-gray-400 text-sm">{{ $contactInfo->email_2 }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1 text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-base sm:text-lg font-semibold text-white">{{ $contactInfo->hours_title }}</h3>
                                    <p class="text-gray-400 text-sm">{{ $contactInfo->weekdays_hours }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-400">
                        No contact information available.
                    </div>
                @endif
            </div>

            <!-- Request a Free Quote Section -->
            <div class="frosted-card p-6 md:p-8 flex flex-col justify-between min-h-[480px] sm:min-h-[520px]">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-white mb-4">Request a Free Quote</h3>
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-6 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-500 text-white p-4 rounded mb-6 text-sm">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('admin.contact-requests.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-gray-300 text-sm mb-1">Full Name</label>
                            <input type="text" id="name" name="name" class="w-full frosted-card bg-gray-900 border border-gray-700 rounded-lg py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('name') }}">
                        </div>
                        <div>
                            <label for="email" class="block text-gray-300 text-sm mb-1">Email</label>
                            <input type="email" id="email" name="email" class="w-full frosted-card bg-gray-900 border border-gray-700 rounded-lg py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-300 text-sm mb-1">Phone</label>
                            <input type="tel" id="phone" name="phone" class="w-full frosted-card bg-gray-900 border border-gray-700 rounded-lg py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" value="{{ old('phone') }}">
                        </div>
                        <div>
                            <label for="service" class="block text-gray-300 text-sm mb-1">Service Interested In</label>
                            <select id="service" name="service" class="w-full frosted-card bg-gray-900 border border-gray-700 rounded-lg py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="Residential Solar" {{ old('service') == 'Residential Solar' ? 'selected' : '' }} class="text-black">Residential Solar</option>
                                <option value="Commercial Solar" {{ old('service') == 'Commercial Solar' ? 'selected' : '' }} class="text-black">Commercial Solar</option>
                                <option value="EV Charging Stations" {{ old('service') == 'EV Charging Stations' ? 'selected' : '' }} class="text-black">EV Charging Stations</option>
                                <option value="Solar Products" {{ old('service') == 'Solar Products' ? 'selected' : '' }} class="text-black">Solar Products</option>
                                <option value="Maintenance" {{ old('service') == 'Maintenance' ? 'selected' : '' }} class="text-black">Maintenance</option>
                            </select>
                        </div>
                        <div>
                            <label for="message" class="block text-gray-300 text-sm mb-1">Your Requirements</label>
                            <textarea id="message" name="message" rows="3" class="w-full frosted-card bg-gray-900 border border-gray-700 rounded-lg py-2 px-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('message') }}</textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full frosted-card bg-gradient-to-r from-blue-500 to-green-500 hover:from-blue-600 hover:to-green-600 text-white font-medium py-2 px-6 rounded-lg glow-border hover-glow transition text-sm">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .frosted-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .frosted-card:hover {
        background: rgba(255, 255, 255, 0.08);
    }
    .glow-border {
        box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
    }
    .hover-glow:hover {
        box-shadow: 0 0 15px rgba(59, 130, 246, 0.7);
    }
    @media (min-width: 1024px) {
        .grid > div {
            height: 100%;
        }
    }
    
    /* Style for select dropdown options */
    #service option {
        color: black !important;
        background-color: white;
    }
</style>