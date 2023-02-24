<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\SubmitType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;


    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="reclamations")
     * @Groups("post:read")
     */
    private $nom_u;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("post:read")
     */
    private $type_rec;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("post:read")
     */
    private $date_rec;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups("post:read")
     */
    private $etat_rec;

    /**
     * @ORM\Column(type="string", length=150)
     * @Groups("post:read")
     */
    private $desc_rec;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNomU(): ?Utilisateur
    {
        return $this->nom_u;
    }

    public function setNomU(?Utilisateur $nom_u): self
    {
        $this->nom_u = $nom_u;

        return $this;
    }

    public function getTypeRec(): ?string
    {
        return $this->type_rec;
    }

    public function setTypeRec(string $type_rec): self
    {
        $this->type_rec = $type_rec;

        return $this;
    }

    public function getDateRec(): ?\DateTimeInterface
    {
        return $this->date_rec;
    }

    public function setDateRec(\DateTimeInterface $date_rec): self
    {
        $this->date_rec = $date_rec;

        return $this;
    }

    public function getEtatRec(): ?string
    {
        return $this->etat_rec;
    }

    public function setEtatRec(string $etat_rec): self
    {
        $this->etat_rec = $etat_rec;

        return $this;
    }

    public function getDescRec(): ?string
    {
        return $this->desc_rec;
    }

    public function setDescRec(string $desc_rec): self
    {
        $this->desc_rec = $desc_rec;

        return $this;
    }
    public function __toString()
    {
        return $this->nom_u;
    }
}