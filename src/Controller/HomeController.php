<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Component\TerminalController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
    public function __construct() {
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
        return $this->render('pages/home/index.html.twig', [
            'terminal' => $terminal,
        ]);
    }
}
