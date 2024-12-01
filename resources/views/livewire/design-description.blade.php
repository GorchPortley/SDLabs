<div class="mt-2">
    @if($this->hasAccess())
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex" aria-label="Design Information">
                @foreach($this->availableTabs as $tab => $enabled)
                    <x-button
                        wire:click="setActiveTab('{{ $tab }}')"
                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                            {{ $activeTab === $tab
                                ? 'border-primary text-primary'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                            }}"
                    >
                        @switch($tab)
                            @case('description')
                                Full Description
                                @break
                            @case('materials')
                                Bill of Materials
                                @break
                            @case('forum')
                                Discussion Forum
                                @break
                        @endswitch
                    </x-button>
                @endforeach
            </nav>
        </div>

        <div class="py-8">
            <!-- Description Tab -->
            @if($activeTab === 'description')
                <div
                    class="prose prose-zinc dark:prose-invert max-w-none"
                    wire:transition.opacity.duration.300ms
                >
                    @if($design->description)
                        <iframe srcdoc="{{$design->description}}" ></iframe>
                    @else
                        <p class="text-gray-500 italic">No description available</p>
                    @endif
                </div>
            @endif

            <!-- Materials Tab -->
            @if($activeTab === 'materials' && $design->bill_of_materials)
                <div wire:transition.opacity.duration.300ms>
                    <ul class="divide-y divide-gray-200">
                        @foreach($design->bill_of_materials as $material => $quantity)
                            <li class="py-3 flex justify-between items-center">
                                <span class="text-gray-900">{{ $material }}</span>
                                <div class="border-2-black flex items-center gap-4">
                                    <span class="text-gray-500">x{{ $quantity }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Forum Tab -->
            @if($activeTab === 'forum' && $design->forum_slug)
                <div wire:transition.opacity.duration.300ms>
                    <iframe
                        src="https://sandbox.sdlabs.cc/forum/embed/{{ $design->forum_slug }}"
                        class="w-full h-[800px]"
                        scrolling="auto"
                        title="Discussion Forum"
                    ></iframe>
                </div>
            @endif
        </div>

    @else
        <div class="w-full bg-zinc-600 p-8 rounded-lg text-center">
            @auth
                <p class="text-white text-lg">Sorry, you need Access for this section</p>
                <a
                    href="{{ route('shop.show', $design->id) }}"
                    class="mt-4 inline-block px-4 py-2 bg-white text-zinc-600 rounded-md hover:bg-zinc-100"
                >
                    Purchase Access
                </a>
            @else
                <p class="text-white text-lg">Sorry, you need to be logged in to access this section</p>
                <div class="mt-4 space-x-4">
                    <a
                        href="{{ route('login') }}"
                        class="inline-block px-4 py-2 bg-white text-zinc-600 rounded-md hover:bg-zinc-100"
                    >
                        Login
                    </a>
                    <a
                        href="{{ route('register') }}"
                        class="inline-block px-4 py-2 bg-white text-zinc-600 rounded-md hover:bg-zinc-100"
                    >
                        Register
                    </a>
                </div>
            @endauth
        </div>
    @endif
</div>
