<?php

namespace App\Entity\Models;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Domain
 *
 * @ORM\Table(name="domain", indexes={@ORM\Index(name="index", columns={"ord"})})
 * @ORM\Entity(repositoryClass="App\Repository\Models\DomainRepository")
 */
class Domain
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="ord", type="smallint", nullable=false)
     */
    private $ord;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Models\Model", inversedBy="domain")
     * @ORM\JoinTable(name="domain_has_model",
     *   joinColumns={
     *     @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="model_id", referencedColumnName="id")
     *   }
     * )
     */
    private $model;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return Domain
     */
    public function setName(string $name): Domain
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrd(): int
    {
        return $this->ord;
    }

    /**
     * @param int $ord
     * @return Domain
     */
    public function setOrd(int $ord): Domain
    {
        $this->ord = $ord;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getModel(): Collection
    {
        return $this->model;
    }

    /**
     * @param Collection $model
     * @return Domain
     */
    public function setModel(Collection $model): Domain
    {
        $this->model = $model;
        return $this;
    }

}
