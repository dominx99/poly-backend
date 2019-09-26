<?php declare(strict_types=1);

namespace App\System\Infrastructure\Exceptions;

class UnexpectedException extends \Exception
{
    protected $message = 'Unexpected exception.';
}
