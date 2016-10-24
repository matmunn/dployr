<?php

namespace App\Jobs;

use App\Jobs\FtpDeployer;
use App\Jobs\SftpDeployer;
use App\Models\Environment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FileDeployer implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $files;
    protected $environment;
    protected $branch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Environment $env, array $files, string $branch)
    {
        //
        $this->files = $files;
        $this->environment = $env;
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
        // if($this->environment->deploy_mode === $this->environment::DEPLOY_MODE_AUTO)
        // {
        foreach ($this->environment->servers as $server) {
            if ($server->type == "ftp") {
                dispatch(new FtpDeployer($server, $this->files, $this->branch));
            }
            if ($server->type == "sftp") {
                dispatch(new SftpDeployer($server, $this->files, $this->branch));
            }
        }
        // }
    }
}
