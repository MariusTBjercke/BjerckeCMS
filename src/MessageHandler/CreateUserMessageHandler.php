<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Event\UserCreatedEvent;
use App\Exception\EmailAlreadyExistsException;
use App\Exception\UsernameAlreadyExistsException;
use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class CreateUserMessageHandler implements MessageHandlerInterface {
    private UserRepository $userRepository;
    private EventDispatcherInterface $dispatcher;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        UserRepository $repository,
        EventDispatcherInterface $dispatcher,
        UserPasswordHasherInterface $passwordHasher,
    ) {
        $this->userRepository = $repository;
        $this->dispatcher = $dispatcher;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Create a new user.
     *
     * @param CreateUserMessage $message
     * @return string|bool Return a string to indicate a failure or true to indicate success.
     */
    public function __invoke(CreateUserMessage $message): string|bool {
        $user = (new User())
            ->setUsername($message->getUsername())
            ->setFirstname($message->getFirstname())
            ->setLastname($message->getLastname())
            ->setEmail($message->getEmail())
            ->setLoggedIn($message->isLoggedIn())
            ->setIsAdmin($message->isAdmin());

        $plainTextPassword = $message->getPassword();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainTextPassword);

        $user->setPassword($hashedPassword);

        $this->userRepository->add($user, true);

        // Fire event
        $this->dispatcher->dispatch(new UserCreatedEvent($user));

        return true;
    }
}
