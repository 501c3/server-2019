<?php

namespace App\Entity\Access;

use Doctrine\ORM\Mapping as ORM;

/**
 * Controller
 *
 * @ORM\Table(name="controller", uniqueConstraints={@ORM\UniqueConstraint(name="name_UNIQUE", columns={"name"})})
 * @ORM\Entity
 */
class Controller
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\User", inversedBy="controller")
     * @ORM\JoinTable(name="user_has_controller",
     *   joinColumns={
     *     @ORM\JoinColumn(name="controller_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *   }
     * )
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
