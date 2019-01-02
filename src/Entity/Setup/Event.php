<?php

namespace App\Entity\Setup;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event", indexes={@ORM\Index(name="fk_event_model1_idx", columns={"model_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Setup\EventRepository")
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
     * @ORM\Column(name="`describe`", type="json", nullable=false)
     */
    private $describe;

    /**
     * @var \App\Entity\Setup\Model
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Setup\Model")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     * })
     */
    private $model;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\TeamClass", inversedBy="event")
     * @ORM\JoinTable(name="event_has_team_class",
     *   joinColumns={
     *     @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="team_class_id", referencedColumnName="id")
     *   }
     * )
     */
    private $teamClass;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\Value", inversedBy="event")
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
     * Constructor
     */
    public function __construct()
    {
        $this->teamClass = new ArrayCollection();
        $this->value = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    /**
     * @return array
     */
    public function getDescribe(): array
    {
        return $this->describe;
    }

    /**
     * @param array $describe
     * @return Event
     */
    public function setDescribe(array $describe): Event
    {
        $this->describe = $describe;
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
    public function getTeamClass(): Collection
    {
        return $this->teamClass;
    }


    /**
     * @return Collection
     */
    public function getValue(): Collection
    {
        return $this->value;
    }

}
