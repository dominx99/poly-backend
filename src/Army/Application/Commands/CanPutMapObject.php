<?php declare(strict_types=1);

namespace App\Army\Application\Commands;

use App\System\Contracts\Command;

final class CanPutMapObject implements Command
{
    /**
     * @var string
     */
    private $mapId;

    /**
     * @var string
     */
    private $fieldId;

    /**
     * @var string
     */
    private $userId;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->mapId = $request['map_id'];
        $this->fieldId = $request['field_id'];
        $this->userId = $request['user_id'];
    }

    /**
     * @return string
     */
    public function mapId(): string
    {
        return $this->mapId;
    }

    /**
     * @return string
     */
    public function fieldId(): string
    {
        return $this->fieldId;
    }

    /**
     * @return string
     */
    public function userId(): string
    {
        return $this->userId;
    }
}
