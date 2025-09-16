<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 p-4">
    @foreach($drivers as $driver)
        <div x-data="{ showSpecs: false }" wire:key="{{$driver->id}}" class="relative flex flex-col bg-white rounded-lg border-2 border-gray-100 hover:shadow-lg shadow-sm overflow-hidden h-full">
            <!-- Driver image section -->
            <div class="relative pt-4 px-4">
                <!-- Official badge -->
                @if($driver->official)
                    <div class="absolute right-6 top-6 bg-yellow-400 text-white px-2 py-1 rounded-full flex items-center gap-1 z-10">
                        <i class="ph ph-seal-check"></i>
                    </div>
                @endif
                
                <a href="/drivers/driver/{{$driver->id}}" class="block w-full">
                    <div class="flex justify-center items-center h-48 md:h-56">
                        <img
                            src="/storage/{{ is_array($driver->card_image) && !empty($driver->card_image) ? $driver->card_image[0] : $driver->card_image }}"
                            class="max-h-full max-w-full object-contain"
                            alt="{{ $driver->model }}"
                            onerror="this.src='/images/placeholder.jpg'"
                        >
                    </div>
                </a>
            </div>
            
            <!-- Driver details section -->
            <div class="flex flex-col flex-grow p-4">
                <h2 class="text-lg font-semibold text-center mb-1 line-clamp-1">{{$driver->model}}</h2>
                <p class="text-gray-600 text-center text-sm mb-1 line-clamp-1">By: {{$driver->brand}}</p>
                <p class="text-gray-800 text-center text-sm mb-3 line-clamp-1">{{$driver->tag}}</p>
                
                <!-- Specs table -->
                <div class="mt-auto">
                    <table class="w-full mb-4 text-sm">
                        <tbody>
                            <tr>
                                <td class="py-1 font-medium">Size</td>
                                <td class="text-gray-600 text-right line-clamp-1">{{$driver->size}}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-medium">Category</td>
                                <td class="text-gray-600 text-right line-clamp-1">{{$driver->category}}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-medium">Designs</td>
                                <td class="text-gray-600 text-right">{{$driver->designs->count()}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Button section -->
                <div class="flex mt-2 gap-2">
                    <x-button tag="a" href="/drivers/driver/{{$driver->id}}" class="flex-grow">View</x-button>
                    <x-button 
                        @click="showSpecs = true" 
                        x-show="!showSpecs" 
                        color="success" 
                        class="px-3 whitespace-nowrap">
                        Specs
                    </x-button>
                </div>
            </div>
            
            <!-- Sliding specs panel -->
            <div
                x-show="showSpecs"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full"
                x-transition:enter-end="translate-y-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="translate-y-0"
                x-transition:leave-end="translate-y-full"
                class="absolute inset-0 bg-white shadow-lg flex flex-col">
                
                <div class="p-4 flex-grow overflow-auto">
                    <h3 class="text-lg font-semibold text-center mb-4">{{$driver->model}} Specifications</h3>
                    
                    <table class="w-full border-collapse text-sm">
                        <tbody>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Size</td>
                                <td class="py-2 text-right">{{$driver->size}}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Impedance</td>
                                <td class="py-2 text-right">{{$driver->impedance}}Ω</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Power Handling</td>
                                <td class="py-2 text-right">{{$driver->power}}W</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 pt-0">
                    <div class="flex gap-2">
                    <x-button tag="a" href="/drivers/driver/{{$driver->id}}" class="flex-grow">View Details</x-button>
                        <x-button @click="showSpecs = false" color="danger" class="flex-grow">Close</x-button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>