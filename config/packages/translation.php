<?php

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework) {
    $framework->defaultLocale('en');
    $framework
        ->translator()
        ->defaultPath('%kernel.project_dir%/translations')
        ->fallbacks(['no']);

    // Only generate translation files for the locales you need (increase performance)
    $framework->enabledLocales(['en', 'no']);
};
