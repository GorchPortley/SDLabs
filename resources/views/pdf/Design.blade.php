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
        .image-container {
            text-align: center;
            margin: 15px 0;
        }
        .image-container img {
            max-width: 100%;
            height: auto;
        }
        .grid-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
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
        {!! preg_replace('/(<img[^>]+src="[^"]*")/', '$1 style="max-width: 100%; height: auto; display: block; margin: 0 auto;"', tiptap_converter()->asHtml($design->summary)) !!}
    </div>
</div>

<div class="page-break"></div>

<!-- Description Section -->
<h2 class="section-title">Description</h2>
<div class="card">
    <div class="tiptap-content">
        {!! preg_replace('/(<img[^>]+src="[^"]*")/', '$1 style="max-width: 100%; height: auto; display: block; margin: 0 auto;"', tiptap_converter()->asHtml($design->description)) !!}
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
                {!! preg_replace('/(<img[^>]+src="[^"]*")/', '$1 style="max-width: 100%; height: auto; display: block; margin: 0 auto;"', tiptap_converter()->asHtml($component->description)) !!}
            </div>
        </div>
    </div>
@endforeach
</body>
</html>
