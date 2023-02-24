<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use  Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @UniqueEntity("num_compte")
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", unique=true)
     * @Assert\Length(
     * min="11",
     * max="11",
     * minMessage="Le numéro de compte bancaire est composé de 11 chiffres",
     * maxMessage="Le numéro de compte bancaire est composé de 11 chiffres")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $num_compte;

    /**
     * @ORM\Column(type="string",unique=true)
     * @Assert\Length (
     * min="24",
     * max="24",
     * minMessage="Le RIB du compte bancaire est composé de 24 caractères",
     * maxMessage="Le RIB du compte bancaire est composé de 24 caractères")
     * @Assert\NotBlank(message="Ce champs est obligatoire et doit être composé de 24 caractères")
     * @Groups("post:read")
     */
    private $RIB_Compte;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $solde_compte;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Groups("post:read")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $type_compte;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $seuil_compte;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $taux_interet;

    /**
     * @ORM\ManyToOne(targetEntity=Utilisateur::class, inversedBy="comptes")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $fullname_client;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $etat_compte;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="RIB_emetteur")
     */
    private $transactions;

    /**
     * @ORM\OneToMany(targetEntity=Chequier::class, mappedBy="num_compte")
     */
    private $chequiers;

    /**
     * @ORM\OneToMany(targetEntity=Cheques::class, mappedBy="proprietaire")
     */
    private $cheques;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->chequiers = new ArrayCollection();
        $this->cheques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCompte(): ?int
    {
        return $this->num_compte;
    }

    public function setNumCompte(?int $num_compte): self
    {
        $this->num_compte = $num_compte;

        return $this;
    }

    public function getRIBCompte(): ?string
    {
        return $this->RIB_Compte;
    }

    public function setRIBCompte(?string $RIB_Compte): self
    {
        $this->RIB_Compte = $RIB_Compte;

        return $this;
    }

    public function getSoldeCompte(): ?float
    {
        return $this->solde_compte;
    }

    public function setSoldeCompte(?float $solde_compte): self
    {
        $this->solde_compte = $solde_compte;

        return $this;
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

    public function getTypeCompte(): ?string
    {
        return $this->type_compte;
    }

    public function setTypeCompte(?string $type_compte): self
    {
        $this->type_compte = $type_compte;

        return $this;
    }

    public function getSeuilCompte(): ?float
    {
        return $this->seuil_compte;
    }

    public function setSeuilCompte(?float $seuil_compte): self
    {
        $this->seuil_compte = $seuil_compte;

        return $this;
    }

    public function getTauxInteret(): ?float
    {
        return $this->taux_interet;
    }

    public function setTauxInteret(?float $taux_interet): self
    {
        $this->taux_interet = $taux_interet;

        return $this;
    }

    public function getFullnameClient(): ?Utilisateur
    {
        return $this->fullname_client;
    }

    public function setFullnameClient(?Utilisateur $fullname_client): self
    {
        $this->fullname_client = $fullname_client;

        return $this;
    }

    public function getEtatCompte(): ?int
    {
        return $this->etat_compte;
    }

    public function setEtatCompte(?int $etat_compte): self
    {
        $this->etat_compte = $etat_compte;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setRIBEmetteur($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getRIBEmetteur() === $this) {
                $transaction->setRIBEmetteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Chequier[]
     */
    public function getChequiers(): Collection
    {
        return $this->chequiers;
    }

    public function addChequier(Chequier $chequier): self
    {
        if (!$this->chequiers->contains($chequier)) {
            $this->chequiers[] = $chequier;
            $chequier->setNumCompte($this);
        }

        return $this;
    }

    public function removeChequier(Chequier $chequier): self
    {
        if ($this->chequiers->removeElement($chequier)) {
            // set the owning side to null (unless already changed)
            if ($chequier->getNumCompte() === $this) {
                $chequier->setNumCompte(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Cheques[]
     */
    public function getCheques(): Collection
    {
        return $this->cheques;
    }

    public function addCheque(Cheques $cheque): self
    {
        if (!$this->cheques->contains($cheque)) {
            $this->cheques[] = $cheque;
            $cheque->setProprietaire($this);
        }

        return $this;
    }

    public function removeCheque(Cheques $cheque): self
    {
        if ($this->cheques->removeElement($cheque)) {
            // set the owning side to null (unless already changed)
            if ($cheque->getProprietaire() === $this) {
                $cheque->setProprietaire(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->fullname_client;
    }
}