<?php

namespace App\Mail;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegistrationInvite extends Mailable
{
    use Queueable, SerializesModels;

    public $link;
    public $group;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $link, Group $group)
    {
        //
        $this->link = $link;
        $this->group = $group;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.invite')
                    ->subject('Dployr Registration Invitation');
    }
}
