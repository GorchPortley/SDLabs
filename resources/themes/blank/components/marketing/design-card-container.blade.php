<div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-4 p-4">
    @foreach($designs as $design)
        <div x-data="{ showSpecs: false }" wire:key="{{ $design->id }}" class="relative flex flex-col bg-white rounded-lg border-2 border-gray-100 hover:shadow-lg shadow-sm overflow-hidden h-full">
            <!-- Design image section -->
            <div class="relative pt-4 px-4">
                <!-- Official badge -->
                @if($design->official)
                    <div class="absolute right-6 top-6 bg-yellow-400 text-white px-2 py-1 rounded-full flex items-center gap-1 z-10">
                        <i class="ph ph-seal-check"></i>
                    </div>
                @endif
                
                <a href="/designs/design/{{$design->id}}" class="block w-full">
                    <div class="flex justify-center items-center h-48 md:h-56">
                        <img
                            src="/storage/{{ is_array($design->card_image) && !empty($design->card_image) ? $design->card_image[0] : $design->card_image }}"
                            class="max-h-full max-w-full object-contain"
                            alt="{{ $design->name }}"
                            onerror="this.src='/images/placeholder.jpg'"
                        >
                    </div>
                </a>
            </div>
            
            <!-- Design details section -->
            <div class="flex flex-col flex-grow p-4">
                <h2 class="text-lg font-semibold text-center mb-1 line-clamp-1">{{$design->name}}</h2>
                <p class="text-gray-600 text-center text-sm mb-1 line-clamp-1">By: {{$design->designer?->name}}</p>
                <p class="text-gray-800 text-center text-sm mb-3 line-clamp-1">{{$design->tag ?? 'A Nice Design'}}</p>
                
                <!-- Specs table -->
                <div class="mt-auto">
                    <table class="w-full mb-4 text-sm">
                        <tbody>
                            <tr>
                                <td class="py-1 font-medium">Category</td>
                                <td class="text-gray-600 text-right line-clamp-1">{{$design->category}}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-medium">Price</td>
                                <td class="text-gray-600 text-right">${{number_format($design->price, 2)}}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-medium">Build Cost</td>
                                <td class="text-gray-600 text-right">${{number_format($design->build_cost, 2)}}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-medium">Rating</td>
                                <td class="text-gray-600 text-right">
                                    <div class="flex items-center justify-end">
                                        @if($design->averageRating() > 0)
                                            <div class="flex">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= round($design->averageRating()))
                                                        <svg class="w-3 h-3 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-3 h-3 text-gray-300" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="ml-1">({{ $design->numberOfReviews() }})</span>
                                        @else
                                            <span>No ratings</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Button section -->
                <div class="flex mt-2 gap-2">
                    <x-button tag="a" href="/designs/design/{{$design->id}}" class="flex-grow">View</x-button>
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
                    <h3 class="text-lg font-semibold text-center mb-4">{{$design->name}} Specifications</h3>
                    
                    <table class="w-full border-collapse text-sm">
                        <tbody>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Impedance</td>
                                <td class="py-2 text-right">{{$design->impedance}}Ω</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Power Handling</td>
                                <td class="py-2 text-right">{{$design->power}}W</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Category</td>
                                <td class="py-2 text-right">{{$design->category}}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Build Cost</td>
                                <td class="py-2 text-right">${{number_format($design->build_cost, 2)}}</td>
                            </tr>
                            <tr class="border-b">
                                <td class="py-2 font-medium">Price</td>
                                <td class="py-2 text-right">${{number_format($design->price, 2)}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 pt-0">
                    <div class="flex gap-2">
                    <x-button tag="a" href="/designs/design/{{$design->id}}" class="flex-grow">View Details</x-button>
                        <x-button @click="showSpecs = false" color="danger" class="flex-grow">Close</x-button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
</div>