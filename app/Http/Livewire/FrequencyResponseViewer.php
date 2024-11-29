<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\{Storage, Log};

class FrequencyResponseViewer extends Component
{
    // Properties with type declarations for better code clarity
    public $design;
    public array $chartData = [];
    public array $summedResponse = [];
    public array $phaseData = [];
    public array $debugInfo = [];

    // Constants for frequency response calculations and validation
    private const FREQ_MIN = 20;
    private const FREQ_MAX = 20000;
    private const AMP_MIN = 50;
    private const AMP_MAX = 120;
    private const LOW_FREQ_THRESHOLD = 120;
    private const COLORS = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
    ];

    public function mount($design)
    {
        $this->design = $design;
        $this->initDebugInfo();
        $this->loadFrequencyResponses();
    }

    private function initDebugInfo(): void
    {
        $this->debugInfo = [
            'has_design' => !is_null($this->design),
            'frd_files_type' => gettype($this->design->frd_files)
        ];
    }

    protected function loadFrequencyResponses(): void
    {
        if (empty($this->design->frd_files)) {
            $this->debugInfo['error'] = 'No FRD files found in design';
            return;
        }

        $frdFiles = $this->getFrdFiles();
        $summedData = [];

        foreach ($frdFiles as $filePath) {
            $this->processFile($filePath, $summedData);
        }

        if (!empty($summedData)) {
            $this->processSummedData($summedData);
        }
    }

    private function getFrdFiles(): array
    {
        return is_string($this->design->frd_files)
            ? json_decode($this->design->frd_files, true)
            : $this->design->frd_files;
    }

    private function processFile(string $filePath, array &$summedData): bool
    {
        if (!Storage::exists($filePath)) {
            $this->debugInfo['missing_files'][] = $filePath;
            return false;
        }

        $content = Storage::get($filePath);
        $data = $this->parseFRDFile($content);

        if (empty($data)) {
            return false;
        }

        $this->addChartData($filePath, $data);
        $this->addToSummedData($data, $summedData);

        return true;
    }

    private function addChartData(string $filePath, array $data): void
    {
        $label = pathinfo($filePath, PATHINFO_FILENAME);
        $color = $this->getRandomColor();

        $this->chartData[] = [
            'label' => $label,
            'data' => $this->formatPointData($data, 'amplitude'),
            'borderColor' => $color,
            'fill' => false
        ];

        $this->phaseData[] = [
            'label' => $label . ' Phase',
            'data' => $this->formatPointData($data, 'phase'),
            'borderColor' => $color,
            'fill' => false
        ];
    }

    private function addToSummedData(array $data, array &$summedData): void
    {
        foreach ($data as $point) {
            $freq = $point['frequency'];
            $amplitude = pow(10, $point['amplitude'] / 20.0);
            $phase = $this->normalizePhase($point['phase']);
            $phaseRad = deg2rad($phase);

            $real = $amplitude * cos($phaseRad);
            $imag = $amplitude * sin($phaseRad);

            if (!isset($summedData[$freq])) {
                $summedData[$freq] = ['real' => $real, 'imag' => $imag, 'count' => 1];
            } else {
                $summedData[$freq]['real'] += $real;
                $summedData[$freq]['imag'] += $imag;
                $summedData[$freq]['count']++;
            }
        }
    }

    private function processSummedData(array $summedData): void
    {
        ksort($summedData);
        $lastValidDb = null;

        foreach ($summedData as $freq => $complex) {
            $magnitude = sqrt(pow($complex['real'], 2) + pow($complex['imag'], 2));
            $db = 20 * log10(max($magnitude, 1e-20));

            // Smooth low frequency response
            if ($freq < self::LOW_FREQ_THRESHOLD && $lastValidDb !== null) {
                $dbDiff = abs($db - $lastValidDb);
                if ($dbDiff > 3) {
                    $db = $lastValidDb + (3 * ($db > $lastValidDb ? 1 : -1));
                }
            }

            $db = max(min($db, self::AMP_MAX), self::AMP_MIN);
            $lastValidDb = $db;

            $this->summedResponse[] = ['x' => $freq, 'y' => $db];
        }
    }

    private function normalizePhase(float $phase): float
    {
        while ($phase > 180) $phase -= 360;
        while ($phase < -180) $phase += 360;
        return $phase;
    }

    private function formatPointData(array $data, string $valueKey): array
    {
        return array_map(fn($point) => [
            'x' => $point['frequency'],
            'y' => $point[$valueKey]
        ], $data);
    }

    protected function parseFRDFile(string $content): array
    {
        $lines = explode("\n", trim($content));
        $data = [];

        foreach ($lines as $line) {
            $point = $this->parseDataPoint($line);
            if ($point) {
                $data[] = $point;
            }
        }

        return $this->filterAndSortData($data);
    }

    private function parseDataPoint(string $line): ?array
    {
        $line = trim($line);
        if (empty($line) || $line[0] === '*' || $line[0] === '#') {
            return null;
        }

        $values = preg_split('/\s+/', $line);
        if (count($values) < 3) {
            return null;
        }

        return $this->validateDataPoint($values);
    }

    private function validateDataPoint(array $values): ?array
    {
        $freq = (float)$values[0];
        $amp = (float)$values[1];
        $phase = (float)$values[2];

        if ($freq < self::FREQ_MIN || $freq > self::FREQ_MAX) {
            return null;
        }

        if ($amp < self::AMP_MIN || $amp > self::AMP_MAX) {
            return null;
        }

        return [
            'frequency' => $freq,
            'amplitude' => $amp,
            'phase' => $phase
        ];
    }

    private function filterAndSortData(array $data): array
    {
        if (empty($data)) {
            return [];
        }

        usort($data, fn($a, $b) => $a['frequency'] <=> $b['frequency']);
        return $data;
    }

    protected function getRandomColor(): string
    {
        static $colorIndex = 0;
        return self::COLORS[$colorIndex++ % count(self::COLORS)];
    }

    public function render()
    {
        return view('livewire.frequency-response-viewer', [
            'chartData' => $this->chartData,
            'summedResponse' => $this->summedResponse,
            'phaseData' => $this->phaseData,
            'debugInfo' => $this->debugInfo
        ]);
    }
}
