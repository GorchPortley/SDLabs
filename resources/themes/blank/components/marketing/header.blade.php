<section x-data="{ mobileNavOpen: false }" class="bg-purple-950">
    <div class="container mx-auto">
        <div class="flex items-center justify-between bg-transparent">
            <!-- Left side with logo and navigation -->
            <div class="flex items-center">
                <div class="flex-shrink-0 py-5 pl-5 lg:pl-0">
                    <a href="/" class="flex items-center">
                        <x-logo class="invert h-8"></x-logo>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:block ml-6">
                    <ul class="flex items-center space-x-1">
                        <li x-data="{ isOpen: false }"
                            @mouseenter="isOpen = true"
                            @mouseleave="isOpen = false"
                            class="relative group">
                            <div class="flex items-center h-full px-4 py-5 cursor-pointer text-white hover:text-amber-200 transition duration-150">
                                <i class="ph ph-intersect-three mr-2 text-3xl"></i>
                                <span class="font-bold">Design Database</span>
                            </div>
                            <div x-show="isOpen"
                                 x-transition
                                 class="absolute top-full left-0 mt-0 w-48 bg-white text-black shadow-lg border-t-2 border-amber-200 z-50">
                                <a href="{{ route('designs') }}" class="block px-4 py-2 hover:bg-gray-100">All Designs</a>
                                <a href="{{ route('designs', ["category" => "Subwoofer"]) }}" class="block px-4 py-2 hover:bg-gray-100">Subwoofer</a>
                                <a href="{{ route('designs', ["category" => "Full-Range"]) }}" class="block px-4 py-2 hover:bg-gray-100">Full-Range</a>
                                <a href="{{ route('designs', ["category" => "Two-Way"]) }}" class="block px-4 py-2 hover:bg-gray-100">Two-Way</a>
                                <a href="{{ route('designs', ["category" => "Three-Way"]) }}" class="block px-4 py-2 hover:bg-gray-100">Three-Way</a>
                                <a href="{{ route('designs', ["category" => "Four-Way+"]) }}" class="block px-4 py-2 hover:bg-gray-100">Four-Way+</a>
                                <a href="{{ route('designs', ["category" => "Portable"]) }}" class="block px-4 py-2 hover:bg-gray-100">Portable</a>
                                <a href="{{ route('designs', ["category" => "Esoteric"]) }}" class="block px-4 py-2 hover:bg-gray-100">Esoteric</a>
                            </div>
                        </li>

                        <li x-data="{ isOpen: false }"
                            @mouseenter="isOpen = true"
                            @mouseleave="isOpen = false"
                            class="relative group">
                            <div class="flex items-center h-full px-4 py-5 cursor-pointer text-white hover:text-amber-200 transition duration-150">
                                <i class="ph ph-circles-three mr-2 text-4xl"></i>
                                <span class="font-bold">Driver Database</span>
                            </div>
                            <div x-show="isOpen"
                                 x-transition
                                 class="absolute top-full left-0 mt-0 w-48 bg-white text-black shadow-lg border-t-2 border-amber-200 z-50">
                                <a href="{{ route('drivers') }}" class="block px-4 py-2 hover:bg-gray-100">All Drivers</a>
                                <a href="{{ route('drivers', ["category" => "Subwoofer"]) }}" class="block px-4 py-2 hover:bg-gray-100">Subwoofers</a>
                                <a href="{{ route('drivers', ["category" => "Woofer"]) }}" class="block px-4 py-2 hover:bg-gray-100">Woofers</a>
                                <a href="{{ route('drivers', ["category" => "Tweeter"]) }}" class="block px-4 py-2 hover:bg-gray-100">Tweeters</a>
                                <a href="{{ route('drivers', ["category" => "Compression Driver"]) }}" class="block px-4 py-2 hover:bg-gray-100">Compression Drivers</a>
                                <a href="{{ route('drivers', ["category" => "Exciter"]) }}" class="block px-4 py-2 hover:bg-gray-100">Exciters</a>
                                <a href="{{ route('drivers', ["category" => "Other"]) }}" class="block px-4 py-2 hover:bg-gray-100">Others</a>
                            </div>
                        </li>
                        
                        <li class="group">
                            <a href="{{ route('blog') }}" class="flex items-center h-full px-4 py-5 text-white hover:text-amber-200 transition duration-150">
                                <i class="ph ph-megaphone-simple mr-2 text-3xl"></i>
                                <span class="font-bold">Soap Box</span>
                            </a>
                        </li>
                        
                        <li class="group">
                            <a href="/forum" class="flex items-center h-full px-4 py-5 text-white hover:text-amber-200 transition duration-150">
                                <i class="ph ph-user-sound mr-2 text-3xl"></i>
                                <span class="font-bold">Forum</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Right side with search and user menu -->
            <div class="flex items-center flex-1 justify-end">
                <!-- Search Bar - Takes all available space -->
                <div class="hidden lg:block flex-grow px-4 max-w-xl">
                    @livewire('top-search-bar')
                </div>
                
                <!-- User menu or login -->
                <div class="flex items-center">
                    @if(auth()->guest())
                        <div class="hidden lg:block">
                            <a href="{{ route('login') }}" class="px-5 py-3 font-medium text-white hover:text-amber-200 transition duration-150" type="button">Sign In</a>
                            <a href="{{ route('register') }}" class="px-5 py-3 ml-2 font-medium text-white bg-purple-800 hover:bg-purple-700 transition duration-150" type="button">Register</a>
                        </div>
                    @else
                        <div class="hidden lg:block mr-6">
                            <x-app.user-menu position="top"/>
                        </div>
                    @endif
                    
                    <!-- Mobile menu toggle -->
                    <div class="lg:hidden px-5">
                        <button x-on:click="mobileNavOpen = !mobileNavOpen" class="p-2 text-white  hover:bg-purple-900">
                            <x-phosphor-list class="w-6 h-6" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Navigation Drawer -->
    <div :class="{'block': mobileNavOpen, 'hidden': !mobileNavOpen}" class="fixed inset-0 z-50 lg:hidden">
        <div x-on:click="mobileNavOpen = !mobileNavOpen" class="fixed inset-0 bg-gray-800 opacity-80"></div>
        <nav class="relative z-10 w-4/6 max-w-sm h-full overflow-y-auto bg-white transform transition-transform duration-200 ease-in-out">
            <div class="flex flex-col h-full">
                <!-- Top with logo and close button -->
                <div class="flex items-center justify-between p-4 border-b">
                    <a href="/" class="inline-block">
                        <x-logo class="h-8" />
                    </a>
                    <button x-on:click="mobileNavOpen = !mobileNavOpen" class="p-2 hover:bg-gray-100">
                        <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 18L18 6M6 6L18 18" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Mobile search -->
                <div class="px-4 py-3 border-b">
                    @livewire('top-search-bar')
                </div>
                
                <!-- Navigation links -->
                <div class="flex-grow py-6">
                    <ul class="px-4 space-y-6">
                        <li class="py-2">
                            <a class="text-lg font-medium hover:text-purple-700" href="{{route('designs')}}">Design Database</a>
                            <ul class="pl-4 mt-2 space-y-2 text-gray-600">
                                <li><a href="{{ route('designs', ["category" => "Subwoofer"]) }}" class="hover:text-purple-700">Subwoofers</a></li>
                                <li><a href="{{ route('designs', ["category" => "Full-Range"]) }}" class="hover:text-purple-700">Full-Range</a></li>
                                <li><a href="{{ route('designs', ["category" => "Two-Way"]) }}" class="hover:text-purple-700">Two-Way</a></li>
                                <li><a href="{{ route('designs', ["category" => "Three-Way"]) }}" class="hover:text-purple-700">Three-Way</a></li>
                                <li><a href="{{ route('designs', ["category" => "Four-Way+"]) }}" class="hover:text-purple-700">Four-Way +</a></li>
                                <li><a href="{{ route('designs', ["category" => "Portable"]) }}" class="hover:text-purple-700">Portable</a></li>
                                <li><a href="{{ route('designs', ["category" => "Esoteric"]) }}" class="hover:text-purple-700">Esoteric</a></li>
                            </ul>
                        </li>
                        <li class="py-2">
                            <a class="text-lg font-medium hover:text-purple-700" href="{{route('drivers')}}">Driver Database</a>
                            <ul class="pl-4 mt-2 space-y-2 text-gray-600">
                                <li><a href="{{ route('drivers') }}" class="hover:text-purple-700">All Drivers</a></li>
                                <li><a href="{{ route('drivers', ["category" => "Subwoofer"]) }}" class="hover:text-purple-700">Subwoofers</a></li>
                                <li><a href="{{ route('drivers', ["category" => "Woofer"]) }}" class="hover:text-purple-700">Woofers</a></li>
                                <li><a href="{{ route('drivers', ["category" => "Tweeter"]) }}" class="hover:text-purple-700">Tweeters</a></li>
                                <li><a href="{{ route('drivers', ["category" => "Compression Driver"]) }}" class="hover:text-purple-700">Compression Drivers</a></li>
                                <li><a href="{{ route('drivers', ["category" => "Exciter"]) }}" class="hover:text-purple-700">Exciters</a></li>
                                <li><a href="{{ route('drivers', ["category" => "Other"]) }}" class="hover:text-purple-700">Others</a></li>
                            </ul>
                        </li>
                        <li class="py-2"><a class="text-lg font-medium hover:text-purple-700" href="/blog">Soap Box</a></li>
                        <li class="py-2"><a class="text-lg font-medium hover:text-purple-700" href="/forum">Community Forum</a></li>
                    </ul>
                </div>
                
                <!-- Bottom user actions -->
                <div class="p-4 border-t">
                    @if(auth()->guest())
                        <div class="space-y-3">
                            <a href="{{ route('login') }}" class="block w-full px-5 py-3 font-medium text-center bg-gray-100 hover:bg-gray-200 transition duration-150" type="button">Sign In</a>
                            <a href="{{ route('register') }}" class="block w-full px-5 py-3 font-medium text-center text-white bg-purple-900 hover:bg-purple-800 transition duration-150">Register</a>
                        </div>
                    @else
                        <x-app.user-menu position="bottom" class="border-black" />
                    @endif
                </div>
            </div>
        </nav>
    </div>
</section>