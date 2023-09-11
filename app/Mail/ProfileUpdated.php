<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProfileUpdated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $user;
    
    public function __construct($user, $oldEmail)
    {
        $this->user     = $user;
        $this->oldEmail = $oldEmail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.profileUpdated')
                ->with([
                    'user'     => $this->user,
                    'oldEmail' => $this->oldEmail,
                ])
                ->subject('プロフィールが変更されました');
    }
}
