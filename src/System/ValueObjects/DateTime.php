<?php declare(strict_types=1);

namespace App\System\ValueObjects;

class DateTime extends \DateTime
{
    public static function now(): self
    {
        return new static('now');
    }

    public function subSeconds(int $seconds): self
    {
        $this->sub(new \DateInterval("PT{$seconds}S"));

        return $this;
    }
}
