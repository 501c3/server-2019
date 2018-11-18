<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Result
 *
 * @ORM\Table(name="result")
 * @ORM\Entity
 */
class Result
{
    /**
     * @var int
     *
     * @ORM\Column(name="competition_id", type="integer", nullable=false)
     */
    private $competitionId;

    /**
     * @var int
     *
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     */
    private $eventId;

    /**
     * @var bool
     *
     * @ORM\Column(name="model_id", type="boolean", nullable=false)
     */
    private $modelId;

    /**
     * @var bool
     *
     * @ORM\Column(name="placement", type="boolean", nullable=false)
     */
    private $placement;

    /**
     * @var string
     *
     * @ORM\Column(name="round", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $round;

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
