<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\SpinnerProject;
use App\Models\SpinnerQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpinnerGameController extends Controller
{
    public function start(SpinnerProject $project)
    {
        abort_unless($project->status === 'published', 404);

        $activeQuestionCount = $project->questions()
            ->where('is_active', true)
            ->count();

        return view('game.start', compact('project', 'activeQuestionCount'));
    }

    public function createSession(Request $request, SpinnerProject $project)
    {
        abort_unless($project->status === 'published', 404);

        $activeQuestionCount = $project->questions()
            ->where('is_active', true)
            ->count();

        if ($activeQuestionCount === 0) {
            return back()->withErrors([
                'game' => 'Project ini belum memiliki pertanyaan aktif untuk dimainkan.',
            ]);
        }

        $validated = $request->validate([
            'player_name' => ['nullable', 'string', 'max:100'],
        ]);

        $session = GameSession::create([
            'uuid' => (string) Str::uuid(),
            'spinner_project_id' => $project->id,
            'player_name' => filled($validated['player_name'] ?? null)
                ? $validated['player_name']
                : 'Anonim',
            'total_questions' => $activeQuestionCount,
            'correct_count' => 0,
            'wrong_count' => 0,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('game.play', $session);
    }

    public function play(GameSession $session)
{
    abort_unless($session->project->status === 'published', 404);

    $spinnerQuestions = $session->project->questions()
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->get([
            'id',
            'question_text',
            'spinner_label',
        ]);

    return view('game.play', compact('session', 'spinnerQuestions'));
}

   public function nextQuestion(GameSession $session): JsonResponse
{
    abort_unless($session->project->status === 'published', 404);

    if ($session->status !== 'in_progress') {
        return response()->json([
            'completed' => true,
            'message' => 'Sesi permainan sudah selesai.',
        ]);
    }

    $validated = request()->validate([
        'question_id' => ['required', 'integer'],
    ]);

    $answeredQuestionIds = $session->answers()
        ->pluck('spinner_question_id');

    $question = $session->project->questions()
        ->where('is_active', true)
        ->whereNotIn('id', $answeredQuestionIds)
        ->whereKey($validated['question_id'])
        ->first();

    if (! $question) {
        return response()->json([
            'message' => 'Pertanyaan tidak tersedia atau sudah dijawab.',
        ], 422);
    }

    return response()->json([
        'completed' => false,
        'question' => [
            'id' => $question->id,
            'text' => $question->question_text,
        ],
        'progress' => [
            'answered' => $session->answers()->count(),
            'total' => $session->total_questions,
            'correct' => $session->correct_count,
            'wrong' => $session->wrong_count,
        ],
    ]);
}

    public function submitAnswer(Request $request, GameSession $session): JsonResponse
    {
        abort_unless($session->project->status === 'published', 404);

        if ($session->status !== 'in_progress') {
            return response()->json([
                'message' => 'Sesi permainan ini sudah selesai.',
            ], 422);
        }

        $validated = $request->validate([
            'question_id' => ['required', 'integer'],
            'selected_answer' => ['required', 'in:mitos,fakta'],
        ]);

        $question = $session->project->questions()
            ->where('is_active', true)
            ->findOrFail($validated['question_id']);

        $alreadyAnswered = $session->answers()
            ->where('spinner_question_id', $question->id)
            ->exists();

        if ($alreadyAnswered) {
            return response()->json([
                'message' => 'Pertanyaan ini sudah dijawab dalam sesi ini.',
            ], 422);
        }

        $isCorrect = $validated['selected_answer'] === $question->correct_answer;

        $questionOrder = $session->answers()->count() + 1;

        $session->answers()->create([
            'spinner_question_id' => $question->id,
            'question_snapshot' => $question->question_text,
            'selected_answer' => $validated['selected_answer'],
            'correct_answer_snapshot' => $question->correct_answer,
            'is_correct' => $isCorrect,
            'question_order' => $questionOrder,
            'answered_at' => now(),
        ]);

        $session->increment($isCorrect ? 'correct_count' : 'wrong_count');
        $session->refresh();

        $answeredCount = $session->answers()->count();
        $isCompleted = $answeredCount >= $session->total_questions;

        if ($isCompleted) {
            $session->update([
                'status' => 'completed',
                'finished_at' => now(),
            ]);
        }

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $question->correct_answer,
            'explanation' => $question->explanation,
            'completed' => $isCompleted,
            'progress' => [
                'answered' => $answeredCount,
                'total' => $session->total_questions,
                'correct' => $session->correct_count,
                'wrong' => $session->wrong_count,
            ],
        ]);
    }

    public function result(GameSession $session)
    {
        abort_unless($session->project->status === 'published', 404);

        $session->loadCount('answers');

        $totalQuestions = $session->total_questions;
        $answeredCount = $session->answers_count;

        $scorePercentage = $totalQuestions > 0
            ? (int) round(($session->correct_count / $totalQuestions) * 100)
            : 0;

        return view('game.result', compact(
            'session',
            'totalQuestions',
            'answeredCount',
            'scorePercentage'
        ));
    }
}