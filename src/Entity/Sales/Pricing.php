<?php

namespace App\Entity\Sales;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pricing
 *
 * @ORM\Table(name="pricing", indexes={@ORM\Index(name="fk_pricing_channel1_idx", columns={"channel_id"}), @ORM\Index(name="idx_start_at", columns={"start_at"})})
 * @ORM\Entity
 */
class Pricing
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_at", type="datetime", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $startAt;

    /**
     * @var json|null
     *
     * @ORM\Column(name="inventory", type="json", nullable=true)
     */
    private $inventory;

    /**
     * @var \App\Entity\Sales\Channel
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="App\Entity\Sales\Channel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="channel_id", referencedColumnName="id")
     * })
     */
    private $channel;


}
