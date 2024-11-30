<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Design;

name('designs');

new class extends Component {
    use WithPagination;

    public function with(): array
    {
        $user = auth()->user()?->load('cart.items');
        $cartItems = $user?->cart?->items->pluck('design_id')->toArray() ?? [];

        return [
            'designs' => Design::query()
                ->where('active', 1)
                ->with(['designer', 'sales' => function($query) {
                    $query->where('user_id', auth()->id());
                }])
                ->paginate(12),
            'cartItems' => $cartItems
        ];
    }
}?>

<x-layouts.marketing>
    @volt('designs')
    <div >
        <div class="flex h-full w-full bg-gray-300 rounded-md">
            <img src="https://placehold.co/1920x300">
        </div>
        <div class="flex w-full h-full mt-5">
            <div class="hidden lg:flex h-full lg:w-1/5 rounded-md">Tex Test</div>
            <div class="flex flex-col h-full w-full lg:w-4/5 rounded-md"><x-marketing.design-card-container wire:key="{{$designs}}" :designs="$designs"/>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>

