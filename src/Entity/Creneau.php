<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CreneauRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass=CreneauRepository::class)
 */
class Creneau
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $horaire;

    /**
     * @ORM\Column(type="integer")
     */
    private $nb_place;

    /**
     * @ORM\OneToMany(targetEntity=Programmation::class, mappedBy="creneau", orphanRemoval=true)
     */
    private $programmations;

    public function __construct()
    {
        $this->programmations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoraire(): ?\DateTimeInterface
    {
        return $this->horaire;
    }

    public function getJourFormat($format = 'd/m/Y à H:i:s')
    {
        return $this->horaire->format($format);
    }

    public function setHoraire(\DateTimeInterface $horaire): self
    {
        $this->horaire = $horaire;

        return $this;
    }

    public function getNbPlace(): ?int
    {
        return $this->nb_place;
    }

    public function setNbPlace(int $nb_place): self
    {
        $this->nb_place = $nb_place;

        return $this;
    }

    /**
     * @return Collection<int, Programmation>
     */
    public function getProgrammations(): Collection
    {
        return $this->programmations;
    }

    public function addProgrammation(Programmation $programmation): self
    {
        if (!$this->programmations->contains($programmation)) {
            $this->programmations[] = $programmation;
            $programmation->setCreneau($this);
        }

        return $this;
    }

    public function removeProgrammation(Programmation $programmation): self
    {
        if ($this->programmations->removeElement($programmation)) {
            // set the owning side to null (unless already changed)
            if ($programmation->getCreneau() === $this) {
                $programmation->setCreneau(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getJourFormat();
    }
}
