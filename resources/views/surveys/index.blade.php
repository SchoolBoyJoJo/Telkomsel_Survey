<x-app-layout>
    <div class="max-w-6xl mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">📋 Daftar Survey</h1>
            <a href="{{ route('surveys.create') }}"
               class="bg-red-500 hover:bg-red-600 text-white font-semibold px-4 py-2 rounded-lg shadow">
                + Buat Survey Baru
            </a>
        </div>

        <!-- Flash Message -->
        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Survey Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Judul</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tipe</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surveys as $survey)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 text-gray-800 font-medium">
                                {{ $survey->title }}
                            </td>
                            <td class="px-6 py-4 capitalize text-gray-600">
                                {{ $survey->survey_type }}
                            </td>
                            <td class="px-6 py-4 text-center flex justify-center gap-2">

                                @php
                                    $hash = Hashids::encode($survey->id);
                                @endphp

                                <!-- Tombol Lihat -->
                                <a href="{{ route('survey.public.show', $hash) }}" 
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-sm shadow"
                                target="_blank">
                                    🔗 Lihat Survey
                                </a>

                                <!-- Tombol Copy -->
                                <button onclick="copyLink('{{ route('survey.public.show', $hash) }}')" 
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg text-sm shadow">
                                    📋 Copy Link
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                Belum ada survey yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Script Copy Link -->
    <script>
        function copyLink(link) {
            navigator.clipboard.writeText(link).then(() => {
                alert("✅ Link survey berhasil disalin!");
            }).catch(err => {
                console.error("Gagal menyalin link: ", err);
            });
        }
    </script>
</x-app-layout>
