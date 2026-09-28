<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('pages.projects', [
            'frontendProjects' => Project::publishedFrontendProjects(),
        ]);
    }

    public function show(string $slug): View
    {
        $frontendProjects = Project::publishedFrontendProjects();
        $frontendProject = collect($frontendProjects)->firstWhere('id', $slug);

        abort_unless($frontendProject, 404);

        $project = Project::query()
            ->published()
            ->where('slug', $slug)
            ->first()
            ?->detailPayload()
            ?? config("sif_projects.projects.$slug");

        return view('pages.project-detail', compact('slug', 'project', 'frontendProjects'));
    }
}
