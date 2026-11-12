<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MilestoneController extends Controller
{
    /**
     * Store a new milestone within a project.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('manageMilestones', $project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'due_date' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $milestone = $project->milestones()->create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'],
            'due_date' => $validated['due_date'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? ($project->milestones()->count() + 1)),
            'created_by' => $user->id,
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', "Milestone '{$milestone->title}' berhasil ditambahkan ke project.");
    }

    /**
     * Update an existing milestone.
     */
    public function update(Request $request, Milestone $milestone): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('manageMilestones', $milestone->project);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        $milestone->update($validated);

        return redirect()->route('projects.show', $milestone->project)
            ->with('success', "Milestone '{$milestone->title}' berhasil diperbarui.");
    }

    /**
     * Delete a milestone.
     */
    public function destroy(Milestone $milestone): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();
        $this->authorize('manageMilestones', $milestone->project);

        $project = $milestone->project;
        $title = $milestone->title;

        $milestone->delete();

        return redirect()->route('projects.show', $project)
            ->with('success', "Milestone '{$title}' berhasil dihapus.");
    }
}
