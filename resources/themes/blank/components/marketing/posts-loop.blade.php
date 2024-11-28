<!-- Loop Through Posts Here -->
@foreach($posts as $post)
    <article id="post-{{ $post->id }}" class="overflow-hidden relative col-span-2 bg-white border border-black cursor-pointer sm:col-span-1" onClick="window.location='{{ $post->link() }}'">
        <meta property="name" content="{{ $post->title }}">
        <meta property="author" typeof="Person" content="admin">
        <meta property="dateModified" content="{{ Carbon\Carbon::parse($post->updated_at)->toIso8601String() }}">
        <meta class="uk-margin-remove-adjacent" property="datePublished" content="{{ Carbon\Carbon::parse($post->created_at)->toIso8601String() }}">

        <img src="{{ $post->image() }}" class="w-full h-auto">
        <div class="p-5">
            <h2 class="text-lg font-semibold leading-6 text-gray-900 group-hover:text-gray-600">
                <a href="{{ $post->link() }}">
                    <span class="absolute inset-0"></span>
                    {{ $post->title }}
                </a>
            </h2>
            <p class="mt-5 text-sm leading-6 text-gray-600 line-clamp-3">{{ substr(strip_tags($post->body), 0, 110) }}@if(strlen(strip_tags($post->body)) > 200){{ '...' }}@endif</p>
            
        </div>
    </article>
@endforeach
<!-- End Post Loop Here -->