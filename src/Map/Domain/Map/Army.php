<?php declare(strict_types = 1);

namespace App\Map\Domain\Map;

use \Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="armies")
 */
class Army extends Field
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     */
    private $id;

    /**
     * @var \App\World\Domain\World
     *
     * @ORM\OneToOne(targetEntity="\App\World\Domain\World")
     */
    private $world;

    /**
         shit
     * @var \Doctrine\Common\Collections\ArrayCollection|Field
     *
     * @ORM\OneToMany(targetEntity="Field", mappedBy="map", cascade={"persist"})
     */
    private $fields;

    public function __construct(string $id, Status $status)
    {
        $this->id     = $id;
        $this->status = $status;
        $this->users  = new ArrayCollection();
        $this->fields = new ArrayCollection();
    }

    /**
     * @param \App\User\Domain\User $user
     * @return void
     */
    public function addUser(User $user): void
    {
        if ($this->users->contains($user)) {
            return;
        }

        $this->users->add($user);
        $user->addMap($this);
    }

    public function addField(Field $field): void
    {
        if ($this->fields->contains($field) {
            return;
        }

        $this->fields->add($field);
        $field->addMap($this);
    }
}
