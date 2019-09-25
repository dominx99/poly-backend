<?php declare(strict_types = 1);

namespace App\Map\Domain\Map\Unit;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class DisplayName
{
    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", nullable=false, unique=true)
     */
    private $displayName;

    /**
     * @param string $displayName
     */
    public function __construct(string $displayName)
    {
        $this->displayName = $displayName;
    }
}
