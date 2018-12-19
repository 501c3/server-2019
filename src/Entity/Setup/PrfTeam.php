<?php

namespace App\Entity\Setup;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ORM\Mapping as ORM;

/**
 * PrfTeam
 *
 * @ORM\Table(name="prf_team", indexes={@ORM\Index(name="fk_prf_team_prf_team_class1_idx", columns={"prf_team_class_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Setup\PrfTeamRepository")
 */
class PrfTeam
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
     * @var \App\Entity\Setup\PrfTeamClass
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Setup\PrfTeamClass")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="prf_team_class_id", referencedColumnName="id")
     * })
     */
    private $prfTeamClass;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\AgeTeam", mappedBy="prfTeam")
     */
    private $ageTeam;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfPerson", mappedBy="prfTeam")
     */
    private $prfPerson;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ageTeam = new ArrayCollection();
        $this->prfPerson = new ArrayCollection();
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
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return PrfTeamClass
     */
    public function getPrfTeamClass(): PrfTeamClass
    {
        return $this->prfTeamClass;
    }

    /**
     * @param PrfTeamClass $prfTeamClass
     */
    public function setPrfTeamClass(PrfTeamClass $prfTeamClass): void
    {
        $this->prfTeamClass = $prfTeamClass;
    }

    /**
     * @return Collection
     */
    public function getAgeTeam(): Collection
    {
        return $this->ageTeam;
    }

    /**
     * @param Collection $ageTeam
     */
    public function setAgeTeam(Collection $ageTeam): void
    {
        $this->ageTeam = $ageTeam;
    }

    /**
     * @return Collection
     */
    public function getPrfPerson(): Collection
    {
        return $this->prfPerson;
    }

    /**
     * @param Collection $prfPerson
     */
    public function setPrfPerson(Collection $prfPerson): void
    {
        $this->prfPerson = $prfPerson;
    }



}
