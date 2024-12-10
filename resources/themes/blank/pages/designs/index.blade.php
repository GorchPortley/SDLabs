<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Design;
use Illuminate\Http\Request;

name('designs');

new class extends Component {
    use WithPagination;

    public function with(Request $request): array
    {
        $query = Design::query()
            ->where('active', 1)
            ->with(['designer', 'sales' => function($query) {
                $query->where('user_id', auth()->id());
            }]);

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter by price range
        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Get cart items
        $user = auth()->user()?->load('cart.items');
        $cartItems = $user?->cart?->items->pluck('design_id')->toArray() ?? [];

        // Get distinct categories for filter dropdown
        $categories = Design::distinct('category')->pluck('category');

        return [
            'designs' => $query->paginate(12),
            'cartItems' => $cartItems,
            'categories' => $categories,
            'maxPrice' => ceil(Design::max('price'))
        ];
    }
} ?>

<x-layouts.marketing>
    @volt('designs')
    <div>
        <div class="flex h-full w-full bg-gray-300 rounded-md">
            <img src="https://placehold.co/1920x300">
        </div>
        <div class="flex-row lg:flex w-full h-full mt-5">
            <div class="lg:flex flex-col h-full lg:w-1/5 rounded-md p-4 bg-white shadow">
                <form method="GET" class="sticky-top space-y-6">
                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <div class="flex gap-2">
                            <select name="sort" class="w-1/2 rounded-md border-gray-300">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date</option>
                                <option value="price" {{ request('sort') === 'price' ? 'selected' : '' }}>Price</option>
                                <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Name</option>
                            </select>
                            <select name="direction" class="w-1/2 rounded-md border-gray-300">
                                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" class="w-full rounded-md border-gray-300">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                        <div class="flex items-center gap-2">
                            <input
                                type="number"
                                name="min_price"
                                value="{{ request('min_price') }}"
                                placeholder="Min"
                                min="0"
                                max="{{ $maxPrice }}"
                                class="w-1/2 rounded-md border-gray-300"
                            >
                            <span>-</span>
                            <input
                                type="number"
                                name="max_price"
                                value="{{ request('max_price') }}"
                                placeholder="Max"
                                min="0"
                                max="{{ $maxPrice }}"
                                class="w-1/2 rounded-md border-gray-300"
                            >
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <x-button type="submit" class="flex-1 px-4 py-2">
                            Apply Filters
                        </x-button>
                        <a href="{{ request()->url() }}" class="flex-1 px-4 py-2 bg-gray-100 text-center text-gray-700 rounded-md hover:bg-gray-200">
                            Clear
                        </a>
                    </div>
                </form>
            </div>

            <div class="flex flex-col h-full w-full lg:w-4/5 rounded-md lg:pl-6">
                <x-marketing.design-card-container
                    :designs="$designs"
                    :cartItems="$cartItems"
                />
                <div class="flex gap-4 justify-center mt-8">
                    {{ $designs->links() }}
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
