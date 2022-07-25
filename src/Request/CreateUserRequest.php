<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserRequest {
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=1, max=255)
     * @var string
     */
    public string $username;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    public string $firstname;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    public string $lastname;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    public string $password;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    public string $email;

    /**
     * @var bool
     */
    public bool $loggedIn = false;

    /**
     * @var bool
     */
    public bool $isAdmin = false;
}
