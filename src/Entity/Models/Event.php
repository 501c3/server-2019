<?php

namespace App\Entity\Models;

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
     * @var json
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var \Entity\Models\Model
     *
     * @ORM\ManyToOne(targetEntity="Entity\Models\Model")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     * })
     */
    private $model;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Value", inversedBy="event")
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Team", mappedBy="event")
     */
    private $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = new \Doctrine\Common\Collections\ArrayCollection();
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return json
     */
    public function getDescription(): json
    {
        return $this->description;
    }

    /**
     * @param json $description
     * @return Event
     */
    public function setDescription(json $description): Event
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValue(): \Doctrine\Common\Collections\Collection
    {
        return $this->value;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $value
     * @return Event
     */
    public function setValue(\Doctrine\Common\Collections\Collection $value): Event
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeam(): \Doctrine\Common\Collections\Collection
    {
        return $this->team;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $team
     * @return Event
     */
    public function setTeam(\Doctrine\Common\Collections\Collection $team): Event
    {
        $this->team = $team;
        return $this;
    }


}
