@push('head') <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
@endpush

<div x-data="{ selectedTab: 'summary' }" class="w-full">
    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex gap-2 overflow-x-auto border-b border-neutral-300 dark:border-neutral-700" role="tablist" aria-label="tab options">
        <button @click="selectedTab = 'summary'" :aria-selected="selectedTab === 'summary'" :tabindex="selectedTab === 'summary' ? '0' : '-1'" :class="selectedTab === 'summary' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelSummary" >Summary</button>
        <button @click="selectedTab = 'description'" :aria-selected="selectedTab === 'description'" :tabindex="selectedTab === 'description' ? '0' : '-1'" :class="selectedTab === 'description' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelDescription" >Description</button>
        <button @click="selectedTab = 'discussion'" :aria-selected="selectedTab === 'discussion'" :tabindex="selectedTab === 'discussion' ? '0' : '-1'" :class="selectedTab === 'discussion' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelDiscussion" >Discussion</button>
        <button @click="selectedTab = 'components'" :aria-selected="selectedTab === 'components'" :tabindex="selectedTab === 'components' ? '0' : '-1'" :class="selectedTab === 'components' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelComponents" >Component Details</button>
        <button @click="selectedTab = 'files'" :aria-selected="selectedTab === 'files'" :tabindex="selectedTab === 'files' ? '0' : '-1'" :class="selectedTab === 'files' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelFiles" >Files</button>
    </div>
    <div class="px-2 py-4 text-neutral-600 dark:text-neutral-300">
        <div x-show="selectedTab === 'summary'" id="tabpanelSummary" class="w-full" role="tabpanel" aria-label="summary">
                <livewire:frequency-response-viewer :design="$design" />
            <div>{!! $design->summary !!}</div>
        </div>
        <div class="w-full " x-show="selectedTab === 'description'" id="tabpanelDescription" role="tabpanel" aria-label="description">
            @if($this->hasAccess)
                <div>{!! $design->description !!}</div>
                    @else
                <div class="w-full h-auto bg-zinc-600 p-8 rounded-lg text-center">
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
        </div>
        <div class="w-full h-auto min-h-[200px]" x-show="selectedTab === 'discussion'" id="tabpanelDiscussion" role="tabpanel" aria-label="discussion">
            @if($this->hasAccess)
                <iframe src="https://sandbox.sdlabs.cc/forum/embed/{{ $design->forum_slug }}" width="100%" height="800px"></iframe>
            @else
                <div class="w-full h-auto bg-zinc-600 p-8 rounded-lg text-center">
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
        </div>

        <div x-show="selectedTab === 'components'" id="tabpanelcomponents" role="tabpanel" class="w-full h-auto min-h-[200px]" aria-label="components">
            @if($this->hasAccess)
                <div>
                    <div class="w-full">
                        @foreach($design->components as $component)
                            <div x-data="{ isOpen: false }" class="border-t border-gray-200 pt-8">
                                <button @click="isOpen = !isOpen" class="flex items-center justify-between w-full text-xl font-semibold text-gray-900 pb-4 border-b-2 border-zinc-400">
                                    <span>
                                        {{$component->driver->brand}} - {{$component->driver->model}}
                                    </span>
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
                                    <iframe class="w-full h-auto min-h-[200px]" srcdoc="{{$component->description}}" sandbox seamless></iframe>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="w-full h-auto bg-zinc-600 p-8 rounded-lg text-center">
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
        </div>

        <div class="" x-show="selectedTab === 'files'" id="tabpanelFiles" role="tabpanel" aria-label="files">
            @if($this->hasAccess)
                <div>
                        <iframe width="100%" height="800px" src="/filemanager"></iframe>
                </div>
            @else
                <div class="w-full h-auto bg-zinc-600 p-8 rounded-lg text-center">
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
        </div>
    </div>
</div>
