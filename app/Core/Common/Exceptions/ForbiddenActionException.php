<?php

namespace App\Core\Common\Exceptions;

use Exception;

class ForbiddenActionException extends Exception
{
    protected $message = 'Forbidden action.';
}