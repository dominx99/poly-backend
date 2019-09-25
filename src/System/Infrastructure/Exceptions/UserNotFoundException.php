<?php declare(strict_types=1);

namespace App\System\Infrastructure\Exceptions;

class UserNotFoundException extends BusinessException
{
    protected $message = 'User not found.';
}
