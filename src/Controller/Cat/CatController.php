<?php

declare(strict_types=1);

namespace App\Controller\Cat;

use App\Service\Cat\CatFactsRetriever;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cat")
 */
class CatController extends AbstractController {
    private array $facts;

    public function __construct(CatFactsRetriever $retriever) {
        $this->facts = $retriever->retrieveFacts();
    }

    /**
     * @Route("/", name="cat")
     */
    public function index(): Response {
        return $this->render('pages/cat/index.html.twig', [
            'facts' => $this->facts,
        ]);
    }
}
