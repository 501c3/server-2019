<?php

namespace App\Entity\Access;

use Doctrine\ORM\Mapping as ORM;

/**
 * Channel
 *
 * @ORM\Table(name="channel")
 * @ORM\Entity
 */
class Channel
{
    /**
     * @var int
     *
     * @ORM\Column(name="channel", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $channel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Access\User", inversedBy="channelChannel")
     * @ORM\JoinTable(name="user_has_channel",
     *   joinColumns={
     *     @ORM\JoinColumn(name="channel_channel", referencedColumnName="channel")
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
