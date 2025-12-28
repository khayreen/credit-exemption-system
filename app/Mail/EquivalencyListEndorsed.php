<?php

namespace App\Mail;

use App\Models\EquivalencyList;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EquivalencyListEndorsed extends Mailable
{
    use Queueable, SerializesModels;

    public EquivalencyList $list;
    public string $endorserName;
    public ?string $endorsementNotes;
    public string $viewUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(EquivalencyList $list, string $endorserName, ?string $endorsementNotes, string $viewUrl)
    {
        $this->list = $list;
        $this->endorserName = $endorserName;
        $this->endorsementNotes = $endorsementNotes;
        $this->viewUrl = $viewUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $category = $this->list->isInternal() ? 'CS110' : $this->list->source_institution;

        return new Envelope(
            subject: "[Success] Your Equivalency List Has Been Published: {$category} → {$this->list->program_code}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.equivalency_list.endorsed',
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
