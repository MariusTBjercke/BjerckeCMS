<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class NewBlogPostRequest {
    /**
     * Title.
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     * @var string
     */
    public string $title;

    /**
     * Content.
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     * @var string
     */
    public string $content;
}
