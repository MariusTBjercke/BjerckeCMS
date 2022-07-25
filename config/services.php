<?php
// config/services.php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Entity\User;
use App\EventListener\WelcomeEmail;

return static function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', '../src/*');
};
