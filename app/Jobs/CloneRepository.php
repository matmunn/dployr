<?php

namespace App\Jobs;

use Exception;
use App\Models\Repository;
use App\Events\CloneComplete;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CloneRepository implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $repository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Repository $repository)
    {
        //
        $this->repository = $repository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        
        Storage::makeDirectory('repos/'.$this->repository->id);

        $git = $this->repository->getGitInstance();
        $git->clone($this->repository->url, $this->repository->repositoryPath);

        event(new CloneComplete($this->repository));
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        $this->repository->status = 16;
        $this->repository->save();
    }
}
