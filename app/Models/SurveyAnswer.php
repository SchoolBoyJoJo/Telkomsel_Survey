<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyAnswer extends Model
{
    protected $fillable = ['answers', 'survey_type'];
    protected $casts = [
        'answers' => 'array',
    ];
}