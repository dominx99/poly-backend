<?php declare(strict_types = 1);

namespace App\System\Infrastructure;

class DecodedJWT
{
    /**
     * @var array
     */
    private $payload;

    /**
     * @param array $payload
     * @return void
     */
    public function set(array $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getKey(string $key)
    {
        if (! array_key_exists($key, $this->payload)) {
            throw new \Exception("The {$key} key does not exists on jwt token payload.");
        }

        return $this->payload[$key];
    }
}
