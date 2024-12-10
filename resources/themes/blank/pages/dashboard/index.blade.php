<?php
    use function Laravel\Folio\{middleware, name};
	middleware('auth');
    name('dashboard');
?>

<x-layouts.app>
    @volt('dashboard')

	<h1>This will show a lot of cool things i think</h1>

    @endvolt
</x-layouts.app>
