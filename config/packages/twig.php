<?php

use App\Service\GetLocaleService;
use Symfony\Config\TwigConfig;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (TwigConfig $twig) {
    $twig->defaultPath('%kernel.project_dir%/templates');

    $twig->global('language_code')->value(service(GetLocaleService::class));

    $twig->global('year')->value(date('Y'));
};
