<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class NewMapRequest {
    /**
     * Map name.
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=255)
     * @var string
     */
    public string $name;

    /**
     * Map description.
     *
     * @Assert\NotBlank()
     * @Assert\Length(min=1)
     * @var string
     */
    public string $description;

    /**
     * Map image.
     *
     * @Assert\NotBlank()
     * @var string
     */
    public string $image;
}
