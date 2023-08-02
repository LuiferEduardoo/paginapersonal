<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GithubService;
use App\Models\Projects;
use App\Models\ProjectHistory;


class SyncGitHubProjects extends Command
{
    protected $signature = 'repositories:check';

    protected $description = 'Check repository changes and update history';

    public function handle()
    {
        $projects = Projects::all(); // Obtener todos los proyectos
        $githubService = new GithubService();

        foreach ($projects as $project) {
            $information = $project->history()->latest('created_at')->first();
            $repositoryUrl = $information->url_repository;
            $date = $information->pushed_at;

            $githubService->updateInformation($project, $repositoryUrl, $date);
        }

        $this->info('Repositories checked and history updated successfully.');
    }
}