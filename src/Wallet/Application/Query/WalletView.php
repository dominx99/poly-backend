<?php declare (strict_types = 1);

namespace Wallet\Wallet\Application\Query;

class WalletView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function email(): string
    {
        return $this->email;
    }

    /**
     * @param array $wallet
     * @return self
     */
    public static function createFromDatabase(array $wallet): self
    {
        return new static(
            $wallet['id'],
            $wallet['name']
        );
    }
}
