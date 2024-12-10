<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
<head>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @stack('head')
</head>
<body class="bg-zinc-200">
<x-marketing.header />

<main >
    {!! $slot !!}
</main>

@livewire('notifications')
@filamentScripts
@livewireScripts

{{ $javascript ?? '' }}
</body>
</html>
