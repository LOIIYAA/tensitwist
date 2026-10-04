<?php

namespace App\Http\Controllers;

use App\Models\SpinnerProject;
use App\Models\SpinnerQuestion;
use Illuminate\Http\Request;

class SpinnerQuestionController extends Controller
{
    public function index(SpinnerProject $project)
    {
        $this->ensureOwner($project);

        $questions = $project->questions()->get();

        return view('questions.index', compact('project', 'questions'));
    }

    public function create(SpinnerProject $project)
    {
        $this->ensureOwner($project);

        return view('questions.create', compact('project'));
    }

    public function store(Request $request, SpinnerProject $project)
    {
        $this->ensureOwner($project);

        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:1000'],
            'spinner_label' => ['nullable', 'string', 'max:50'],
            'correct_answer' => ['required', 'in:mitos,fakta'],
            'explanation' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $nextOrder = $project->questions()->max('sort_order') + 1;

        $project->questions()->create([
            'question_text' => $validated['question_text'],
            'spinner_label' => $validated['spinner_label'] ?? null,
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'sort_order' => $nextOrder,
        ]);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }

    public function edit(SpinnerProject $project, SpinnerQuestion $question)
    {
        $this->ensureOwner($project);
        $this->ensureQuestionBelongsToProject($project, $question);

        return view('questions.edit', compact('project', 'question'));
    }

    public function update(
        Request $request,
        SpinnerProject $project,
        SpinnerQuestion $question
    ) {
        $this->ensureOwner($project);
        $this->ensureQuestionBelongsToProject($project, $question);

        $validated = $request->validate([
            'question_text' => ['required', 'string', 'max:1000'],
            'spinner_label' => ['nullable', 'string', 'max:50'],
            'correct_answer' => ['required', 'in:mitos,fakta'],
            'explanation' => ['nullable', 'string', 'max:2000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'spinner_label' => $validated['spinner_label'] ?? null,
            'correct_answer' => $validated['correct_answer'],
            'explanation' => $validated['explanation'] ?? null,
            'is_active' => $request->boolean('is_active', false),
        ]);

        return redirect()
            ->route('projects.questions.index', $project)
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }

    public function destroy(SpinnerProject $project, SpinnerQuestion $question)
    {
        $this->ensureOwner($project);
        $this->ensureQuestionBelongsToProject($project, $question);

        $question->delete();

        return redirect()
            ->route('projects.questions.index', $project)
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    private function ensureOwner(SpinnerProject $project): void
    {
        abort_unless($project->user_id === auth()->id(), 403);
    }

    private function ensureQuestionBelongsToProject(
        SpinnerProject $project,
        SpinnerQuestion $question
    ): void {
        abort_unless(
            $question->spinner_project_id === $project->id,
            404
        );
    }
}