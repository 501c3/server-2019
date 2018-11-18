<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Placement
 *
 * @ORM\Table(name="placement", indexes={@ORM\Index(name="fk_placement_official1_idx", columns={"official_id"}), @ORM\Index(name="fk_placement_subevent1_idx", columns={"subevent_id"}), @ORM\Index(name="fk_placement_team1_idx", columns={"team_id"}), @ORM\Index(name="IDX_48DB750E7B39D312", columns={"competition_id"})})
 * @ORM\Entity
 */
class Placement
{
    /**
     * @var int
     *
     * @ORM\Column(name="dance_id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $danceId;

    /**
     * @var bool
     *
     * @ORM\Column(name="placement", type="boolean", nullable=false)
     */
    private $placement;

    /**
     * @var \App\Entity\Score\Competition
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Score\Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     * })
     */
    private $competition;

    /**
     * @var \App\Entity\Score\Official
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Score\Official")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="official_id", referencedColumnName="id")
     * })
     */
    private $official;

    /**
     * @var \App\Entity\Score\Subevent
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Score\Subevent")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="subevent_id", referencedColumnName="id")
     * })
     */
    private $subevent;

    /**
     * @var \App\Entity\Score\Team
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Score\Team")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * })
     */
    private $team;


}
