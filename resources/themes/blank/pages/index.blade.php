<?php
    use function Laravel\Folio\{name};
    name('home');
?>

<x-layouts.marketing
    :seo="[
        'title'         => setting('site.title', 'Laravel Wave'),
        'description'   => setting('site.description', 'Software as a Service Starter Kit'),
        'image'         => url('/og_image.png'),
        'type'          => 'website'
    ]"
>
    <div class="hidden lg:block">
        <livewire:banner-display  location="front_page"></livewire:banner-display>
    </div>
    <x-marketing.hero></x-marketing.hero>
    <x-marketing.features></x-marketing.features>
</x-layouts.marketing>
