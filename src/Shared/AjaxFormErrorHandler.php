<?php

namespace App\Shared;

use Symfony\Component\Form\FormInterface;

/**
 * AjaxFormErrorHandler class.
 */
class AjaxFormErrorHandler {
    private FormInterface $form;
    private array $errors;

    /**
     * Constructor.
     *
     * @param FormInterface $form The form.
     */
    public function __construct(FormInterface $form) {
        $this->form = $form;
        $this->errors = [];
    }

    /**
     * Loop through the form errors and add them to the errors array.
     *
     * @return array The errors array.
     */
    public function getErrors(): array {
        foreach ($this->form->all() as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $error = [
                        'input' => $child->getName(),
                        'message' => $error->getMessage(),
                    ];

                    $this->errors[] = $error;
                }
            }
        }

        return $this->errors;
    }
}
