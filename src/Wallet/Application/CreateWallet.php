<?php declare (strict_types = 1);

namespace Wallet\Wallet\Application;

use Wallet\System\Contracts\Command;

class CreateWallet implements Command
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var string
     */
    private $ownerId;

    /**
     * @param string $name
     */
    public function __construct(string $ownerId, string $name)
    {
        $this->name = $name;
        $this->setSlug($name);
        $this->ownerId = $ownerId;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function slug(): string
    {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function ownerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @param string $name
     * @return void
     */
    protected function setSlug(string $name): void
    {
        $this->slug = str_replace(' ', '-', trim(strtolower($name)));
    }
}
