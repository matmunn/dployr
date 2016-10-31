<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyOfRegistration implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(HttpClient $http)
    {
        //
        $message = [
            'json' => [
                'channel' => config('dployr.site.registrations_channel'),
                'username' => 'dployr',
                'icon_url' => config('dployr.site.slack_icon_url'),
                'text' => "Email address '". $this->user->email . "' just registered.",
            ]
        ];

        $http->post(config('dployr.site.notifications_webhook'), $message);
    }
}
