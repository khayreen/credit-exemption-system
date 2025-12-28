<?php

namespace App\Mail;

use App\Models\EquivalencyList;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EquivalencyListSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public EquivalencyList $list;
    public string $submitterName;
    public string $reviewUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(EquivalencyList $list, string $submitterName, string $reviewUrl)
    {
        $this->list = $list;
        $this->submitterName = $submitterName;
        $this->reviewUrl = $reviewUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $category = $this->list->isInternal() ? 'CS110' : $this->list->source_institution;

        return new Envelope(
            subject: "[Action Required] New Equivalency List Submitted: {$category} → {$this->list->program_code}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.equivalency_list.submitted',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
