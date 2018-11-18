<?php

namespace App\Entity\Sales;

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
     * @ORM\Column(name="id", type="smallint", nullable=false, options={"unsigned"=true})
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
     * @var json
     *
     * @ORM\Column(name="heading", type="json", nullable=false)
     */
    private $heading;

    /**
     * @var bool
     *
     * @ORM\Column(name="live", type="boolean", nullable=false)
     */
    private $live = '0';

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="blob", length=65535, nullable=false)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="offline_at", type="datetime", nullable=true)
     */
    private $offlineAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="online_at", type="datetime", nullable=true)
     */
    private $onlineAt;

    /**
     * @var json|null
     *
     * @ORM\Column(name="parameters", type="json", nullable=true)
     */
    private $parameters;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


}
