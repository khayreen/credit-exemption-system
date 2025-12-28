<?php

namespace App\Mail;

use App\Models\EquivalencyList;
use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewEquivalencyListPublished extends Mailable
{
    use Queueable, SerializesModels;

    public EquivalencyList $list;
    public ?Student $student;

    /**
     * Create a new message instance.
     */
    public function __construct(EquivalencyList $list, ?Student $student = null)
    {
        $this->list = $list;
        $this->student = $student;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Course Equivalency List Available - ' . $this->list->program_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $category = $this->list->isInternal() ? 'internal' : 'external';
        $source = $this->list->source_institution;

        return new Content(
            markdown: 'emails.equivalency.published-student',
            with: [
                'list' => $this->list,
                'student' => $this->student,
                'viewUrl' => route('student.course_equivalencies.show', [
                    'category' => $category,
                    'source' => $source,
                    'program' => $this->list->program_code
                ]),
                'applyUrl' => route('student.application.create'),
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
