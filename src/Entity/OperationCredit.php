<?php

namespace App\Entity;

use App\Repository\OperationCreditRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @ORM\Entity(repositoryClass=OperationCreditRepository::class)
 */
class OperationCredit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $dateOp;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */

    private $montPayer;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $echeance;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $tauxInteret;

    /**
     * @ORM\Column(type="integer",)
     *  @ORM\GeneratedValue(strategy="AUTO")
     * @Groups("post:read")
     */
    private $solvabilite;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $typeOperation;

    /**
     * @ORM\ManyToOne(targetEntity=Credit::class, inversedBy="OperationCredits")
     * @ORM\JoinColumn(nullable=false)
     */
    private $credit;


    public function __construct()
    {
        $this->solvabilite     = 1;
        $this->Credit = new ArrayCollection();


    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOp(): ?\DateTimeInterface
    {
        return $this->dateOp;
    }

    public function setDateOp(\DateTimeInterface $dateOp): self
    {
        $this->dateOp = $dateOp;

        return $this;
    }

    public function getMontPayer(): ?int
    {
        return $this->montPayer;
    }

    public function setMontPayer(int $montPayer): self
    {
        $this->montPayer = $montPayer;

        return $this;
    }

    public function getEcheance(): ?\DateTimeInterface
    {
        return $this->echeance;
    }

    public function setEcheance(\DateTimeInterface $echeance): self
    {
        $this->echeance = $echeance;

        return $this;
    }

    public function getTauxInteret(): ?int
    {
        return $this->tauxInteret;
    }

    public function setTauxInteret(int $tauxInteret): self
    {
        $this->tauxInteret = $tauxInteret;

        return $this;
    }

    public function getSolvabilite(): ?int
    {
        return $this->solvabilite;
    }

    public function setSolvabilite(int $solvabilite): self
    {
        $this->solvabilite = $solvabilite;

        return $this;
    }

    public function getTypeOperation(): ?string
    {
        return $this->typeOperation;
    }

    public function setTypeOperation(string $typeOperation): self
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    public function getCredit(): ?Credit
    {
        return $this->credit;
    }

    public function setCredit(?Credit $credit): self
    {
        $this->credit = $credit;

        return $this;
    }
}
