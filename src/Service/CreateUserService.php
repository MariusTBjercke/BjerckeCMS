<?php

namespace App\Service;

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

    public function createUser(CreateUserRequest $request): mixed {
        $message = new CreateUserMessage(
            $request->username,
            $request->firstname,
            $request->lastname,
            $request->password,
            $request->email,
            $request->loggedIn,
            $request->isAdmin,
        );
        $envelope = $this->bus->dispatch($message);

        return $envelope->last(HandledStamp::class)->getResult();
    }
}
