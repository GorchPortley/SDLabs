<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="min-h-full">
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @stack('head')
</head>
<body class="min-h-full flex flex-col">
<x-marketing.header />

<main class="flex-grow">
    {{ $slot }}
</main>

@livewire('notifications')
@filamentScripts
@livewireScripts

{{ $javascript ?? '' }}
</body>
</html>
