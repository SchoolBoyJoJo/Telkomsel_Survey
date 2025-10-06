<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Survey Pengguna</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in { opacity: 0; transform: translateY(30px); transition: all 0.5s cubic-bezier(.4, 0, .2, 1); }
        .fade-in.show { opacity: 1; transform: translateY(0); }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; }
    </style>
</head>
<body class="bg-gradient-to-br from-red-100 via-white to-red-50 min-h-screen w-full font-sans">
<div class="min-h-screen w-full flex items-center justify-center">
    <div class="bg-white w-full max-w-md md:rounded-3xl shadow-2xl flex flex-col justify-between overflow-hidden transition-all duration-500 my-8">

        <!-- Welcome Screen -->
        <div id="welcome" class="flex flex-col items-center justify-center py-20 px-8 fade-in">
            <img src="{{ asset('img/logo_telkom.png') }}" alt="Telkomsel Logo" class="w-24 h-24 mb-6">
            <div class="text-3xl font-bold text-red-600 mb-3">Selamat Datang!</div>
            <div class="text-gray-700 text-lg text-center mb-8">
                {{ $survey->description ?? 'Terima kasih atas waktu Anda. Mohon luangkan beberapa menit untuk mengisi survey pengalaman Anda bersama Telkomsel.' }}
            </div>
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

        <!-- Thank You -->
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
    window.steps = @json($steps);

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
                <div class="text-2xl font-bold text-gray-800 mb-8">${step.text}</div>
                <div class="flex flex-col gap-4">
        `;

       if ((step.type === 'radio' || step.type === 'multiple') && step.options) {
        // ---- Single Option (Radio)
        let opts = step.options.split(',');
        const saved = answers["q_" + step.id] || '';
        opts.forEach(opt => {
            opt = opt.trim();
            html += `
            <label class="flex items-center gap-3 py-3 px-4 rounded-2xl border border-gray-200 hover:border-red-400 transition cursor-pointer bg-gray-50">
                <input type="radio" name="q_${step.id}" value="${opt}" class="accent-red-500 w-6 h-6" ${saved === opt ? 'checked' : ''}>
                <span class="text-lg">${opt}</span>
            </label>`;
        });
        } else if (step.type === 'scale') {
            // ---- Skala 1 - 5 dengan label kiri-kanan
            let labels = step.options ? step.options.split('|') : ['', ''];
            let leftLabel = labels[0] || '';
            let rightLabel = labels[1] || '';

            html += `
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">${leftLabel}</span>
                    <span class="text-sm text-gray-600">${rightLabel}</span>
                </div>
                <div class="flex justify-between">
            `;

            for (let i = 1; i <= 5; i++) {
                html += `
                <label class="flex flex-col items-center">
                    <input type="radio" name="q_${step.id}" value="${i}" 
                        class="accent-red-500 w-6 h-6" 
                        ${answers["q_"+step.id]==i?'checked':''}>
                    <span class="text-sm mt-1">${i}</span>
                </label>`;
            }

            html += `</div>`;
        } else {
            // ---- Input Text
            html += `
                <input type="text" name="q_${step.id}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-xl 
                            focus:outline-none focus:border-red-500 transition"
                    value="${answers["q_"+step.id]||''}">
            `;
        }

        html += `</div></div>`;
        stepContent.innerHTML = html;

        setTimeout(() => stepContent.firstElementChild.classList.add('show'), 20);

        backBtn.style.visibility = currentStep === 0 ? 'hidden' : 'visible';
        nextBtn.innerHTML = currentStep === steps.length - 1 ? '<span class="text-lg font-semibold">Kirim</span>' : '&#8594;';
    }

    nextBtn.addEventListener("click", () => {
        const step = steps[currentStep];
        const inputName = "q_" + step.id;
        let value = "";

        if (step.type === "radio" || step.type === "scale") {
            const checked = document.querySelector(`input[name="${inputName}"]:checked`);
            value = checked ? checked.value : "";
        } else if (step.type === "textarea") {
            const input = document.querySelector(`textarea[name="${inputName}"]`);
            value = input ? input.value.trim() : "";
        } else {
            const input = document.querySelector(`input[name="${inputName}"]`);
            value = input ? input.value.trim() : "";
        }

        if (!value) {
            stepContent.firstElementChild.classList.remove('show');
            setTimeout(() => stepContent.firstElementChild.classList.add('show'), 100);
            stepContent.firstElementChild.classList.add('ring-2', 'ring-red-300');
            setTimeout(() => stepContent.firstElementChild.classList.remove('ring-2', 'ring-red-300'), 700);
            return;
        }

        answers[inputName] = value;

        if (currentStep < steps.length - 1) {
            currentStep++;
            renderStep();
        } else {
            surveyForm.classList.add('hidden');
            surveyForm.style.display = 'none';
            thankyou.classList.remove('hidden');
            setTimeout(() => thankyou.classList.add('show'), 20);

            console.log("Jawaban semua:", answers);
            // --- TODO: fetch POST ke server untuk simpan jawaban ---
            fetch(`/survey/{{ $survey->id }}/dynamic-submit`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify(answers)
            })
            .then(res => res.json())
            .then(data => {
                console.log("Respon server:", data);
            })
            .catch(err => console.error("Error simpan survey:", err));
        }
    });

    backBtn.addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            renderStep();
        }
    });

    surveyForm.addEventListener('submit', e => e.preventDefault());
</script>

</body>
</html>
