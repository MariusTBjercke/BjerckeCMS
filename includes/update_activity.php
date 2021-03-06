<?php

use Bjercke\Entity\User;
use Bjercke\Site;
use Bjercke\DatabaseManager;

require_once(__DIR__ . '/../vendor/autoload.php');

$em = DatabaseManager::getInstance()->getEntityManager();

$currentUser = Site::getInstance()->getCurrentUser();

/**
 * Update the activity of the current user in the database.
 */
if ($currentUser->getLoggedIn()) {
    $user = $em->find(User::class, Site::getInstance()->getCurrentUser());

    $user->setLastActivity(time());
    $em->persist($user);
    $em->flush();
}