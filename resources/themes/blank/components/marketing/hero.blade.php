<section class="pt-16 sm:pt-24 lg:px-5 px-0 mx-auto max-w-5xl">
    <div class="space-y-5 px-5 lg:px-0 sm:text-center text-left">
        <h1 class="text-4xl sm:text-5xl font-bold tracking-tighter text-gray-900 md:text-6xl lg:text-7xl">
            Discover Your Next Favorite Speaker
        </h1>
        <p class="text-base font-medium text-gray-500 sm:text-lg">
            Discover, design, and bring your speaker ideas to life with SDLabs.
        </p>
        <x-button tag="a" class="animate-shimmer" href="https://www.sdlabs.cc/blog/site-news/welcome-to-the-sdlabscc-beta">Read an introduction</x-button>
        <x-button tag="a" href="https://www.sdlabs.cc/blog/tutorials/how-to-use-sdlabscc">How to use SDLabs</x-button>
        <div class="flex flex-col sm:flex-row sm:space-x-3 sm:space-y-0 space-y-3 w-full mx-auto items-stretch lg:items-center justify-center">
            @auth
                <x-button tag="a" href="{{route('dashboard')}}">Dashboard</x-button>
                <x-button tag="a" href="{{route('designs')}}">Browse Designs</x-button>
                <x-button tag="a" href="{{route('drivers')}}">Browse Drivers</x-button>
            @endauth
            @guest
            <x-button tag="a" href="{{ route('login') }}" >
                Login
            </x-button>
            <x-button tag="a" href="{{ route('register') }}" >
                Sign Up
            </x-button>
                @endguest
        </div>
    </div>
</section>
