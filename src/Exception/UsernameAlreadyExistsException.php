<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;

class UsernameAlreadyExistsException extends Exception {
    protected $message = 'Username already exists.';
}
