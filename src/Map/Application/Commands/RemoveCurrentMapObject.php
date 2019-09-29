<?php declare(strict_types=1);

namespace App\Map\Application\Commands;

use App\System\Contracts\Command;

final class RemoveCurrentMapObject implements Command
{
    /** @var string */
    private $fieldId;

    /**
     * @param string $fieldId
     */
    public function __construct(string $fieldId)
    {
        $this->fieldId = $fieldId;
    }

    /**
     * @return string
     */
    public function fieldId(): string
    {
        return $this->fieldId;
    }
}
