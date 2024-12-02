<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use App\Models\Design;

name('design');


new class extends Component {
    public Design $design;
    public array $cartItems = [];

    public function mount(string $id)
    {
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
    }
} ?>

<x-layouts.marketing>
    @volt('design')
    <div class="bg-zinc-200 min-h-dvh dark:text-white">
        <main class="mx-auto bg-white max-w-7xl px-4 sm:px-6 lg:px-8 min-h-full py-4">
            <!-- Breadcrumb -->
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="/designs" class="text-gray-500 hover:text-gray-700">Designs</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900">{{ $design->name }}</li>
                </ol>
            </nav>

            <hr class="mb-8 border-t-2 border-gray-300">

            <!-- Content Container -->
            <div class="space-y-8">
                <!-- Top Section: Image and Details -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-8">
                    <div class="mb-6 lg:mb-0">
                        <div x-data="{
    currentIndex: 0,
    images: {{ Js::from(is_array($design->card_image) ? $design->card_image : [$design->card_image]) }},

    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
    },
    previous() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
    }
}" class="relative aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                            <!-- Current image -->
                            <template x-if="images.length > 0">
                                <img
                                    :src="`/storage/${images[currentIndex]}`"
                                    :alt="`Image ${currentIndex + 1} of ${images.length}`"
                                    class="w-full h-full object-contain"
                                >
                            </template>

                            <!-- Navigation buttons - only show if there are multiple images -->
                            <div x-show="images.length > 1"
                                 class="absolute inset-0 flex items-center justify-between p-4">
                                <!-- Previous -->
                                <button
                                    @click="previous()"
                                    class="bg-black/50 text-white p-2 rounded-full hover:bg-black/70"
                                >
                                    ←
                                </button>

                                <!-- Next -->
                                <button
                                    @click="next()"
                                    class="bg-black/50 text-white p-2 rounded-full hover:bg-black/70"
                                >
                                    →
                                </button>
                            </div>

                            <!-- Image counter -->
                            <div
                                x-show="images.length > 1"
                                class="absolute bottom-4 right-4 bg-black/50 text-white px-2 py-1 rounded text-sm"
                            >
                                <span x-text="`${currentIndex + 1} / ${images.length}`"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Design Info -->
                    <div class="space-y-6">
                        <!-- Title and Tag -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $design->name }}</h1>
                            @if($design->tag)
                                <p class="mt-2 text-lg text-gray-600 italic">{{ $design->tag }}</p>
                            @endif
                        </div>

                        <!-- Designer Info -->
                        <div>
                            <p class="text-sm text-gray-500">Designed by</p>
                            <p class="text-lg font-medium text-gray-900">{{ $design->designer->name ?? 'Speaker Designer' }}</p>
                        </div>

                        <!-- Specifications -->
                        <div class="border-t border-gray-200 pt-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Specifications</h2>
                            <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
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
                                    <dd class="mt-1 text-lg font-medium text-gray-900">
                                        ${{ number_format($design->price, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Estimated Build Cost</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">
                                        ${{ number_format($design->build_cost, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Category</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ $design->category }}</dd>
                                </div>
                                <div class="flex items-center">
                                    <livewire:add-to-cart-button
                                        :design="$design"
                                        :isInCart="in_array($design->id, $cartItems)"
                                        wire:key="cart-{{ $design->id }}"
                                    />
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
                <!-- Components Section - Now Collapsible -->
                @if($design->components->count() > 0)
                    <div x-data="{ isOpen: true }" class="border-t border-gray-200 pt-8">
                        <button @click="isOpen = !isOpen" class="flex items-center justify-between w-full text-xl font-semibold text-gray-900 pb-4 border-b-2 border-zinc-400">
                            <span>File Overview</span>
                            <svg
                                class="w-6 h-6 transition-transform"
                                :class="{ 'rotate-180': !isOpen }"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            x-show="isOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-2"
                            class="divide-y divide-gray-200"
                        >
                            <div class="w-full gap-4">
                                <dl class="space-y-1">
                                    <div class="flex justify-between">
                                        <dt class="">Design Enclosure Files:</dt>
                                        <dd>{{ json_decode(count($design->enclosure_files)) }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="">Design Electronics Files:</dt>
                                        <dd>{{ json_decode(count($design->electronic_files))}}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="">Design Other Files:</dt>
                                        <dd>{{ json_decode(count($design->design_other_files))}}</dd>
                                    </div>
                                </dl>
                            </div>
                            @foreach($design->components as $component)
                                <div x-data="{ showDetails: false }" class="border-b last:border-b-0">
                                    <div
                                        @click="showDetails = !showDetails"
                                        class="py-4 cursor-pointer hover:bg-gray-50 transition-colors"
                                    >
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                        <span
                            class="transform transition-transform duration-200"
                            :class="{ 'rotate-90': showDetails }"
                        >
                            →
                        </span>
                                                    <h4 class="text-lg font-medium text-gray-900">
                                                        {{$component->driver->brand}} - {{$component->driver->model}}
                                                    </h4>
                                                </div>
                                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                    <span>{{$component->position}}</span>
                                                    <span>•</span>
                                                    <span>{{$component->driver->size}}</span>
                                                    <span>•</span>
                                                    <span>{{$component->driver->category}}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4 text-right">
                                                <span class="text-sm font-medium text-gray-900">Qty: {{$component->quantity}}</span>
                                                <div class="mt-1 text-sm text-gray-500">
                                                    {{$component->low_frequency}} Hz - {{$component->high_frequency}} Hz
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Expandable Details Section -->
                                    <div
                                        x-show="showDetails"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                                        class="bg-gray-50 p-4"
                                    >
                                        <div class="w-full gap-4">
                                            <dl class="space-y-1">
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-600">Frequency Files:</dt>
                                                    <dd>{{ json_decode(count($component->frequency_files)) }}</dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-600">Impedance Files:</dt>
                                                    <dd>{{ json_decode(count($component->impedance_files))}}</dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="text-gray-600">Other Files:</dt>
                                                    <dd>{{ json_decode(count($component->other_files))}}</dd>
                                                </div>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <!-- Description Section -->
                <div class="border-t border-gray-200 pt-8">
                    <livewire:design-description :design="$design"/>
                </div>
            </div>
        </main>
    </div>
    @endvolt
</x-layouts.marketing>
