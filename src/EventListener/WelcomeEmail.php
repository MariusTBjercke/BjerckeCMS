<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class WelcomeEmail {
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer) {
        $this->mailer = $mailer;
    }

    public function postPersist(LifecycleEventArgs $args): void {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        // Send email to user
        //        $email = (new Email())
        //            ->from(new Address('kontakt@bjerckemedia.no', 'Bjercke Media'))
        //            ->to($entity->getEmail())
        //            ->subject('Velkommen til Bjercke Media!')
        //            ->text('Dette er en velkomstmelding.')
        //            ->html('<p>Velkommen til denne siden!</p>');

        // Uncomment below to send email

        //        $this->mailer->send($email);
    }
}
