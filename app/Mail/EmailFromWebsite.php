<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailFromWebsite extends Mailable
{
    use Queueable, SerializesModels;

    protected $name, $email, $password, $access, $permissions, $emailAction;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $name, $email, $password, $permissions, $access, $emailAction)
    {

        
        $this->subject = $subject;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->permissions = $permissions;
        $this->access = $access;
        $this->emailAction = $emailAction;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email)
               ->subject($this->subject)
               ->view('emails.new-user')
               ->with([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                    'permissions' => $this->permissions,
                    'access' => $this->access,
                    'emailAction' => $this->emailAction
               ]);
    }
}
