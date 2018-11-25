<?php

namespace App\Entity\Models;

use Doctrine\ORM\Mapping as ORM;

/**
 * Competition
 *
 * @ORM\Table(name="competition")
 * @ORM\Entity(repositoryClass="App\Repository\Models\CompetitionRepository")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="update", type="datetime", nullable=true)
     */
    private $update;


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Competition
     */
    public function setId(int $id): Competition
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Competition
     */
    public function setName(string $name): Competition
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @return \DateTime|null
     */
    public function getUpdate(): ?\DateTime
    {
        return $this->update;
    }

    /**
     * @param \DateTime|null $update
     * @return Competition
     */
    public function setUpdate(?\DateTime $update): Competition
    {
        $this->update = $update;
        return $this;
    }
}