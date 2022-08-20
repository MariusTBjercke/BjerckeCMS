<?php

declare(strict_types=1);

namespace App\Controller\Component;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class AbstractComponent extends AbstractController {
    public string $template;
    public string $date;

    public function renderComponent(): string {
        return $this->renderView($this->template, [
            'component' => $this,
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template): void {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getDate(): string {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate(string $date): void {
        $this->date = $date;
    }
}
