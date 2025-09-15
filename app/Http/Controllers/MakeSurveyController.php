<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MakeSurveyController extends Controller
{
    public function create()
    {
        return view('surveys.create');
    }
}
