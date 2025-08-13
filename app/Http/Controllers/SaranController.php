<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SaranController extends Controller
{
    public function summaryAjax(Request $request)
    {
        $sarans = $request->input('sarans', []);

        if (empty($sarans)) {
            return response()->json([
                'summary' => '<p class="text-red-500">Tidak ada saran untuk diringkas.</p>'
            ]);
        }

        // Gabungkan semua saran
        $text = implode("\n", $sarans);

        $apiKey = env('GEMINI_API_KEY');
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent', [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "Dari saran dan keluhan yang diberikan oleh pelanggan, berikan kesimpulan supaya kedepannya saya bisa menjadi lebih baik:\n{$text}"]
                    ]
                ]
            ]
        ]);

        if ($response->successful()) {
            $rawSummary = $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? 'Ringkasan tidak tersedia.';
            $summary = Str::markdown($rawSummary);
        } else {
            $summary = '<p class="text-red-500">Gagal mengambil ringkasan dari API.</p>';
        }

        return response()->json(['summary' => $summary]);
    }
}
