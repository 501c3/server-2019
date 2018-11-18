<?php

namespace App\Entity\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subevent
 *
 * @ORM\Table(name="subevent", indexes={@ORM\Index(name="fk_subevent_event1_idx", columns={"event_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Models\SubeventRepository")
 */
class Subevent
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
     * @var \Entity\Models\Event
     *
     * @ORM\ManyToOne(targetEntity="Entity\Models\Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private $event;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Competition", mappedBy="subevent")
     */
    private $competition;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->competition = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Subevent
     */
    public function setId(int $id): Subevent
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
     * @return Subevent
     */
    public function setDescription(json $description): Subevent
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param Event $event
     * @return Subevent
     */
    public function setEvent(Event $event): Subevent
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCompetition(): \Doctrine\Common\Collections\Collection
    {
        return $this->competition;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $competition
     * @return Subevent
     */
    public function setCompetition(\Doctrine\Common\Collections\Collection $competition): Subevent
    {
        $this->competition = $competition;
        return $this;
    }


}
