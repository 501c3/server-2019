<?php

namespace App\Entity\Competition;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlayerHasEvent
 *
 * @ORM\Table(name="player_has_event")
 * @ORM\Entity
 */
class PlayerHasEvent
{
    /**
     * @var int
     *
     * @ORM\Column(name="event_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $eventId;

    /**
     * @var bool
     *
     * @ORM\Column(name="event_model_id", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $eventModelId;

    /**
     * @var int
     *
     * @ORM\Column(name="player_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $playerId;

    /**
     * @var bool
     *
     * @ORM\Column(name="player_model_id", type="boolean", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $playerModelId;


}
