<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\UserType;
use App\Service\CreateUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController {
    public function __construct() {
    }

    /**
     * @Route("/register", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CreateUserService $createUserService): Response {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $createUserService->createUser($user);

            $this->addFlash('success', 'User created successfully.');
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/register/index.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/login", name="user_login", methods={"GET", "POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->renderForm('pages/login/index.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="user_logout", methods={"GET"})
     */
    public function logout(): void {
    }

    /**
     * Change language/locale and go back to the previous page.
     *
     * @Route("/lang/{locale}", name="change_locale", methods={"GET"})
     */
    public function changeLocale(Request $request, string $locale): Response {
        $request->getSession()->set('_locale', $locale);

        $refUrl = $request->headers->get('referer');

        return $this->redirect($refUrl);
    }
}
