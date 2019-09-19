<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = $this->user;
        $subject = $data->isValid == false ? 'Validation Account' : 'Activation Email';

        return $this->from(env('MAIL_USERNAME'), env("APP_NAME") . ' | SISKA - Sistem Informasi Karier')
            ->subject('SISKA Account: ' . $subject)
            ->view('emails.auth.activation', compact('data'));
    }
}
