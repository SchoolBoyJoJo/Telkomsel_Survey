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

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    @if ($selectedType === 'telkomsel')

        {{-- Grafik Usia --}}
        @if (isset($usiaCounts) && count($usiaCounts))
            <div class="bg-white p-4 mb-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Distribusi Usia Responden</h2>
                <canvas id="usiaChart" width="400" height="150"></canvas>
            </div>

            <script>
                const usiaLabels = @json($usiaCounts->keys());
                const usiaData = @json($usiaCounts->values());

                const ctx = document.getElementById('usiaChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: usiaLabels,
                        datasets: [{
                            label: 'Jumlah Responden',
                            data: usiaData,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Responden'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Usia'
                                }
                            }
                        }
                    }
                });
            </script>
        @else
            <div class="bg-white p-4 rounded shadow text-gray-600">
                <p class="text-center">Belum ada data usia yang tersedia untuk ditampilkan.</p>
            </div>
        @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- Jenis Kelamin -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Jenis Kelamin</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartJenisKelamin" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- Jenis Tempat Tinggal -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Jenis Tempat Tinggal</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartTempatTinggal" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- Status Pekerjaan -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Status Pekerjaan</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartPekerjaan" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- Pendapatan -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Pendapatan Pribadi</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartPendapatan" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- Aktif Telkomsel -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah Aktif Menggunakan Telkomsel?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartAktifTelkomsel" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- Multisimer -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah Multisimer?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartMultisimer" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- sim kedua -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Sim Kedua</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartSimKedua" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- wifi rumah -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah Ada Wifi Di Rumah?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartWifiRumah" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- provider wifi -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Provider Wifi</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartProviderWifi" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- wifi vs data luar -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah Saat Diluar Lebih Sering Menggunakan Wifi Dibanding Data?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartWifiVsDataLuar" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- durasi wifi publik -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Rata - Rata Penggunaan Wifi Publik</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartDurasiWifiPublik" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- keluar kota -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Rata - Rata Keluar Kota Dalam Sebluan</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartKeluarKotaBulanan" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- keluarga mayoritas telkomsel -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah keluarga mayoritas telkomsel?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartKeluargaTelkomsel" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- aktivitas rutin internet -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah rutin melakukan aktivitas internet koneksi stabil?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartAktifitasInternetBerat" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- jenis paket yang dibeli -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Jenis paket yang dibeli</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartJenisPaket" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- beli paket dimana -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Dari mana biasanya membeli paket internet?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartSumberPembelianPaket" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- kualitas pelayanan telkomsel -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Bagaimana anda menilai kualitas layanan telkomsel?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartPenilaianKualitasTelkomsel" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- gangguan sinyal -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Seberapa sering anda mengalami gangguan saat menggunakan telkomsel?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartFrekuensiGangguan" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- kemudahan beli telkomsel -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Seberapa mudah anda membeli produk telkomsel?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartKemudahanBeliTelkomsel" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- harga paket wajar -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Berapa harga maksimal paket perbulan yang masih anda anggap wajar?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartHargaPaketWajar" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- sepadan harga telkomsel -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah harga layanan telkomsel sepadan dengan apa yang anda dapat?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartSepadanHargaTelkomsel" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- tertarik promo lain -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah anda tertarik dengan promo dari provider lain?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartTertarikPromoLain" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- mudah pindah provider -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Seberapa mudah anda pindah ke provider lain?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartKemudahanPindahProvider" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- telkomsel mahal dibanding -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah tarif layanan telkomsel lebih mahal?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartMahalDibandingkan" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">
            Saran dan keluhan lain selama Anda menggunakan Telkomsel.
            <span class="text-gray-500 text-sm">(Total : {{ count($saranTelkomsel) }})</span>
        </h2>
        
        @if(isset($saranTelkomsel) && count($saranTelkomsel))
            <div class="max-h-72 overflow-y-auto pr-2">
                <ul class="space-y-3">
                    @foreach($saranTelkomsel as $saran)
                        <li class="border-b pb-2 text-gray-800">
                            {{ $saran }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="text-gray-500 italic">
                Belum ada saran atau kritik yang diberikan.
            </p>
        @endif
    </div>
    
    @endif

    @if ($selectedType === 'indihome')

        {{-- Grafik Usia --}}
        @if (isset($usiaCounts) && count($usiaCounts))
            <div class="bg-white p-4 mb-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Distribusi Usia Responden</h2>
                <canvas id="usiaChart" width="400" height="150"></canvas>
            </div>

            <script>
                const usiaLabels = @json($usiaCounts->keys());
                const usiaData = @json($usiaCounts->values());

                const ctx = document.getElementById('usiaChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: usiaLabels,
                        datasets: [{
                            label: 'Jumlah Responden',
                            data: usiaData,
                            backgroundColor: 'rgba(255, 99, 132, 0.6)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Responden'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Usia'
                                }
                            }
                        }
                    }
                });
            </script>
        @else
            <div class="bg-white p-4 rounded shadow text-gray-600">
                <p class="text-center">Belum ada data usia yang tersedia untuk ditampilkan.</p>
            </div>
        @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- Jenis Kelamin -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Jenis Kelamin</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartJenisKelaminIndi" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- status pekerjaan -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Status Pekerjaan</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartPekerjaanIndi" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- pendapatan -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Pendapatan Per bulan</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartPendapatanIndi" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- indi tempat tinggal -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Status Tempat Tinggal</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartTempatTinggalIndi" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- Aktif Telkomsel -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah masih berlangganan Indihome?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartAktifIndihome" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- wifi vs data indihome -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah lebih sering menggunakan wifi dibanding data?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartWifiVsDataIndi" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- alasan wifi vs data -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apa alasannya?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartAlasanWifiVsData" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- mudah cari wifi di tempat umum -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah mudah mencari wifi di tempat umum?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartWifiGratisUmum" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- wifi gratis untuk hemat -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah anda sering menggunakan wifi gratis untuk menghemat?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartHematWifiGratis" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- gangguan jaringan wifi -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah anda sering mengalami gangguan jaringan wifi?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartGangguanWifi" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- teknisi datang -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Berapa lama biasanya teknisi datang setelah melapor?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartResponTeknisi" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- waktu teknisi ekspetasi -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah kedatangan teknisi sudah sesuai dengan ekspektasi?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartEkspektasiTeknisi" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

        <!-- pengaruh teknisi -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Apakah kecepatan teknisi mempengaruhi anda untuk tetap menggunakan layanan?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartPengaruhTeknisi" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- biaya wifi -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Berapa biaya rata-rata untuk bayar layanan wifi setiap bulan?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartBiayaWifi" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- harga wifi sebanding -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">Dengan harga tersebut apakah layanan wifi sudah sebanding?</h3>
            <div class="flex justify-center items-center">
                <canvas id="chartSebandingWifi" width="250" height="250"></canvas>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mt-6">

        <!-- ekspektasi biaya -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">
                Berapa ekspektasi biaya yang anda keluarkan untuk layanan internet wifi yang stabil dan cepat?
            </h3>
            <div class="flex justify-center items-center">
                <canvas id="chartEkspektasiBiaya" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- ekspektasi kecepatan -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">
                Berapa kecepatan (Mbps) yang anda harapkan?
            </h3>
            <div class="flex justify-center items-center">
                <canvas id="chartEkspektasiKecepatan" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- sumber indihome -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">
                Bagaimana anda mengetahui tentang layanan Indihome?
            </h3>
            <div class="flex justify-center items-center">
                <canvas id="chartSumberIndihome" width="250" height="250"></canvas>
            </div>
        </div>

        <!-- provider terbaik -->
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-md font-semibold mb-2 text-center">
                Apa provider terbaik menurut anda saat ini?
            </h3>
            <div class="flex justify-center items-center">
                <canvas id="chartProviderTerbaik" width="250" height="250"></canvas>
            </div>
        </div>

    </div>
    
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-lg font-semibold mb-4">
            Saran dan keluhan lain selama Anda menggunakan IndiHome.
            <span class="text-gray-500 text-sm">(Total : {{ count($saranIndihome) }})</span>
        </h2>
        
        @if(isset($saranIndihome) && count($saranIndihome))
            <div class="max-h-72 overflow-y-auto pr-2">
                <ul class="space-y-3">
                    @foreach($saranIndihome as $saran)
                        <li class="border-b pb-2 text-gray-800">
                            {{ $saran }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="text-gray-500 italic">
                Belum ada saran atau kritik yang diberikan.
            </p>
        @endif
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            new Chart(document.getElementById("chartJenisKelaminIndi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($jenisKelaminIndiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($jenisKelaminIndiCounts->values()) !!},
                        backgroundColor: ["#FF9900", "#3366CC"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartPekerjaanIndi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($pekerjaanIndiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($pekerjaanIndiCounts->values()) !!},
                        backgroundColor: ["#FF9900", "#3366CC", "#DC3912", "#109618"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartPendapatanIndi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($pendapatanIndiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($pendapatanIndiCounts->values()) !!},
                        backgroundColor: ["#FF9900", "#3366CC", "#DC3912", "#109618", "#990099"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartTempatTinggalIndi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($tempatTinggalIndiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($tempatTinggalIndiCounts->values()) !!},
                        backgroundColor: ["#FF9900", "#3366CC", "#DC3912", "#109618"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartAktifIndihome"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($aktifIndihomeCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($aktifIndihomeCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartWifiVsDataIndi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($wifiVsDataCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($wifiVsDataCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartAlasanWifiVsData"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($alasanWifiVsDataCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($alasanWifiVsDataCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618", "#990099"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartWifiGratisUmum"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($wifiGratisUmumCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($wifiGratisUmumCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartHematWifiGratis"), {
                type: "bar",
                data: {
                    labels: {!! json_encode($hematWifiGratisCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($hematWifiGratisCounts->values()) !!},
                        backgroundColor: "#FF9900"
                    }]
                },
                options: {
                    layout: {
                        padding: {
                            top: 30
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false,
                            text: "Penggunaan WiFi Gratis untuk Menghemat Kuota"
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            offset: 5,
                            formatter: Math.round,
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Skala Frekuensi (1 = Jarang, 5 = Sering)'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartGangguanWifi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($gangguanWifiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($gangguanWifiCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartResponTeknisi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($responTeknisiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($responTeknisiCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartEkspektasiTeknisi"), {
                type: "bar",
                data: {
                    labels: {!! json_encode($ekspektasiTeknisiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($ekspektasiTeknisiCounts->values()) !!},
                        backgroundColor: "#FF9900"
                    }]
                },
                options: {
                    layout: {
                        padding: {
                            top: 30
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: false,
                            text: "Ekspektasi Terhadap Kedatangan Teknisi"
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            offset: 5,
                            formatter: Math.round,
                            font: {
                                weight: 'bold'
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Skala Ekspektasi (1 = Jauh lebih lambat, 5 = Jauh lebih cepat)'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartPengaruhTeknisi"), {
                type: "bar",
                data: {
                    labels: {!! json_encode($pengaruhTeknisiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($pengaruhTeknisiCounts->values()) !!},
                        backgroundColor: "#FF9900"
                    }]
                },
                options: {
                    layout: { padding: { top: 30 } },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: false,
                            text: "Pengaruh Kecepatan Teknisi terhadap Keputusan Pengguna"
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            offset: 5,
                            formatter: Math.round,
                            font: { weight: 'bold' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Skala Pengaruh (1 = Sangat tidak setuju, 5 = Sangat setuju)'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartBiayaWifi"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($biayaWifiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($biayaWifiCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618", "#990099"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartSebandingWifi"), {
                type: "bar",
                data: {
                    labels: {!! json_encode($sebandingWifiCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($sebandingWifiCounts->values()) !!},
                        backgroundColor: "#FF9900"
                    }]
                },
                options: {
                    layout: { padding: { top: 30 } },
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: false,
                            text: "Keseimbangan Harga dan Layanan WiFi"
                        },
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            offset: 5,
                            formatter: Math.round,
                            font: { weight: 'bold' }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Responden'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Skala Keseimbangan (1 = Sangat tidak sebanding, 5 = Sangat sebanding)'
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartEkspektasiBiaya"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($ekspektasiBiayaCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($ekspektasiBiayaCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618", "#990099"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartEkspektasiKecepatan"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($ekspektasiKecepatanCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($ekspektasiKecepatanCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartSumberIndihome"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($sumberIndihomeCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($sumberIndihomeCounts->values()) !!},
                        backgroundColor: [
                            "#3366CC", "#DC3912", "#FF9900", "#109618",
                            "#990099", "#0099C6", "#DD4477", "#66AA00"
                        ]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

            new Chart(document.getElementById("chartProviderTerbaik"), {
                type: "pie",
                data: {
                    labels: {!! json_encode($providerTerbaikCounts->keys()) !!},
                    datasets: [{
                        data: {!! json_encode($providerTerbaikCounts->values()) !!},
                        backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618", "#990099", "#0099C6"]
                    }]
                },
                options: {
                    plugins: {
                        legend: { position: 'right' },
                        datalabels: {
                            formatter: (value, context) => {
                                const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                const percentage = (value / total) * 100;
                                return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                            },
                            color: '#fff',
                            font: { weight: 'bold', size: 14 }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

        });
    </script>


    @endif

    <script>

        // Jenis Kelamin
        new Chart(document.getElementById("chartJenisKelamin"), {
            type: "pie",
            data: {
                labels: {!! json_encode($jenisKelaminCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($jenisKelaminCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


        // Jenis Tempat Tinggal
        new Chart(document.getElementById("chartTempatTinggal"), {
            type: "pie",
            data: {
                labels: {!! json_encode($jenisTempatTinggalCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($jenisTempatTinggalCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Status Pekerjaan
        new Chart(document.getElementById("chartPekerjaan"), {
            type: "pie",
            data: {
                labels: {!! json_encode($statusPekerjaanCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($statusPekerjaanCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        new Chart(document.getElementById("chartPendapatan"), {
            type: "pie",
            data: {
                labels: {!! json_encode($pendapatanPribadiCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($pendapatanPribadiCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618", "#990099"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        new Chart(document.getElementById("chartAktifTelkomsel"), {
            type: "pie",
            data: {
                labels: {!! json_encode($aktifTelkomselCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($aktifTelkomselCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        new Chart(document.getElementById("chartMultisimer"), {
            type: "pie",
            data: {
                labels: {!! json_encode($multisimerCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($multisimerCounts->values()) !!},
                    backgroundColor: ["#109618", "#990099"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Chart 1: Kartu SIM Kedua
        new Chart(document.getElementById("chartSimKedua"), {
            type: "pie",
            data: {
                labels: {!! json_encode($simKeduaCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($simKeduaCounts->values()) !!},
                    backgroundColor: [
                        "#3366CC", "#DC3912", "#FF9900", "#109618",
                        "#990099", "#0099C6", "#DD4477", "#66AA00"
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Chart 2: Ada WiFi di Rumah
        new Chart(document.getElementById("chartWifiRumah"), {
            type: "pie",
            data: {
                labels: {!! json_encode($wifiRumahCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($wifiRumahCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Chart 3: Provider WiFi
        new Chart(document.getElementById("chartProviderWifi"), {
            type: "pie",
            data: {
                labels: {!! json_encode($providerWifiCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($providerWifiCounts->values()) !!},
                    backgroundColor: [
                        "#3366CC", "#DC3912", "#FF9900", "#109618",
                        "#990099", "#0099C6", "#DD4477", "#66AA00", "#B82E2E"
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // WiFi Publik vs Data Seluler Saat di Luar
        new Chart(document.getElementById("chartWifiVsDataLuar"), {
            type: "pie",
            data: {
                labels: {!! json_encode($wifiVsDataLuarCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($wifiVsDataLuarCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912"]
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'right' },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Durasi WiFi Publik
        new Chart(document.getElementById("chartDurasiWifiPublik"), {
            type: "pie",
            data: {
                labels: {!! json_encode($durasiWifiPublikCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($durasiWifiPublikCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912", "#FF9900", "#109618", "#990099", "#0099C6"]
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'right' },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Frekuensi Bepergian Keluar Kota
        new Chart(document.getElementById("chartKeluarKotaBulanan"), {
            type: "pie",
            data: {
                labels: {!! json_encode(array_values($keluarKotaBulananCounts->keys()->map(fn($v) => html_entity_decode($v))->toArray())) !!},
                datasets: [{
                    data: {!! json_encode(array_values($keluarKotaBulananCounts->values()->toArray())) !!},
                    backgroundColor: [
                        "#3366CC", "#DC3912", "#FF9900", "#109618", "#990099",
                        "#0099C6", "#DD4477", "#66AA00", "#B82E2E", "#316395"
                    ]
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'right' },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: { weight: 'bold', size: 14 }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        new Chart(document.getElementById("chartKeluargaTelkomsel"), {
            type: "pie",
            data: {
                labels: {!! json_encode($keluargaTelkomselCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($keluargaTelkomselCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        new Chart(document.getElementById("chartAktifitasInternetBerat"), {
            type: "pie",
            data: {
                labels: {!! json_encode($aktifitasInternetBeratCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($aktifitasInternetBeratCounts->values()) !!},
                    backgroundColor: ["#109618", "#990099"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Data untuk pertanyaan: "Apa jenis paket internet yang biasa Anda beli?"
        new Chart(document.getElementById("chartJenisPaket"), {
            type: "pie",
            data: {
                labels: {!! json_encode($jenisPaketCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($jenisPaketCounts->values()) !!},
                    backgroundColor: ["#FF9900", "#3366CC", "#DC3912"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Pie chart: "Dari mana biasanya Anda membeli paket internet?"
        new Chart(document.getElementById("chartSumberPembelianPaket"), {
            type: "pie",
            data: {
                labels: {!! json_encode($sumberPembelianPaketCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($sumberPembelianPaketCounts->values()) !!},
                    backgroundColor: ["#FF9900", "#3366CC", "#109618", "#990099", "#DD4477"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Bar chart: "Bagaimana Anda menilai kualitas layanan Telkomsel secara keseluruhan?"
        new Chart(document.getElementById("chartPenilaianKualitasTelkomsel"), {
            type: "bar",
            data: {
                labels: {!! json_encode($penilaianKualitasTelkomselCounts->keys()) !!}, // 1-5
                datasets: [{
                    data: {!! json_encode($penilaianKualitasTelkomselCounts->values()) !!},
                    backgroundColor: "#3366CC"
                }]
            },
            options: {
                layout: {
                    padding: {
                        top: 30 // beri ruang ekstra di atas untuk menghindari tabrakan angka
                    }
                },
                plugins: {
                    legend: {
                        display: false // legend dihilangkan
                    },
                    title: {
                        display: false,
                        text: "Penilaian Kualitas Layanan Telkomsel"
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 5, // jarak angka dari bar
                        formatter: Math.round,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Responden'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Skala Penilaian (1 = Sangat Buruk, 5 = Sangat Baik)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


        // Bar chart: "Seberapa sering Anda mengalami gangguan sinyal atau panggilan saat memakai Telkomsel?"
        new Chart(document.getElementById("chartFrekuensiGangguan"), {
            type: "bar",
            data: {
                labels: {!! json_encode($frekuensiGangguanCounts->keys()) !!}, // ambil label dari DB
                datasets: [{
                    data: {!! json_encode($frekuensiGangguanCounts->values()) !!}, // ambil nilai dari DB
                    backgroundColor: "#FF9900"
                }]
            },
            options: {
                layout: {
                    padding: {
                        top: 30 // beri ruang tambahan di atas supaya angka tidak menimpa title
                    }
                },
                plugins: {
                    legend: {
                        display: false // legend dihilangkan
                    },
                    title: {
                        display: false,
                        text: "Frekuensi Gangguan Sinyal atau Panggilan Telkomsel"
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 5, // jarak angka dari bar
                        formatter: Math.round,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Responden'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Skala Frekuensi (1 = Sangat Jarang, 5 = Sangat Sering)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


        // Bar Chart untuk: "Seberapa mudah Anda membeli produk atau paket Telkomsel?"
        new Chart(document.getElementById("chartKemudahanBeliTelkomsel"), {
            type: "bar",
            data: {
                labels: {!! json_encode($kemudahanBeliTelkomselCounts->keys()) !!}, // Label dari DB
                datasets: [{
                    data: {!! json_encode($kemudahanBeliTelkomselCounts->values()) !!}, // Data dari DB
                    backgroundColor: "#FF9900"
                }]
            },
            options: {
                layout: {
                    padding: {
                        top: 30 // memberi ruang tambahan di atas agar label tidak menimpa title
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false,
                        text: "Kemudahan Membeli Produk Telkomsel"
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 5, // jarak label angka dari bar
                        formatter: Math.round,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Responden'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Skala Kemudahan (1 = Sangat Mudah, 5 = Sangat Sulit)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


        // Pie Chart untuk: "Berapa maksimal harga paket internet bulanan Telkomsel yang masih Anda anggap wajar?"
        new Chart(document.getElementById("chartHargaPaketWajar"), {
            type: "pie",
            data: {
                labels: {!! json_encode($hargaPaketWajarCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($hargaPaketWajarCounts->values()) !!},
                    backgroundColor: ["#FF9900", "#3366CC", "#DC3912", "#109618", "#990099"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Bar Chart untuk: "Apakah harga layanan Telkomsel sepadan dengan manfaat yang Anda dapatkan?"
        new Chart(document.getElementById("chartSepadanHargaTelkomsel"), {
            type: "bar",
            data: {
                labels: {!! json_encode($sepadanHargaTelkomselCounts->keys()) !!}, // 1-5
                datasets: [{
                    data: {!! json_encode($sepadanHargaTelkomselCounts->values()) !!},
                    backgroundColor: "#3366CC"
                }]
            },
            options: {
                layout: {
                    padding: {
                        top: 30 // ruang ekstra di atas untuk menghindari tabrakan angka dan title
                    }
                },
                plugins: {
                    legend: {
                        display: false // legend dihilangkan
                    },
                    title: {
                        display: false,
                        text: "Kesesuaian Harga & Manfaat Telkomsel"
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 5, // beri jarak label angka dari bar
                        formatter: Math.round,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Responden'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Skala Sepadan (1 = Tidak Sepadan, 5 = Sangat Sepadan)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Pie Chart: "Apakah Anda tertarik dengan promo dari provider selain Telkomsel?"
        new Chart(document.getElementById("chartTertarikPromoLain"), {
            type: "pie",
            data: {
                labels: {!! json_encode($tertarikPromoLainCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($tertarikPromoLainCounts->values()) !!},
                    backgroundColor: ["#3366CC", "#DC3912"]
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            const percentage = (value / total) * 100;
                            return percentage >= 10 ? percentage.toFixed(1) + '%' : '';
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Bar Chart: "Menurut Anda, seberapa mudah Anda untuk pindah ke provider lain?"
        new Chart(document.getElementById("chartKemudahanPindahProvider"), {
            type: "bar",
            data: {
                labels: {!! json_encode($kemudahanPindahProviderCounts->keys()) !!}, // 1-5
                datasets: [{
                    data: {!! json_encode($kemudahanPindahProviderCounts->values()) !!},
                    backgroundColor: "#FF9900"
                }]
            },
            options: {
                layout: {
                    padding: { top: 30 }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false,
                        text: "Kemudahan Pindah ke Provider Lain"
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 5,
                        formatter: Math.round,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Responden'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Skala Kemudahan (1 = Sangat Sulit, 5 = Sangat Mudah)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Bar Chart: "Apakah tarif layanan Telkomsel lebih mahal dibandingkan provider lain?"
        new Chart(document.getElementById("chartMahalDibandingkan"), {
            type: "bar",
            data: {
                labels: {!! json_encode($mahalDibandingkanCounts->keys()) !!}, // 1-5
                datasets: [{
                    data: {!! json_encode($mahalDibandingkanCounts->values()) !!},
                    backgroundColor: "#3366CC"
                }]
            },
            options: {
                layout: {
                    padding: { top: 30 }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false,
                        text: "Tarif Telkomsel vs Provider Lain"
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        offset: 5,
                        formatter: Math.round,
                        font: {
                            weight: 'bold'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Responden'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Skala Tarif (1 = Jauh Lebih Murah, 5 = Jauh Lebih Mahal)'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });


    </script>

</x-app-layout>
