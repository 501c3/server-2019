<?php

namespace App\Entity\Models;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Model", inversedBy="domain")
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Entity\Models\Competition", mappedBy="domain")
     */
    private $competition;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->model = new \Doctrine\Common\Collections\ArrayCollection();
        $this->competition = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
