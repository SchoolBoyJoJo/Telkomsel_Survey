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

        {{-- Grafik Usia --}}
        @if ($selectedType === 'telkomsel' && isset($usiaCounts) && count($usiaCounts))
            <div class="bg-white p-4 mb-6 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">Distribusi Usia Responden</h2>
                <canvas id="usiaChart" width="400" height="150"></canvas>
            </div>

            {{-- Chart.js CDN --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
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


    <script>
        // data pada semua chart belum dari database
        // Jenis Kelamin
        new Chart(document.getElementById("chartJenisKelamin"), {
            type: "pie",
            data: {
                labels: ["Laki-laki", "Perempuan"],
                datasets: [{
                    data: [37, 78],
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
                labels: ["Kontrakan", "Rumah Sendiri", "Apartemen", "Kos"],
                datasets: [{
                    data: [5, 27, 2, 81],
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
                labels: ["Tidak Bekerja", "Pelajar/Mahasiswa", "Pekerja", "Wirausaha"],
                datasets: [{
                    data: [17, 55, 30, 13],
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
                labels: [
                    "< Rp 1.000.000",
                    "Rp 1.000.000 – Rp 4.999.999",
                    "Rp 5.000.000 – Rp 7.999.999",
                    "Rp 8.000.000 – Rp 14.999.999",
                    "≥ Rp 15.000.000"
                ],
                datasets: [{
                    data: [10, 25, 8, 5, 2], // Ganti data sesuai hasil survey
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
                labels: ["Ya", "Tidak"],
                datasets: [{
                    data: [30, 10], // Ganti data sesuai hasil survey
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
                labels: ["Ya", "Tidak"],
                datasets: [{
                    data: [12, 28], // Ganti data sesuai hasil survey
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
                labels: [
                    "Bukan Multisimer",
                    "Telkomsel",
                    "IM3",
                    "Tri",
                    "XL",
                    "Axis",
                    "Smartfren",
                    "Other:"
                ],
                datasets: [{
                    data: [12, 20, 15, 10, 18, 5, 3, 2], // Ganti sesuai hasil survey
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
                labels: ["Ya", "Tidak"],
                datasets: [{
                    data: [30, 10], // Ganti sesuai hasil survey
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
                labels: [
                    "Tidak ada WiFi",
                    "Indihome",
                    "Biznet",
                    "MyRepublic",
                    "First Media",
                    "IconNet",
                    "MNC Play",
                    "XL Home",
                    "Other:"
                ],
                datasets: [{
                    data: [5, 20, 8, 4, 3, 2, 1, 1, 1], // Ganti sesuai hasil survey
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
                labels: ["Ya", "Tidak"],
                datasets: [{
                    data: [30, 70], // Ganti sesuai data hasil survey
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
                labels: [
                    "Tidak Pernah",
                    "< 30 Menit",
                    "30 Menit - 1 Jam",
                    "1 - 4 Jam",
                    "4 - 7 Jam",
                    "> 7 Jam"
                ],
                datasets: [{
                    data: [25, 15, 20, 10, 5, 2], // Ganti sesuai data hasil survey
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
                labels: ["0", "1", "2", "3", "> 3"],
                datasets: [{
                    data: [40, 20, 15, 10, 5], // Ganti sesuai data hasil survey
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

        new Chart(document.getElementById("chartKeluargaTelkomsel"), {
            type: "pie",
            data: {
                labels: ["Ya", "Tidak"],
                datasets: [{
                    data: [60, 40], // Ganti dengan data real
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
                labels: ["Ya", "Tidak"],
                datasets: [{
                    data: [55, 45], // Ganti dengan data real
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
                labels: ["Harian", "Mingguan", "Bulanan"],
                datasets: [{
                    data: [10, 20, 70], // Ganti dengan data real
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
                labels: [
                    "Outlet / Konter",
                    "UMB (*123#, *363#, *808#, dll)",
                    "Apps Provider (MyTelkomsel, myXL, myIM3, dll)",
                    "Minimarket (Indomaret, Alfamart, dll)",
                    "Lainnya"
                ],
                datasets: [{
                    data: [20, 15, 40, 10, 15], // Ganti dengan data real
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
                labels: ["1", "2", "3", "4", "5"], // 1 = Sangat Buruk, 5 = Sangat Baik
                datasets: [{
                    data: [5, 10, 20, 30, 35], // Ganti dengan data real
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
                labels: ["1", "2", "3", "4", "5"], // 1 = Sangat Jarang, 5 = Sangat Sering
                datasets: [{
                    data: [10, 15, 25, 20, 10], // Ganti dengan data real
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
                labels: ["1", "2", "3", "4", "5"], 
                datasets: [{
                    data: [5, 10, 20, 15, 8], 
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
                labels: [
                    "Di bawah Rp 50.000",
                    "Rp 50.000 – Rp 100.000",
                    "Rp 100.000 – Rp 150.000",
                    "Rp 150.000 – Rp 200.000",
                    "Lebih dari Rp 200.000"
                ],
                datasets: [{
                    data: [5, 15, 25, 10, 3], // Ganti dengan data real
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
                labels: ["1", "2", "3", "4", "5"], // 1 = Sangat Tidak Sepadan, 5 = Sangat Sepadan
                datasets: [{
                    data: [3, 6, 18, 14, 9], // Ganti dengan data real
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
                labels: ["Ya", "Tidak"],
                datasets: [{
                    data: [60, 40], // Ganti dengan data real
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
                labels: ["1", "2", "3", "4", "5"], // 1 = Sangat Sulit, 5 = Sangat Mudah
                datasets: [{
                    data: [5, 12, 18, 10, 7], // Ganti dengan data real
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
                labels: ["1", "2", "3", "4", "5"], // 1 = Jauh Lebih Murah, 5 = Jauh Lebih Mahal
                datasets: [{
                    data: [4, 8, 15, 20, 10], // Ganti dengan data real
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
