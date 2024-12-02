<div>
    @push('head')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

        <div x-data="{
    selectedTab: 'amplitude',
    hideData: false,

    init() {
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: true,
            elements: {
                line: {
                    tension: 0.4,  // Makes lines smoother (0 = no smoothing, 1 = maximum smoothing)
                    borderWidth: 2  // Makes all lines thicker
                },
                point: {
                    radius: 0  // Hides individual points
                }
            }
        };

        // Create Amplitude Chart
        const amplitudeCtx = document.getElementById('amplitudeChart').getContext('2d');
        new Chart(amplitudeCtx, {
            type: 'line',
            data: {
                datasets: [
                    ...@js($chartData),
                    {
                        label: 'Summed Response',
                        data: @js($summedResponse),
                        borderColor: '#000000',
                        borderWidth: 2,
                        fill: false
                    }
                ]
            },
            options: {
                ...commonOptions,
                scales: {
                    x: {
                        type: 'logarithmic',
                        min: 20,
                        max: 20000,
                        title: { display: true, text: 'Frequency (Hz)' }
                    },
                    y: {
                        min: 50,
                        max: 100,
                        title: { display: true, text: 'Amplitude (dB)' }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y.toFixed(1)} dB`;
                            }
                        }
                    }
                }
            }
        });

        // Create Phase Chart
        const phaseCtx = document.getElementById('phaseChart').getContext('2d');
        new Chart(phaseCtx, {
            type: 'line',
            data: { datasets: @js($phaseData) },
            options: {
                ...commonOptions,
                scales: {
                    x: {
                        type: 'logarithmic',
                        min: 20,
                        max: 20000,
                        title: { display: true, text: 'Frequency (Hz)' }
                    },
                    y: {
                        min: -180,
                        max: 180,
                        title: { display: true, text: 'Phase (degrees)' }
                    }
                },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                return `${context.dataset.label}: ${context.parsed.y.toFixed(1)}°`;
                            }
                        }
                    }
                }
            }
        });
    }
}"
    >
        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()"
             class="flex gap-2 overflow-x-auto border-b border-neutral-300 dark:border-neutral-700" role="tablist"
             aria-label="tab options">
            <button @click="selectedTab = 'amplitude'" :aria-selected="selectedTab === 'amplitude'"
                    :tabindex="selectedTab === 'amplitude' ? '0' : '-1'"
                    :class="selectedTab === 'amplitude' && !hideData  ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'"
                    class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelAmplitude">
                Amplitude
            </button>
            <button @click="selectedTab = 'phase'" :aria-selected="selectedTab === 'phase'"
                    :tabindex="selectedTab === 'phase' ? '0' : '-1'"
                    :class="selectedTab === 'phase' && !hideData ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'"
                    class="h-min px-4 py-2 text-sm" type="button" role="tab" aria-controls="tabpanelphase">Phase
            </button>
            <button
                @click="hideData = !hideData"
                :aria-selected="hideData"
                :class="hideData ? 'font-bold text-black border-b-2 border-black dark:border-white dark:text-white' : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-neutral-800 hover:text-neutral-900'"
                class="h-min px-4 py-2 text-sm"
                type="button"
                x-text="hideData ? 'Show Data' : 'Hide Data'"
            >
            </button>
        </div>
        <div class="px-2 py-4 text-neutral-600 dark:text-neutral-300" x-show="!hideData">
            <div x-show="selectedTab === 'amplitude'" class="w-full" id="tabpanelamplitude" role="tabpanel" aria-label="amplitude">
                <canvas id="amplitudeChart"></canvas>
            </div>
            <div x-show="selectedTab === 'phase'" class="w-full" id="tabpanelphase" role="tabpanel" aria-label="phase">
                <canvas id="phaseChart"></canvas>
            </div>
        </div>
    </div>
</div>
