<?php

namespace App\Entity\Competition;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event
 *
 * @ORM\Table(name="event", indexes={@ORM\Index(name="fk_event_competition1_idx", columns={"competition_id"})})
 * @ORM\Entity
 */
class Event
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var json
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var \App\Entity\Competition\Competition
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Competition\Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     * })
     */
    private $competition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Competition\Team", inversedBy="eventCompetition")
     * @ORM\JoinTable(name="placement",
     *   joinColumns={
     *     @ORM\JoinColumn(name="event_competition_id", referencedColumnName="competition_id"),
     *     @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     *   }
     * )
     */
    private $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
