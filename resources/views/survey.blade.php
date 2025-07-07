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
        const steps = [{
                question: "Seberapa sering Anda menggunakan layanan Telkomsel dalam 3 bulan terakhir sebelum berhenti?",
                name: "frekuensi",
                options: [
                    "Setiap hari",
                    "Beberapa kali seminggu",
                    "Sekali seminggu",
                    "Jarang",
                    "Tidak pernah"
                ]
            },
            {
                question: "Berapa besar rata-rata pengeluaran Anda per bulan untuk layanan Telkomsel sebelum berhenti?",
                name: "pengeluaran",
                options: [
                    "< Rp20.000",
                    "Rp20.000 – Rp50.000",
                    "Rp50.001 – Rp100.000",
                    "> Rp100.000"
                ]
            },
            {
                question: "Apa alasan utama Anda tidak lagi menggunakan nomor Telkomsel? (Pilih satu)",
                name: "alasan",
                options: [
                    "Harga paket terlalu mahal",
                    "Jaringan kurang stabil",
                    "Paket internet tidak sesuai kebutuhan",
                    "Beralih ke provider lain yang lebih murah",
                    "Tidak ada promo menarik",
                    "Layanan pelanggan kurang memuaskan",
                    "Alasan pribadi"
                ]
            },
            {
                question: "Apakah Anda merasa benefit/promo dari Telkomsel cukup menarik dibandingkan provider lain?",
                name: "promo",
                options: [
                    "Sangat tidak menarik",
                    "Tidak menarik",
                    "Netral",
                    "Menarik",
                    "Sangat menarik"
                ]
            },
            {
                question: "Apakah Anda mengalami kesulitan saat membeli paket data atau pulsa Telkomsel?",
                name: "kesulitan",
                options: [
                    "Sering",
                    "Kadang-kadang",
                    "Tidak pernah"
                ]
            },
            {
                question: "Seberapa puas Anda dengan kualitas jaringan Telkomsel di lokasi utama Anda (rumah/kantor)?",
                name: "jaringan",
                options: [
                    "Sangat tidak puas",
                    "Tidak puas",
                    "Netral",
                    "Puas",
                    "Sangat puas"
                ]
            },
            {
                question: "Apakah Anda menerima informasi promo atau penawaran dari Telkomsel secara berkala?",
                name: "info_promo",
                options: [
                    "Ya, sering",
                    "Kadang-kadang",
                    "Tidak pernah"
                ]
            },
            {
                question: "Setelah berhenti menggunakan Telkomsel, apakah Anda berpindah ke provider lain? Jika ya, ke mana?",
                name: "pindah",
                options: [
                    "Tidak pindah",
                    "Indosat",
                    "XL/Axis",
                    "Tri",
                    "Smartfren",
                    "Lainnya"
                ],
                withOther: true
            },
            {
                question: "Apakah Anda bersedia kembali menggunakan Telkomsel di masa depan?",
                name: "kembali",
                options: [
                    "Ya",
                    "Tidak",
                    "Mungkin"
                ]
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
            let html = `
                <div class="fade-in px-2">
                  <div class="text-2xl font-bold text-gray-800 mb-8">${step.question}</div>
                  <div class="flex flex-col gap-4">
              `;
            step.options.forEach((opt, idx) => {
                if (step.withOther && opt === "Lainnya") {
                    html += `
                        <label class="flex items-center gap-3 py-3 px-4 rounded-2xl border border-gray-200 hover:border-red-400 transition cursor-pointer bg-gray-50">
                          <input type="radio" name="${step.name}" value="Lainnya" class="accent-red-500 w-6 h-6" id="lainnyaRadio">
                          <span>Lainnya:</span>
                          <input type="text" name="${step.name}_lainnya" id="lainnyaInput" class="ml-0 border-b border-gray-300 px-2 py-1 w-36 focus:outline-none focus:border-red-500 transition-all bg-transparent" style="display:none;" placeholder="Isi provider lain">
                        </label>
                      `;
                } else {
                    html += `
                      <label class="flex items-center gap-3 py-3 px-4 rounded-2xl border border-gray-200 hover:border-red-400 transition cursor-pointer bg-gray-50">
                        <input type="radio" name="${step.name}" value="${opt}" class="accent-red-500 w-6 h-6" ${answers[step.name]===opt?'checked':''}>
                        <span class="text-lg">${opt}</span>
                      </label>`;
                }
            });
            html += `</div></div>`;
            stepContent.innerHTML = html;
            setTimeout(() => stepContent.firstElementChild.classList.add('show'), 20);
            // "Lainnya" JS
            if (step.withOther) handleLainnya();
            // Back btn
            backBtn.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
            // Next btn
            nextBtn.innerHTML = currentStep === steps.length - 1 ? '<span class="text-lg font-semibold">Kirim</span>' : '&#8594;';
        }

        function handleLainnya() {
            const lainnyaRadio = document.getElementById('lainnyaRadio');
            const lainnyaInput = document.getElementById('lainnyaInput');
            if (!lainnyaRadio) return;
            // Restore value
            if (answers['pindah'] === 'Lainnya') {
                lainnyaInput.style.display = 'inline-block';
                lainnyaInput.value = answers['pindah_lainnya'] || '';
                lainnyaRadio.checked = true;
            }
            document.querySelectorAll('input[name="pindah"]').forEach(radio => {
                radio.addEventListener('change', function() {
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

        nextBtn.addEventListener('click', () => {
            const step = steps[currentStep];
            const selected = surveyForm.querySelector(`input[name="${step.name}"]:checked`);
            let valid = !!selected;
            if (step.withOther && selected && selected.value === "Lainnya") {
                const lainnyaInput = document.getElementById('lainnyaInput');
                if (!lainnyaInput.value.trim()) valid = false;
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
                answers[`${step.name}_lainnya`] = document.getElementById('lainnyaInput').value.trim();
            }
            if (currentStep < steps.length - 1) {
                currentStep++;
                renderStep();
            } else {
                surveyForm.classList.add('hidden');
                surveyForm.style.display = 'none'; // <-- tambahan agar benar-benar hilang
                thankyou.classList.remove('hidden');
                setTimeout(() => thankyou.classList.add('show'), 20);

            fetch('/submit-survey', {
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
                        const lainnyaInput = document.getElementById('lainnyaInput');
                        if (lainnyaInput) {
                            lainnyaInput.value = answers[`${step.name}_lainnya`] || '';
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