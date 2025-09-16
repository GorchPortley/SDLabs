<div
    x-data="bannerCarousel()"
    x-init="init"
    class="relative w-full overflow-hidden"
>
    @if($banners->count() > 0)
        <div
            class="flex transition-transform duration-500 ease-in-out"
            x-ref="carousel"
            :style="`transform: translateX(-${currentIndex * 100}%)`"
        >
            @foreach($banners as $index => $banner)
                <div class="w-full flex-shrink-0">
                    <img
                        src="{{ Storage::url($banner->image_path) }}"
                        alt="{{ $banner->name ?? 'Banner' }}"
                        class="w-[1920px] h-[480px] object-contain"
                    >
                </div>
            @endforeach
        </div>

        @if($banners->count() > 1)
            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                @foreach($banners as $index => $banner)
                    <button
                        @click="goToSlide({{ $index }})"
                        class="w-3 h-3 rounded-full"
                        :class="{
                            'bg-blue-500': currentIndex === {{ $index }},
                            'bg-gray-300': currentIndex !== {{ $index }}
                        }"
                    ></button>
                @endforeach
            </div>

            <button
                @click="prev"
                class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-full"
            >
                &lt;
            </button>
            <button
                @click="next"
                class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-full"
            >
                &gt;
            </button>
        @endif
    @endif
</div>

<script>
    function bannerCarousel() {
        return {
            currentIndex: 0,
            totalSlides: {{ $banners->count() }},

            init() {
                if (this.totalSlides > 1) {
                    this.startAutoplay();
                }
            },

            startAutoplay() {
                this.autoplayInterval = setInterval(() => {
                    this.next();
                }, 5000); // Change slide every 5 seconds
            },

            next() {
                this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
            },

            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
            },

            goToSlide(index) {
                this.currentIndex = index;
            }
        }
    }
</script>
