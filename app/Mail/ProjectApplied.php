<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectApplied extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $post_user;
    public $project;
    
    public function __construct($user, $post_user, $project)
    {
        $this->user = $user;
        $this->post_user = $post_user;
        $this->project = $project;
  }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.projectApplied')
                ->with([
                    'user'    => $this->user,
                    'post_user' => $this->post_user,
                    'project' => $this->project,
                ])
                ->subject('案件への応募通知');
    }
}
