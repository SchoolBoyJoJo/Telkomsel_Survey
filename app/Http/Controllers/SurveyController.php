<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\Question;
use App\Models\SurveyAnswer;
use App\Models\DynamicSurveyAnswer;
use Vinkla\Hashids\Facades\Hashids;

class SurveyController extends Controller
{
    /**
     * ==============================
     * ADMIN SIDE: CREATE SURVEY
     * ==============================
     */
    public function create()
    {
        return view('surveys.create'); // view form create survey
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'survey_type' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|string',
            'questions.*.options' => 'nullable|string',
            'questions.*.left_label' => 'nullable|string',
            'questions.*.right_label' => 'nullable|string',
        ]);

        // 1. Simpan survey utama
        $survey = Survey::create([
            'survey_type' => $validated['survey_type'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'hash' => Hashids::encode(time()),
        ]);

        // 2. Simpan pertanyaan
        foreach ($validated['questions'] as $q) {
            $options = null;

            if ($q['type'] === 'multiple') {
                $options = $q['options'] ?? null;
            } elseif ($q['type'] === 'scale') {
                $left = $q['left_label'] ?? '';
                $right = $q['right_label'] ?? '';
                $options = $left . '|' . $right; // simpan sebagai string "Left|Right"
            }

            $survey->questions()->create([
                'text' => $q['text'],
                'type' => $q['type'],
                'options' => $options,
            ]);
        }

        return redirect()->route('surveys.index')
            ->with('success', 'Survey berhasil dibuat!');
    }

    public function index()
    {
        $surveys = Survey::withCount('dynamicAnswers')->get();
        return view('surveys.index', compact('surveys')); //survey list
    }

    public function publicShow($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);

        // siapkan steps array khusus untuk dipakai di Blade
        $steps = $survey->questions->map(fn($q) => [
            'id'       => $q->id,
            'text' => $q->text,
            'name'     => 'q_' . $q->id,
            'options'  => $q->options,
            'withOther'=> $q->with_other ?? false,
            'type'     => $q->type ?? 'radio',
        ])->values()->toArray();

        return view('surveys.public', compact('survey', 'steps'));
    }

    public function showResults($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);
        $answers = DynamicSurveyAnswer::where('survey_id', $id)->get();

        // decode semua jawaban dari JSON
        $decodedAnswers = $answers->map(function ($a) {
            return json_decode($a->answers, true) ?: [];
        })->filter()->values();

        $respondentCount = $decodedAnswers->count();

        // index pertanyaan berdasarkan id
        $questionsById = $survey->questions->keyBy('id');

        // inisialisasi variabel
        $ageCounts = [];
        $chartsMap = [];
        $textAnswers = [];

        // --- 1) Inisialisasi berdasarkan tipe pertanyaan ---
        foreach ($questionsById as $qid => $q) {
            $label = $q->text;

            if (in_array($q->type, ['input', 'textarea'])) {
                // jawaban teks terbuka
                $textAnswers[$label] = [];
            } else {
                // tentukan jenis chart
                $chartType = $q->type === 'scale' ? 'bar' : 'pie';
                $chartsMap[$label] = [
                    'type' => $chartType,
                    'counts' => []
                ];

                // ✅ Inisialisasi opsi multiple choice agar selalu tampil di chart
                if ($q->type === 'multiple' && !empty($q->options)) {
                    // asumsikan opsi disimpan dipisah dengan koma
                    $options = array_map('trim', explode(',', $q->options));
                    foreach ($options as $opt) {
                        if ($opt !== '') {
                            $chartsMap[$label]['counts'][$opt] = 0;
                        }
                    }
                }

                // ✅ Inisialisasi skala 1–5 agar selalu tampil di chart
                if ($q->type === 'scale') {
                    for ($i = 1; $i <= 5; $i++) {
                        $chartsMap[$label]['counts'][(string)$i] = 0;
                    }
                }
            }
        }

        // --- 2) Agregasi jawaban responden ---
        foreach ($decodedAnswers as $entry) {
            foreach ($entry as $key => $val) {
                // tangani data usia
                if ($key === 'usia' && is_numeric($val)) {
                    $ageCounts[$val] = ($ageCounts[$val] ?? 0) + 1;
                    continue;
                }

                // hanya proses pertanyaan valid
                if (str_starts_with($key, 'q_')) {
                    $qid = (int) substr($key, 2);
                    if (!isset($questionsById[$qid])) continue;

                    $q = $questionsById[$qid];
                    $label = $q->text;

                    if (in_array($q->type, ['input', 'textarea'])) {
                        // tambahkan jawaban teks
                        $textAnswers[$label][] = (string) $val;
                    } else {
                        // tambahkan jumlah jawaban untuk opsi terkait
                        $chartsMap[$label]['counts'][$val] = ($chartsMap[$label]['counts'][$val] ?? 0) + 1;
                    }
                }
            }
        }

        // --- 3) Siapkan data usia ---
        if (!empty($ageCounts)) {
            ksort($ageCounts, SORT_NUMERIC);
        }
        $ageLabels = array_keys($ageCounts);
        $ageData = array_values($ageCounts);

        // --- 4) Ubah struktur chartsMap menjadi array chart siap pakai ---
        $charts = [];
        foreach ($chartsMap as $question => $info) {
            $counts = $info['counts'];

            // tetap tampilkan meskipun semua 0
            if ($info['type'] === 'bar') {
                ksort($counts, SORT_NUMERIC);
            } else {
                arsort($counts);
            }

            $charts[] = [
                'question' => $question,
                'type'     => $info['type'],
                'labels'   => array_keys($counts),
                'data'     => array_values($counts),
            ];
        }

        // --- 5) Kirim data ke view ---
        return view('surveys.results', compact(
            'survey', 'charts', 'textAnswers',
            'respondentCount', 'ageLabels', 'ageData'
        ));
    }

    /**
     * ==============================
     * USER SIDE: JAWAB SURVEY
     * ==============================
     */
    public function telkomsel()
    {
        return view('surveys.telkomsel');
    }

    public function storeTelkomsel(Request $request)
    {
        $this->storeGeneric($request, 'telkomsel');
    }

    public function indihome()
    {
        return view('surveys.indihome');
    }

    public function storeIndihome(Request $request)
    {
        $this->storeGeneric($request, 'indihome');
    }

    public function template()
    {
        return view('surveys.template');
    }

    public function storeTemplate(Request $request)
    {
        // contoh: simpan hasil survey
        $surveyId = $request->input('survey_id');
        $nomorHp  = $request->input('nomorHp');
        // proses simpan data jawaban survey di sini

        return redirect()->route('surveys.index')->with('success', 'Survey berhasil disimpan!');
    }

    private function storeGeneric(Request $request, $type)
    {
        $data = $request->all();

        SurveyAnswer::create([
            'nomorHp' => $data['nomorHp'] ?? null,
            'survey_type' => $type,
            'answers' => json_encode($data),
        ]);

        return response()->json(['success' => true]);
    }

    public function storeDynamicAnswer(Request $request, $hash)
    {
        $decoded = Hashids::decode($hash);
        $surveyId = $decoded[0] ?? null;

        abort_unless($surveyId, 404);

        $survey = Survey::findOrFail($surveyId);

        DynamicSurveyAnswer::create([
            'survey_id'   => $survey->id,
            'survey_type' => $survey->survey_type,
            'answers'     => json_encode($request->all()),
        ]);

        return response()->json(['success' => true]);
    }


}
