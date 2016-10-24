<?php

namespace App\Jobs;

use Maknz\Slack\Client;
use App\Models\Environment;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifySlack implements ShouldQueue
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
        $env = $this->environment;

        $commit = $env->current_commit;
        $time = $env->updated_at;

        $notifiers = $env->notifierSlack;
        if ($notifiers->count() > 0) {
            foreach ($notifier as $notify) {
                $client = new Client($notify->endpoint, ['username' => 'dployr.io']);
                $client->attach([
                    'fallback' => "Commit deployed to $env->name",
                    'text' => "Commit deployed to $env->name",
                    'color' => 'good',
                    'fields' => [
                        [
                            'title' => 'Commit Hash',
                            'value' => $commit,
                        ],
                        [
                            'title' => 'Deployment Time',
                            'value' => $time,
                        ],
                        [
                            'title' => 'Servers Deployed',
                            'value' => $env->servers->count()
                        ]
                    ]
                ])->send('Deployment succesful');
            }
        }
    }
}
