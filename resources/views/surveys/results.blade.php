<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Survei - {{ $survey->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen text-gray-800">

<div class="max-w-6xl mx-auto py-10 px-4">
    <h1 class="text-3xl font-bold mb-4 text-center">📊 Hasil Survey: {{ $survey->title }}</h1>
    <p class="text-gray-600 text-center mb-6">Total Responden: <strong>{{ $respondentCount }}</strong></p>

    {{-- 1) Bar chart usia --}}
    <div class="bg-white shadow-md rounded-2xl p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4 text-gray-800 text-center">Distribusi Usia Responden</h2>

        @if(!empty($ageLabels) && count($ageLabels))
            <canvas id="ageChart" height="120"></canvas>
        @else
            <div class="text-center text-gray-600">Belum ada data usia untuk ditampilkan.</div>
        @endif
    </div>

    {{-- 2) Grid chart pertanyaan --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($charts as $i => $chart)
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-semibold mb-3">{{ $chart['question'] }}</h3>
                <canvas id="chart-{{ $i }}" height="120"></canvas>
            </div>
        @empty
            <div class="col-span-full bg-white p-6 rounded shadow text-gray-600">
                Tidak ada grafik (pertanyaan bertipe pilihan belum memiliki jawaban).
            </div>
        @endforelse
    </div>

    {{-- 3) Jawaban teks terbuka --}}
    @if(!empty($textAnswers) && count(array_filter($textAnswers)))
        <div class="bg-white p-6 rounded shadow mb-12">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Saran / Jawaban Teks</h2>
            </div>

            @foreach($textAnswers as $question => $answers)
                <div class="mb-6 border-b pb-4">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-medium">
                            {{ $question }}
                            <span class="text-sm text-gray-500">({{ count($answers) }})</span>
                        </h3>

                        @if(count($answers))
                            <button
                                data-question="{{ $question }}"
                                data-answers='@json($answers)'
                                class="btn-summary px-3 py-1 bg-blue-500 text-white rounded text-sm">
                                Summary With AI
                            </button>
                        @endif
                    </div>

                    {{-- Loading indicator per pertanyaan --}}
                    <div class="loading hidden mb-2 flex items-center text-gray-600 text-sm">
                        <svg class="animate-spin h-4 w-4 mr-2 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        AI sedang memproses ringkasan...
                    </div>

                    {{-- Hasil summary AI --}}
                    <div class="summary-card hidden mb-3 p-3 bg-green-50 border border-green-300 rounded">
                        <h4 class="font-semibold mb-1">Summary:</h4>
                        <div class="summary-content text-gray-800 text-sm"></div>
                    </div>

                    @if(count($answers))
                        <ul class="list-disc list-inside space-y-1 text-gray-700">
                            @foreach($answers as $a)
                                <li>{{ $a }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-gray-500">Belum ada jawaban.</p>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white p-6 rounded shadow text-gray-600 mb-8">
            Tidak ada jawaban teks terbuka untuk ditampilkan.
        </div>
    @endif
</div>

<script>
    // === Chart Usia ===
    const ageLabels = @json($ageLabels ?? []);
    const ageData   = @json($ageData ?? []);

    if (ageLabels.length) {
        const ctx = document.getElementById('ageChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ageLabels,
                datasets: [{
                    label: 'Jumlah Responden',
                    data: ageData,
                    backgroundColor: 'rgba(240,73,117,0.6)',
                    borderColor: 'rgba(240,73,117,1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // === Chart Pertanyaan ===
    const charts = @json($charts ?? []);
    charts.forEach((c, idx) => {
        const el = document.getElementById(`chart-${idx}`);
        if (!el) return;
        const ctx = el.getContext('2d');

        new Chart(ctx, {
            type: c.type === 'bar' ? 'bar' : 'pie',
            data: {
                labels: c.labels,
                datasets: [{
                    label: c.question,
                    data: c.data,
                    backgroundColor: [
                        '#60A5FA','#F472B6','#34D399','#FBBF24','#A78BFA','#F87171','#10B981','#F59E0B'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: c.type !== 'bar' }
                },
                scales: c.type === 'bar' ? { y: { beginAtZero: true } } : {}
            }
        });
    });

    // === Tombol Summary With AI (terintegrasi dengan backend) ===
    document.querySelectorAll('.btn-summary').forEach(btn => {
        btn.addEventListener('click', () => {
            const question = btn.getAttribute('data-question');
            const answers = JSON.parse(btn.getAttribute('data-answers'));

            const wrapper = btn.closest('div.mb-6');
            const loading = wrapper.querySelector('.loading');
            const summaryCard = wrapper.querySelector('.summary-card');
            const summaryContent = wrapper.querySelector('.summary-content');

            loading.classList.remove('hidden');
            summaryCard.classList.add('hidden');

            fetch('{{ route("saran.summary.ajax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sarans: answers,
                    question: question
                })
            })
                .then(res => res.json())
                .then(data => {
                    loading.classList.add('hidden');
                    summaryContent.innerHTML = data.summary || '<em>Tidak ada ringkasan yang dihasilkan.</em>';
                    summaryCard.classList.remove('hidden');
                })
                .catch(() => {
                    loading.classList.add('hidden');
                    alert('Terjadi kesalahan saat memproses ringkasan AI.');
                });
        });
    });
</script>

</body>
</html>
