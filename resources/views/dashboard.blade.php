<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900">
        {{-- Form Filter dan Download --}}
        <form method="GET" action="{{ route('dashboard') }}" class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label for="survey_type" class="block mb-1 font-semibold">Pilih Tipe Survey:</label>
                <select name="survey_type" id="survey_type" class="p-2 border rounded w-full">
                    <option value="telkomsel" {{ $selectedType == 'telkomsel' ? 'selected' : '' }}>Survey Telkomsel</option>
                    <option value="indihome" {{ $selectedType == 'indihome' ? 'selected' : '' }}>Survey Indihome</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">Tampilkan Data</button>
            </div>
            <div>
                <a href="{{ route('dashboard.download', ['survey_type' => $selectedType]) }}" class="w-full inline-block bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded">Download Data</a>
            </div>
        </form>

        <div class="overflow-x-auto bg-white p-4 rounded shadow">
            <h2 class="text-lg font-bold mb-4">Hasil Survey: {{ ucfirst($selectedType) }}</h2>

            <table class="min-w-full text-sm border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">No</th>
                        <th class="p-2 border">Nomor HP</th>
                        <th class="p-2 border">Usia</th>
                        <th class="p-2 border">Jenis Kelamin</th>
                        <th class="p-2 border">Pendapatan</th>
                        <th class="p-2 border">Saran/Kritik</th>
                        <th class="p-2 border">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surveys as $i => $survey)
                        @php
                            $answers = json_decode($survey->answers, true);
                        @endphp
                        <tr>
                            <td class="p-2 border">{{ $surveys->firstItem() + $i }}</td>
                            <td class="p-2 border">{{ $answers['nomorHp'] ?? '-' }}</td>
                            <td class="p-2 border">{{ $answers['usia'] ?? '-' }}</td>
                            <td class="p-2 border">{{ $answers['jenis_kelamin'] ?? '-' }}</td>
                            <td class="p-2 border">{{ $answers['pendapatan'] ?? '-' }}</td>
                            <td class="p-2 border">
                                {{ $answers['saran_telkomsel'] ?? $answers['saran_kritik_lainnya'] ?? '-' }}
                            </td>
                            <td class="p-2 border">{{ $survey->created_at->format('d-m-Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">Tidak ada data ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">
                {{ $surveys->appends(['survey_type' => $selectedType])->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
