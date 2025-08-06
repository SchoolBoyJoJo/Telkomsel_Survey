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
        $surveyType = $request->get('survey_type', 'telkomsel');

        // Ambil data survey berdasarkan tipe
        $rawSurveys = SurveyAnswer::where('survey_type', $surveyType)
            ->latest()
            ->get();

        // Decode JSON 'answers' ke array dan tambahkan created_at
        $decodedSurveys = $rawSurveys->map(function ($item) {
            $decoded = json_decode($item->answers, true);
            $decoded['created_at'] = $item->created_at;
            return $decoded;
        });

        // Data usia untuk grafik
        $usiaCounts = $decodedSurveys->pluck('usia')->countBy()->sortKeys();

        // Ambil semua saran_telkomsel (hanya yang tidak kosong)
        $saranTelkomsel = $decodedSurveys
            ->pluck('saran_telkomsel')
            ->filter(function ($value) {
                return !empty($value);
            })
            ->values();
        
        $saranIndihome = $rawSurveys->map(function ($item) {
            $decoded = json_decode($item->answers, true);

            if (!isset($decoded['saran_kritik'])) {
                return null;
            }

            $saran = $decoded['saran_kritik'];

            if ($saran === 'Ada (isi di kolom di bawah)' || $saran === 'Lainnya') {
                return $decoded['saran_kritik_lainnya'] ?? null;
            }

            if ($saran !== 'Tidak ada') {
                return $saran;
            }

            return null;
        })->filter()->values();

        
        return view('dashboard', [
            'decodedSurveys'  => $decodedSurveys,
            'usiaCounts'      => $usiaCounts,
            'selectedType'    => $surveyType,
            'saranTelkomsel'  => $saranTelkomsel,
            'saranIndihome'   => $saranIndihome
        ]);
    }

    public function download(Request $request): StreamedResponse
    {
        $surveyType = $request->get('survey_type', 'telkomsel');
        $fileName = 'survey_' . $surveyType . '_' . now()->format('Ymd_His') . '.csv';

        $surveys = SurveyAnswer::where('survey_type', $surveyType)->get();

        $headers = [
            'Content-Type'        => 'text/csv',
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
