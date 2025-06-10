<?php

namespace App\Mail;

use App\Models\TascaProposal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TascaProposalMail extends Mailable
{
    use Queueable, SerializesModels;

    public TascaProposal $tascaProposal;

    public function __construct( TascaProposal $tascaProposal )
    {
        $this->tascaProposal = $tascaProposal;
        $this->onQueue('emails');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tasca Proposal',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tasca-proposal',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
