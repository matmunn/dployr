<?php

namespace App\Jobs;

use App\Models\Repository;
use Illuminate\Bus\Queueable;
use App\Events\UpdateComplete;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRepository implements ShouldQueue
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
        $remoteBranches = $this->repository->getBranches('remote');
        $git = $this->repository->getGitInstance();

        foreach($this->repository->environments as $environment)
        {
            if(!in_array($environment->branch, $remoteBranches))
            {
                return response()->json("Your environment is configured incorrectly.", 400);
            }

            $this->repository->changeBranch($environment->branch);
            $files = explode("\n", $git->git('diff --name-status'));
            $files = array_filter($files);
            // var_dump($files);
            $changedFiles = [];
            foreach($files as $file)
            {
                preg_match('/([ACDMR]{1})\s(.+)/', $file, $matches);
                $changedFiles[] = [$matches[1], $matches[2]];
            }
        }

        event(new UpdateComplete($this->repository));
    }
}
