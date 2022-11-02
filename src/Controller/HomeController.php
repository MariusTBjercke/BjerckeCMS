<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Component\TerminalController;
use App\Repository\BlogPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;

class HomeController extends AbstractController {
    private Registry $workflows;

    public function __construct(Registry $workflows) {
        $this->workflows = $workflows;
    }

    /**
     * @Route("/", name="home_index")
     */
    public function __invoke(): Response {
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/home", name="homepage")
     */
    public function index(TerminalController $terminal): Response {
        return $this->render('pages/home/home.html.twig', [
            'terminal' => $terminal,
        ]);
    }
}
