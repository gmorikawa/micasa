<?php

namespace App\Core\User\Exceptions;

use Exception;

class DuplicatedEmailException extends Exception
{
    protected $message = 'The email address is already in use.';
}