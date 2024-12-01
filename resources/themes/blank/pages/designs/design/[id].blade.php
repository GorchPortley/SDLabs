<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use App\Models\Design;

name('design');


new class extends Component {
    public Design $design;
    public array $cartItems = [];

    public function mount(string $id) {
        $user = auth()->user()?->load('cart.items');
        $this->cartItems = collect($user?->cart?->items ?? [])->pluck('design_id')->toArray();

        $this->design = Design::with([
            'designer',
            'components.driver',
            'sales' => fn($q) => $q->where('user_id', auth()->id())
        ])->findOrFail($id);
    }

    public function with(): array
    {
        return [
            'cartItems' => $this->cartItems
        ];
    }} ?>

<x-layouts.marketing>
    @volt('design')
    <div class="bg-white dark:bg-zinc-900 dark:text-white" x-data="{
        publicSectionOpen: true,
        lockedSectionOpen: true
    }">
        <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex py-4" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="/designs" class="text-gray-500 hover:text-gray-700">Designs</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900">{{ $design->name }}</li>
                </ol>
            </nav>
            <hr class="my-6 border-t-2 border-gray-300">

{{--Begin Key Details section--}}

            <div class="">
                <!-- Image section -->
                <div class="lg:grid lg:grid-cols-2">
                    <div class="aspect-w-16 aspect-h-9 w-full rounded-lg mb-2 lg:mb-0 flex items-center justify-center">
                        <img
                            src="{{$appUrl = config('app.url')}}/storage/{{ $design->card_image }}"
                            alt="{{ $design->name }}"
                            class="w-full h-auto object-contain"
                        >
                    </div>

                <!-- Design info -->
                <div class="px-4 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $design->name }}</h1>
                    @if($design->tag)
                        <p class="mt-1 text-lg text-gray-600 italic">{{ $design->tag }}</p>
                    @endif

                    <div class="mt-2">
                        <p class="text-sm text-gray-500">Designed by</p>
                        <p class="text-lg font-medium text-gray-900">{{ $design->designer->name ?? 'Speaker Designer' }}</p>
                    </div>

                    <!-- Key Specifications -->
                    <div class="mt-2 border-t border-gray-200 pt-4">
                        <h2 class="text-xl font-semibold text-gray-900">Specifications</h2>
                        <dl class="mt-2 grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-gray-500">Power Handling</dt>
                                <dd class="mt-1 text-lg font-medium text-gray-900">{{ $design->power }}W</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Impedance</dt>
                                <dd class="mt-1 text-lg font-medium text-gray-900">{{ $design->impedance }}Ω</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Plans Price</dt>
                                <dd class="mt-1 text-lg font-medium text-gray-900">${{ number_format($design->price, 2) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Estimated Build Cost</dt>
                                <dd class="mt-1 text-lg font-medium text-gray-900">${{ number_format($design->build_cost, 2) }}</dd>
                            </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Category</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ $design->category }}</dd>
                                </div>
                            <div>
                                <dd class="mt-4">
                                    <livewire:add-to-cart-button
                                        :design="$design"
                                        :isInCart="in_array($design->id, $cartItems)"
                                        wire:key="cart-{{ $design->id }}" /></dd>
                            </div>
                        </dl>
                    </div>
                </div>
{{--      End Key Details Section              --}}
                </div>
                    {{-- Collapsible Public Section --}}
                    <div class="mt-8 col-span-2">
                        <button
                            @click="publicSectionOpen = !publicSectionOpen"
                            class="w-full flex justify-between items-center p-4 bg-gray-100 hover:bg-gray-200 rounded-t-lg transition-colors"
                        >
                            <h2 class="text-xl font-bold">Frequency Response & Design Overview</h2>
                            <span x-text="publicSectionOpen ? '−' : '+'" class="text-2xl"></span>
                        </button>

                        <div x-show="publicSectionOpen" x-transition>
                            <div class="w-auto py-4 col-span-2">
                                <livewire:frequency-response-viewer :design="$design" />


                            <div>
                                <div class="border-gray-200 pt-8">
                                    <h2 class="text-xl font-semibold text-gray-900">About this Design</h2>
                                    <div class="mt-4">
                                        <x-safe-html-renderer :content="$design->summary" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    {{-- Collapsible Locked Section --}}
                    <div class="mt-8">
                        <button
                            @click="lockedSectionOpen = !lockedSectionOpen"
                            class="w-full flex justify-between items-center p-4 bg-gray-100 hover:bg-gray-200 rounded-t-lg transition-colors"
                        >
                            <h2 class="text-xl font-bold">Build Details & Full Description</h2>
                            <span x-text="lockedSectionOpen ? '−' : '+'" class="text-2xl"></span>
                        </button>
                        <div x-show="lockedSectionOpen" x-transition>
                            <livewire:design-description :design="$design" />
                        </div>
    @endvolt
</x-layouts.marketing>
