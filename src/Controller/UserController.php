<?php

namespace App\Controller;

use App\Form\UserType;
use App\Request\CreateUserRequest;
use App\Service\CreateUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Translation\LocaleSwitcher;

class UserController extends AbstractController {
    public function __construct(LocaleSwitcher $localeSwitcher) {
        $this->localeSwitcher = $localeSwitcher;
    }

    /**
     * @Route("/register", name="user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CreateUserService $createUserService): Response {
        $createUserRequest = new CreateUserRequest();

        $form = $this->createForm(UserType::class, $createUserRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $result = $createUserService->createUser($createUserRequest);

            if ($result === 'username') {
                $this->addFlash('error', 'Username already exists.');
                return $this->redirectToRoute('user_new');
            }

            if ($result === 'email') {
                $this->addFlash('error', 'Email already exists.');
                return $this->redirectToRoute('user_new');
            }

            $this->addFlash('success', 'User created successfully.');
            return $this->redirectToRoute('homepage', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pages/register/new.html.twig', [
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
