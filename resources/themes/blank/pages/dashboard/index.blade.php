<?php
    use function Laravel\Folio\{middleware, name};
	middleware('auth');
    name('dashboard');
?>

<x-layouts.app>
    @volt('dashboard')
<div>
	<h1>Welcome to the SDLabs.cc Beta!</h1>
<p>If you have questions, please head to the Soap Box to read an introduction and some walkthroughs about what SDLabs.cc is about!</p>
    <p>feel free to email Adrian at admin@sdlabs.cc for any further questions or discussions!</p>
    <x-button tag="a" href="https://www.sdlabs.cc/blog/site-news/welcome-to-the-sdlabscc-beta">Read the introduction here</x-button>
    <x-button tag="a" href="https://www.sdlabs.cc/blog/">Browse the SoapBox!</x-button>
</div>
    @endvolt
</x-layouts.app>
