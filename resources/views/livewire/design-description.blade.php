@push('head') <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
<style>
    .tiptap-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.25rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .tiptap-content table th,
    .tiptap-content table td {
        border: 1px solid #e0e0e0;
        padding: 0.75rem;
        text-align: left;
    }

    .tiptap-content table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .tiptap-content table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .tiptap-content table tr:hover {
        background-color: #f0f0f0;
    }

    .tiptap-content table th p,
    .tiptap-content table td p {
        margin: 0;
        padding: 0;
    }

    .tiptap-content .filament-tiptap-grid-builder {
        display: grid;
        gap: 1rem;
        width: 100%;
    }

    .tiptap-content .filament-tiptap-grid-builder[data-cols="3"] {
        grid-template-columns: repeat(3, 1fr);
    }

    .tiptap-content .filament-tiptap-grid-builder[data-cols="2"] {
        grid-template-columns: repeat(2, 1fr);
    }

    .tiptap-content .filament-tiptap-grid-builder[data-cols="4"] {
        grid-template-columns: repeat(4, 1fr);
    }

    .tiptap-content .filament-tiptap-grid-builder__column {
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .tiptap-content .filament-tiptap-grid-builder__column h2 {
        margin-bottom: 0.75rem;
    }

    @media (max-width: 640px) {
        .tiptap-content .filament-tiptap-grid-builder {
            grid-template-columns: 1fr !important;
        }

        .tiptap-content table {
            font-size: 0.875rem;
        }

        .tiptap-content table th,
        .tiptap-content table td {
            padding: 0.5rem;
        }
    }
</style>
@endpush

<div x-data="{ selectedTab: 'summary' }" class="w-full">
    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex gap-2 overflow-x-auto border-b border-neutral-300 dark:border-neutral-700" role="tablist" aria-label="tab options">
        <button @click="selectedTab = 'summary'" :aria-selected="selectedTab === 'summary'" :tabindex="selectedTab === 'summary' ? '0' : '-1'" :class="selectedTab === 'summary' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelSummary">Summary</button>
        <button @click="selectedTab = 'description'" :aria-selected="selectedTab === 'description'" :tabindex="selectedTab === 'description' ? '0' : '-1'" :class="selectedTab === 'description' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelDescription">Description</button>
        <button @click="selectedTab = 'discussion'" :aria-selected="selectedTab === 'discussion'" :tabindex="selectedTab === 'discussion' ? '0' : '-1'" :class="selectedTab === 'discussion' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelDiscussion">Discussion</button>
        <button @click="selectedTab = 'components'" :aria-selected="selectedTab === 'components'" :tabindex="selectedTab === 'components' ? '0' : '-1'" :class="selectedTab === 'components' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelComponents">Component Details</button>
        <button @click="selectedTab = 'files'" :aria-selected="selectedTab === 'files'" :tabindex="selectedTab === 'files' ? '0' : '-1'" :class="selectedTab === 'files' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelFiles">Files</button>
        <button @click="selectedTab = 'reviews'" :aria-selected="selectedTab === 'reviews'" :tabindex="selectedTab === 'reviews' ? '0' : '-1'" :class="selectedTab === 'reviews' ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'" class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelReviews">Reviews ({{ $designReviewQty }})</button>
    </div>
    <div class="px-2 py-4 text-neutral-600 dark:text-neutral-300">
    <div x-show="selectedTab === 'summary'" id="tabpanelSummary" class="w-full min-h-[300px]" role="tabpanel" aria-label="summary">
                    <livewire:frequency-response-viewer :design="$design" />
        @if(trim(strip_tags(tiptap_converter()->asHtml($design->summary))) || $design->frequencyResponse)

            <div class="w-full prose max-w-none">
                <div class="tiptap-content w-full">
                    {!! tiptap_converter()->asHtml($design->summary) !!}
                </div>
            </div>
        @else
            <div class="flex items-center justify-center h-[300px] py-8 text-gray-500">
                <p>No summary data submitted</p>
            </div>
        @endif
    </div>
    
    <div class="w-full min-h-[300px]" x-show="selectedTab === 'description'" id="tabpanelDescription" role="tabpanel" aria-label="description">
        @if(trim(strip_tags(tiptap_converter()->asHtml($design->description))))
            <div class="w-full prose max-w-none">
                <div class="tiptap-content w-full">
                    {!! tiptap_converter()->asHtml($design->description) !!}
                </div>
            </div>
        @else
            <div class="flex items-center justify-center h-[300px] py-8 text-gray-500">
                <p>No description data submitted</p>
            </div>
        @endif
    </div>
    
    <div class="min-h-[300px]" x-show="selectedTab === 'discussion'" id="tabpanelDiscussion" role="tabpanel" aria-label="discussion">
        @if($design->forum_slug)
            <iframe src="{{env('APP_URL')}}/forum/embed/{{ $design->forum_slug }}" style="width:100%; height: 800px;"></iframe>
        @else
            <div class="flex items-center justify-center h-[300px] py-8 text-gray-500">
                <p>No discussion data available</p>
            </div>
        @endif
    </div>

    <div x-show="selectedTab === 'components'" id="tabpanelcomponents" role="tabpanel" class="w-full min-h-[300px]" aria-label="components">
        @if(count($design->components) > 0)
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
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                            class="divide-y divide-gray-200">
                            <div class="w-full prose max-w-none">
                                <div class="tiptap-content w-full">
                                    {!! tiptap_converter()->asHtml($component->description) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex items-center justify-center h-[300px] py-8 text-gray-500">
                <p>No component data submitted</p>
            </div>
        @endif
    </div>

    <div class="min-h-[300px]" x-show="selectedTab === 'files'" id="tabpanelFiles" role="tabpanel" aria-label="files">
        @if(count($design->snapshots) > 0)
            <div>
                <div class="w-full">
                    @foreach($design->snapshots as $snapshot)
                    <br>
                    <div>
                        <a href="{{ route('design-snapshots.download', $snapshot->id) }}" class="button">
                            <span>{{$snapshot->snapshot_name}}</span>
                            <i class="ph ph-download-simple"></i>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="flex items-center justify-center h-[300px] py-8 text-gray-500">
                <p>No files submitted</p>
            </div>
        @endif
    </div>
    
    <div class="min-h-[300px]" x-show="selectedTab === 'reviews'" id="tabpanelReviews" role="tabpanel" aria-label="reviews">
        <div class="w-full pb-8 max-w-4xl mx-auto">
            <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold">Reviews ({{ $designReviewQty }})</h3>
                        @if($designRating)
                        <div class="flex items-center mt-1">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=round($designRating))
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    @endif
                                    @endfor
                            </div>
                            <span class="ml-2 text-gray-600 dark:text-gray-300">{{ number_format($designRating, 1) }} out of 5</span>
                        </div>
                        @else
                        <p class="text-gray-500 dark:text-gray-400">No ratings yet</p>
                        @endif
                    </div>
                    <button
                        @if(Auth::check())
                            @click="$refs.reviewForm.scrollIntoView({behavior: 'smooth'})"
                        @else
                            onclick="window.location.href='{{ route('login') }}'"
                        @endif
                        class="px-4 py-2 bg-purple-950 hover:bg-purple-600 text-white transition-colors">
                        Write a Review
                    </button>
                </div>
            </div>
            <div class="space-y-6 mb-10">
                @forelse($designReviews as $review)
                <div class="p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-lg">{{ $review['title'] }}</h4>
                            <div class="flex items-center my-1">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$review['rating'])
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    @else
                                    <svg class="w-4 h-4 text-gray-300 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    @endif
                                    @endfor
                            </div>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($review['created_at'])->format('M d, Y') }}
                        </div>
                    </div>
                    <div class="mt-2 text-gray-700 dark:text-gray-300">
                        {{ $review['review'] }}
                    </div>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        By: {{ $review['author']['name'] ?? 'Anonymous' }}
                    </div>
                </div>
                @empty
                <div class="p-6 text-center border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-500 dark:text-gray-400">No reviews yet. Be the first to review!</p>
                </div>
                @endforelse
            </div>
            @if(Auth::check())
            <div x-ref="reviewForm" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-xl font-semibold mb-4">Write a Review</h3>

                @if(session()->has('message'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 dark:bg-green-200 dark:text-green-800">
                    {{ session('message') }}
                </div>
                @endif

                <form wire:submit.prevent="submitReview">
                    <div class="mb-4">
                        <label for="reviewTitle" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Review Title</label>
                        <input wire:model.defer="reviewTitle" type="text" id="reviewTitle" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Summarize your experience" required>
                        @error('reviewTitle') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Rating</label>
                        <div class="flex items-center space-x-1" x-data="{ temporaryRating: @entangle('reviewRating') }">
                            @for($i = 1; $i <= 5; $i++)
                                <button
                                type="button"
                                wire:click="$set('reviewRating', {{ $i }})"
                                @click="temporaryRating = {{ $i }}"
                                class="focus:outline-none">
                                <svg class="w-8 h-8" :class="{{ $i }} <= temporaryRating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600'" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                                </button>
                                @endfor
                        </div>
                        @error('reviewRating') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="reviewContent" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Review Content</label>
                        <textarea wire:model.defer="reviewContent" id="reviewContent" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Share your experience with this design" required></textarea>
                        @error('reviewContent') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="text-white bg-purple-950 hover:bg-purple-600 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Submit Review
                    </button>
                </form>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 text-center">
                <p class="mb-4 text-gray-700 dark:text-gray-300">You need to be logged in to write a review.</p>
                <a href="{{ route('login') }}" class="text-white bg-purple-950 hover:bg-purple-600 focus:ring-4 focus:ring-blue-300 font-medium text-sm px-5 py-2.5 inline-block dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Log in to write a review
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
</div>
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>