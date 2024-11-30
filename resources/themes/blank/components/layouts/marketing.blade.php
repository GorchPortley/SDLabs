<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
</head>
<body class="flex flex-col min-h-screen overflow-x-hidden @if($bodyClass ?? false){{ $bodyClass }}@endif">

    <x-marketing.header />

    <main class="w-full h-full">
        {{ $slot }}
    </main>

    @livewire('notifications')
    @filamentScripts
    @livewireScripts

    {{ $javascript ?? '' }}

</body>
</html>
