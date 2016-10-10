<?php

namespace App\Jobs;

use Exception;
use App\Models\Repository;
use App\Services\GitService;
use GitWrapper\GitException;
use App\Events\CloneComplete;
use Illuminate\Bus\Queueable;
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
        //
        
        $repo = $this->git->getRepository();

        Storage::makeDirectory('repos/'.$repo->id);

        try
        {
            $this->git->clone($repo->url, $repo->repositoryPath);
            event(new CloneComplete($repo));
        }
        catch(GitException $e)
        {
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
        $repo = $this->git->getRepository();
        $repo->status = $repo::STATUS_ERROR;
        $repo->save();
    }
}
