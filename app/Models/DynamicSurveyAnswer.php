<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicSurveyAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'survey_type',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array',
    ];
}

