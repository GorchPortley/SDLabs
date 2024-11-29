<div class="w-full">
    @guest
        <x-button class="w-full" color="danger" tag="a" href="{{route('login')}}">Log In to Purchase</x-button>
    @endguest
    @auth
        @if($design->price <= 0)
            @if($design->sales->isNotEmpty())
                <x-button class="w-full" disabled color="success">Design Owned</x-button>
            @else
                <x-button class="w-full" wire:click="addToCart" wire:loading.attr="disabled" color="success">
                    <span wire:loading.remove>Free Design</span>
                    <span wire:loading>Adding...</span>
                </x-button>
            @endif
        @else
            @if($isInCart)
                <x-button class="w-full" disabled>Design Added</x-button>
            @else
                <x-button class="w-full" wire:click="addToCart" wire:loading.attr="disabled" color="danger">
                    <span wire:loading.remove>Add to Cart</span>
                    <span wire:loading>Adding...</span>
                </x-button>
            @endif
        @endif
    @endauth
</div>
