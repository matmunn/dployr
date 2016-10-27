<?php

namespace App\Jobs;

use App\Models\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteRepository implements ShouldQueue
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
        Storage::delete($this->repository->privateKeyPath(false));

        Storage::deleteDirectory('repos/'.$this->repository->id);

        foreach ($this->repository->environments as $environment) {
            foreach ($environment->servers as $server) {
                $server->deployments()->delete();
            }
        }
        $this->repository->environments()->delete();
        $this->repository->delete();
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
    }
}
