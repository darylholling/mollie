<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FactuurRepository")
 */
class Factuur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Betaling")
     */
    private $betaald;

    protected $orderregels;

    /**
     * Factuur constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->timestamp = new \DateTime();
        $this->orderregels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBetaald()
    {
        return $this->betaald;
    }

    /**
     * @param mixed $betaald
     */
    public function setBetaald($betaald): void
    {
        $this->betaald = $betaald;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrderregels(): ArrayCollection
    {
        return $this->orderregels;
    }


}
