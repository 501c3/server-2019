<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Dance
 *
 * @ORM\Table(name="dance", indexes={@ORM\Index(name="fk_dance_subevent1_idx", columns={"subevent_id"})})
 * @ORM\Entity
 */
class Dance
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="abbr", type="string", length=4, nullable=false)
     */
    private $abbr;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=false)
     */
    private $name;

    /**
     * @var json|null
     *
     * @ORM\Column(name="scroresheet", type="json", nullable=true)
     */
    private $scroresheet;

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


}
