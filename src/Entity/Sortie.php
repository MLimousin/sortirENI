<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateHeureDebut;

    /**
     * @ORM\Column(type="dateinterval")
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateLimiteInscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbreInscriptionMax;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infosSortie;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $etat;

    /**
     * @ORM\OneToMany(targetEntity=Etat::class, mappedBy="sortie")
     */
    private $Etat;

    /**
     * @ORM\OneToMany(targetEntity=Campus::class, mappedBy="sortie")
     */
    private $Campus;

    public function __construct()
    {
        $this->Etat = new ArrayCollection();
        $this->Campus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?\DateInterval
    {
        return $this->duree;
    }

    public function setDuree(\DateInterval $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbreInscriptionMax(): ?int
    {
        return $this->nbreInscriptionMax;
    }

    public function setNbreInscriptionMax(int $nbreInscriptionMax): self
    {
        $this->nbreInscriptionMax = $nbreInscriptionMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(?string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function addEtat(Etat $etat): self
    {
        if (!$this->Etat->contains($etat)) {
            $this->Etat[] = $etat;
            $etat->setSortie($this);
        }

        return $this;
    }

    public function removeEtat(Etat $etat): self
    {
        if ($this->Etat->removeElement($etat)) {
            // set the owning side to null (unless already changed)
            if ($etat->getSortie() === $this) {
                $etat->setSortie(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Campus[]
     */
    public function getCampus(): Collection
    {
        return $this->Campus;
    }

    public function addCampus(Campus $campus): self
    {
        if (!$this->Campus->contains($campus)) {
            $this->Campus[] = $campus;
            $campus->setSortie($this);
        }

        return $this;
    }

    public function removeCampus(Campus $campus): self
    {
        if ($this->Campus->removeElement($campus)) {
            // set the owning side to null (unless already changed)
            if ($campus->getSortie() === $this) {
                $campus->setSortie(null);
            }
        }

        return $this;
    }
}
