<?php

namespace App\Entity\Setup;

use /** @noinspection PhpUnusedAliasInspection */
    Doctrine\ORM\Mapping as ORM;

/**
 * Value
 *
 * @ORM\Table(name="value", indexes={@ORM\Index(name="fk_value_domain1_idx", columns={"domain_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Setup\ValueRepository")
 */
class Value
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
     * @var string|null
     *
     * @ORM\Column(name="abbr", type="string", length=6, nullable=true)
     */
    private $abbr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var \App\Entity\Setup\Domain
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Setup\Domain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     * })
     */
    private $domain;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\AgePerson", mappedBy="value")
     */
    private $agePerson;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\AgeTeamClass", mappedBy="value")
     */
    private $ageTeamClass;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\Event", mappedBy="value")
     */
    private $event;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\Model", mappedBy="value")
     */
    private $model;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfPerson", mappedBy="value")
     */
    private $prfPerson;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Setup\PrfTeamClass", mappedBy="value")
     */
    private $prfTeamClass;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->agePerson = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ageTeamClass = new \Doctrine\Common\Collections\ArrayCollection();
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
        $this->model = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prfPerson = new \Doctrine\Common\Collections\ArrayCollection();
        $this->prfTeamClass = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getAbbr(): ?string
    {
        return $this->abbr;
    }

    /**
     * @param null|string $abbr
     * @return Value
     */
    public function setAbbr(?string $abbr): Value
    {
        $this->abbr = $abbr;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param null|string $name
     * @return Value
     */
    public function setName(?string $name): Value
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Domain
     */
    public function getDomain(): Domain
    {
        return $this->domain;
    }

    /**
     * @param Domain $domain
     * @return Value
     */
    public function setDomain(Domain $domain): Value
    {
        $this->domain = $domain;
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
     * @return Value
     */
    public function setAgePerson(\Doctrine\Common\Collections\Collection $agePerson): Value
    {
        $this->agePerson = $agePerson;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAgeTeamClass(): \Doctrine\Common\Collections\Collection
    {
        return $this->ageTeamClass;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $ageTeamClass
     * @return Value
     */
    public function setAgeTeamClass(\Doctrine\Common\Collections\Collection $ageTeamClass): Value
    {
        $this->ageTeamClass = $ageTeamClass;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEvent(): \Doctrine\Common\Collections\Collection
    {
        return $this->event;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $event
     * @return Value
     */
    public function setEvent(\Doctrine\Common\Collections\Collection $event): Value
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getModel(): \Doctrine\Common\Collections\Collection
    {
        return $this->model;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $model
     * @return Value
     */
    public function setModel(\Doctrine\Common\Collections\Collection $model): Value
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPrfPerson(): \Doctrine\Common\Collections\Collection
    {
        return $this->prfPerson;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $prfPerson
     * @return Value
     */
    public function setPrfPerson(\Doctrine\Common\Collections\Collection $prfPerson): Value
    {
        $this->prfPerson = $prfPerson;
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
     * @return Value
     */
    public function setPrfTeamClass(\Doctrine\Common\Collections\Collection $prfTeamClass): Value
    {
        $this->prfTeamClass = $prfTeamClass;
        return $this;
    }



}
