<?php

namespace App\Mail;

use App\Models\EquivalencyList;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EquivalencyListUnderReview extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $timeout = 60;
    public int $tries = 3;

    public EquivalencyList $list;
    public string $reviewerName;
    public string $statusUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(EquivalencyList $list, string $reviewerName, string $statusUrl)
    {
        $this->list = $list;
        $this->reviewerName = $reviewerName;
        $this->statusUrl = $statusUrl;
        \$this->onQueue('emails');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $category = $this->list->isInternal() ? 'CS110' : $this->list->source_institution;

        return new Envelope(
            subject: "[Update] Your Equivalency List is Under Review: {$category} → {$this->list->program_code}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.equivalency_list.under_review',
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
