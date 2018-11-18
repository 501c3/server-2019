<?php

namespace App\Entity\Models;

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
     * @var json
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Event", inversedBy="team")
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Person", inversedBy="team")
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
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
        $this->person = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return json
     */
    public function getDescription(): json
    {
        return $this->description;
    }

    /**
     * @param json $description
     * @return Team
     */
    public function setDescription(json $description): Team
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvent(): \Doctrine\Common\Collections\Collection
    {
        return $this->event;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $event
     * @return Team
     */
    public function setEvent(\Doctrine\Common\Collections\Collection $event): Team
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPerson(): \Doctrine\Common\Collections\Collection
    {
        return $this->person;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $person
     * @return Team
     */
    public function setPerson(\Doctrine\Common\Collections\Collection $person): Team
    {
        $this->person = $person;
        return $this;
    }

}
