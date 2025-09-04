<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $line;

    public function __construct(string $line)
    {
        $this->line = $line;
    }

    public function build()
    {
        return $this->subject('Notification')
            ->view('emails.notification');
    }
}
