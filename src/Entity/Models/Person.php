<?php

namespace Entity\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity(repositoryClass="Repository\Models\PersonRepository")
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
     * @var bool
     *
     * @ORM\Column(name="age", type="boolean", nullable=false)
     */
    private $age;

    /**
     * @var json
     *
     * @ORM\Column(name="description", type="json", nullable=false)
     */
    private $description;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Value", inversedBy="personAge")
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Team", mappedBy="person")
     */
    private $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->value = new \Doctrine\Common\Collections\ArrayCollection();
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param bool $age
     * @return Person
     */
    public function setAge(bool $age): Person
    {
        $this->age = $age;
        return $this;
    }

    /**
     * @return json
     */
    public function getDescription(): json
    {
        return $this->description;
    }

    /**
     * @param json $description
     * @return Person
     */
    public function setDescription(json $description): Person
    {
        $this->description = $description;
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
     * @return Person
     */
    public function setValue(\Doctrine\Common\Collections\Collection $value): Person
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeam(): \Doctrine\Common\Collections\Collection
    {
        return $this->team;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $team
     * @return Person
     */
    public function setTeam(\Doctrine\Common\Collections\Collection $team): Person
    {
        $this->team = $team;
        return $this;
    }

}
