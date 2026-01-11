<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SyllabusRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $timeout = 60;
    public int $tries = 3;

    public $lecturerName;
    public $studentName;
    public $resourcePersonName;
    public $diplomaCourseCode;
    public $diplomaCourseName;
    public $diplomaCreditHours;
    public $diplomaInstitution;
    public $diplomaProgram;
    public $submissionUrl;
    public $requestNotes;
    public $tokenExpiresAt;

    /**
     * Create a new message instance.
     */
    public function __construct($lecturerName, $studentName, $resourcePersonName, $diplomaCourseCode, $diplomaCourseName, $diplomaCreditHours, $diplomaInstitution, $diplomaProgram, $submissionUrl, $requestNotes = null, $tokenExpiresAt = null)
    {
        $this->lecturerName = $lecturerName;
        $this->studentName = $studentName;
        $this->resourcePersonName = $resourcePersonName;
        $this->diplomaCourseCode = $diplomaCourseCode;
        $this->diplomaCourseName = $diplomaCourseName;
        $this->diplomaCreditHours = $diplomaCreditHours;
        $this->diplomaInstitution = $diplomaInstitution;
        $this->diplomaProgram = $diplomaProgram;
        $this->submissionUrl = $submissionUrl;
        $this->requestNotes = $requestNotes;
        $this->tokenExpiresAt = $tokenExpiresAt;
        \$this->onQueue('emails');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Course Syllabus Request - UiTM Credit Exemption System',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.syllabus_request',
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
