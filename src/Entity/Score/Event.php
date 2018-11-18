<?php

namespace App\Entity\Score;

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
     * @var bool
     *
     * @ORM\Column(name="model_id", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $modelId;

    /**
     * @var json
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var json|null
     *
     * @ORM\Column(name="scoresheet", type="json", nullable=true)
     */
    private $scoresheet;

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


}
