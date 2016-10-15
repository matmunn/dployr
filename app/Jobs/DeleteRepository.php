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

        $this->repository->environments->servers->deployments()->delete();
        $this->repository->environments->servers()->delete();
        $this->repository->environments()->delete();
        $this->repository->delete();
    }
}
