<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\{Storage, Cache};

class DriverFrequencyResponseViewer extends Component
{
    public $driver;
    public array $chartData = [];
    public array $summedResponse = [];
    public array $phaseData = [];
    public string $activeTab = 'amplitude';

    private const FREQ_RANGE = [
        'min' => 20,
        'max' => 20000
    ];

    private const AMP_RANGE = [
        'min' => 50,
        'max' => 120
    ];

    private const COLORS = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
    ];

    public function mount($driver)
    {
        $this->driver = $driver;

        $data = $this->processAllData();
        $this->setData($data);
    }

    private function processAllData(): array
    {
        if (empty($this->driver->frequency_files)) {
            return [];
        }

        $frdFiles = $this->getFrdFiles();
        $processedData = [];
        $colorIndex = 0;

        foreach ($frdFiles as $filePath) {
            if (!Storage::exists($filePath)) {
                continue;
            }

            $fileData = $this->processFile($filePath);
            if (!empty($fileData)) {
                $label = pathinfo($filePath, PATHINFO_FILENAME);
                $color = self::COLORS[$colorIndex % count(self::COLORS)];
                $colorIndex++;

                $processedData[] = [
                    'data' => $fileData,
                    'label' => $label,
                    'color' => $color
                ];
            }
        }

        // Process both amplitude and phase data at once
        $amplitudeData = $this->processAmplitudeData($processedData);
        $phaseData = $this->processPhaseData($processedData);

        return [
            'chartData' => $amplitudeData['chartData'],
            'summedResponse' => $amplitudeData['summedResponse'],
            'phaseData' => $phaseData['phaseData']
        ];
    }

    private function setData(array $data): void
    {
        $this->chartData = $data['chartData'] ?? [];
        $this->summedResponse = $data['summedResponse'] ?? [];
        $this->phaseData = $data['phaseData'] ?? [];
    }

    private function getFrdFiles(): array
    {
        return is_string($this->driver->frequency_files)
            ? json_decode($this->driver->frequency_files, true)
            : $this->driver->frequency_files;
    }

    private function processFile(string $filePath): array
    {
        $content = Storage::get($filePath);
        return $this->parseFRDFile($content);
    }

    private function parseFRDFile(string $content): array
    {
        $lines = array_filter(
            explode("\n", trim($content)),
            fn($line) => !empty($line) && $line[0] !== '*' && $line[0] !== '#'
        );

        $data = [];
        foreach ($lines as $line) {
            $values = preg_split('/\s+/', trim($line));
            if (count($values) < 3) continue;

            $point = $this->validateDataPoint($values);
            if ($point) {
                $data[] = $point;
            }
        }

        return $data;
    }

    private function validateDataPoint(array $values): ?array
    {
        $freq = (float)$values[0];
        $amp = (float)$values[1];

        if ($freq < self::FREQ_RANGE['min'] || $freq > self::FREQ_RANGE['max']) {
            return null;
        }

        if ($amp < self::AMP_RANGE['min'] || $amp > self::AMP_RANGE['max']) {
            return null;
        }

        return [
            'frequency' => $freq,
            'amplitude' => $amp,
            'phase' => (float)$values[2]
        ];
    }

    private function processAmplitudeData(array $processedData): array
    {
        $chartData = [];
        $summedData = [];

        foreach ($processedData as $dataset) {
            $chartData[] = [
                'label' => $dataset['label'],
                'data' => array_map(fn($point) => [
                    'x' => $point['frequency'],
                    'y' => $point['amplitude']
                ], $dataset['data']),
                'borderColor' => $dataset['color'],
                'fill' => false
            ];

            foreach ($dataset['data'] as $point) {
                $freq = $point['frequency'];
                $amplitude = pow(10, $point['amplitude'] / 20.0);
                $phase = deg2rad($point['phase']);

                $real = $amplitude * cos($phase);
                $imag = $amplitude * sin($phase);

                if (!isset($summedData[$freq])) {
                    $summedData[$freq] = ['real' => $real, 'imag' => $imag, 'count' => 1];
                } else {
                    $summedData[$freq]['real'] += $real;
                    $summedData[$freq]['imag'] += $imag;
                    $summedData[$freq]['count']++;
                }
            }
        }

        $summedResponse = [];
        if (!empty($summedData)) {
            ksort($summedData);
            foreach ($summedData as $freq => $complex) {
                $magnitude = sqrt(pow($complex['real'], 2) + pow($complex['imag'], 2));
                $db = 20 * log10(max($magnitude, 1e-20));
                $db = max(min($db, self::AMP_RANGE['max']), self::AMP_RANGE['min']);

                $summedResponse[] = ['x' => $freq, 'y' => $db];
            }
        }

        return [
            'chartData' => $chartData,
            'summedResponse' => $summedResponse
        ];
    }

    private function processPhaseData(array $processedData): array
    {
        $phaseData = [];

        foreach ($processedData as $dataset) {
            $phaseData[] = [
                'label' => $dataset['label'] . ' Phase',
                'data' => array_map(fn($point) => [
                    'x' => $point['frequency'],
                    'y' => $this->normalizePhase($point['phase'])
                ], $dataset['data']),
                'borderColor' => $dataset['color'],
                'fill' => false
            ];
        }

        return ['phaseData' => $phaseData];
    }

    private function normalizePhase(float $phase): float
    {
        while ($phase > 180) $phase -= 360;
        while ($phase < -180) $phase += 360;
        return $phase;
    }

    public function render()
    {
        return view('livewire.driver-frequency-response-viewer', [
            'chartData' => $this->chartData,
            'summedResponse' => $this->summedResponse,
            'phaseData' => $this->phaseData,
            'activeTab' => $this->activeTab
        ]);
    }
}
