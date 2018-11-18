<?php

namespace App\Entity\Competition;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team
 *
 * @ORM\Table(name="team")
 * @ORM\Entity
 */
class Team
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=120, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Competition\Event", mappedBy="team")
     */
    private $eventCompetition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Competition\Subevent", mappedBy="team")
     */
    private $subeventCompetition;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Competition\Person", mappedBy="team")
     */
    private $person;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventCompetition = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subeventCompetition = new \Doctrine\Common\Collections\ArrayCollection();
        $this->person = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
