<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $table = 'survey_answers';

    protected $fillable = [
        'nomorHp',
        'frekuensi',
        'pengeluaran',
        'alasan',
        'promo',
        'kesulitan',
        'jaringan',
        'info_promo',
        'pindah',
        'pindah_lainnya',
        'kembali',
    ];
}