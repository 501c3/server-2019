<?php

namespace App\Entity\Setup;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\AgePerson", mappedBy="ageTeam")
     */
    private $agePerson;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->agePerson = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgePerson(): \Doctrine\Common\Collections\Collection
    {
        return $this->agePerson;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $agePerson
     * @return AgeTeam
     */
    public function setAgePerson(\Doctrine\Common\Collections\Collection $agePerson): AgeTeam
    {
        $this->agePerson = $agePerson;
        return $this;
    }



}
