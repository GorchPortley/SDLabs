<?php

use function Laravel\Folio\{name};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Design;
use Illuminate\Support\Facades\DB;

name('designs');

new class extends Component {

} ?>



<x-layouts.marketing>
    @volt('designs')
            <div class="lg:w-4/5 border-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                driver area
            </div>

@endvolt
</x-layouts.marketing>

