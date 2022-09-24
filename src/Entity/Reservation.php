<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity=Programmation::class, inversedBy="reservations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $programmation;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_participant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $reserved_at;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getProgrammation(): ?Programmation
    {
        return $this->programmation;
    }

    public function setProgrammation(?Programmation $programmation): self
    {
        $this->programmation = $programmation;

        return $this;
    }

    public function getNbParticipant(): ?int
    {
        return $this->nb_participant;
    }

    public function setNbParticipant(int $nb_participant): self
    {
        $this->nb_participant = $nb_participant;

        return $this;
    }

    public function getReservedAt(): ?\DateTime
    {
        return $this->reserved_at;
    }

    public function setReservedAt(\DateTime $reserved_at): self
    {
        $this->reserved_at = $reserved_at;

        return $this;
    }

    public function getJourFormat($format = 'd/m/Y')
    {
        return $this->reserved_at->format($format);
    }

    public function __toString()
    {
        return $this->nb_participant;
    }
}
