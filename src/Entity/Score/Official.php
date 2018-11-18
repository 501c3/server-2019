<?php

namespace App\Entity\Score;

use Doctrine\ORM\Mapping as ORM;

/**
 * Official
 *
 * @ORM\Table(name="official")
 * @ORM\Entity
 */
class Official
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
     * @var json|null
     *
     * @ORM\Column(name="credentials", type="json", nullable=true)
     */
    private $credentials;

    /**
     * @var string|null
     *
     * @ORM\Column(name="first", type="string", length=45, nullable=true)
     */
    private $first;

    /**
     * @var string|null
     *
     * @ORM\Column(name="last", type="string", length=45, nullable=true)
     */
    private $last;


}
