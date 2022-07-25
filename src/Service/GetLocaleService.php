<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class GetLocaleService {
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
    }

    public function getLocale(): string {
        return $this->requestStack->getCurrentRequest()->getLocale();
    }

    public function getOppositeLocale(): string {
        return $this->requestStack->getCurrentRequest()->getLocale() === 'en' ? 'no' : 'en';
    }
}
