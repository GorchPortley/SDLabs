<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            margin: 20mm;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .page-break {
            page-break-after: always;
        }
        .header {
            background-color: #1E40AF; /* Indigo-800 */
            color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .section-title {
            border-bottom: 3px solid #3730A3; /* Indigo-900 */
            padding-bottom: 10px;
            margin-bottom: 15px;
            color: #3730A3;
        }
        .card {
            background-color: #F0F9FF; /* Light blue background */
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .detail-label {
            color: #1E40AF; /* Indigo-800 */
            font-weight: bold;
            margin-right: 5px;
        }

        .tiptap-content table {
             width: 100%;
             border-collapse: collapse;
             margin-bottom: 1.25rem;
             box-shadow: 0 2px 4px rgba(0,0,0,0.1);
         }

        .tiptap-content img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .tiptap-content table th, .tiptap-content table td {
            border: 1px solid #e0e0e0;
            padding: 0.75rem;
            text-align: left;
        }

        .tiptap-content table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .tiptap-content table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .tiptap-content table tr:hover {
            background-color: #f0f0f0;
        }

        .tiptap-content table th p, .tiptap-content table td p {
            margin: 0;
            padding: 0;
        }

        .tiptap-content .filament-tiptap-grid-builder {
            display: grid;
            gap: 1rem;
            width: 100%;
        }

        .tiptap-content .filament-tiptap-grid-builder[data-cols="3"] {
            grid-template-columns: repeat(3, 1fr);
        }

        .tiptap-content .filament-tiptap-grid-builder[data-cols="2"] {
            grid-template-columns: repeat(2, 1fr);
        }

        .tiptap-content .filament-tiptap-grid-builder[data-cols="4"] {
            grid-template-columns: repeat(4, 1fr);
        }

        .tiptap-content .filament-tiptap-grid-builder__column {
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .tiptap-content .filament-tiptap-grid-builder__column h2 {
            margin-bottom: 0.75rem;
        }

        .tiptap-content .filament-tiptap-grid-builder__column p {
        }

        @media (max-width: 640px) {
            .tiptap-content .filament-tiptap-grid-builder {
                grid-template-columns: 1fr !important;
            }

            .tiptap-content table {
                font-size: 0.875rem;
            }

            .tiptap-content table th, .tiptap-content table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
<!-- Front Page -->
<div class="header text-center">
    <h1 class="text-3xl font-bold">{{ $design->name }}</h1>
    <h2 class="text-xl">By: {{ $design->designer?->name }}</h2>
    <p class="text-lg">Variation: {{ $variation }}</p>
</div>

<!-- Design Details -->
<div class="card">
    <div class="grid grid-cols-2 gap-4">
        <p><span class="detail-label">Tag:</span> {{ $design->tag }}</p>
        <p><span class="detail-label">Category:</span> {{ $design->category }}</p>
        <p><span class="detail-label">Build Cost:</span> {{ $design->build_cost }}</p>
        <p><span class="detail-label">Impedance:</span> {{ $design->impedance }}</p>
        <p><span class="detail-label">Power Handling:</span> {{ $design->power }}</p>
    </div>
</div>

<div class="page-break"></div>

<!-- Summary Section -->
<h2 class="section-title">Summary</h2>
<div class="card">
    <div class="tiptap-content">
        {!! tiptap_converter()->asHtml($design->summary) !!}
    </div>
</div>

<div class="page-break"></div>

<!-- Description Section -->
<h2 class="section-title">Description</h2>
<div class="card">
    <div class="tiptap-content">
        {!! tiptap_converter()->asHtml($design->description) !!}
    </div>
</div>

<!-- Component Details -->
@foreach($design->components as $component)
    <div class="page-break"></div>

    <h2 class="section-title">Component Details</h2>
    <div class="card">
        <div class="grid grid-cols-2 gap-4 mb-4">
            <p><span class="detail-label">Driver:</span> {{ $component->driver->brand }} - {{ $component->driver->model }}</p>
            <p><span class="detail-label">Position:</span> {{ $component->position }}</p>
            <p><span class="detail-label">Quantity:</span> {{ $component->quantity }}</p>
            <p><span class="detail-label">Frequency Range:</span> {{ $component->low_frequency }}Hz - {{ $component->high_frequency }}</p>
            <p><span class="detail-label">Air Volume:</span> {{ $component->air_volume }}</p>
        </div>

        <div class="bg-indigo-50 p-4 rounded-lg">
            <div class="tiptap-content">
                {!! tiptap_converter()->asHtml($component->description) !!}
            </div>
        </div>
    </div>
@endforeach
</body>
</html>
