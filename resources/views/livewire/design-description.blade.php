<div class="w-full mt-2">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex" aria-label="Design Information">
            <x-button
                wire:click="setActiveTab('tab1')"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $activeTab === 'tab1'
                        ? 'border-primary text-primary'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    }}"
            >
                Summary
            </x-button>
            <x-button
                wire:click="setActiveTab('tab2')"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $activeTab === 'tab2'
                        ? 'border-primary text-primary'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    }}"
            >
               Description
            </x-button>
            <x-button
                wire:click="setActiveTab('tab3')"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $activeTab === 'tab3'
                        ? 'border-primary text-primary'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    }}"
            >
                Discussion
            </x-button>
            <x-button
                wire:click="setActiveTab('tab4')"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $activeTab === 'tab4'
                        ? 'border-primary text-primary'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    }}"
            >
                Files
            </x-button>
            <x-button
                wire:click="setActiveTab('tab5')"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm
                    {{ $activeTab === 'tab5'
                        ? 'border-primary text-primary'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    }}"
            >
                Coming Soon
            </x-button>
        </nav>
    </div>

    <div class="h-screen flex-grow w-full py-8">
        <!-- Tab 1 Content -->
        @if($activeTab === 'tab1')
            <div wire:transition.opacity.duration.100ms>
                <div class="w-full h-full">
                    <livewire:frequency-response-viewer :design="$design" />
                    <iframe class="w-full h-full min-h-[2400px]" srcdoc="{{ $design->summary }}"></iframe>
                </div>
            </div>
        @endif

        <!-- Tab 2 Content (Protected Example) -->
        @if($activeTab === 'tab2')
            @if($this->hasAccess)
                <div wire:transition.opacity.duration.100ms>
                    <div class="w-full h-full">
                        <iframe class="w-full h-full min-h-[1600px]" srcdoc="{{ $design->description }}"></iframe>
                    </div>
                </div>
            @else
                <div class="w-full bg-zinc-600 p-8 rounded-lg text-center">
                    @auth
                        <p class="text-white text-lg">Sorry, you need Access for this section</p>
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
        @endif

        <!-- Tab 3 Content -->
        @if($activeTab === 'tab3')
            @if($this->hasAccess)
                <div wire:transition.opacity.duration.100ms>
                    <div class="w-full h-full">
                        <iframe src="https://sandbox.sdlabs.cc/forum/embed/{{ $design->forum_slug }}" class="w-full min-h-[1600px]"></iframe>
                    </div>
                </div>
            @else
                <div class="w-full bg-zinc-600 p-8 rounded-lg text-center">
                    @auth
                        <p class="text-white text-lg">Sorry, you need Access for this section</p>
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
        @endif

        <!-- Tab 4 Content -->
        @if($activeTab === 'tab4')
            @if($this->hasAccess)
                <div wire:transition.opacity.duration.300ms>
                    <div class="w-full h-full">
                        <iframe class="w-full h-[800px]" src="/filemanager?&path=/files/{{ $design->designer->id }}/{{ $design->name }}/"></iframe>
                    </div>
                </div>
            @else
                <div class="w-full bg-zinc-600 p-8 rounded-lg text-center">
                    @auth
                        <p class="text-white text-lg">Sorry, you need Access for this section</p>
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
        @endif

        <!-- Tab 5 Content -->
        @if($activeTab === 'tab5')
            @if($this->hasAccess)
                <div wire:transition.opacity.duration.300ms>
                    <div class="w-full h-full"></div>
                </div>
            @else
                <div class="w-full bg-zinc-600 p-8 rounded-lg text-center">
                    @auth
                        <p class="text-white text-lg">Sorry, you need Access for this section</p>
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
        @endif
    </div>
</div>
