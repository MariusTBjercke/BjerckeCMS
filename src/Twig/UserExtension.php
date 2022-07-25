<?php

namespace App\Twig;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension {
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array {
        return [new TwigFunction('getUser', [$this, 'getUser'])];
    }

    public function getUser(): ?User {
        $session = $this->requestStack->getSession();
        $userSession = $session->get('user');

        if ($userSession) {
            return $userSession;
        }

        return null;
    }
}
