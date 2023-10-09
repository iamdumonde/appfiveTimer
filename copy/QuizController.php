<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;
use Inertia\Inertia;
use Inertia\Response;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
        // return response('quiz Pages');
        return Inertia::render('Quiz/QuizForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'duration' => 'required'
        ]);

        // Envoi du quizz dans la base de données
        $request->user()->quizs()->create($validated);

        //La redirection après l'envoi
        return redirect(route('quizs.index'));

    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        //
    }
}
