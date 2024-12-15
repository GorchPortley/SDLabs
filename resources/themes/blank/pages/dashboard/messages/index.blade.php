<?php

use function Laravel\Folio\{middleware, name};

middleware('auth');
name('dashboard.messages');
?>

<x-layouts.app>
    <x-app.heading
        title="Chat and Messages"
        description="Connect with fellow SDLabs users">
    </x-app.heading>
    <iframe src="/chatify"
            class="rounded-lg"
            style="width: 100%; height:800px; overflow: hidden;"></iframe>
</x-layouts.app>
