<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['survey_id', 'text', 'type', 'options'];

    protected $casts = [
        'options' => 'array', // biar otomatis decode/encode JSON
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }
}
