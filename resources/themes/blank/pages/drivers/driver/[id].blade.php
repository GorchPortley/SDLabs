<?php

use Livewire\WithPagination;
use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use App\Models\Driver;

name('driver');

new class extends Component {

    use WithPagination;

    public Driver $driver;

    public function mount(string $id)
    {
        $this->driver = Driver::with([
            'designs.design'  // Load related designs with their parent design info
        ])->findOrFail($id);
    }
} ?>

<x-layouts.marketing>
    @volt('driver')
    <div class="bg-zinc-200 min-h-dvh dark:text-white">
        <main class="mx-auto bg-white max-w-7xl px-4 sm:px-6 lg:px-8 min-h-full py-4">
            <!-- Breadcrumb -->
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="/drivers" class="text-gray-500 hover:text-gray-700">Drivers</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900">{{ $driver->brand }} - {{ $driver->model }}</li>
                </ol>
            </nav>

            <hr class="mb-8 border-t-2 border-gray-300">

            <!-- Content Container -->
            <div class="space-y-8">
                <!-- Top Section: Image and Details -->
                <div class="lg:grid lg:grid-cols-2 lg:gap-8">
                    <!-- Left: Image Section -->
                    <div>
                                <img src="/storage/{{$driver->card_image}}" class="w-full h-full object-contain" alt="{{$driver->brand}} - {{$driver->model}}">
                        </div>

                    <!-- Right: Driver Info -->
                    <div class="space-y-6">
                        <!-- Title and Tag -->
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $driver->brand }}
                                - {{ $driver->model }}</h1>
                            @if($driver->tag)
                                <p class="mt-2 text-lg text-gray-600 italic">{{ $driver->tag }}</p>
                            @endif
                        </div>

                        <!-- Specifications -->
                        <div class="border-t border-gray-200 pt-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Specifications</h2>
                            <dl class="grid grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-sm text-gray-500">Power Handling</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ $driver->power }}W</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Impedance</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ $driver->impedance }}Ω</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Size</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ $driver->size }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500">Category</dt>
                                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ $driver->category }}</dd>
                                </div>
                                @if($driver->price)
                                    <div>
                                        <dt class="text-sm text-gray-500">Price</dt>
                                        <dd class="mt-1 text-lg font-medium text-gray-900">
                                            ${{ number_format($driver->price, 2) }}</dd>
                                    </div>
                                @endif
                                @if($driver->link)
                                    <div class="mt-4">
                                        <x-button  tag="a" href="{{ $driver->link }}"
                                           class="hover:bg-blue-800">
                                            View Manufacturer Page →
                                        </x-button>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Designs Using This Driver -->
                @if($driver->designs->count() > 0)
                    <div x-data="{ isOpen: true }" class="border-t border-gray-200 pt-8">
                        <button @click="isOpen = !isOpen"
                                class="flex items-center justify-between w-full text-xl font-semibold text-gray-900 pb-4 border-b-2 border-zinc-400">
                            <span>Designs Using This Driver</span>
                            <svg class="w-6 h-6 transition-transform" :class="{ 'rotate-180': !isOpen }" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="isOpen" class="divide-y divide-gray-200">
                            @foreach($driver->designs as $design)
                                <a href="/designs/design/{{$design->id}}">
                                    <div class="py-4">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900">
                                                    <a href="/designs/design/{{$design->design->id}}"
                                                       class="hover:text-blue-600">
                                                        {{ $design->design->name }}
                                                    </a>
                                                </h4>
                                                <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                                    <span>{{ $design->position }}</span>
                                                    <span>•</span>
                                                    <span>{{ $design->design->category }}</span>
                                                    <span>•</span>
                                                    <span>{{ $design->low_frequency }} Hz - {{ $design->high_frequency }} Hz</span>
                                                    <span>•</span>
                                                    <span>Qty: {{ $design->quantity }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Factory Specs -->
                @if($driver->factory_specs)
                    <div x-data="{ isOpen: true }">
                    <button @click="isOpen = !isOpen"
                            class="flex items-center justify-between w-full text-xl font-semibold text-gray-900 pb-4 border-b-2 border-zinc-400">
                        <span>Factory Data</span>
                        <svg class="w-6 h-6 transition-transform" :class="{ 'rotate-180': !isOpen }" fill="none"
                             stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="isOpen" class="border-t border-gray-200 pt-8">
                        <livewire:driverfrequencyresponseviewer :driver="$driver"></livewire:driverfrequencyresponseviewer>
                        <div>
                            <span class="text-xl justify-center font-semibold text-gray-900">T/S Parameters</span>
                        <dl class="grid grid-cols-2 gap-4 mx-20">
                            @foreach($driver->factory_specs as $key => $value)
                                <div class="flex justify-between col-span-2 py-2 border-b">
                                    <dt class="font-medium text-gray-600">{{ $key }}</dt>
                                    <dd class="text-gray-900">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                        </div>
                    </div>
                    </div>
                @endif

                <!-- Description Section -->
                <div class="border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                    <div class="prose max-w-none">
                        <iframe srcdoc="{{$driver->description}}" class="w-full"></iframe>
                    </div>
                </div>
            </div>
        </main>
    </div>
    @endvolt
</x-layouts.marketing>
