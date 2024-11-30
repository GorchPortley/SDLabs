<div class="grid grid-cols-1 lg:grid-cols-5 gap-6 p-4">
    @foreach($drivers as $driver)
        <div x-data="{ showSpecs: false }" class="relative flex flex-row xs:flex-col lg:flex-col bg-white rounded-lg border-2 border-gray-100 hover:shadow-lg shadow-sm overflow-hidden">
            <!-- Image Container: Using aspect-ratio for consistent sizing -->
            <div class="w-1/2 lg:w-full">
                <a href="/designs/design/{{$driver->id}}" class="">
                    <img src="/storage/{{$driver->card_image}}" class="w-full h-full object-cover" alt="{{$driver->brand}} - {{$driver->model}}">
                </a>
            </div>

            <!-- Official Badge -->
            @if($driver->official)
                <div class="absolute left-2 lg:left-auto lg:right-2 top-2 bg-yellow-400 text-white px-2 py-1 rounded-full flex items-center gap-1">
                    <i class="ph ph-seal-check"></i>
                </div>
            @endif

            <!-- Content Container: Removed fixed height constraints -->
            <div class="w-1/2 flex-shrink-0 lg:w-full flex flex-col">
                <h2 class="text-lg font-semibold text-center truncate px-4">{{$driver->model}}</h2>
                <p class="text-gray-600 text-center truncate px-4">By: {{$driver->brand}}</p>
                <p class="text-gray-800 text-center mb-2 px-4 truncate">{{$driver->tag}}</p>

                <table class="w-full mb-4">
                    <tbody>
                    <tr class="text-lg font-semibold text-center">
                        <td class="py-1">Size:</td>
                        <td class="text-gray-600 truncate max-w-[100px] py-1">{{$driver->size}}</td>
                    </tr>
                    <tr class="text-lg font-semibold text-center">
                        <td class="py-1">Category</td>
                        <td class="text-gray-600 truncate max-w-[100px] py-1">{{$driver->category}}</td>
                    </tr>
                    <tr class="text-lg font-semibold text-center">
                        <td class="py-1">Designs:</td>
                        <td class="text-gray-600 truncate max-w-[100px] py-1">{{$driver->designs->count()}}</td>
                    </tr>
                    </tbody>
                </table>

                <x-button tag="a" href="/drivers/driver/{{$driver->id}}" class="w-full mt-auto">View Driver</x-button>
            </div>

            <!-- Specs Slide-out Panel -->
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
                        <td class="p-2">Size:</td>
                        <td class="p-2">{{$driver->size}}</td>
                    </tr>
                    <tr class="border">
                        <td class="p-2">Impedance</td>
                        <td class="p-2">{{$driver->impedance}}</td>
                    </tr>
                    <tr class="border">
                        <td class="p-2">Power Handling</td>
                        <td class="p-2">{{$driver->power}}</td>
                    </tr>
                    </tbody>
                </table>

                <x-button @click="showSpecs = false" color="danger" class="w-full">Hide Specs</x-button>
            </div>

            <!-- Show Specs Button -->
            <x-button
                x-show="!showSpecs"
                @click="showSpecs = true"
                class="absolute right-0 top-0 h-full lg:relative"
                color="success">
                <p class="w-full h-full -rotate-90 lg:rotate-0">Show Specs</p>
            </x-button>
        </div>
    @endforeach

    <div class="col-span-full flex gap-4 justify-center mt-8">{{ $drivers->links() }}</div>
</div>
