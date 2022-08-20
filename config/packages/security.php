<?php

use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    $security->passwordHasher(PasswordAuthenticatedUserInterface::class, 'auto');

    $devFirewall = $security->firewall('dev');

    $devFirewall
        ->formLogin()
        ->loginPath('user_login')
        ->checkPath('user_login');

    $devFirewall->jsonLogin()->checkPath('api_login');

    $devFirewall->logout()->path('user_logout');

    $mainFirewall = $security->firewall('main');

    $mainFirewall
        ->formLogin()
        ->loginPath('user_login')
        ->checkPath('user_login');

    $mainFirewall->jsonLogin()->checkPath('api_login');

    $mainFirewall->logout()->path('user_logout');

    $security
        ->provider('app_user_provider')
        ->entity()
        ->class(User::class)
        ->property('username');

    $security->passwordHasher(PasswordAuthenticatedUserInterface::class)->algorithm('auto');
};
