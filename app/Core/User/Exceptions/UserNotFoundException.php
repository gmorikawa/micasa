<?php

namespace App\Core\User\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{
    protected $message = 'User not found.';
}