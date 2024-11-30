<?php
use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Driver;

name('drivers');

new class extends Component {

    use WithPagination;

    public function with(): array
    {
        $user = auth()->user()?->load('cart.items');
        $cartItems = $user?->cart?->items->pluck('design_id')->toArray() ?? [];

        return [
            'drivers' => Driver::query()
                ->where('active', 1)
                ->with('designs')
                ->paginate(12),
            'cartItems' => $cartItems
        ];
    }
}?>

<x-layouts.marketing>
    @volt('drivers')
    <div >
        <div class="flex h-full w-full bg-gray-300 rounded-md">
            <img src="https://placehold.co/1920x300">
        </div>
        <div class="flex w-full h-full mt-5">
            <div class="hidden lg:flex h-full lg:w-1/5 rounded-md">Tex Test</div>
            <div class="flex flex-col h-full w-full lg:w-4/5 rounded-md"><x-marketing.driver-card-container wire:key="{{$drivers}}" :drivers="$drivers"/>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>

