<?php

namespace App\Entity\Sales;

use Doctrine\ORM\Mapping as ORM;

/**
 * Picture
 *
 * @ORM\Table(name="picture")
 * @ORM\Entity
 */
class Picture
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="data", type="blob", length=65535, nullable=false)
     */
    private $data;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Sales\Form", mappedBy="picture")
     */
    private $form;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->form = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
