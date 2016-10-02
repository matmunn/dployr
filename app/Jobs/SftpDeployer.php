<?php

namespace App\Jobs;

use App\Models\Server;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SftpDeployer implements ShouldQueue
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
        // dd($this->server->returnConnection());
        if(!$ftp = $this->server->returnConnection())
        {
            return false;
        }

        $repo = $this->server->environment->repository;
        $repo->status = 8;
        $repo->save();

        // dd($this->files);

        $repo->changeBranch($this->branch);

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

        $ftp->changeDirectory($serverPath);
        foreach($this->files as $file)
        {
            $parts = explode("/", $file[1]);
            $filename = array_pop($parts);
            $remotePath = implode("/", $parts);

            // dd([$serverPath.$file[1], $path.$file[1]]);
            try
            {
                $ftp->createDirectory($serverPath.$remotePath);
            }
            catch(\Exception $e)
            {
                
            }

            if(in_array($file[0], ["A", "M"]))
            {
                $ftp->uploadFile($serverPath.$file[1], $path.$file[1]);

            }

            if($file[0] == "D")
            {
                try
                {
                    $ftp->deleteFile($serverPath.$file[1]);
                }
                catch(\Exception $e)
                {
                    
                }
            }
        }

        $repo->status = 1;
        $repo->save();
    }
}
