<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use  Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transactions")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $RIB_emetteur;


    /**
     * @ORM\Column(type="string", length=24)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $RIB_recepteur;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $montant_transaction;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     * @Groups("post:read")
     */
    private $date_transaction;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $description_transaction;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transactions")
     * @Groups("post:read")
     */
    private $fullname_emetteur;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $fullname_recepteur;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups("post:read")
     */
    private $type_transaction;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $etat_transaction;

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getRIBEmetteur(): ?Compte
    {
        return $this->RIB_emetteur;
    }

    public function setRIBEmetteur(?Compte $RIB_emetteur): self
    {
        $this->RIB_emetteur = $RIB_emetteur;

        return $this;
    }

    public function getRIBRecepteur(): ?string
    {
        return $this->RIB_recepteur;
    }

    public function setRIBRecepteur(?string $RIB_recepteur): self
    {
        $this->RIB_recepteur = $RIB_recepteur;

        return $this;
    }

    public function getMontantTransaction(): ?float
    {
        return $this->montant_transaction;
    }

    public function setMontantTransaction(?float $montant_transaction): self
    {
        $this->montant_transaction = $montant_transaction;

        return $this;
    }

    public function getDateTransaction(): ?\DateTimeInterface
    {
        return $this->date_transaction;
    }

    public function setDateTransaction(\DateTimeInterface $date_transaction): self
    {
        $this->date_transaction = $date_transaction;

        return $this;
    }

    public function getDescriptionTransaction(): ?string
    {
        return $this->description_transaction;
    }

    public function setDescriptionTransaction(?string $description_transaction): self
    {
        $this->description_transaction = $description_transaction;

        return $this;
    }

    public function getFullnameEmetteur(): ?Compte
    {
        return $this->fullname_emetteur;
    }

    public function setFullnameEmetteur(?Compte $fullname_emetteur): self
    {
        $this->fullname_emetteur = $fullname_emetteur;

        return $this;
    }

    public function getFullnameRecepteur(): ?string
    {
        return $this->fullname_recepteur;
    }

    public function setFullnameRecepteur(?string $fullname_recepteur): self
    {
        $this->fullname_recepteur = $fullname_recepteur;

        return $this;
    }

    public function getTypeTransaction(): ?string
    {
        return $this->type_transaction;
    }

    public function setTypeTransaction(?string $type_transaction): self
    {
        $this->type_transaction = $type_transaction;

        return $this;
    }

    public function getEtatTransaction(): ?int
    {
        return $this->etat_transaction;
    }

    public function setEtatTransaction(?int $etat_transaction): self
    {
        $this->etat_transaction = $etat_transaction;

        return $this;
    }
}