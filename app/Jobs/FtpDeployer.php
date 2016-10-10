<?php

namespace App\Jobs;

use App\Models\Server;
use Touki\FTP\Model\File;
use Illuminate\Support\Str;
use App\Services\GitService;
use Illuminate\Bus\Queueable;
use Touki\FTP\Model\Directory;
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
        // dd($this->files);
        if(!$ftp = $this->server->returnConnection())
        {
            return false;
        }

        $factory = $ftp[0];
        $ftp = $factory->build($ftp[1]);

        $repo = $this->server->environment->repository;
        $repo->status = $repo::STATUS_DEPLOYING;
        $repo->save();

        // dd($this->files);
        $git = new GitService($repo);

        $git->changeBranch($this->branch);

        $path = $repo->repositoryPath;
        if(!Str::endsWith($path, "/"))
        {
            $path .= '/';
        }
        $serverPath = $this->server->server_path;
        if(!Str::endsWith($serverPath, "/"))
        {
            $serverPath .= '/';
        }

        $factory->getWrapper()->chdir($serverPath);
        foreach($this->files as $file)
        {
            Log::info("Uploading ".$file);
            $parts = explode("/", $file[1]);
            $filename = array_pop($parts);
            $remotePath = implode("/", $parts);

            try
            {
                $ftp->create(new Directory($serverPath.$remotePath));
            }
            catch(\Exception $e)
            {
                
            }

            if(in_array($file[0], ["A", "M"]))
            {
                $ftp->upload(new File($serverPath.$file[1]), $path.$file[1]);

            }

            if($file[0] == "D")
            {
                try
                {
                    $ftp->delete(new File($serverPath.$file[1]));
                }
                catch(\Exception $e)
                {
                    
                }
            }
        }

        $this->server->environment->current_commit = $repo->currentCommit();
        $this->server->environment->save();

        $repo->status = $repo::STATUS_IDLE;
        $repo->save();
    }
}
