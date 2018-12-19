<?php

namespace App\Entity\Setup;

use Doctrine\Common\Collections\ArrayCollection;
use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ORM\Mapping as ORM;

/**
 * PrfPerson
 *
 * @ORM\Table(name="prf_person")
 * @ORM\Entity(repositoryClass="App\Repository\Setup\PrfPersonRepository")
 */
class PrfPerson
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var array
     *
     * @ORM\Column(name="`describe`", type="json", nullable=false)
     */
    private $describe;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\AgePerson", mappedBy="prfPerson")
     */
    private $agePerson;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\Value", inversedBy="prfPerson")
     * @ORM\JoinTable(name="prf_person_has_value",
     *   joinColumns={
     *     @ORM\JoinColumn(name="prf_person_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="value_id", referencedColumnName="id")
     *   }
     * )
     */
    private $value;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfTeam", inversedBy="prfPerson", cascade={"persist"})
     * @ORM\JoinTable(name="prf_team_has_prf_person",
     *   joinColumns={
     *     @ORM\JoinColumn(name="prf_person_id", referencedColumnName="id")
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
        $this->value = new ArrayCollection();
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
     * @return PrfPerson
     */
    public function setId(int $id): PrfPerson
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function getDescribe(): array
    {
        return $this->describe;
    }

    /**
     * @param array $describe
     * @return PrfPerson
     */
    public function setDescribe(array $describe): PrfPerson
    {
        $this->describe = $describe;
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
     * @return PrfPerson
     */
    public function setAgePerson(\Doctrine\Common\Collections\Collection $agePerson): PrfPerson
    {
        $this->agePerson = $agePerson;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValue(): \Doctrine\Common\Collections\Collection
    {
        return $this->value;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $value
     * @return PrfPerson
     */
    public function setValue(\Doctrine\Common\Collections\Collection $value): PrfPerson
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrfTeam(): \Doctrine\Common\Collections\Collection
    {
        return $this->prfTeam;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $prfTeam
     * @return PrfPerson
     */
    public function setPrfTeam(\Doctrine\Common\Collections\Collection $prfTeam): PrfPerson
    {
        $this->prfTeam = $prfTeam;
        return $this;
    }



}
