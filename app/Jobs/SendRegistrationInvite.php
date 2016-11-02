<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Group;
use App\Models\Invite;
use Illuminate\Bus\Queueable;
use App\Mail\RegistrationInvite;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendRegistrationInvite implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $group;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, Group $group)
    {
        //
        $this->email = $email;
        $this->group = $group;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $invite = new Invite;
        $invite->email = $this->email;
        $invite->generateSecretKey();
        $invite->expires_at = Carbon::now()->addDay(2);
        $this->group->invites()->save($invite);

        $link = url("/register/$invite->hash");

        Mail::to($this->email)
            ->send(new RegistrationInvite($link, $this->group));
    }
}
