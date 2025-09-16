<x-layouts.marketing>

    <div class="lg:py-12">
        <div class="px-5 md:px-12 lg:px-0">
        <x-elements.back-button
            class="max-w-4xl mx-auto mt-8"
            text="Return Home"
            :href="route('home')"
        />
        </div>
        
        <x-container>
            <article id="post-{{ $page->id }}" class="max-w-4xl pb-20 mx-auto prose prose-md lg:prose-lg lg:px-0">

                <meta property="name" content="{{ $page->title }}">
                <meta property="author" typeof="Person" content="admin">
                <meta property="dateModified" content="{{ Carbon\Carbon::parse($page->updated_at)->toIso8601String() }}">
                <meta property="datePublished" content="{{ Carbon\Carbon::parse($page->created_at)->toIso8601String() }}">

                <div class="max-w-4xl mx-auto mt-6">
                    <h1 class="flex flex-col leading-none">
                        <span>{{ $page->title }}</span>
                    </h1>
                </div>

                @if($page->image)
                    <div class="relative">
                        <img class="w-full h-auto" src="{{ $page->image() }}" alt="{{ $page->title }}" srcset="{{ $page->image() }}">
                    </div>
                @endif

                <div class="max-w-4xl mx-auto">
                    {!! $page->body !!}
                </div>

            </article>
        </x-container>
    </div>

</x-layouts.marketing>
