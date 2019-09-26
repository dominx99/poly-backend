<?php declare(strict_types=1);

namespace App\Map\Application\Commands;

use App\System\Contracts\Command;

final class PutMapObject implements Command
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
     * @var string
     */
    private $unitId;

    /**
     * @var string
     */
    private $type;

    /**
     * @param array $request
     */
    public function __construct(array $request)
    {
        $this->mapId   = $request['map_id'];
        $this->fieldId = $request['field_id'];
        $this->userId  = $request['user_id'];
        $this->unitId  = $request['unit_id'];
        $this->type    = $request['type'];
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

    /**
     * @return string
     */
    public function unitId(): string
    {
        return $this->unitId;
    }

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }
}
