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

    public function storeDynamicAnswer(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);

        DynamicSurveyAnswer::create([
            'survey_id'   => $survey->id,
            'survey_type' => $survey->survey_type,
            'answers'     => json_encode($request->all()),
        ]);

        return response()->json(['success' => true]);
    }

    public function showResults($id)
    {
        $survey = Survey::findOrFail($id);

        // Ambil semua jawaban dari tabel DynamicSurveyAnswer
        $answers = \App\Models\DynamicSurveyAnswer::where('survey_id', $id)->get();

        // Decode semua jawaban JSON ke array
        $decodedAnswers = $answers->map(function ($item) {
            return json_decode($item->answers, true);
        });

        // Kirim ke view
        return view('surveys.results', compact('survey', 'decodedAnswers'));
    }


}
