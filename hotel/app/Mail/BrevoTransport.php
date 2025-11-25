<?php

namespace App\Mail;

use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mime\RawMessage;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Configuration;
use SendinBlue\Client\Model\SendSmtpEmail;

class BrevoTransport extends AbstractTransport
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $raw = $message->getOriginalMessage();

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->apiKey);
        $apiInstance = new TransactionalEmailsApi(null, $config);

        $email = new SendSmtpEmail();
        
        $from = config('mail.from');

$email->setSender([
    'email' => $from['address'] ?? 'no-reply@pasaelextrainn.com',
    'name'  => $from['name'] ?? 'Pasa el Extra Inn',
]);


        $email->setSubject($raw->getSubject());

// Sacamos cuerpo en HTML y texto
$htmlBody = $raw->getHtmlBody();
$textBody = $raw->getTextBody();

// Si no hay HTML pero sí texto (caso Mail::raw), usamos el texto como HTML simple
if (empty($htmlBody) && !empty($textBody)) {
    $htmlBody = nl2br($textBody);
}

if (!empty($htmlBody)) {
    $email->setHtmlContent($htmlBody);
}

if (!empty($textBody)) {
    $email->setTextContent($textBody);
}


        $to = [];
        foreach ($raw->getTo() as $recipient) {
            $to[] = ['email' => $recipient->getAddress()];
        }

        $email->setTo($to);

        $apiInstance->sendTransacEmail($email);
    }

    public function __toString(): string
    {
        return 'brevo-api';
    }
}

