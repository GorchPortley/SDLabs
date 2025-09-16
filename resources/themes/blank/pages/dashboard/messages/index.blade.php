<?php

use function Laravel\Folio\{middleware, name};

middleware('auth');
name('dashboard.messages');
?>

<x-layouts.app>
        <x-app.container>
    <x-app.heading
        title="Chat and Messages"
        description="Connect with fellow SDLabs users"
        border="true">
    </x-app.heading>

<iframe src="/chatify"
            class="rounded-lg"
            style="width: 100%; height:800px; overflow: hidden;"></iframe>
    </x-app.container>
</x-layouts.app>