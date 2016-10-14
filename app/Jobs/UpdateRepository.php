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
        $remoteBranches = $this->git->getBranches('remote');
        $repo = $this->git->getRepository();
        
        $repo->status = $repo::STATUS_UPDATING;
        $repo->last_action = "update";
        $repo->save();

        foreach($repo->environments as $environment)
        {
            if(!in_array($environment->branch, $remoteBranches))
            {
                return response()->json("Your environment is configured incorrectly.", 400);
            }

            // dd($environment);
            try
            {
                $this->git->changeBranch($environment->branch);
                $currentCommit = $this->environment->current_commit;
                $this->git->git("pull origin ".$environment->branch);
                $changedFiles = [];
                if(!empty($currentCommit))
                {
                    if($currentCommit !== $this->git->currentCommit())
                    {
                        $files = explode("\n", $this->git->changedFiles());
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
                    $files = array_slice(scandir($repo->repositoryPath), 2);
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

        event(new UpdateComplete($repo));
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
