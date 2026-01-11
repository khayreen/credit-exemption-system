<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SemesterReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $timeout = 60;
    public int $tries = 3;

    public string $resourcePersonName;
    public array $assignedPrograms;
    public string $upcomingSemester;
    public array $programsMissingLists;
    public string $createUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        string $resourcePersonName,
        array $assignedPrograms,
        string $upcomingSemester,
        array $programsMissingLists,
        string $createUrl
    ) {
        $this->resourcePersonName = $resourcePersonName;
        $this->assignedPrograms = $assignedPrograms;
        $this->upcomingSemester = $upcomingSemester;
        $this->programsMissingLists = $programsMissingLists;
        $this->createUrl = $createUrl;
        \$this->onQueue('emails');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Reminder] Update Equivalency Lists for {$this->upcomingSemester}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.equivalency_list.semester_reminder',
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
