<?php
// config/services.php
namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Entity\User;
use App\EventListener\WelcomeEmail;

return static function (ContainerConfigurator $configurator) {
    // Compile the entire container into a single file
    $configurator->parameters()->set('container.dumper.inline_factories', true);

    $configurator->parameters()->set('images_directory', '/uploads/images');

    $configurator->parameters()->set('relative_images_directory', '%kernel.project_dir%/public%images_directory%');

    $services = $configurator->services();

    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('App\\', '../src/*');
};
