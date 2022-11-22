<?php

use App\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
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

    $security->roleHierarchy('ROLE_ADMIN', ['ROLE_USER']);

    // Block access to the profile page and all its sub-pages.
    $security
        ->accessControl()
        ->path('^/profile')
        ->roles('ROLE_USER');
};
