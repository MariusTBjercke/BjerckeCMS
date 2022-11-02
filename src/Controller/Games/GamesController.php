<?php

namespace App\Controller\Games;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/games")
 */
class GamesController extends AbstractController {
    private MessageBusInterface $bus;
    private RequestStack $requestStack;
    private LoggerInterface $logger;


    public function __construct(MessageBusInterface $bus, RequestStack $requestStack, LoggerInterface $logger) {
        $this->bus = $bus;
        $this->requestStack = $requestStack;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="games_index", methods={"GET"})
     */
    public function index(): Response {
        return $this->render('pages/games/games.html.twig', []);
    }
}
