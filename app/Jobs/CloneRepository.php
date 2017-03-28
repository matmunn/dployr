<?php

namespace App\Jobs;

use Exception;
use App\Models\Repository;
use App\Services\GitService;
use GitWrapper\GitException;
use App\Events\CloneComplete;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CloneRepository implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $git;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GitService $git)
    {
        //
        $this->git = $git;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $repo = $this->git->getRepository();
        $repo->last_action = "clone";
        $repo->status = $repo::STATUS_INITIALISING;
        $repo->save();

        Storage::makeDirectory('repos/'.$repo->id);

        try {
            $git = $this->git->getGitInstance(false);
            $git->clone($repo->url, $repo->repositoryPath);
            event(new CloneComplete($repo));
        } catch (GitException $e) {
            Log::error($e);
            $repo->status = $repo::STATUS_ERROR;
            $repo->save();
        }
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::error($exception);
        $repo = $this->git->getRepository();
        $repo->status = $repo::STATUS_ERROR;
        $repo->save();
    }
}
