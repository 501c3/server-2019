<?php

namespace App\Entity\Setup;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ORM\Mapping as ORM;

/**
 * AgeTeam
 *
 * @ORM\Table(name="age_team", indexes={@ORM\Index(name="fk_age_team_age_team_class1_idx", columns={"age_team_class_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Setup\AgeTeamRepository")
 */
class AgeTeam
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
     * @var \App\Entity\Setup\AgeTeamClass
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Setup\AgeTeamClass")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="age_team_class_id", referencedColumnName="id")
     * })
     */
    private $ageTeamClass;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\AgePerson", mappedBy="ageTeam")
     */
    private $agePerson;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfTeam", inversedBy="ageTeam")
     * @ORM\JoinTable(name="age_team_has_prf_team",
     *   joinColumns={
     *     @ORM\JoinColumn(name="age_team_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="prf_team_id", referencedColumnName="id")
     *   }
     * )
     */
    private $prfTeam;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->agePerson = new ArrayCollection();
        $this->prfTeam = new ArrayCollection();
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
     * @return AgeTeam
     */
    public function setId(int $id): AgeTeam
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return AgeTeamClass
     */
    public function getAgeTeamClass(): AgeTeamClass
    {
        return $this->ageTeamClass;
    }

    /**
     * @param AgeTeamClass $ageTeamClass
     * @return AgeTeam
     */
    public function setAgeTeamClass(AgeTeamClass $ageTeamClass): AgeTeam
    {
        $this->ageTeamClass = $ageTeamClass;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getAgePerson(): Collection
    {
        return $this->agePerson;
    }

    /**
     * @param Collection $agePerson
     * @return AgeTeam
     */
    public function setAgePerson(Collection $agePerson): AgeTeam
    {
        $this->agePerson = $agePerson;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPrfTeam(): Collection
    {
        return $this->prfTeam;
    }

    /**
     * @param Collection $prfTeam
     * @return AgeTeam
     */
    public function setPrfTeam(Collection $prfTeam): AgeTeam
    {
        $this->prfTeam = $prfTeam;
        return $this;
    }



}
