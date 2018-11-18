<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subevent
 *
 * @ORM\Table(name="subevent", indexes={@ORM\Index(name="fk_subevent_competition1_idx", columns={"competition_id"})})
 * @ORM\Entity
 */
class Subevent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
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
     * @var int
     *
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     */
    private $eventId;

    /**
     * @var bool
     *
     * @ORM\Column(name="group", type="boolean", nullable=false)
     */
    private $group;

    /**
     * @var int
     *
     * @ORM\Column(name="heat", type="integer", nullable=false)
     */
    private $heat;

    /**
     * @var int
     *
     * @ORM\Column(name="model_id", type="integer", nullable=false)
     */
    private $modelId;

    /**
     * @var json|null
     *
     * @ORM\Column(name="placement", type="json", nullable=true)
     */
    private $placement;

    /**
     * @var bool
     *
     * @ORM\Column(name="round_id", type="boolean", nullable=false)
     */
    private $roundId;

    /**
     * @var json|null
     *
     * @ORM\Column(name="scoresheet", type="json", nullable=true)
     */
    private $scoresheet;

    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="smallint", nullable=false)
     */
    private $sequence;

    /**
     * @var int
     *
     * @ORM\Column(name="subevent_id", type="integer", nullable=false)
     */
    private $subeventId;

    /**
     * @var \App\Entity\Score\Competition
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Score\Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     * })
     */
    private $competition;


}
