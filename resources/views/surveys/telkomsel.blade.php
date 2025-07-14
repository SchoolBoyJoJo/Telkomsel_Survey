<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Survey Pengguna Telkomsel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.5s cubic-bezier(.4, 0, .2, 1);
        }

        .fade-in.show {
            opacity: 1;
            transform: translateY(0);
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-red-100 via-white to-red-50 min-h-screen w-full font-sans">
    <div class="min-h-screen w-full flex items-center justify-center">
        <div class="bg-white w-full max-w-md md:rounded-3xl shadow-2xl flex flex-col justify-between overflow-hidden transition-all duration-500 my-8">

            <!-- Welcome Screen -->
            <div id="welcome" class="flex flex-col items-center justify-center py-20 px-8 fade-in">
                <img src="{{ asset('img/logo_telkom.png') }}" alt="Telkomsel Logo" class="w-24 h-24 mb-6">
                <div class="text-3xl font-bold text-red-600 mb-3">Selamat Datang!</div>
                <div class="text-gray-700 text-lg text-center mb-8">Terima kasih atas waktu Anda.<br>Mohon luangkan beberapa menit untuk mengisi survey pengalaman Anda bersama Telkomsel.</div>
                <button id="startSurveyBtn" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-8 py-3 rounded-2xl shadow transition text-lg">Mulai</button>
            </div>

            <!-- Progress Bar -->
            <div class="px-8 pt-10 pb-2" id="progress-container" style="display:none;">
                <div class="flex items-center mb-6">
                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                        <div id="progress-bar" class="bg-red-500 h-2 rounded-full transition-all duration-500" style="width:0%"></div>
                    </div>
                    <div class="text-sm font-medium text-gray-500" id="progress-text"></div>
                </div>
            </div>

            <!-- Phone Number Form -->
            <form id="phoneForm" class="flex flex-col justify-center items-center px-8 py-16 fade-in" style="display:none;">
                <div class="text-2xl font-bold text-gray-800 mb-8 text-center">Masukkan Nomor HP Anda</div>
                <input type="tel" id="nomorHp" name="nomorHp" pattern="08[0-9]{8,12}" maxlength="13" minlength="10"
                    placeholder="Contoh: 081234567890" required
                    class="w-full px-5 py-3 rounded-xl border border-gray-300 focus:border-red-500 focus:outline-none text-lg mb-6 transition-all">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold px-8 py-3 rounded-2xl shadow transition text-lg w-full">Lanjut</button>
            </form>

            <!-- Steps -->
            <form id="surveyForm" class="flex-1 flex flex-col justify-center items-center px-8 pb-12 relative overflow-y-auto" style="display:none;">
                <div id="step-content" class="w-full"></div>
                <div class="flex justify-between items-center w-full mt-10">
                    <button type="button" id="backBtn" class="rounded-full w-14 h-14 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-600 text-2xl font-bold shadow transition disabled:opacity-40" style="visibility:hidden">&#8592;</button>
                    <button type="button" id="nextBtn" class="rounded-full w-16 h-16 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white text-2xl font-bold shadow-lg transition">&#8594;</button>
                </div>
            </form>
            <div id="thankyou" class="hidden flex flex-col items-center justify-center py-24 px-8 fade-in">
                <svg width="64" height="64" fill="none" class="mb-6">
                    <circle cx="32" cy="32" r="32" fill="#F3F8F4" />
                    <path d="M19 33.5L28.5 43L45 25.5" stroke="#22C55E" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="text-2xl font-bold text-green-600 mb-2">Terima kasih!</div>
                <div class="text-gray-600 text-lg text-center">Jawaban Anda sudah kami terima.</div>
            </div>
        </div>
    </div>
    <script>

        // ========== Survey Steps ==========
        const steps = [
            {
                question: "Usia Anda (dalam tahun)?",
                name: "usia",
                options: [
                    "< 18",
                    "18 - 25",
                    "26 - 35",
                    "36 - 45",
                    "46 - 60",
                    "> 60"
                ]
            },
            {
                question: "Jenis Kelamin?",
                name: "jenis_kelamin",
                options: [
                    "Laki-laki",
                    "Perempuan"
                ]
            },
            {
                question: "Jenis Tempat Tinggal?",
                name: "jenis_tempat_tinggal",
                options: [
                    "Kontrakan",
                    "Rumah Sendiri",
                    "Apartemen",
                    "Kos"
                ]
            },
            {
                question: "Status Pekerjaan Anda saat ini?",
                name: "status_pekerjaan",
                options: [
                    "Tidak Bekerja",
                    "Pelajar/Mahasiswa",
                    "Pekerja",
                    "Wirausaha"
                ]
            },
            {
                question: "Berapa total pendapatan pribadi Anda per bulan?",
                name: "pendapatan",
                options: [
                    "< Rp 1.000.000",
                    "Rp 1.000.000 – Rp 4.999.999",
                    "Rp 5.000.000 – Rp 7.999.999",
                    "Rp 8.000.000 – Rp 14.999.999",
                    "≥ Rp 15.000.000"
                ]
            },
            {
                question: "Apakah saat ini Anda masih aktif menggunakan Telkomsel untuk membeli pulsa dan kuota internet?",
                name: "aktif_telkomsel",
                options: [
                    "Ya",
                    "Tidak"
                ]
            },
            {
                question: "Apakah saat ini Anda menggunakan lebih dari 1 kartu SIM (Multisimer)?",
                name: "multisimer",
                options: [
                    "Ya",
                    "Tidak"
                ]
            },
            {
                question: "Jika Anda Multisimer, apa kartu SIM kedua yang saat ini Anda pakai?",
                name: "sim_kedua",
                options: [
                    "Bukan Multisimer",
                    "Telkomsel",
                    "IM3",
                    "Tri",
                    "XL",
                    "Axis",
                    "Smartfren",
                    "Other:"
                ],
                withOther: true,
                otherValues: ["Other:"]
            },
            {
                question: "Apakah di rumah Anda ada jaringan WiFi?",
                name: "wifi_rumah",
                options: [
                    "Ya",
                    "Tidak"
                ]
            },
            {
                question: "Jika Anda memakai WiFi di rumah, apa provider jaringan WiFi yang Anda pakai saat ini?",
                name: "provider_wifi",
                options: [
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
                withOther: true,
                otherValues: ["Other:"]
            },
            {
                question: "Apakah saat di luar Anda lebih sering menggunakan WiFi publik dibanding data seluler?",
                name: "wifi_vs_data_luar",
                options: [
                    "Ya",
                    "Tidak"
                ]
            },
            {
                question: "Berapa lama rata-rata Anda menggunakan WiFi publik dalam sehari saat berada di luar rumah?",
                name: "durasi_wifi_publik",
                options: [
                    "Tidak Pernah",
                    "< 30 Menit",
                    "30 Menit - 1 Jam",
                    "1 - 4 Jam",
                    "4 - 7 Jam",
                    "> 7 Jam"
                ]
            },
            {
                question: "Berapa rata-rata Anda bepergian ke luar kota dalam sebulan?",
                name: "keluar_kota_bulanan",
                options: [
                    "0",
                    "1",
                    "2",
                    "3",
                    "> 3"
                ]
            },
            {
                question: "Apakah di keluarga Anda mayoritas memakai Telkomsel?",
                name: "keluarga_telkomsel",
                options: [
                    "Ya",
                    "Tidak"
                ]
            },
            {
                question: "Apakah Anda rutin melakukan aktivitas yang membutuhkan koneksi internet stabil seperti gaming atau streaming?",
                name: "aktifitas_internet_berat",
                options: [
                    "Ya",
                    "Tidak"
                ]
            },
            {
                question: "Apa jenis paket internet yang biasa Anda beli?",
                name: "jenis_paket",
                options: [
                    "Harian",
                    "Mingguan",
                    "Bulanan"
                ]
            },
            {
                question: "Dari mana biasanya Anda membeli paket internet?",
                name: "sumber_pembelian_paket",
                options: [
                    "Outlet / Konter",
                    "UMB (*123#, *363#, *808#, dll)",
                    "Apps Provider (MyTelkomsel, myXL, myIM3, dll)",
                    "Minimarket (Indomaret, Alfamart, dll)",
                    "Lainnya"
                ],
                withOther: true,
                otherValues: ["Lainnya"]
            },
            {
                question: "Bagaimana Anda menilai kualitas layanan Telkomsel secara keseluruhan?",
                name: "penilaian_kualitas_telkomsel",
                type: "scale",
                scale: {
                    minLabel: "Sangat Buruk",
                    maxLabel: "Sangat Baik",
                    values: [1, 2, 3, 4, 5]
                }
            },
            {
                question: "Seberapa sering Anda mengalami gangguan sinyal atau panggilan saat memakai Telkomsel?",
                name: "frekuensi_gangguan",
                type: "scale",
                scale: {
                    minLabel: "Sangat Jarang",
                    maxLabel: "Sangat Sering",
                    values: [1, 2, 3, 4, 5]
                }
            },
            {
                question: "Seberapa mudah Anda membeli produk atau paket Telkomsel?",
                name: "kemudahan_beli_telkomsel",
                type: "scale",
                scale: {
                    minLabel: "Sangat Mudah",
                    maxLabel: "Sangat Sulit",
                    values: [1, 2, 3, 4, 5]
                }
            },
            {
                question: "Berapa maksimal harga paket internet bulanan Telkomsel yang masih Anda anggap wajar?",
                name: "harga_paket_wajar",
                options: [
                    "Di bawah Rp 50.000",
                    "Rp 50.000 – Rp 100.000",
                    "Rp 100.000 – Rp 150.000",
                    "Rp 150.000 – Rp 200.000",
                    "Lebih dari Rp 200.000"
                ]
            },
            {
                question: "Menurut Anda, apakah harga layanan Telkomsel sepadan dengan manfaat yang Anda dapatkan?",
                name: "sepadan_harga_telkomsel",
                type: "scale",
                scale: {
                    minLabel: "Sangat Tidak Sepadan",
                    maxLabel: "Sangat Sepadan",
                    values: [1, 2, 3, 4, 5]
                }
            },
            {
                question: "Apakah Anda tertarik dengan promo dari provider selain Telkomsel?",
                name: "tertarik_promo_lain",
                options: [
                    "Ya",
                    "Tidak"
                ]
            },
            {
                question: "Menurut Anda, seberapa mudah Anda untuk pindah ke provider lain?",
                name: "kemudahan_pindah_provider",
                type: "scale",
                scale: {
                    minLabel: "Sangat Sulit",
                    maxLabel: "Sangat Mudah",
                    values: [1, 2, 3, 4, 5]
                }
            },
            {
                question: "Menurut Anda, apakah tarif layanan Telkomsel lebih mahal dibandingkan provider lain?",
                name: "mahal_dibandingkan",
                type: "scale",
                scale: {
                    minLabel: "Jauh Lebih Murah",
                    maxLabel: "Jauh Lebih Mahal",
                    values: [1, 2, 3, 4, 5]
                }
            },
            {
                question: "Tambahkan saran dan keluhan lain selama Anda menggunakan Telkomsel jika ada.",
                name: "saran_telkomsel",
                type: "text"
            }

        ];

        let currentStep = 0;
        const answers = {};

        // DOM Elements
        const welcome = document.getElementById('welcome');
        const startSurveyBtn = document.getElementById('startSurveyBtn');
        const phoneForm = document.getElementById('phoneForm');
        const nomorHpInput = document.getElementById('nomorHp');
        const surveyForm = document.getElementById('surveyForm');
        const stepContent = document.getElementById('step-content');
        const nextBtn = document.getElementById('nextBtn');
        const backBtn = document.getElementById('backBtn');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');
        const thankyou = document.getElementById('thankyou');
        const progressContainer = document.getElementById('progress-container');

        // ========== Welcome Logic ==========
        setTimeout(() => welcome.classList.add('show'), 20);

        startSurveyBtn.addEventListener('click', () => {
            welcome.classList.remove('show');
            setTimeout(() => {
                welcome.style.display = 'none';
                phoneForm.style.display = 'flex';
                setTimeout(() => phoneForm.classList.add('show'), 20);
            }, 400);
        });

        // ========== Phone Number Logic ==========
        phoneForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const hp = nomorHpInput.value.trim();
            // Validasi: harus mulai 08 dan 10-13 digit
            if (!/^08\d{8,12}$/.test(hp)) {
                nomorHpInput.classList.add('ring-2', 'ring-red-300');
                setTimeout(() => nomorHpInput.classList.remove('ring-2', 'ring-red-300'), 700);
                nomorHpInput.focus();
                return;
            }
            answers['nomorHp'] = hp;
            phoneForm.classList.remove('show');
            setTimeout(() => {
                phoneForm.style.display = 'none';
                progressContainer.style.display = '';
                surveyForm.style.display = 'flex';
                renderStep();
            }, 400);
        });

        // ========== Survey Logic ==========
        function renderProgress() {
            const percent = Math.round(((currentStep + 1) / steps.length) * 100);
            progressBar.style.width = `${percent}%`;
            progressText.textContent = `${currentStep + 1}/${steps.length}`;
        }

        function renderStep() {
            renderProgress();
            const step = steps[currentStep];
            const name = step.name;
            let html = `<div class="fade-in px-2">
                <div class="text-2xl font-bold text-gray-800 mb-8">${step.question}</div>`;

            // ======== Cek jika type: 'scale' (skala Likert) ========
            if (step.type === 'scale') {
                html += `
                    <div class="flex items-center justify-between px-2 mb-2 text-sm text-gray-500">
                        <span>${step.scale.minLabel}</span>
                        <span>${step.scale.maxLabel}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                `;
                step.scale.values.forEach(val => {
                    html += `
                        <label class="flex flex-col items-center gap-2">
                            <input type="radio" name="${name}" value="${val}" class="accent-red-500 w-6 h-6" ${answers[name]==val ? 'checked' : ''}>
                            <span class="text-sm">${val}</span>
                        </label>
                    `;
                });
                html += `</div>`;
            }
            else if (step.type === "text") {
                html += `
                    <div class="w-full">
                        <textarea name="${name}" rows="5" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-red-500 focus:outline-none text-lg transition-all resize-none" placeholder="Tuliskan saran atau keluhan Anda...">${answers[name] || ''}</textarea>
                    </div>
                `;
            }
            // ======== Pertanyaan biasa / opsi biasa ========
            else {
                html += `<div class="flex flex-col gap-4">`;
                step.options.forEach(opt => {
                    const isOther = step.withOther && (step.otherValues || ["Lainnya"]).includes(opt);
                    if (isOther) {
                        html += `
                            <label class="flex items-center gap-3 py-3 px-4 rounded-2xl border border-gray-200 hover:border-red-400 transition cursor-pointer bg-gray-50">
                                <input type="radio" name="${name}" value="Lainnya" class="accent-red-500 w-6 h-6" id="lainnyaRadio_${name}">
                                <span>${opt}</span>
                                <input type="text" name="${name}_lainnya" id="lainnyaInput_${name}" class="ml-0 border-b border-gray-300 px-2 py-1 w-36 focus:outline-none focus:border-red-500 transition-all bg-transparent" style="display:none;" placeholder="Isi jawaban lainnya">
                            </label>
                        `;
                    } else {
                        html += `
                            <label class="flex items-center gap-3 py-3 px-4 rounded-2xl border border-gray-200 hover:border-red-400 transition cursor-pointer bg-gray-50">
                                <input type="radio" name="${name}" value="${opt}" class="accent-red-500 w-6 h-6" ${answers[name] === opt ? 'checked' : ''}>
                                <span class="text-lg">${opt}</span>
                            </label>
                        `;
                    }
                });
                html += `</div>`;
            }

            html += `</div>`;
            stepContent.innerHTML = html;
            setTimeout(() => stepContent.firstElementChild.classList.add('show'), 20);

            // Handle Lainnya jika ada
            if (step.withOther) handleLainnya();

            // Back dan Next Button
            backBtn.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
            nextBtn.innerHTML = currentStep === steps.length - 1 ? '<span class="text-lg font-semibold">Kirim</span>' : '&#8594;';
        }

        function handleLainnya() {
            const step = steps[currentStep];
            const name = step.name;
            const radioId = `lainnyaRadio_${name}`;
            const inputId = `lainnyaInput_${name}`;
            const lainnyaRadio = document.getElementById(radioId);
            const lainnyaInput = document.getElementById(inputId);
            const isOther = (step.otherValues || ["Lainnya"]).includes(answers[step.name]);
            if (!lainnyaRadio || !lainnyaInput) return;

            // Restore value jika sebelumnya diisi
            if (answers[name] === 'Lainnya') {
                lainnyaInput.style.display = 'inline-block';
                lainnyaInput.value = answers[`${name}_lainnya`] || '';
                lainnyaRadio.checked = true;
            }

            document.querySelectorAll(`input[name="${name}"]`).forEach(radio => {
                radio.addEventListener('change', () => {
                    if (lainnyaRadio.checked) {
                        lainnyaInput.style.display = 'inline-block';
                        lainnyaInput.required = true;
                        setTimeout(() => lainnyaInput.focus(), 100);
                    } else {
                        lainnyaInput.style.display = 'none';
                        lainnyaInput.required = false;
                        lainnyaInput.value = '';
                    }
                });
            });
        }

        function submitSurvey() {
            surveyForm.classList.add('hidden');
            surveyForm.style.display = 'none';
            thankyou.classList.remove('hidden');
            setTimeout(() => thankyou.classList.add('show'), 20);

            fetch('/telkomsel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(answers)
            })
            .then(response => response.json())
            .then(data => {
                console.log("Survey berhasil disimpan", data);
            })
            .catch(error => {
                console.error("Gagal mengirim survey:", error);
            });
        }

        nextBtn.addEventListener('click', () => {
            const step = steps[currentStep];

            // === Handle type: text ===
            if (step.type === "text") {
                const input = surveyForm.querySelector(`textarea[name="${step.name}"]`);
                const value = input?.value.trim();
                if (!value) {
                    input.classList.add('ring-2', 'ring-red-300');
                    setTimeout(() => input.classList.remove('ring-2', 'ring-red-300'), 700);
                    return;
                }
                answers[step.name] = value;

                if (currentStep < steps.length - 1) {
                    currentStep++;
                    renderStep();
                } else {
                    submitSurvey(); // <- Ini yang wajib dipanggil kalau step terakhir
                }
                return;
            }

            const selected = surveyForm.querySelector(`input[name="${step.name}"]:checked`);
            let valid = !!selected;
            const isOther = step.withOther && (step.otherValues || ["Lainnya"]).includes(selected.value);
            if (step.type === "text") {
                const input = surveyForm.querySelector(`textarea[name="${step.name}"]`);
                const value = input?.value.trim();
                if (!value) {
                    input.classList.add('ring-2', 'ring-red-300');
                    setTimeout(() => input.classList.remove('ring-2', 'ring-red-300'), 700);
                    return;
                }
                answers[step.name] = value;
            }
            if (isOther) {
                const lainnyaInput = document.getElementById(`lainnyaInput_${step.name}`);
                if (!lainnyaInput || !lainnyaInput.value.trim()) valid = false;
            }
            if (!valid) {
                stepContent.firstElementChild.classList.remove('show');
                setTimeout(() => stepContent.firstElementChild.classList.add('show'), 100);
                stepContent.firstElementChild.classList.add('ring-2', 'ring-red-300');
                setTimeout(() => stepContent.firstElementChild.classList.remove('ring-2', 'ring-red-300'), 700);
                return;
            }
            answers[step.name] = selected.value;
            if (step.withOther && selected.value === "Lainnya") {
                answers[`${step.name}_lainnya`] = document.getElementById(`lainnyaInput_${step.name}`).value.trim();
            }
            if (isOther) {
            answers[`${step.name}_lainnya`] = document.getElementById(`lainnyaInput_${step.name}`).value.trim();
            }
            if (currentStep < steps.length - 1) {
                currentStep++;
                renderStep();
            } else {
                surveyForm.classList.add('hidden');
                surveyForm.style.display = 'none'; // <-- tambahan agar benar-benar hilang
                thankyou.classList.remove('hidden');
                setTimeout(() => thankyou.classList.add('show'), 20);

            fetch('/telkomsel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(answers)
            })
            .then(response => response.json())
            .then(data => {
                console.log("Survey berhasil disimpan", data);
            })
            .catch(error => {
                console.error("Gagal mengirim survey:", error);
            });


            }
        });

        backBtn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                renderStep();
                setTimeout(() => {
                    const step = steps[currentStep];
                    const selectedVal = answers[step.name];
                    if (selectedVal) {
                        const radio = surveyForm.querySelector(`input[name="${step.name}"][value="${selectedVal}"]`);
                        if (radio) radio.checked = true;
                    }
                        if (step.withOther && selectedVal === "Lainnya") {
                            const lainnyaInput = document.getElementById(`lainnyaInput_${step.name}`);
                            if (lainnyaInput) {
                                lainnyaInput.value = answers[`${step.name}_lainnya`] || '';
                                lainnyaInput.style.display = 'inline-block'; // Optional: biar muncul juga pas kembali
                            }
                        }
                }, 100);
            }
        });

        surveyForm.addEventListener('submit', e => e.preventDefault());

        // renderStep(); // Jangan render otomatis, tunggu setelah phoneForm
    </script>
</body>

</html>