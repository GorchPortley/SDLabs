<div>
<div class="grid flex-no-shrink grid-cols-1 lg:grid-cols-5 gap-6 p-4">
    @foreach($designs as $design)
        <div x-data="{ showSpecs: false }" wire:key="{{ $design->id }}" class="relative lg:h-[540px] h-[300px] flex flex-row xs:flex-col lg:flex-col bg-white rounded-lg border-2 border-gray-100 hover:shadow-lg shadow-sm overflow-hidden">
            <div class="w-1/2 h-full lg:w-full">
                <a href="/designs/design/{{$design->id}}" class="">
                    <img
                        src="/storage/{{ is_array($design->card_image) && !empty($design->card_image) ? $design->card_image[0] : $design->card_image }}"
                        class="w-[270px] h-[270px] object-scale-down"
                        alt="{{ $design->name }}"
                        onerror="this.src='/images/placeholder.jpg'"
                    >
                </a>
            </div>
            @if($design->official)
                <div class="absolute left-2 lg:left-auto lg:right-2 top-2 bg-yellow-400 text-white px-2 py-1 rounded-full flex items-center gap-1">
                    <i class="ph ph-seal-check"></i>
                </div>
            @endif
            <div class="w-1/2 h-full flex-grow-1 lg:w-full lg:h-[270px] flex flex-col">
                    <h2 class="text-lg font-semibold text-center truncate px-4">{{$design->name}}</h2>
                    <p class="text-gray-600 text-center truncate px-4">By: {{$design->designer?->name}}</p>
                    <p class="text-gray-800 text-center mb-2 px-4 truncate">{{$design->tag ?? 'A Nice Design'}}</p>
                    <table class="w-full mb-2">
                        <tbody>
                        <tr class="text-lg font-semibold text-center">
                            <td class="py-1">Category</td>
                            <td class="text-gray-600 truncate max-w-[100px]">{{$design->category}}</td>
                        </tr>
                        <tr class="text-lg font-semibold text-center">
                            <td class="py-1">Price</td>
                            <td class="text-gray-600 truncate max-w-[100px]">{{$design->price}}</td>
                        </tr>
                        <tr class="text-lg font-semibold text-center">
                            <td class="py-1">Build Cost:</td>
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
                    <x-button tag="a" href="/designs/design/{{$design->id}}" class="w-full">View Design</x-button>
                </div>

            </div>
            <div
                x-show="showSpecs"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full lg:translate-x-0 lg:translate-y-[100%]"
                x-transition:enter-end="translate-x-0 lg:translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="translate-x-0 lg:translate-y-0"
                x-transition:leave-end="translate-x-full lg:translate-x-0 lg:translate-y-[100%]"
                class="absolute right-0 lg:bottom-0 h-full lg:h-auto w-3/5 lg:w-full bg-white shadow-lg">
                <table class="w-full border-collapse">
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
                <x-button
                x-show="!showSpecs"
                @click="showSpecs = true"
                class="absolute right-0 top-0 h-full lg:relative"
                color="success">
                <p class="w-full -rotate-90 lg:rotate-0">Show Specs</p>
            </x-button>
        </div>
    @endforeach
</div>
</div>
