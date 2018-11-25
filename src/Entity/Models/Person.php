<?php

namespace App\Entity\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="App\Repository\Models\PersonRepository")
 */
class Person
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
     * @var integer
     *
     * @ORM\Column(name="age", type="integer", nullable=false)
     */
    private $age;

    /**
     * @var array
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Value", inversedBy="personAge")
     * @ORM\JoinTable(name="person_has_value",
     *   joinColumns={
     *     @ORM\JoinColumn(name="person_age", referencedColumnName="id")
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Team", mappedBy="person")
     */
    private $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = new ArrayCollection();
        $this->team = new ArrayCollection();
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
     * @return Person
     */
    public function setId(int $id): Person
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAge(): bool
    {
        return $this->age;
    }

    /**
     * @param int $age
     * @return Person
     */
    public function setAge(int $age): Person
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return array
     */
    public function getDescription(): array
    {
        return $this->description;
    }

    /**
     * @param array $description
     * @return Person
     */
    public function setDescription(array $description): Person
    {
        $this->description = $description;
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
     * @return Person
     */
    public function setValue(Collection $value): Person
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTeam(): Collection
    {
        return $this->team;
    }

    /**
     * @param Collection $team
     * @return Person
     */
    public function setTeam(Collection $team): Person
    {
        $this->team = $team;
        return $this;
    }

    public function getAge() : int
    {
        return $this->age;
    }


}
