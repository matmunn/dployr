<?php

namespace App\Notifications;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use App\Notifications\Channels\SlackImageUrlChannel;

class DeploySuccess extends Notification implements ShouldQueue
{
    use Queueable;

    protected $server;
    protected $url;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Server $server)
    {
        //
        $this->server = $server;
        $this->url = action('ServerController@manage', $server);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SlackImageUrlChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $server = $this->server;
        $url = $this->url;

        return (new MailMessage)
                    ->line('Your deployment was successful.')
                    ->action('View Server Log', $url)
                    ->line(
                        $server->environment->repository->name .
                        " has been deployed to " . $server->environment->name
                    )->line(
                        'The commit message was "' .
                        $server->deployments->last()->commit_message.'"'
                    )->line(
                        'Deployment of branch \'' . $server->environment->branch .
                        '\' is set to '. ($server->environment->deploy_mode
                        == $server->environment::DEPLOY_MODE_AUTO ?
                        "automatic" : "manual") . ' deployment.'
                    );
    }


    /**
     * Get the slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $server = $this->server;
        $url = $this->url;

        return (new SlackMessage)
            ->success()
            ->from("dployr", config('dployr.site.slack_icon_url'))
            ->content(
                $server->environment->repository->name .
                " has been deployed to " . $server->environment->name
            )->attachment(
                function ($attachment) use ($server, $url) {
                    $attachment->title('Deployed to ' . $server->name, $url)
                        ->fields(
                            [
                                'Commit Message' => $server->deployments->last()->commit_message,
                                'Branch' => $server->environment->branch,
                                'Status' => "Success",
                                'Deployment Trigger' => $server->environment->deploy_mode == $server->environment::DEPLOY_MODE_AUTO ? "Automatic" : "Manual"
                                
                            ]
                        );
                }
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
