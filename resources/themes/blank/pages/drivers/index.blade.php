<?php
use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Driver;
use Illuminate\Http\Request;

name('drivers');

new class extends Component {
    use WithPagination;

    public function with(Request $request): array
    {
        $query = Driver::query()
            ->where('active', 1)
            ->withCount('designs')  // Add count of designs
            ->with('designs');      // Eager load designs

        // Filter by brand
        if ($request->brand) {
            $query->where('brand', $request->brand);
        }

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Filter by power range
        if ($request->min_power) {
            $query->where('power', '>=', $request->min_power);
        }
        if ($request->max_power) {
            $query->where('power', '<=', $request->max_power);
        }

        // Filter by impedance
        if ($request->impedance) {
            $query->where('impedance', $request->impedance);
        }

        // Custom sorting
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';

        // Handle special sort case for designs count
        if ($sortField === 'designs') {
            $query->orderBy('designs_count', $sortDirection);
        } else {
            $query->orderBy($sortField, $sortDirection);
        }

        // Cache frequently accessed collections
        $manufacturers = cache()->remember('driver-manufacturers', 3600, function() {
            return Driver::distinct('brand')->pluck('brand');
        });

        $categories = cache()->remember('driver-categories', 3600, function() {
            return Driver::distinct('category')->pluck('category');
        });

        $impedances = cache()->remember('driver-impedances', 3600, function() {
            return Driver::distinct('impedance')->pluck('impedance')->sort();
        });

        $maxPower = cache()->remember('driver-max-power', 3600, function() {
            return ceil(Driver::max('power'));
        });

        return [
            'drivers' => $query->paginate(15),
            'manufacturers' => $manufacturers,
            'impedances' => $impedances,
            'maxPower' => $maxPower,
            'categories' => $categories,
        ];
    }
} ?>

<x-layouts.marketing>
    @volt('drivers')
    <div>
        <div class="flex h-full w-full bg-gray-300 rounded-md">

            <livewire:banner-display location="driver_page">
        </div>
        <div class="lg:flex flex-row w-full h-full mt-5">
            <div class=" h-full lg:w-1/5 rounded-md p-4 bg-white shadow">
                <form method="GET" class="space-y-6">

                    <!-- Sort -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <div class="flex gap-2">
                            <select name="sort" class="w-1/2 rounded-md border-gray-300">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Date</option>
                                <option value="power_handling" {{ request('sort') === 'power_handling' ? 'selected' : '' }}>Power</option>
                                <option value="designs" {{request('sort') === 'designs'? 'selected' : ''}}>Design Number</option>
                            </select>
                            <select name="direction" class="w-1/2 rounded-md border-gray-300">
                                <option value="asc" {{ request('direction') === 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('direction') === 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>
                    </div>

                    <!-- Manufacturer Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Manufacturer</label>
                        <select name="manufacturer" class="w-full rounded-md border-gray-300">
                            <option value="">All Manufacturers</option>
                            @foreach($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer }}" {{ request('manufacturer') === $manufacturer ? 'selected' : '' }}>
                                    {{ $manufacturer }}
                                </option>
                            @endforeach
                        </select>
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

                    <!-- Impedance Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Impedance (Ω)</label>
                        <select name="impedance" class="w-full rounded-md border-gray-300">
                            <option value="">All Impedances</option>
                            @foreach($impedances as $impedance)
                                <option value="{{ $impedance }}" {{ request('impedance') == $impedance ? 'selected' : '' }}>
                                    {{ $impedance }}Ω
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Power Handling Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Power Handling (W)</label>
                        <div class="flex items-center gap-2">
                            <input
                                type="number"
                                name="min_power"
                                value="{{ request('min_power') }}"
                                placeholder="Min"
                                min="0"
                                max="{{ $maxPower }}"
                                class="w-1/2 rounded-md border-gray-300"
                            >
                            <span>-</span>
                            <input
                                type="number"
                                name="max_power"
                                value="{{ request('max_power') }}"
                                placeholder="Max"
                                min="0"
                                max="{{ $maxPower }}"
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
                <x-marketing.driver-card-container
                    :drivers="$drivers"
                />
                <div class="flex gap-4 justify-center mt-8">
                    {{ $drivers->links() }}
                </div>
            </div>
        </div>
    </div>
    @endvolt
</x-layouts.marketing>
