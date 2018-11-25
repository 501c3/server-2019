<?php

namespace App\Entity\Models;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;



/**
 * Value
 *
 * @ORM\Table(name="value", indexes={@ORM\Index(name="fk_value_domain1_idx", columns={"domain_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\Models\ValueRepository")
 */
class Value
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abbr", type="string", length=4, nullable=false)
     */
    private $abbr;

    /**
     * @var \App\Entity\Models\Domain
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Models\Domain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     * })
     */
    private $domain;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Event", mappedBy="value")
     */
    private $event;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Person", mappedBy="value")
     */
    private $personAge;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event = new ArrayCollection();
        $this->personAge = new ArrayCollection();
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
     * @return Value
     */
    public function setId(int $id): Value
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Value
     */
    public function setName(string $name): Value
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAbbr(): string
    {
        return $this->abbr;
    }

    /**
     * @param string $abbr
     * @return Value
     */
    public function setAbbr(string $abbr): Value
    {
        $this->abbr = $abbr;
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
     * @return Collection
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    /**
     * @param Collection $event
     * @return Value
     */
    public function setEvent(Collection $event): Value
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPersonAge(): Collection
    {
        return $this->personAge;
    }

    /**
     * @param Collection $personAge
     * @return Value
     */
    public function setPersonAge(Collection $personAge): Value
    {
        $this->personAge = $personAge;
        return $this;
    }

}
