<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="">
<head>
   
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-D6528Z9ZYR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-D6528Z9ZYR');
</script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @stack('head')
</head>
<body class="min-h-screen">
<x-marketing.header />
<main>
    {!! $slot !!}
</main>
{{ $javascript ?? '' }}
@livewire('notifications')
@filamentScripts
@livewireScripts

</body>
</html>