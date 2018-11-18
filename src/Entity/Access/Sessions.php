<?php

namespace App\Entity\Access;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sessions
 *
 * @ORM\Table(name="sessions", indexes={@ORM\Index(name="fk_sessions_user1_idx", columns={"user_id"})})
 * @ORM\Entity
 */
class Sessions
{
    /**
     * @var binary
     *
     * @ORM\Column(name="sess_id", type="binary", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $sessId;

    /**
     * @var string
     *
     * @ORM\Column(name="sess_data", type="blob", length=65535, nullable=false)
     */
    private $sessData;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_lifetime", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $sessLifetime;

    /**
     * @var int
     *
     * @ORM\Column(name="sess_time", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $sessTime;

    /**
     * @var \App\Entity\Access\User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Access\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


}
