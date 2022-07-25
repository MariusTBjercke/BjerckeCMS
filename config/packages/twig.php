<?php

use Symfony\Config\TwigConfig;

return static function (TwigConfig $twig) {
    $twig->defaultPath('%kernel.project_dir%/templates');
};
