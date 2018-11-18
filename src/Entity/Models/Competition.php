<?php

namespace App\Entity\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity(repositoryClass="App\Repository\Models\CompetitionRepository")
 */
class Competition
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="start", type="datetime", nullable=true)
     */
    private $start;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    private $end;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Domain", inversedBy="competition")
     * @ORM\JoinTable(name="priority",
     *   joinColumns={
     *     @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     *   }
     * )
     */
    private $domain;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Subevent", inversedBy="competition")
     * @ORM\JoinTable(name="sequence",
     *   joinColumns={
     *     @ORM\JoinColumn(name="competition_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="subevent_id", referencedColumnName="id")
     *   }
     * )
     */
    private $subevent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->domain = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subevent = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Competition
     */
    public function setId(int $id): Competition
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Competition
     */
    public function setName(string $name): Competition
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStart(): ?\DateTime
    {
        return $this->start;
    }

    /**
     * @param \DateTime|null $start
     * @return Competition
     */
    public function setStart(?\DateTime $start): Competition
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getEnd(): ?\DateTime
    {
        return $this->end;
    }

    /**
     * @param \DateTime|null $end
     * @return Competition
     */
    public function setEnd(?\DateTime $end): Competition
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDomain(): \Doctrine\Common\Collections\Collection
    {
        return $this->domain;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $domain
     * @return Competition
     */
    public function setDomain(\Doctrine\Common\Collections\Collection $domain): Competition
    {
        $this->domain = $domain;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubevent(): \Doctrine\Common\Collections\Collection
    {
        return $this->subevent;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $subevent
     * @return Competition
     */
    public function setSubevent(\Doctrine\Common\Collections\Collection $subevent): Competition
    {
        $this->subevent = $subevent;
        return $this;
    }

}
