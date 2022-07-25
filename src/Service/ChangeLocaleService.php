<?php

namespace App\Service;

use Symfony\Component\Translation\LocaleSwitcher;

class ChangeLocaleService {
    private LocaleSwitcher $localeSwitcher;

    public function __construct(LocaleSwitcher $localeSwitcher) {
        $this->localeSwitcher = $localeSwitcher;
    }

    public function changeLocale(string $locale): void {
        $this->localeSwitcher->setLocale($locale);
    }
}
