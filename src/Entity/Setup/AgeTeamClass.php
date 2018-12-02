<?php

namespace App\Entity\Setup;

use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ORM\Mapping as ORM;

/**
 * AgeTeamClass
 *
 * @ORM\Table(name="age_team_class")
 * @ORM\Entity(repositoryClass="App\Repository\Setup\AgeTeamClassRepository")
 */
class AgeTeamClass
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfTeamClass", inversedBy="ageTeamClass")
     * @ORM\JoinTable(name="age_team_class_has_prf_team_class",
     *   joinColumns={
     *     @ORM\JoinColumn(name="age_team_class_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="prf_team_class_id", referencedColumnName="id")
     *   }
     * )
     */
    private $prfTeamClass;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\Value", inversedBy="ageTeamClass")
     * @ORM\JoinTable(name="age_team_class_has_value",
     *   joinColumns={
     *     @ORM\JoinColumn(name="age_team_class_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="value_id", referencedColumnName="id")
     *   }
     * )
     */
    private $value;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prfTeamClass = new \Doctrine\Common\Collections\ArrayCollection();
        $this->value = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return AgeTeamClass
     */
    public function setId(int $id): AgeTeamClass
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
     * @return AgeTeamClass
     */
    public function setDescribe(array $describe): AgeTeamClass
    {
        $this->describe = $describe;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrfTeamClass(): \Doctrine\Common\Collections\Collection
    {
        return $this->prfTeamClass;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $prfTeamClass
     * @return AgeTeamClass
     */
    public function setPrfTeamClass(\Doctrine\Common\Collections\Collection $prfTeamClass): AgeTeamClass
    {
        $this->prfTeamClass = $prfTeamClass;
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
     * @return AgeTeamClass
     */
    public function setValue(\Doctrine\Common\Collections\Collection $value): AgeTeamClass
    {
        $this->value = $value;
        return $this;
    }



}
