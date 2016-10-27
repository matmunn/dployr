<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Server;
use Touki\FTP\Model\File;
use Illuminate\Support\Str;
use App\Services\GitService;
use Illuminate\Bus\Queueable;
use Touki\FTP\Model\Directory;
use App\Events\DeploymentComplete;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FtpDeployer implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $server;
    protected $files;
    protected $branch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Server $server, array $files, string $branch)
    {
        //
        $this->server = $server;
        $this->files = $files;
        $this->branch = $branch;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if (!$ftp = $this->server->returnConnection()) {
            // return false;
            event(new DeploymentFailed($this->server, $this->server::ERR_CONN_FAILED));
            return false;
        }


        $factory = $ftp[0];
        $ftp = $factory->build($ftp[1]);

        $repo = $this->server->environment->repository;
        $repo->last_action = 'deploy';
        $repo->status = $repo::STATUS_DEPLOYING;
        $repo->save();

        $thisDeployment = $this->server->deployments()->create(['started_at' => Carbon::now()]);
        // dd($this->files);
        $git = new GitService($repo);

        $git->changeBranch($this->branch);

        $path = $repo->repositoryPath;
        if (!Str::endsWith($path, "/")) {
            $path .= '/';
        }
        $serverPath = $this->server->server_path;
        if (!Str::endsWith($serverPath, "/")) {
            $serverPath .= '/';
        }

        $factory->getWrapper()->chdir($serverPath);

        foreach ($this->files as $file) {
            $parts = explode("/", $file[1]);
            // Log::info("Uploading ".$file[1]);
            $filename = array_pop($parts);
            $remotePath = implode("/", $parts);

            try {
                $ftp->create(new Directory($serverPath.$remotePath));
            } catch (\Exception $e) {
                // Log::error($e);
            }

            if (in_array($file[0], ["A", "M"])) {
                // Log::info('Attempting path '.$path.$file[1]);
                try {
                    $ftp->upload(new File($serverPath.$file[1]), $path.$file[1]);
                } catch (\Exception $e) {
                    // Log::error($e);
                }
            }

            if ($file[0] == "D") {
                try {
                    $ftp->delete(new File($serverPath.$file[1]));
                } catch (\Exception $e) {
                    // Log::error($e);
                }
            }
        }

        $this->server->environment->current_commit = $git->currentCommit();
        $this->server->environment->save();

        $repo->status = $repo::STATUS_IDLE;
        $repo->save();

        $thisDeployment->commit_hash = $this->server->environment->current_commit;
        $thisDeployment->commit_message = $git->getCommitMessage($this->server->environment->current_commit);
        $thisDeployment->finished_at = Carbon::now();
        $thisDeployment->file_count = count($this->files);
        $thisDeployment->save();

        event(new DeploymentComplete($this->server));
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
        $repo = $this->server->environment->repository;
        $repo->status = $repo::STATUS_ERROR;
        $repo->save();
    }
}
