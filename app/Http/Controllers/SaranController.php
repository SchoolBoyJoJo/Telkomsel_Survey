<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;

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

        $text = implode("\n- ", $sarans);

        try {
            $response = OpenAI::chat()->create([
                'model' => 'gpt-5-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Kamu adalah asisten profesional yang bertugas meringkas saran pelanggan dengan jelas dan positif dalam point-point penting (misal 1, 2, 3, dst).'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Buat ringkasan dalam point-point penting (misal 1, 2, 3, dst) dan positif dari daftar saran berikut :\n\n" 
                                    . implode("\n- ", array_slice($sarans, 0, 100))
                    ],
                ],
                'max_completion_tokens' => 1000, 
            ]);

            // 🪵 Tambahkan log isi full response
            \Log::info('OpenAI summary response:', [
                'raw' => $response->toArray(),
            ]);

            $rawSummary = $response->choices[0]->message->content ?? null;

            if (!$rawSummary) {
                $summary = '<p class="text-red-500">AI tidak mengembalikan teks ringkasan.</p>';
            } else {
                $summary = Str::markdown($rawSummary);
            }

        } catch (\Exception $e) {
            \Log::error('Gagal summary OpenAI:', [
                'error' => $e->getMessage(),
            ]);

            $summary = '<p class="text-red-500">Gagal mengambil ringkasan dari API OpenAI GPT-5-mini.</p>';
        }


        return response()->json(['summary' => $summary]);
    }
}
