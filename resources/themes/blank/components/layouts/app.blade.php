<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
</head>
<body x-data class="flex min-h-screen overflow-x-hidden  bg-gray-100 min-h-sreen @if($bodyClass ?? false){{ $bodyClass }}@endif">

    <x-app.sidebar />
    <div class="relative z-20 flex flex-col w-full h-full">
        {{-- Mobile Header --}}
        <header class="lg:hidden px-6 block flex justify-between sticky top-0 z-40 bg-gray-50 -mb-px border-b border-zinc-200/70 h-[72px] items-center">
            <button x-on:click="window.dispatchEvent(new CustomEvent('open-sidebar'));" class="flex items-center justify-center flex-shrink-0 w-10 h-10 border border-black hover:bg-gray-200">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" /></svg>
            </button>
            <x-app.user-menu position="top" />
        </header>
        {{-- End Mobile Header --}}
        <main class="w-full h-full p-6 lg:p-7 lg:pl-72">
            {{ $slot }}
        </main>
    </div>

    @livewire('notifications')
    @filamentScripts
    @livewireScripts
    
    {{ $javascript ?? '' }}

</body>
</html>
