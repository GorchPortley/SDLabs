<!DOCTYPE html>
<html>
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
        {!! $design->summary !!}
    </div>
</div>

<div class="page-break"></div>

<!-- Description Section -->
<h2 class="section-title">Description</h2>
<div class="card">
    <div class="tiptap-content">
        {!! $design->description !!}
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
                {!! $component->description !!}
            </div>
        </div>
    </div>
@endforeach
</body>
</html>
