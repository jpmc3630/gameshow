<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function getQuestion() 
    {
        $question = Question::inRandomOrder()->first();

        return response()->json($question);
    }
}
