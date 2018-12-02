<?php

namespace App\Entity\Setup;

use Doctrine\Common\Collections\Collection;
use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ORM\Mapping as ORM;

/**
 * AgePerson
 *
 * @ORM\Table(name="age_person")
 * @ORM\Entity(repositoryClass="App\Repository\Setup\AgePersonRepository")
 */
class AgePerson
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
     * @ORM\Column(name="describe", type="json", nullable=false)
     */
    private $describe;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfPerson", inversedBy="agePerson")
     * @ORM\JoinTable(name="age_person_has_prf_person",
     *   joinColumns={
     *     @ORM\JoinColumn(name="age_person_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="prf_person_id", referencedColumnName="id")
     *   }
     * )
     */
    private $prfPerson;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\Value", inversedBy="agePerson")
     * @ORM\JoinTable(name="age_person_has_value",
     *   joinColumns={
     *     @ORM\JoinColumn(name="age_person_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="value_id", referencedColumnName="id")
     *   }
     * )
     */
    private $value;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\AgeTeam", inversedBy="agePerson")
     * @ORM\JoinTable(name="age_team_has_age_person",
     *   joinColumns={
     *     @ORM\JoinColumn(name="age_person_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="age_team_id", referencedColumnName="id")
     *   }
     * )
     */
    private $ageTeam;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prfPerson = new \Doctrine\Common\Collections\ArrayCollection();
        $this->value = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ageTeam = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return AgePerson
     */
    public function setDescribe(array $describe): AgePerson
    {
        $this->describe = $describe;
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
     * @return AgePerson
     */
    public function setPrfPerson(Collection $prfPerson): AgePerson
    {
        $this->prfPerson = $prfPerson;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getValue(): Collection
    {
        return $this->value;
    }

    /**
     * @param Collection $value
     * @return AgePerson
     */
    public function setValue(Collection $value): AgePerson
    {
        $this->value = $value;
        return $this;
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
     * @return AgePerson
     */
    public function setAgeTeam(Collection $ageTeam): AgePerson
    {
        $this->ageTeam = $ageTeam;
        return $this;
    }



}
