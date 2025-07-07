<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyAnswer;
use Illuminate\Support\Facades\Validator;

class SurveyController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'nomorHp'       => ['required', 'regex:/^08\d{8,12}$/'],
            'frekuensi'     => 'required',
            'pengeluaran'   => 'required',
            'alasan'        => 'required',
            'promo'         => 'required',
            'kesulitan'     => 'required',
            'jaringan'      => 'required',
            'info_promo'    => 'required',
            'pindah'        => 'required',
            'kembali'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan jawaban
        $survey = SurveyAnswer::create([
            'nomorHp'        => $data['nomorHp'],
            'frekuensi'      => $data['frekuensi'],
            'pengeluaran'    => $data['pengeluaran'],
            'alasan'         => $data['alasan'],
            'promo'          => $data['promo'],
            'kesulitan'      => $data['kesulitan'],
            'jaringan'       => $data['jaringan'],
            'info_promo'     => $data['info_promo'],
            'pindah'         => $data['pindah'],
            'pindah_lainnya' => $data['pindah_lainnya'] ?? null,
            'kembali'        => $data['kembali'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'id'      => $survey->id,
        ]);
    }
}