<div class="w-full flex items-center justify-start sm:justify-center">
    <ul class="inline-flex px-3 py-2 mt-7 w-auto text-xs font-medium border border-black">
        <li class="mr-4 font-bold uppercase text-zinc-800 lg:block hidden">Categories:</li>
        <li class="@if(!isset($category)){{ 'text-zinc-800' }}@endif"><a href="{{ route('blog') }}">View All</a></li>
        <li class="mx-2">&middot;</li>
        @foreach($categories as $cat)
            <li class="@if(isset($category) && isset($cat->slug)){{ 'text-zinc-800' }}@endif"><a href="{{ route('blog.category', ['category' => $cat]) }}"> {{ $cat->name }} </a></li>
            @if(!$loop->last)
                <li class="mx-2">-</li>
            @endif
        @endforeach
    </ul>
</div>
