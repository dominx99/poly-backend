<?php declare(strict_types=1);

namespace App\User\Application\Commands;

use App\System\Contracts\Command;

final class UserGainField implements Command
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $fieldId;

    /**
     * @param string $userId
     * @param string $fieldId
     */
    public function __construct(string $userId, string $fieldId)
    {
        $this->userId  = $userId;
        $this->fieldId = $fieldId;
    }

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function fieldId(): string
    {
        return $this->fieldId;
    }
}
