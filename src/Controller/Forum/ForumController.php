<?php

declare(strict_types=1);

namespace App\Controller\Forum;

use App\Service\GetLocaleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forum")
 */
class ForumController extends AbstractController {
    private GetLocaleService $changeLocaleService;

    public function __construct(GetLocaleService $changeLocaleService) {
        $this->changeLocaleService = $changeLocaleService;
    }

    /**
     * @Route("/", name="forum_index", methods={"GET"})
     */
    public function index(): Response {
        return $this->render('pages/forum/index.html.twig', [
            'message' => 'Hello world!',
        ]);
    }

    /**
     * @Route("/new-post", name="forum_post_new", methods={"POST"})
     */
    public function ajax(): JsonResponse {
        return JsonResponse::fromJsonString('{"message": "Hello world!"}');
    }
}
