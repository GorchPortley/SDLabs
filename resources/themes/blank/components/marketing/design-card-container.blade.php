<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-4">
    @foreach($designs as $design)
        <div x-data="{
    currentIndex: 0,
    images: {{ Js::from(is_array($design->card_image) ? $design->card_image : [$design->card_image]) }},

    next() {
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
    },
    previous() {
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
    }"
    class="relative flex lg:flex-col flex-row h-[300px] lg:h-full w-full bg-white rounded-lg border-2 border-gray-100 hover:shadow-lg shadow-sm overflow-hidden">

            {{-- Image Section --}}
            <div class="w-1/2 lg:w-full flex-shrink-0 relative">
                <!-- Current image -->
                <template x-if="images.length > 0">
                    <img
                        :src="`/storage/${images[currentIndex]}`"
                        :alt="`Image ${currentIndex + 1} of ${images.length}`"
                        class="w-full h-full object-cover"
                    >
                </template>

                <!-- Navigation buttons -->
                <div x-show="images.length > 1" class="absolute inset-0 flex items-center justify-between p-4">
                    <button
                        @click="previous()"
                        class="bg-black/50 text-white p-2 rounded-full hover:bg-black/70"
                    >
                        ←
                    </button>
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

            @if($design->official)
                <div class="absolute left-2 lg:left-auto lg:right-2 top-2 bg-yellow-400 text-white px-2 py-1 rounded-full flex items-center gap-1 z-10">
                    <i class="ph ph-seal-check"></i>
                </div>
            @endif

            {{-- Details Section --}}
            <div class="w-1/2 lg:w-full flex flex-col justify-center">
                <h2 class="text-lg font-semibold text-center h-7 overflow-hidden text-ellipsis">{{$design->name}}</h2>
                <p class="text-gray-600 text-center h-6 overflow-hidden text-ellipsis">By: {{$design->designer?->name}}</p>
                <p class="text-gray-800 text-center mb-2 h-6 overflow-hidden text-ellipsis">{{$design->tag}}</p>

                <table class="w-full">
                    <tbody>
                    <tr class="text-lg font-semibold text-center h-8">
                        <td>Category</td>
                        <td class="text-gray-600 truncate max-w-[100px]">{{$design->category}}</td>
                    </tr>
                    <tr class="text-lg font-semibold text-center h-8">
                        <td>Price</td>
                        <td class="text-gray-600 truncate max-w-[100px]">{{$design->price}}</td>
                    </tr>
                    <tr class="text-lg font-semibold text-center h-8">
                        <td>Build Cost:</td>
                        <td class="text-gray-600 truncate max-w-[100px]">{{$design->build_cost}}</td>
                    </tr>
                    </tbody>
                </table>

                <div class="flex flex-col lg:flex-row w-full">
                    <livewire:add-to-cart-button
                        :design="$design"
                        :isInCart="in_array($design->id, $cartItems ?? [])"
                        wire:key="cart-{{ $design->id }}"
                    />
                    <x-button tag="a" href="/designs/design/{{$design->id}}" class="w-full m-0">View Design</x-button>
                </div>
            </div>

            {{-- Specs Panel --}}
            <div
                x-show="showSpecs"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full lg:translate-x-0 lg:translate-y-[100%]"
                x-transition:enter-end="translate-x-0 lg:translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="translate-x-0 lg:translate-y-0"
                x-transition:leave-end="translate-x-full lg:translate-x-0 lg:translate-y-[100%]"
                class="absolute right-0 lg:bottom-0 h-full lg:h-auto w-3/5 lg:w-full bg-white shadow-lg"
            >
                <table class="w-full border-collapse mb-4">
                    <tbody>
                    <tr class="border">
                        <td class="p-2">Impedance</td>
                        <td class="p-2">{{$design->impedance}}</td>
                    </tr>
                    <tr class="border">
                        <td class="p-2">Power Handling</td>
                        <td class="p-2">{{$design->power}}</td>
                    </tr>
                    </tbody>
                </table>
                <x-button @click="showSpecs = false" color="danger" class="w-full">Hide Specs</x-button>
            </div>

            {{-- Specs Button --}}
            <x-button
                x-show="!showSpecs"
                @click="showSpecs = true"
                class="lg:w-full w-[30px] h-full"
                color="success"
            >
                <p class="-rotate-90 lg:rotate-0">Show Specs</p>
            </x-button>
        </div>
    @endforeach
</div>
