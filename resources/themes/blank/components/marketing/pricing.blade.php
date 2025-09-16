<section class="pt-16 pb-10 sm:py-20">

    <x-container>    
        <x-marketing.heading
            level="h2"
            title="Pricing plans"
            description="Modify this view from your theme pages/pricing/index.blade.php file." 
        />

        <div x-data="{ on: false, billing: '{{ get_default_billing_cycle() }}',
                toggleRepositionMarker(toggleButton){
                    this.$refs.marker.style.width=toggleButton.offsetWidth + 'px';
                    this.$refs.marker.style.height=toggleButton.offsetHeight + 'px';
                    this.$refs.marker.style.left=toggleButton.offsetLeft + 'px';
                }
            }" 
            x-init="
                    setTimeout(function(){ 
                        toggleRepositionMarker($refs.monthly); 
                        $refs.marker.classList.remove('opacity-0');
                        setTimeout(function(){ 
                            $refs.marker.classList.add('duration-300', 'ease-out');
                        }, 10); 
                    }, 1);
            "
            class="w-full max-w-6xl mx-auto mt-12 mb-4 sm:my-12" x-cloak>

            @if(has_monthly_yearly_toggle())
                <div class="relative flex items-start justify-start pb-5 -translate-y-2">
                    <div class="relative inline-flex items-center justify-center w-auto p-1 text-center -translate-y-3 border-2 border-gray-900 sm:mx-auto">
                        <div x-ref="monthly" x-on:click="billing='Monthly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Monthly' }" class="relative z-20 px-3.5 py-1 text-sm font-medium leading-6 text-gray-900 rounded-full duration-300 ease-out cursor-pointer">
                            Monthly
                        </div>
                        <div x-ref="yearly" x-on:click="billing='Yearly'; toggleRepositionMarker($el)" :class="{ 'text-white': billing == 'Yearly' }" class="relative z-20 px-3.5 py-1 text-sm font-medium leading-6 text-gray-900 rounded-full duration-300 ease-out cursor-pointer">
                            Yearly
                        </div>
                        <div x-ref="marker" class="absolute left-0 z-10 w-1/2 h-full opacity-0" x-cloak>
                            <div class="w-full h-full bg-gray-900 shadow-sm"></div>
                        </div>
                    </div>  
                </div>
            @endif

            <div class="flex flex-col flex-wrap lg:flex-row">

                @foreach(Wave\Plan::where('active', 1)->get() as $plan)
                    @php $features = explode(',', $plan->features); @endphp
                    <div 
                        {{--  Say that you have a monthly plan that doesn't have a yearly plan, in that case we will hide the place that doesn't have a price_id --}}
                        x-show="(billing == 'Monthly' && '{{ $plan->monthly_price_id }}' != '') || (billing == 'Yearly' && '{{ $plan->yearly_price_id }}' != '')" 
                        class="flex-1 w-full max-w-xl mx-auto mb-8 lg:px-4 lg:mb-0" x-cloak>
                        <div class="p-8 border">
                        <h3 class="mb-6 text-xl font-semibold">{{ $plan->name }}</h3>
                        
                        <div class="flex items-end mb-6 space-x-0.5 text-5xl font-bold">
                            <span>$<span x-text="billing == 'Monthly' ? '{{ $plan->monthly_price }}' : '{{ $plan->yearly_price }}'"></span></span>
                            <span class="text-base block -translate-y-0.5 font-medium"><span x-text="billing == 'Monthly' ? '/mo' : '/yr'"></span></span>
                        </div>
                        <ul class="mb-8 text-left">
                            @foreach($features as $feature)
                                <li class="flex items-center mb-2">
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <x-button class="w-full">Get started</x-button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <p class="w-full my-4 text-center sm:my-8 text-zinc-500 md:my-10">All plans are fully configurable in the Admin Area.</p>
    </x-container>
</section>