<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            📊 Hasil Survey: {{ $survey->title }}
        </h1>

        <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-lg font-semibold mb-4">Data Jawaban (Preview)</h2>
            <pre class="bg-gray-100 p-4 rounded-lg text-sm">
                {{ json_encode($decodedAnswers, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
            </pre>
        </div>
    </div>
</x-app-layout>
