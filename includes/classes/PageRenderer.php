<?php

namespace Bjercke;

use Bjercke\Entity\Page;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * BjerckeCMS View Renderer
 * Class for rendering views/pages.
 **/
class PageRenderer extends ViewRenderer {

    public function __construct() {
        parent::__construct();
        $this->loader = new FilesystemLoader(__DIR__ . '/Pages/');
        $this->twig = new Environment($this->loader);
    }

    public function render(): void {
        $site = Site::getInstance();
        $pageName = $site->getPageName();

        // Store the page in last visited session
        $site->storeVisitedPage();

        // Find corresponding page
        $em = DatabaseManager::getInstance()->getEntityManager();
        $page = $em->getRepository(Page::class)->findOneBy(['name' => $pageName]);

        if ($page instanceof Page) {
            $site->setCurrentPage($page);
        }

        $currentPage = $site->getCurrentPage();
        $template = $currentPage->getTemplate();
        $classString = $currentPage->getClass();
        $requiresLogin = $currentPage->getRequiresLogin();

        $storage = new WebStorage('user');

        try {
            if ($requiresLogin && !$storage->getSessionOrCookieSet()) {
                header('Location: /');
            } else {
                $class = call_user_func("$classString::getInstance");
                echo $this->twig->render($template, ['site' => Site::getInstance(), 'tile' => $class]);
            }
        } catch (LoaderError|RuntimeError|SyntaxError $e) {
            echo "Error rendering: " . $e->getMessage();
        }
    }

}
