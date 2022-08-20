<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Message\CreateUserMessage;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController {
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus) {
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/login", name="api_login")
     */
    public function login(): Response {
        $user = $this->getUser();

        if ($user === null) {
            return new JsonResponse(
                [
                    'success' => false,
                    'message' => 'User not found.',
                ],
                Response::HTTP_UNAUTHORIZED,
            );
        }

        return new JsonResponse([
            'user' => $user->getUserIdentifier(),
            'token' => null,
        ]);
    }

    /**
     * @Route("/add", name="api_add")
     */
    public function addUser(): JsonResponse {
        $username = 'JEPPE';
        $password = 'test';
        $email = 'dex@dex.com';

        $envelope = $this->messageBus->dispatch(new CreateUserMessage($username, $password, $email, false, false));

        $handledStamp = $envelope->last(HandledStamp::class);
        $result = $handledStamp->getResult();

        return new JsonResponse(['status' => 'ok']);
    }

    /**
     * @Route("/users", name="api_users")
     */
    public function getUsers(UserRepository $repository): JsonResponse {
        $users = $repository->findAll();

        $response = [
            'result' => 'OK',
            'users' => $users,
        ];

        return new JsonResponse($response);
    }
}
