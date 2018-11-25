<?php

namespace App\Entity\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_event_model1_idx", columns={"model_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Models\EventRepository")
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var \App\Entity\Models\Model
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Models\Model")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     * })
     */
    private $model;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Value", inversedBy="event")
     * @ORM\JoinTable(name="event_has_value",
     *   joinColumns={
     *     @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="value_id", referencedColumnName="id")
     *   }
     * )
     */
    private $value;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Team", mappedBy="event")
     */
    private $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = new ArrayCollection();
        $this->team = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Event
     */
    public function setId(int $id): Event
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getDescription(): array
    {
        return $this->description;
    }

    /**
     * @param array $description
     * @return Event
     */
    public function setDescription(array $description): Event
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Model
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @param Model $model
     * @return Event
     */
    public function setModel(Model $model): Event
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getValue(): Collection
    {
        return $this->value;
    }

    /**
     * @param Collection $value
     * @return Event
     */
    public function setValue(Collection $value): Event
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTeam(): Collection
    {
        return $this->team;
    }

    /**
     * @param Collection $team
     * @return Event
     */
    public function setTeam(Collection $team): Event
    {
        $this->team = $team;
        return $this;
    }
}
