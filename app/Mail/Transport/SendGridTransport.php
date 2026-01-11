<?php

namespace App\Mail\Transport;

use SendGrid;
use SendGrid\Mail\Mail;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;
use Symfony\Component\Mime\RawMessage;

class SendGridTransport extends AbstractTransport
{
    protected $sendgrid;

    public function __construct(SendGrid $sendgrid)
    {
        parent::__construct();
        $this->sendgrid = $sendgrid;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $sendGridMail = new Mail();

        // Set from address
        $from = $email->getFrom()[0];
        $sendGridMail->setFrom($from->getAddress(), $from->getName() ?? '');

        // Set to addresses
        foreach ($email->getTo() as $to) {
            $sendGridMail->addTo($to->getAddress(), $to->getName() ?? '');
        }

        // Set CC addresses
        foreach ($email->getCc() as $cc) {
            $sendGridMail->addCc($cc->getAddress(), $cc->getName() ?? '');
        }

        // Set BCC addresses
        foreach ($email->getBcc() as $bcc) {
            $sendGridMail->addBcc($bcc->getAddress(), $bcc->getName() ?? '');
        }

        // Set subject
        $sendGridMail->setSubject($email->getSubject() ?? '');

        // Set content
        if ($email->getHtmlBody()) {
            $sendGridMail->addContent('text/html', $email->getHtmlBody());
        }

        if ($email->getTextBody()) {
            $sendGridMail->addContent('text/plain', $email->getTextBody());
        }

        // Set reply-to if exists
        if ($replyTo = $email->getReplyTo()) {
            foreach ($replyTo as $reply) {
                $sendGridMail->setReplyTo($reply->getAddress(), $reply->getName() ?? '');
                break; // SendGrid only supports one reply-to
            }
        }

        // Send email
        try {
            $response = $this->sendgrid->send($sendGridMail);

            if ($response->statusCode() >= 400) {
                throw new \Exception('SendGrid API error: ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('SendGrid error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function __toString(): string
    {
        return 'sendgrid';
    }
}
