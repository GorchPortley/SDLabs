<div class="rounded-lg w-full">
    @push('head')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
    @endpush

    <div class="w-full h-full"
         x-data="{
            activeTab: 'amplitude',
            amplitudeData: @js($chartData),
            summedResponse: @js($summedResponse),
            phaseData: @js($phaseData),
            amplitudeChart: null,
            phaseChart: null,

            initCharts() {
                if (this.activeTab === 'amplitude') {
                    if (this.amplitudeChart) {
                        this.amplitudeChart.destroy();
                    }

                    this.$nextTick(() => {
                        const ctxAmplitude = document.getElementById('frequencyResponseChart').getContext('2d');
                        let datasetsAmplitude = JSON.parse(JSON.stringify(this.amplitudeData));

                        if (datasetsAmplitude.length > 0 && this.summedResponse) {
                            datasetsAmplitude.push({
                                label: 'Summed Response',
                                data: this.summedResponse,
                                borderColor: '#000000',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                fill: false
                            });
                        }

                        this.amplitudeChart = new Chart(ctxAmplitude, {
                            type: 'line',
                            data: { datasets: datasetsAmplitude },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                scales: {
                                    x: {
                                        type: 'logarithmic',
                                        title: { display: true, text: 'Frequency (Hz)' },
                                        min: 10,
                                        max: 20000,
                                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                                    },
                                    y: {
                                        title: { display: true, text: 'Amplitude (dB)' },
                                        min: 50,
                                        max: 100,
                                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { usePointStyle: true, padding: 20 }
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        callbacks: {
                                            label: function(context) {
                                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + ' dB';
                                            }
                                        }
                                    }
                                },
                                parsing: { xAxisKey: 'x', yAxisKey: 'y' },
                                elements: {
                                    point: { radius: 0, hoverRadius: 5 },
                                    line: { tension: 0.3 }
                                }
                            }
                        });
                    });
                }

                if (this.activeTab === 'phase') {
                    if (this.phaseChart) {
                        this.phaseChart.destroy();
                    }

                    this.$nextTick(() => {
                        const ctxPhase = document.getElementById('phaseResponseChart').getContext('2d');
                        this.phaseChart = new Chart(ctxPhase, {
                            type: 'line',
                            data: { datasets: this.phaseData },
                            options: {
                                responsive: true,
                                maintainAspectRatio: true,
                                scales: {
                                    x: {
                                        type: 'logarithmic',
                                        title: { display: true, text: 'Frequency (Hz)' },
                                        min: 20,
                                        max: 20000,
                                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                                    },
                                    y: {
                                        title: { display: true, text: 'Phase (degrees)' },
                                        min: -180,
                                        max: 180,
                                        grid: { color: 'rgba(0, 0, 0, 0.1)' }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: { usePointStyle: true, padding: 20 }
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        callbacks: {
                                            label: function(context) {
                                                return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '°';
                                            }
                                        }
                                    }
                                },
                                parsing: { xAxisKey: 'x', yAxisKey: 'y' },
                                elements: {
                                    point: { radius: 0, hoverRadius: 5 },
                                    line: { tension: 0.3 }
                                }
                            }
                        });
                    });
                }
            }
        }"
         x-init="initCharts()"
         @tab-changed.window="activeTab = $event.detail.tab; initCharts();">

        <div class="flex rounded-lg border border-gray-400 flex-col lg:flex-row">
            <!-- Desktop Sidebar Tabs -->
            <div class="hidden border-r border-gray-400 lg:block lg:w-1/4 p-4">
                <div class="space-y-2 flex flex-col items-center">
                    <x-button
                        @click="activeTab = 'amplitude'; initCharts()"
                        :class="{ 'bg-zinc-600': activeTab === 'amplitude' }"
                        class="w-full"
                    >
                        Amplitude Response
                    </x-button>
                    <x-button
                        @click="activeTab = 'phase'; initCharts()"
                        :class="{ 'bg-zinc-600': activeTab === 'phase' }"
                        class="w-full"
                    >
                        Phase Response
                    </x-button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="w-full h-full rounded-lg p-4 lg:p-6">
                <div class="w-full h-full bg-white rounded-lg shadow-sm">
                    <!-- Amplitude Response Tab -->
                    <div x-show="activeTab === 'amplitude'" class="h-full w-full p-4">
                        <h4 class="text-xl font-semibold mb-4">Amplitude Response</h4>
                        <div class="lg:h-[calc(100%-2rem)]">
                            <canvas id="frequencyResponseChart"></canvas>
                        </div>
                    </div>

                    <!-- Phase Response Tab -->
                    <div x-show="activeTab === 'phase'" class="h-full w-full p-4">
                        <h4 class="text-xl font-semibold mb-4">Phase Response</h4>
                        <div class="lg:h-[calc(100%-2rem)]">
                            <canvas id="phaseResponseChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
