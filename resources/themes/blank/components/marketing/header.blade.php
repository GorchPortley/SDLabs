<section x-data="{ mobileNavOpen: false }" class=" bg-purple-950">
    <div class="flex items-center justify-between bg-transparent mx-auto">
        <div class="w-auto h-full">
            <div class="flex h-full flex-wrap p-5 items-center">
                <div class="w-auto ml-7 mr-3.5">
                    <a href="/" class="flex items-center h-full">
                        <x-logo class="invert h-8"></x-logo>
                    </a>
                </div>
                <div class="hidden h-full w-auto lg:block">
                    <ul class="flex my-0 items-center h-full">
                        <li x-data="{ isOpen: false }"
                            @mouseenter="isOpen = true"
                            @mouseleave="isOpen = false"
                            class="relative group flex justify-center items-center text-white h-full pr-4 pl-4 hover:text-amber-200">
                            <div class="flex items-center h-full cursor-pointer">
                                <i class="ph ph-intersect-three mr-2 text-3xl"></i>
                                <span class="font-bold">Design Database</span>
                            </div>
                            <div x-show="isOpen"
                                 x-transition
                                 class="absolute border-b-2 border-l-2 border-r-2 top-full left-0 mt-0 bg-white text-black w-48 shadow-sm z-50">
                                <a href="{{ route('designs') }}" class="block px-4 py-2 hover:bg-gray-200">All Designs</a>
                                <a href="{{ route('designs', ["category" => "Subwoofer"]) }}" class="block px-4 py-2 hover:bg-gray-200">Subwoofer</a>
                                <a href="{{ route('designs', ["category" => "Full-Range"]) }}" class="block px-4 py-2 hover:bg-gray-200">Full-Range</a>
                                <a href="{{ route('designs', ["category" => "Two-Way"]) }}" class="block px-4 py-2 hover:bg-gray-200">Two-Way</a>
                                <a href="{{ route('designs', ["category" => "Three-Way"]) }}" class="block px-4 py-2 hover:bg-gray-200">Three-Way</a>
                                <a href="{{ route('designs', ["category" => "Four-Way+"]) }}" class="block px-4 py-2 hover:bg-gray-200">Four-Way+</a>
                                <a href="{{ route('designs', ["category" => "Portable"]) }}" class="block px-4 py-2 hover:bg-gray-200">Portable</a>
                                <a href="{{ route('designs', ["category" => "Esoteric"]) }}" class="block px-4 py-2 hover:bg-gray-200">Esoteric</a>
                            </div>
                        </li>

                        <li x-data="{ isOpen: false }"
                            @mouseenter="isOpen = true"
                            @mouseleave="isOpen = false"
                            class="relative group flex justify-center items-center text-white h-full pr-4 pl-4 hover:text-amber-200">
                            <div class="flex items-center h-full cursor-pointer">
                                <i class="ph ph-circles-three mr-2 text-4xl"></i>
                                <span class="font-bold">Driver Database</span>
                            </div>
                            <div x-show="isOpen"
                                 x-transition
                                 class="absolute border-b-2 border-l-2 border-r-2 top-full left-0 mt-0 bg-white text-black w-48 shadow-sm z-50">
                                <a href="{{ route('drivers') }}" class="block px-4 py-2 hover:bg-gray-700">All Drivers</a>
                                <a href="{{ route('drivers', ["category" => "Subwoofer"]) }}" class="block px-4 py-2 hover:bg-gray-200">Subwoofers</a>
                                <a href="{{ route('drivers', ["category" => "Woofer"]) }}" class="block px-4 py-2 hover:bg-gray-200">Woofers</a>
                                <a href="{{ route('drivers', ["category" => "Tweeter"]) }}" class="block px-4 py-2 hover:bg-gray-200">Tweeters</a>
                                <a href="{{ route('drivers', ["category" => "Compression Driver"]) }}" class="block px-4 py-2 hover:bg-gray-200">Compression Drivers</a>
                                <a href="{{ route('drivers', ["category" => "Exciter"]) }}" class="block px-4 py-2 hover:bg-gray-200">Exciters</a>
                                <a href="{{ route('drivers', ["category" => "Other"]) }}" class="block px-4 py-2 hover:bg-gray-200">Others</a>
                            </div>
                        </li>

                        <li class="font-medium group flex justify-center items-center text-white h-full pr-4 pl-4 hover:text-amber-200">
                            <i class="ph ph-megaphone-simple mr-2 text-3xl"></i>
                            <a class="font-bold" href="{{ route('blog') }}">Soap Box</a>
                        </li>

                        <li class="font-medium group flex justify-center items-center text-white h-full pr-4 pl-4 hover:text-amber-200">
                            <i class="ph ph-user-sound mr-2 text-3xl"></i>
                            <a class="font-bold" href="/forum">Forum</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-auto">
            <div class="flex flex-wrap items-center">
                @if(auth()->guest())
                    <div class="hidden w-auto mr-5 lg:block">
                        <div class="inline-block">
                            <a href="/auth/login" class="w-full px-5 py-3 font-medium transition duration-200 ease-in-out bg-transparent rounded-xl hover:text-gray-700 text-white" type="button">Sign In</a>
                        </div>
                    </div>
                @else
                    <div class="hidden lg:flex flex-row mr-6">
                        <x-app.user-menu position="top"/>
                    </div>
                @endif
                <div class="w-auto mr-8 lg:hidden">
                    <button x-on:click="mobileNavOpen = !mobileNavOpen" class="p-3 text-white bg-white">
                        <x-phosphor-list class="invert w-6 h-6" />
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div :class="{'block': mobileNavOpen, 'hidden': !mobileNavOpen}" class="fixed top-0 bottom-0 left-0 z-50 hidden w-4/6 sm:max-w-xs">
        <div x-on:click="mobileNavOpen = !mobileNavOpen" class="fixed inset-0 bg-gray-800 opacity-80"></div>
        <nav class="relative z-10 h-full px-5 pt-5 overflow-y-auto bg-white">
            <div class="flex flex-wrap justify-between h-full">
                <div class="w-full pt-px">
                    <div class="flex items-center justify-between">
                        <a class="inline-block" href="/">
                            <x-logo class="h-8" />
                        </a>
                        <div class="w-auto p-2">
                            <button x-on:click="mobileNavOpen = !mobileNavOpen">
                                <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6 18L18 6M6 6L18 18" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col justify-center w-full px-3 py-16">
                    <ul>
                        <li class="mb-12"><a class="font-medium hover:text-gray-700" href="{{route('designs')}}">Design Database</a></li>
                        <li class="mb-12"><a class="font-medium hover:text-gray-700" href="{{route('drivers')}}">Driver Database</a></li>
                        <li class="mb-12"><a class="font-medium hover:text-gray-700" href="/blog">SoapBox</a></li>
                        <li class="mb-12"><a class="font-medium hover:text-gray-700" href="/forum">Community Forum</a></li>
                    </ul>
                </div>
                <div class="flex flex-col justify-end w-full pb-8">
                    @if(auth()->guest())
                        <div class="flex flex-wrap w-full space-y-3">
                            <a href="/auth/login" class="block w-full px-5 py-3 font-medium text-center transition duration-200 ease-in-out bg-gray-100 hover:text-gray-700" type="button">Sign In</a>
                            <a href="/auth/register" class="block w-full px-5 py-3 font-semibold text-center text-white transition duration-200 ease-in-out bg-gray-900 focus:ring focus:ring-gray-900 hover:bg-gray-900">Register</a>
                        </div>
                    @else
                        <x-app.user-menu position="bottom" class="border-black" />
                    @endif
                </div>
            </div>
        </nav>
    </div>
</section>
