<?php

namespace App\Entity\Access;

use Doctrine\ORM\Mapping as ORM;

/**
 * Workarea
 *
 * @ORM\Table(name="workarea")
 * @ORM\Entity
 */
class Workarea
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", length=255, nullable=false)
     */
    private $note;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\User", mappedBy="workarea")
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
