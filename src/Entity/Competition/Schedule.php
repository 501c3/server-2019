<?php

namespace App\Entity\Competition;

use Doctrine\ORM\Mapping as ORM;

/**
 * Schedule
 *
 * @ORM\Table(name="schedule", indexes={@ORM\Index(name="fk_schedule_competition1_idx", columns={"competition_id"}), @ORM\Index(name="idx_heat_round", columns={"heat", "round"})})
 * @ORM\Entity
 */
class Schedule
{
    /**
     * @var int
     *
     * @ORM\Column(name="heat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $heat;

    /**
     * @var int
     *
     * @ORM\Column(name="round", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $round;

    /**
     * @var int
     *
     * @ORM\Column(name="subevent_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $subeventId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="when", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $when;

    /**
     * @var int|null
     *
     * @ORM\Column(name="dances", type="smallint", nullable=true)
     */
    private $dances;

    /**
     * @var int|null
     *
     * @ORM\Column(name="groups", type="smallint", nullable=true)
     */
    private $groups;

    /**
     * @var int|null
     *
     * @ORM\Column(name="players", type="smallint", nullable=true)
     */
    private $players;

    /**
     * @var int
     *
     * @ORM\Column(name="session_id", type="integer", nullable=false)
     */
    private $sessionId;

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


}
