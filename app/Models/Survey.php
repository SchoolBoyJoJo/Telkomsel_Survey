<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'survey_type', 'description', 'hash'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function dynamicAnswers()
    {
        return $this->hasMany(DynamicSurveyAnswer::class, 'survey_id');
    }
}
