<?php declare(strict_types=1);

namespace App\System\Application\Exceptions;

final class ValidationException extends \Exception
{
    /** @var array */
    private $messages;

    /**
     * @param array $messages
     */
    private function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @param array $messages
     * @return self
     */
    public static function withMessages(array $messages): self
    {
        return new static($messages);
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return $this->messages;
    }
}
