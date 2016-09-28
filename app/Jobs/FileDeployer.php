<?php

namespace App\Jobs;

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

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Environment $env, $files)
    {
        //
        $this->files = $files;
        $this->environment = $env;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        foreach($this->environment->servers as $server)
        {
            
        }
    }
}
