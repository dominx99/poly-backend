<?php declare(strict_types = 1);

namespace App\User\Application\Query;

class UserView
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $worldId;

    /**
     * @param string $id
     * @param string $email
     * @param string|null $password
     * @param string|null $worldId
     */
    public function __construct(string $id, string $email, string $password = null, string $worldId = null)
    {
        $this->id       = $id;
        $this->email    = $email;
        $this->password = $password;
        $this->worldId  = $worldId;
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
     * @return string
     */
    public function password(): string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function worldId()
    {
        return $this->worldId;
    }

    /**
     * @param  array $user
     * @return self
     */
    public static function createFromDatabase(array $user): self
    {
        return new static(
            $user['id'],
            $user['email'],
            $user['password'],
            $user['world_id']
        );
    }
}
