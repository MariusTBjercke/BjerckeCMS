<?php

use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $framework) {
    $framework->secret('%env(APP_SECRET)%');
    $framework->httpMethodOverride(false);

    $framework
        ->session()
        ->handlerId(null)
        ->cookieSecure('auto')
        ->cookieSamesite('lax')
        ->storageFactoryId('session.storage.factory.native');

    $framework->phpErrors()->log(true);

    // Path for Macbook Pro
    $framework->ide('phpstorm://open?file=%%f&line=%%l&/var/www/html/>/Users/marius/public_html/devel/BjerckeCMS/');
};
