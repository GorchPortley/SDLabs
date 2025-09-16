<!-- Improved Search Bar with Fixed Dropdown Behavior -->
<div 
    x-data="{ 
        showResults: false, 
        search: @entangle('search').live
    }" 
    @click.away="showResults = false"
    class="relative mx-auto w-full max-w-md"
>
    <!-- Search Form -->
    <form role="search" class="relative w-full" @submit.prevent>
        <input
            x-model="search"
            @focus="showResults = true"
            type="search"
            placeholder="Search Everything"
            aria-label="Top Searchbar"
            class="w-full py-2 px-4 border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
        >
    </form>
   
    <!-- Dropdown Results - Simplified event handling -->
    <div 
        x-show="showResults" 
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute z-50 w-full mt-1 bg-white shadow-lg overflow-hidden border border-gray-200"
    >
        <!-- Results containers -->
        @if(($users && count($users) > 0) || ($designs && count($designs) > 0) || ($drivers && count($drivers) > 0))
            <!-- User Results -->
            @if($users && count($users) > 0)
                <div class="mb-2">
                    <div class="px-3 py-2 font-semibold bg-gray-50">User Results</div>
                    <div class="px-3 py-1 divide-y divide-gray-100">
                        @foreach($users as $user)
                            <a href="/profile/{{$user->username}}" class="block hover:bg-gray-50 transition-colors">
                                <div class="py-2 search-result-item">
                                    <span class="block font-medium">{{$user->name}}</span>
                                    <small class="block text-gray-500 text-sm">{{$user->username ?? 'User'}}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
           
            <!-- Design Results -->
            @if($designs && count($designs) > 0)
                <div class="mb-2">
                    <div class="px-3 py-2 font-semibold bg-gray-50">Design Results</div>
                    <div class="px-3 py-1 divide-y divide-gray-100">
                        @foreach($designs as $design)
                            <a href="/designs/design/{{$design->id}}" class="block hover:bg-gray-50 transition-colors">
                                <div class="py-2 search-result-item">
                                    <span class="block font-medium">{{$design->name}}</span>
                                    <small class="block text-gray-500 text-sm">{{$design->category ?? 'Design'}}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
           
            <!-- Driver Results -->
            @if($drivers && count($drivers) > 0)
                <div class="mb-2">
                    <div class="px-3 py-2 font-semibold bg-gray-50">Driver Results</div>
                    <div class="px-3 py-1 divide-y divide-gray-100">
                        @foreach($drivers as $driver)
                            <a href="/drivers/driver/{{$driver->id}}" class="block hover:bg-gray-50 transition-colors">
                                <div class="py-2 search-result-item">
                                    <span class="block font-medium">{{$driver->model}}</span>
                                    <small class="block text-gray-500 text-sm">{{$driver->brand ?? 'Driver'}}</small>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <!-- Empty or No Results State -->
            <div class="p-4 text-center text-gray-500">
                @if(strlen(trim($search ?? '')) > 0)
                    <!-- No results message when search has text -->
                    No results found for "{{ $search }}"
                @else
                    <!-- Empty state when search field is empty -->
                    <p>Start typing to search for designs, drivers, or users</p>
                    <div class="mt-3 flex justify-center space-x-4 text-sm">
                        <a href="{{ route('designs') }}" class="text-purple-950 hover:underline">Browse Designs</a>
                        <a href="{{ route('drivers') }}" class="text-purple-950 hover:underline">Browse Drivers</a>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>