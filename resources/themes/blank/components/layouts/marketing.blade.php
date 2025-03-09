<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" >
<head>
    
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-D6528Z9ZYR"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-D6528Z9ZYR');
</script>

    @include('theme::partials.head', ['seo' => ($seo ?? null) ])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @stack('head')
</head>
<body class="">
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
