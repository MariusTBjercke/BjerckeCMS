<?php

namespace App\Exception;

use Exception;

class WrongPasswordException extends Exception {
    protected $message = 'Password is incorrect.';
}
