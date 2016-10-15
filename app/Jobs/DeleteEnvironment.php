<?php

namespace App\Jobs;

use App\Models\Environment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteEnvironment implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $environment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Environment $environment)
    {
        //
        $this->environment = $environment;
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
            $server->deployments()->delete();
        }
        $this->environment->servers()->delete();
        $this->environment->delete();
    }
}
