<?php

namespace App\Notifications;

use App\Models\Server;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;

class DeployFail extends Notification
{
    use Queueable;

    protected $server;

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
        return ['mail'];
        // return ['mail', 'slack'];
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
                    ->line('Your deployment failed.')
                    ->action('View Server Log', $url)
                    ->line($server->environment->repository->name . " failed to deploy to " . $server->environment->name)
                    ->line('The commit message was "' . $server->deployments->last()->commit_message.'"')
                    ->line('Deployment of branch \'' . $server->environment->branch .  '\' is set to '. ($server->environment->deploy_mode == $server->environment::DEPLOY_MODE_AUTO ? "automatic" : "manual") . ' deployment.' );
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
                    ->error()
                    ->from("dployr", config('dployr.site.slack_icon_url'))
                    ->content($server->environment->repository->name . " failed while deploying to " . $server->environment->name)
                    ->attachment(function($attachment) use ($server, $url)
                        {

                            $attachment->title('Failed to deploy to ' . $server->name, $url)
                                       ->fields([
                                            'Commit Message' => $server->deployments->last()->commit_message,
                                            'Branch' => $server->environment->branch,
                                            'Status' => "Failure",
                                            'Deployment Trigger' => $server->environment->deploy_mode == $server->environment::DEPLOY_MODE_AUTO ? "Automatic" : "Manual"
                                        ]);
                        });
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
