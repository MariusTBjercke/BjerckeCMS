<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class UserCreatedSubscriber implements EventSubscriberInterface {
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array {
        return [
            UserCreatedEvent::class => 'onUserCreated',
        ];
    }

    public function onUserCreated(UserCreatedEvent $event): void {
        $user = $event->getUser();

        $email = (new Email())
            ->from(new Address('kontakt@bjerckemedia.no', 'Bjercke Media'))
            ->to($user->getEmail())
            ->subject('Velkommen til Bjercke Media!')
            ->text('Dette er en velkomstmelding.')
            ->html('<p>Velkommen til denne siden! Hey listen!</p>');

        $this->mailer->send($email);
    }
}
