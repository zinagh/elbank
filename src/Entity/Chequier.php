<?php

namespace App\Entity;

use App\Repository\ChequierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=ChequierRepository::class)
 */
class Chequier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;


    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThanOrEqual("today", message="La date est incorrecte.")
     * @Groups("post:read")
     */
    private $date_creation;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="chequiers")
     * @Groups("post:read")
     */
    private $num_compte;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length( max = 50 , maxMessage = "Veuillez ne pas dépasser 50 mots ")
     * @Assert\NotBlank(message="Veuillez saisir le motif.")
     * @Groups("post:read")
     */
    private $motif_chequier;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="chequiers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("post:read")
     */
    private  $nom_client;



    public function __toString()
    {
        return (string) $this->id_chequier;
    }


    /**
     * @ORM\OneToMany(targetEntity=Cheques::class, mappedBy="Carnets_cheques")
     * @Groups("post:read")
     */
    private $cheque;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $etat_chequier;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $client_tel;

    /**
     * @ORM\OneToMany(targetEntity=Cheques::class, mappedBy="idchequiers")
     * @Groups("post:read")
     */
    private $cheques;

    public function __construct()
    {
        $this->cheques = new ArrayCollection();
        $this->cheque = new ArrayCollection();
        $this->setDateCreation(new \DateTime());
    }
    public function getClientTel(): ?int
    {
        return $this->client_tel;
    }

    public function setClientTel(?int $client_tel): self
    {
        $this->client_tel = $client_tel;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTimeInterface $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getNumCompte(): ?Compte
    {
        return $this->num_compte;
    }

    public function setNumCompte(?Compte $num_compte): self
    {
        $this->num_compte = $num_compte;

        return $this;
    }

    public function getMotifChequier(): ?string
    {
        return $this->motif_chequier;
    }

    public function setMotifChequier(string $motif_chequier): self
    {
        $this->motif_chequier = $motif_chequier;

        return $this;
    }

    public function getNomClient(): ?Utilisateur
    {
        return $this->nom_client;
    }

    public function setNomClient(?Utilisateur $nom_client): self
    {
        $this->nom_client = $nom_client;

        return $this;
    }

    public function getEtatChequier(): ?int
    {
        return $this->etat_chequier;
    }

    public function setEtatChequier(?int $etat_chequier): self
    {
        $this->etat_chequier = $etat_chequier;

        return $this;
    }


    /**
     * @return Collection<int, Cheques>
     */
    public function getCheques(): Collection
    {
        return $this->cheques;
    }

    /**
     * @return Collection<int, Cheques>
     */
    public function getCheque(): Collection
    {
        return $this->cheque;
    }

    public function addCheque(Cheques $cheque): self
    {
        if (!$this->cheque->contains($cheque)) {
            $this->cheque[] = $cheque;
            $cheque->setCarnetsCheques($this);
        }

        return $this;
    }

    public function removeCheque(Cheques $cheque): self
    {
        if ($this->cheque->removeElement($cheque)) {
            // set the owning side to null (unless already changed)
            if ($cheque->getCarnetsCheques() === $this) {
                $cheque->setCarnetsCheques(null);
            }
        }
        return $this;
    }
}

