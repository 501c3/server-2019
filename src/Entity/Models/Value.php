<?php

namespace App\Entity\Models;

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
     * @var \Entity\Models\Domain
     *
     * @ORM\ManyToOne(targetEntity="Entity\Models\Domain")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     * })
     */
    private $domain;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Event", mappedBy="value")
     */
    private $event;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Person", mappedBy="value")
     */
    private $personAge;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->event = new \Doctrine\Common\Collections\ArrayCollection();
        $this->personAge = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function getPersonAge(): \Doctrine\Common\Collections\Collection
    {
        return $this->personAge;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $personAge
     * @return Value
     */
    public function setPersonAge(\Doctrine\Common\Collections\Collection $personAge): Value
    {
        $this->personAge = $personAge;
        return $this;
    }

}
