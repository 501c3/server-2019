<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity
 */
class Competition
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
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean", nullable=false)
     */
    private $locked = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=40, nullable=true)
     */
    private $name;


}
