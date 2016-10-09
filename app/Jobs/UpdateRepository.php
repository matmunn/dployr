<?php

namespace App\Jobs;

use App\Jobs\FileDeployer;
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

            // dd($environment);
            try
            {
                $this->repository->changeBranch($environment->branch);
                $currentCommit = $this->environment->current_commit;
                $git->git("pull origin ".$environment->branch);
                $changedFiles = [];
                if(!empty($currentCommit))
                {
                    if($currentCommit !== $this->repository->currentCommit())
                    {
                        $files = explode("\n", $this->repository->changedFiles());
                        $files = array_filter($files);
                        // var_dump($files);
                        foreach($files as $file)
                        {
                            preg_match('/([ACDMR]{1})\s(.+)/', $file, $matches);
                            $changedFiles[] = [$matches[1], $matches[2]];
                        }
                    }
                }
                else
                {
                    $files = array_slice(scandir('/path/to/directory/'), 2);
                    foreach($files as $file)
                    {
                        $changedFiles[] = ["A", $file];
                    }
                }

                if(!empty($changedFiles))
                {
                    dispatch(new FileDeployer($environment, $changedFiles, $environment->branch));
                }
            }
            catch(\Exception $e)
            {
                continue;
            }
        }

        event(new UpdateComplete($this->repository));
    }
}
