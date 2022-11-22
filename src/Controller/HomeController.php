<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Component\TerminalController;
use App\Repository\BlogPostRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Workflow\Registry;

class HomeController extends AbstractController {
    private UserRepository $userRepository;

    /**
     * Constructor.
     *
     * @param UserRepository $userRepository User repository.
     */
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * Invoke.
     *
     * @Route("/", name="home_index")
     */
    public function __invoke(): Response {
        return $this->redirectToRoute('homepage');
    }

    /**
     * Index function.
     *
     * @Route("/home", name="homepage")
     * @param TerminalController $terminal Terminal controller.
     * @return Response
     */
    public function index(TerminalController $terminal): Response {
        $admin = $this->userRepository->findByUsernameOrThrow('marius');

        return $this->render('pages/home/home.html.twig', [
            'terminal' => $terminal,
            'profile_image' => $admin->getProfileImage(),
        ]);
    }
}
