<?php

declare(strict_types=1);

namespace App\Event\Listeners\UserRegistered;

use App\Event\UserRegisteredEvent;
use App\Mails\ConfirmEmail;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendConfirmMail implements EventSubscriberInterface
{
    /**
     * @var ConfirmEmail
     */
    private $mail;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        ConfirmEmail $mail,
        LoggerInterface $logger
    ) {
        $this->mail = $mail;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegisteredEvent::NAME => 'onUserRegister'
        ];
    }


    /**
     * @param UserRegisteredEvent $event
     */
    public function onUserRegister(UserRegisteredEvent $event): void
    {
        try {
            dd($event);
            $this->mail->send($event->getRegisteredUser());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage() . ' ' . $e->getTraceAsString());
        }
    }
}
/*wget https://github.com/mailhog/MailHog/releases/download/v1.0.0/MailHog_linux_amd64 &&
sudo cp MailHog_linux_amd64 /usr/local/bin/mailhog &&
sudo chmod +x /usr/local/bin/mailhog*/