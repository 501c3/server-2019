<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Judge
 *
 * @ORM\Table(name="judge", indexes={@ORM\Index(name="fk_judge_competition1_idx", columns={"competition_id"}), @ORM\Index(name="fk_judge_official1_idx", columns={"official_id"})})
 * @ORM\Entity
 */
class Judge
{
    /**
     * @var string
     *
     * @ORM\Column(name="letter", type="string", length=1, nullable=false, options={"fixed"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $letter;

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
     * @ORM\ManyToOne(targetEntity="App\Entity\Score\Official")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="official_id", referencedColumnName="id")
     * })
     */
    private $official;


}
