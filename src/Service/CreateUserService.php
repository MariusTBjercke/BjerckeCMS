<?php

namespace App\Service;

use App\Entity\User;
use App\Message\CreateUserMessage;
use App\Request\CreateUserRequest;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ErrorDetailsStamp;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class CreateUserService {
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus) {
        $this->bus = $bus;
    }

    public function createUser(User $request): mixed {
        $message = new CreateUserMessage(
            $request->getUsername(),
            $request->getFirstname(),
            $request->getLastname(),
            $request->getPassword(),
            $request->getEmail(),
            false,
            false,
        );

        $envelope = $this->bus->dispatch($message);

        return $envelope->last(HandledStamp::class)->getResult();
    }
}
