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
     * @var array
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var \App\Entity\Models\Event
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Models\Event")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * })
     */
    private $event;

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
     * @return array
     */
    public function getDescription(): array
    {
        return $this->description;
    }

    /**
     * @param array $description
     * @return Subevent
     */
    public function setDescription(array $description): Subevent
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \App\Entity\Models\Event
     */
    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @param \App\Entity\Models\Event $event
     * @return Subevent
     */
    public function setEvent(Event $event): Subevent
    {
        $this->event = $event;
        return $this;
    }


}
