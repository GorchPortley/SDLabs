<!-- Tailwind and AlpineJS Integration -->
<script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/focus@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Regular CSS for TipTap -->
<style>
    .tiptap-content table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .tiptap-content table th, .tiptap-content table td {
        border: 1px solid #e0e0e0;
        padding: 1rem;
        text-align: left;
    }

    .tiptap-content table th {
        background-color: #4f46e5; /* Tailwind Indigo */
        color: #ffffff;
        font-weight: bold;
    }

    .tiptap-content table tr:nth-child(even) {
        background-color: #f9fafb; /* Tailwind Gray-50 */
    }

    .tiptap-content table tr:hover {
        background-color: #e0e7ff; /* Tailwind Indigo-100 */
    }

    @media (max-width: 640px) {
        .tiptap-content table {
            font-size: 0.875rem;
        }

        .tiptap-content table th, .tiptap-content table td {
            padding: 0.5rem;
        }
    }
</style>

<!-- Main PDF Content -->
<div class="p-6 bg-gray-100">
    <!-- Front Page -->
    <div class="text-center mb-8 bg-indigo-50 p-8 rounded-lg shadow-md">
        <h1 class="text-5xl font-bold text-indigo-600">{{$design->name}}</h1>
        <h2 class="text-2xl font-medium text-indigo-800 mt-2">By: {{$design->designer?->name}}</h2>
        <h3 class="text-lg text-gray-700 mt-1">Variation: <span class="font-semibold">{{$variation}}</span></h3>
    </div>

    <div class="mb-4 bg-white p-6 rounded-lg shadow-md text-gray-700">
        <p><span class="font-semibold text-indigo-600">Tag:</span> {{$design->tag}}</p>
        <p><span class="font-semibold text-indigo-600">Category:</span> {{$design->category}}</p>
        <p><span class="font-semibold text-indigo-600">Build Cost:</span> {{$design->build_cost}}</p>
        <p><span class="font-semibold text-indigo-600">Impedance:</span> {{ $design->impedance }}</p>
        <p><span class="font-semibold text-indigo-600">Power Handling:</span> {{ $design->power }}</p>
    </div>

    @pageBreak

    <!-- Summary Section -->
    <div>
        <h2 class="text-3xl font-bold text-indigo-700 mb-4 border-b-2 border-indigo-500">Summary</h2>
        <div class="w-full prose max-w-none bg-white p-6 rounded-lg shadow-md">
            <div class="tiptap-content">
                {!! tiptap_converter()->asHtml($design->summary) !!}
            </div>
        </div>
    </div>

    @pageBreak

    <!-- Description Section -->
    <div>
        <h2 class="text-3xl font-bold text-indigo-700 mb-4 border-b-2 border-indigo-500">Description</h2>
        <div class="w-full prose max-w-none bg-white p-6 rounded-lg shadow-md">
            <div class="tiptap-content">
                {!! tiptap_converter()->asHtml($design->description) !!}
            </div>
        </div>
    </div>

    @foreach($design->components as $component)
        @pageBreak

        <!-- Component Details Section -->
        <div>
            <h2 class="text-3xl font-bold text-indigo-700 mb-4 border-b-2 border-indigo-500">Component Details</h2>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <p class="text-gray-700"><span class="font-semibold text-indigo-600">Driver:</span> {{$component->driver->brand}} - {{$component->driver->model}}</p>
                <p class="text-gray-700"><span class="font-semibold text-indigo-600">Position:</span> {{$component->position}}</p>
                <p class="text-gray-700"><span class="font-semibold text-indigo-600">Quantity:</span> {{ $component->quantity }}</p>
                <p class="text-gray-700"><span class="font-semibold text-indigo-600">Frequency Range:</span> {{ $component->low_frequency }}Hz - {{ $component->high_frequency }}</p>
                <p class="text-gray-700"><span class="font-semibold text-indigo-600">Air Volume:</span> {{ $component->air_volume }}</p>

                <div class="w-full prose max-w-none mt-4 bg-indigo-50 p-4 rounded-lg">
                    <div class="tiptap-content">
                        {!! tiptap_converter()->asHtml($component->description) !!}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
