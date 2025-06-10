<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New User',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
