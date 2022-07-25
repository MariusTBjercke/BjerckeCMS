<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class NewBlogPostRequest {
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max=255)
     * @var string
     */
    public string $title;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=10, max=255)
     * @var string
     */
    public string $content;
}
