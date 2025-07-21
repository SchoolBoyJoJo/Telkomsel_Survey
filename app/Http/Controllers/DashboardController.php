<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyAnswer;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $surveyType = $request->get('survey_type', 'telkomsel');

        $surveys = SurveyAnswer::where('survey_type', $surveyType)
            ->latest()
            ->paginate(10);

        return view('dashboard', [
            'surveys' => $surveys,
            'selectedType' => $surveyType
        ]);
    }
}
