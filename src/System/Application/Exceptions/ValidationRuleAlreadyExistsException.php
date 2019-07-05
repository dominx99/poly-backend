<?php declare(strict_types = 1);

namespace App\System\Application\Exceptions;

class ValidationRuleAlreadyExistsException extends \Exception
{
    protected $message;

    public function __construct(string $rule)
    {
        $this->message = "Validation rule {$rule} already exists";
    }
}
