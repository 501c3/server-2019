<?php

namespace App\Entity\Competition;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person
 *
 * @ORM\Table(name="person")
 * @ORM\Entity
 */
class Person
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
     * @var int
     *
     * @ORM\Column(name="age", type="smallint", nullable=false)
     */
    private $age;

    /**
     * @var string|null
     *
     * @ORM\Column(name="email", type="string", length=80, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="first", type="string", length=40, nullable=false)
     */
    private $first;

    /**
     * @var string
     *
     * @ORM\Column(name="last", type="string", length=40, nullable=false)
     */
    private $last;

    /**
     * @var string|null
     *
     * @ORM\Column(name="phone", type="string", length=12, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="sex", type="string", length=1, nullable=false, options={"fixed"=true})
     */
    private $sex;

    /**
     * @var string|null
     *
     * @ORM\Column(name="status", type="string", length=10, nullable=true)
     */
    private $status;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Competition\Number", mappedBy="person")
     */
    private $number;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Competition\Team", inversedBy="person")
     * @ORM\JoinTable(name="team_has_person",
     *   joinColumns={
     *     @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     *   }
     * )
     */
    private $team;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->number = new \Doctrine\Common\Collections\ArrayCollection();
        $this->team = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
