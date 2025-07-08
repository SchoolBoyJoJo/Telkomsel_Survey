<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyAnswer;

class SurveyController extends Controller
{
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
        $this->storeGeneric($request, 'template');
    }

    private function storeGeneric(Request $request, $type)
    {
        $data = $request->all();

        SurveyAnswer::create([
            'nomorHp' => $data['nomorHp'] ?? null,
            'survey_type' => $type, // pastikan kamu tambahkan kolom ini di tabel
            'answers' => json_encode($data),
        ]);

        return response()->json(['success' => true]);
    }
}
