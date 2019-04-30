<?php declare (strict_types = 1);

namespace Wallet\Wallet\Domain\Wallet;

use \Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Slug
{
    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", nullable=false, unique=true)
     */
    private $slug;

    /**
     * @param string $slug
     */
    public function __construct(string $slug)
    {
        $this->slug = $slug;
    }
}
