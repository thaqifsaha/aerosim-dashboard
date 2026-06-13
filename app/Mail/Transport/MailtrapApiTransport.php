<?php

namespace App\Mail\Transport;

use Mailtrap\MailtrapClient;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class MailtrapApiTransport extends AbstractTransport
{
    public function __construct(
        private readonly string $apiToken,
        private readonly int $inboxId,
        private readonly bool $sandbox = true,
    ) {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        // Honour the envelope sender (Symfony Mailer may differ from message From header)
        $email->from($message->getEnvelope()->getSender());

        MailtrapClient::initSendingEmails(
            apiKey: $this->apiToken,
            isSandbox: $this->sandbox,
            inboxId: $this->sandbox ? $this->inboxId : null,
        )->send($email);
    }

    public function __toString(): string
    {
        return 'mailtrap_api';
    }
}
