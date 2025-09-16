<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpecificationsExport implements FromCollection, WithHeadings
{
protected $data;

public function __construct($data)
{
$this->data = $data;
}

/**
* Collection to export
*/
public function collection()
{
return collect($this->data);
}

/**
* Define spreadsheet headings
*/
public function headings(): array
{
// Assuming specifications JSON keys are consistent across all components
return ['Design Position', 'Design Quantity', 'Low Freq', 'High Freq', 'Air Volume', 'Re', 'Fs', 'Qms', 'Qes',
    'Qts', 'Rms', 'Mms', 'Cms', 'Vas', 'Sd', 'BL', 'Xmax', 'Le',
    'SPL', 'EBP', 'Vd', 'Mmd'];
}
}
