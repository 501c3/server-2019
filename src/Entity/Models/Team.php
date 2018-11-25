<?php

namespace App\Entity\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity(repositoryClass="App\Repository\Models\TeamRepository")
 */
class Team
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
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Event", inversedBy="team")
     * @ORM\JoinTable(name="team_has_event",
     *   joinColumns={
     *     @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *   }
     * )
     */
    private $event;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Person", inversedBy="team")
     * @ORM\JoinTable(name="team_has_person",
     *   joinColumns={
     *     @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     *   }
     * )
     */
    private $person;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->person = new ArrayCollection();
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
     * @return Team
     */
    public function setId(int $id): Team
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
     * @return Team
     */
    public function setDescription(array $description): Team
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    /**
     * @param Collection $event
     * @return Team
     */
    public function setEvent(Collection $event): Team
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPerson(): Collection
    {
        return $this->person;
    }

    /**
     * @param Collection $person
     * @return Team
     */
    public function setPerson(Collection $person): Team
    {
        $this->person = $person;
        return $this;
    }
}
