<?php

namespace App\Entity\Setup;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfPerson", mappedBy="prfTeam")
     */
    private $prfPerson;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prfPerson = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return PrfTeam
     */
    public function setId(int $id): PrfTeam
    {
        $this->id = $id;
        return $this;
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
     * @return PrfTeam
     */
    public function setPrfTeamClass(PrfTeamClass $prfTeamClass): PrfTeam
    {
        $this->prfTeamClass = $prfTeamClass;
        return $this;
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
     * @return PrfTeam
     */
    public function setPrfPerson(Collection $prfPerson): PrfTeam
    {
        $this->prfPerson = $prfPerson;
        return $this;
    }



}
