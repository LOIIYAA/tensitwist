<?php

namespace App\Http\Controllers;

use App\Models\SpinnerProject;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpinnerProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = $request->user()
            ->spinnerProjects()
            ->withCount('questions')
            ->latest()
            ->get();

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'theme_color' => ['required', 'string', 'max:20'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 2;

        while (SpinnerProject::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $request->user()->spinnerProjects()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'theme_color' => $validated['theme_color'],
            'slug' => $slug,
            'status' => 'draft',
        ]);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil dibuat.');
    }

    public function show(SpinnerProject $project)
    {
        $this->ensureOwner($project);

        $project->load(['questions' => function ($query) {
            $query->latest();
        }]);

        return view('projects.show', compact('project'));
    }

    public function edit(SpinnerProject $project)
    {
        $this->ensureOwner($project);

        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, SpinnerProject $project)
    {
        $this->ensureOwner($project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:1000'],
            'theme_color' => ['required', 'string', 'max:20'],
            'status' => ['required', 'in:draft,published'],
        ]);

        $project->update($validated);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Project berhasil diperbarui.');
    }

    public function destroy(SpinnerProject $project)
    {
        $this->ensureOwner($project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project berhasil dihapus.');
    }

    private function ensureOwner(SpinnerProject $project): void
    {
        abort_unless($project->user_id === auth()->id(), 403);
    }
}