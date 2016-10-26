<?php

namespace App\Jobs;

use App\Jobs\FileDeployer;
use App\Models\Repository;
use App\Services\GitService;
use Illuminate\Bus\Queueable;
use App\Events\UpdateComplete;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRepository implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $git;
    protected $deployEnvironment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(GitService $git, int $deployEnvironment = 0)
    {
        //
        $this->git = $git;
        $this->deployEnvironment = $deployEnvironment;
    }

    protected function dirScan($folder)
    {
        $foundFiles = [];
        $files = array_slice(scandir($folder), 2);

        foreach ($files as $file) {
            if (is_dir($folder.'/'.$file) && $file != '.git') {
                $foundFiles = array_merge($foundFiles, $this->dirScan($folder.'/'.$file));
            }

            if (is_file($folder.'/'.$file)) {
                $foundFiles[] = $folder.'/'.$file;
            }
        }

        return $foundFiles;
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

        foreach ($repo->environments as $environment) {
            if (!in_array($environment->branch, $remoteBranches)) {
                return response()->json("Your environment is configured incorrectly.", 400);
            }

            try {
                $this->git->changeBranch($environment->branch);
                $currentCommit = $environment->current_commit;
                $this->git->getGitInstance()->pull("origin", $environment->branch);
                $changedFiles = [];
                if (!empty($currentCommit)) {
                    if ($currentCommit !== $this->git->currentCommit()) {
                        $files = explode(
                            "\n",
                            $this->git->changedFiles(
                                'HEAD',
                                $environment->current_commit
                            )
                        );
                        $files = array_filter($files);
                        // var_dump($files);
                        foreach ($files as $file) {
                            preg_match('/([ACDMR]{1})\s(.+)/', $file, $matches);
                            $changedFiles[] = [$matches[1], $matches[2]];
                        }
                    }
                } else {
                    $files = $this->dirScan($repo->repositoryPath);
                    // Log::info(json_encode($files));
                    foreach ($files as $file) {
                        if (is_file($file)) {
                            $file = str_replace($repo->repositoryPath.'/', '', $file);
                            $changedFiles[] = ["A", $file];
                        }
                    }
                }

                if (!empty($changedFiles) && ($this->deployEnvironment === $environment->id || $environment->deploy_mode === $environment::DEPLOY_MODE_AUTO)) {
                    dispatch(new FileDeployer($environment, $changedFiles, $environment->branch));
                }
            } catch (\Exception $e) {
                // dd($e);
                Log::error($e);
                $repo->status = $repo::STATUS_ERROR;
                $repo->save();
                continue;
            }
        }

        event(new UpdateComplete($repo));
    }

    /**
     * The job failed to process.
     *
     * @param Exception $exception
     *
     * @return void
     */
    public function failed(Exception $exception)
    {
        $repo = $this->git->getRepository();
        $repo->status = $repo::STATUS_ERROR;
        $repo->save();
    }
}
