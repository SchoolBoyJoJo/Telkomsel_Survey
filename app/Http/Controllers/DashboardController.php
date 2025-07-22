<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\SurveyAnswer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Ambil jenis survey dari query string, default 'telkomsel'
        $surveyType = $request->get('survey_type', 'telkomsel');

        // Ambil data survey berdasarkan jenisnya
        $surveys = SurveyAnswer::where('survey_type', $surveyType)
            ->latest()
            ->paginate(10);

        return view('dashboard', [
            'surveys' => $surveys,
            'selectedType' => $surveyType
        ]);
    }

    public function download(Request $request): StreamedResponse
    {
        $surveyType = $request->get('survey_type', 'telkomsel');
        $fileName = 'survey_' . $surveyType . '_' . now()->format('Ymd_His') . '.csv';

        $surveys = SurveyAnswer::where('survey_type', $surveyType)->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->stream(function () use ($surveys) {
            $handle = fopen('php://output', 'w');

            if ($surveys->isNotEmpty()) {
                // Header kolom CSV
                fputcsv($handle, array_keys($surveys->first()->toArray()));

                // Baris data
                foreach ($surveys as $survey) {
                    fputcsv($handle, $survey->toArray());
                }
            } else {
                fputcsv($handle, ['Tidak ada data untuk tipe survey ini.']);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
