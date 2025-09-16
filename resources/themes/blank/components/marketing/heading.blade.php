@props([
    'level' => 'h1',
    'title' => 'No Heading Title Entered',
    'description' => 'Be sure to include the description attribute',
    'align' => 'center'
])


<div {{ $attributes->class([
        'relative w-full',
        'text-left' => $align == 'left',
        'text-right' => $align == 'right',
        'text-center' => $align != 'left' && $align != 'right'
    ]) }}>
    <{{ $level }} class="text-3xl sm:text-4xl font-medium sm:text-center text-left tracking-tighter lg:text-5xl text-balance">{!! $title!!}</{{ $level }}>
    <p class="mt-4 text-base font-medium text-gray-500 sm:text-center text-left sm:text-balance @if($align == 'left'){{ 'ml-auto' }}@elseif($align == 'right'){{ 'mr-auto' }}@else{{ 'mx-auto max-w-2xl' }}@endif">{!! $description !!}</p>
</div>