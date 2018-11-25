<?php

namespace Entity\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sequence
 *
 * @ORM\Table(name="sequence", indexes={@ORM\Index(name="fk_sequence_competition1_idx", columns={"competition_id"}), @ORM\Index(name="idx_sequence", columns={"sequence"})})
 * @ORM\Entity(repositoryClass="App\Repository\Models\SequenceRepository")
 */
class Sequence
{
    /**
     * @var int
     *
     * @ORM\Column(name="sequence", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $sequence;

    /**
     * @var int
     *
     * @ORM\Column(name="session", type="smallint", nullable=false)
     */
    private $session;

    /**
     * @var int
     *
     * @ORM\Column(name="subevent_id", type="integer", nullable=false)
     */
    private $subeventId;

    /**
     * @var \Entity\Models\Competition
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Models\Competition")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     * })
     */
    private $competition;

    /**
     * @return int
     */
    public function getSequence(): int
    {
        return $this->sequence;
    }

    /**
     * @param int $sequence
     * @return Sequence
     */
    public function setSequence(int $sequence): Sequence
    {
        $this->sequence = $sequence;
        return $this;
    }

    /**
     * @return int
     */
    public function getSession(): int
    {
        return $this->session;
    }

    /**
     * @param int $session
     * @return Sequence
     */
    public function setSession(int $session): Sequence
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return int
     */
    public function getSubeventId(): int
    {
        return $this->subeventId;
    }

    /**
     * @param int $subeventId
     * @return Sequence
     */
    public function setSubeventId(int $subeventId): Sequence
    {
        $this->subeventId = $subeventId;
        return $this;
    }

    /**
     * @return Competition
     */
    public function getCompetition(): Competition
    {
        return $this->competition;
    }

    /**
     * @param Competition $competition
     * @return Sequence
     */
    public function setCompetition(Competition $competition): Sequence
    {
        $this->competition = $competition;
        return $this;
    }



}
