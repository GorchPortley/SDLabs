<section x-data="{ mobileNavOpen: false }" class="overflow-hidden">
    <div class="flex items-center justify-between max-w-6xl p-5 mx-auto bg-white">
        <div class="w-auto">
            <div class="flex flex-wrap items-center">
                <div class="w-auto mr-14">
                    <a href="/">
                        <x-logo class="h-8"></x-logo>
                    </a>
                </div>
                <div class="hidden w-auto lg:block">
                    <ul class="flex items-center mr-16">
                        <li class="font-medium mr-9 hover:text-gray-700"><a href="/">Home</a></li>
                        <li class="font-medium mr-9 hover:text-gray-700"><a href="/blog">Blog</a></li>
                        <li class="font-medium mr-9 hover:text-gray-700"><a href="#">Features</a></li>
                        <li class="font-medium hover:text-gray-700"><a href="/pricing">Pricing</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="w-auto">
            <div class="flex flex-wrap items-center">
                @if(auth()->guest())
                    <div class="hidden w-auto mr-5 lg:block">
                        <div class="inline-block">
                            <a href="/auth/login" class="w-full px-5 py-3 font-medium transition duration-200 ease-in-out bg-transparent rounded-xl hover:text-gray-700" type="button">Sign In</a>
                        </div>
                    </div>
                    <div class="hidden w-auto lg:block">
                        <div class="inline-block">
                            <x-button size="md" tag="a" href="/auth/register">Try 14 Days Free Trial</x-button>
                        </div>
                    </div>
                @else
                    <div class="hidden w-auto lg:block">
                        <div class="inline-block">
                            <x-button size="md" tag="a" href="/dashboard">View Dashboard</x-button>
                        </div>
                    </div>
                @endif
                <div class="w-auto lg:hidden">
                    <button x-on:click="mobileNavOpen = !mobileNavOpen" class="p-3 text-white bg-gray-900">
                        <x-phosphor-list class="w-6 h-6" />
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
                        <li class="mb-12"><a class="font-medium hover:text-gray-700" href="/">Home</a></li>
                        <li class="mb-12"><a class="font-medium hover:text-gray-700" href="/blog">Blog</a></li>
                        <li class="mb-12"><a class="font-medium hover:text-gray-700" href="#_">Features</a></li>
                        <li><a class="font-medium hover:text-gray-700" href="/pricing">Pricing</a></li>
                    </ul>
                </div>
                <div class="flex flex-col justify-end w-full pb-8">
                    @if(auth()->guest())
                        <div class="flex flex-wrap w-full space-y-3">
                            <a href="/auth/login" class="block w-full px-5 py-3 font-medium text-center transition duration-200 ease-in-out bg-gray-100 hover:text-gray-700" type="button">Sign In</a>
                            <a href="/auth/register" class="block w-full px-5 py-3 font-semibold text-center text-white transition duration-200 ease-in-out bg-gray-900 focus:ring focus:ring-gray-900 hover:bg-gray-900">Try 14 Days Free Trial</a>
                        </div>
                    @else
                        <a href="/dashboard" class="block w-full px-5 py-3 font-semibold text-center text-white transition duration-200 ease-in-out bg-gray-900 focus:ring focus:ring-gray-900 hover:bg-gray-900">View Dashboard</a>
                    @endif
                </div>
            </div>
        </nav>
    </div>
</section>